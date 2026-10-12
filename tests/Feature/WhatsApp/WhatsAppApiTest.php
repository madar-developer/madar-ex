<?php

namespace Tests\Feature\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\Concerns\CreatesWhatsAppSchema;
use Tests\TestCase;

class WhatsAppApiTest extends TestCase
{
    use CreatesWhatsAppSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createWhatsAppSchema();

        config([
            'whatsapp.enabled' => true,
            'whatsapp.access_token' => 'test-access-token',
            'whatsapp.phone_number_id' => '339316869258229',
            'whatsapp.api_version' => 'v25.0',
            'whatsapp.graph_base_url' => 'https://graph.facebook.com',
        ]);
    }

    public function testUnauthorizedApiRequestsAreRejected(): void
    {
        // Language files in this project query order_statuses on load.
        if (! \Illuminate\Support\Facades\Schema::hasTable('order_statuses')) {
            \Illuminate\Support\Facades\Schema::create('order_statuses', function ($table) {
                $table->id();
                $table->string('key')->nullable();
                $table->integer('sort')->default(0);
                $table->timestamps();
            });
        }

        $response = $this->postJson('/api/whatsapp/send-template', [
            'phone' => '+966538825803',
            'template_name' => 'hello_world',
            'language' => 'en_US',
        ]);

        // Project Handler::unauthenticated returns JSON with authFailed code (HTTP 200 body convention).
        $response->assertJsonPath('code', getMsgCode('authFailed'));
        $this->assertFalse(
            data_get($response->json(), 'data.queued') === true,
            'Unauthenticated callers must not queue WhatsApp messages'
        );
    }

    public function testDisabledWhatsAppIntegrationViaServiceConfig(): void
    {
        config(['whatsapp.enabled' => false]);

        Http::fake();

        $this->expectException(\App\Exceptions\WhatsAppException::class);

        app(\App\Services\WhatsApp\WhatsAppService::class)
            ->sendTemplate('+966538825803', 'hello_world', 'en_US');
    }

    public function testSendTemplateEndpointQueuesWhenAuthenticated(): void
    {
        Queue::fake();
        Http::fake();

        $company = $this->makeActiveCompany();
        $token = auth('api-company')->login($company);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/whatsapp/send-template', [
                'phone' => '+966538825803',
                'template_name' => 'hello_world',
                'language' => 'en_US',
                'components' => [],
                'async' => true,
            ]);

        $response->assertOk()
            ->assertJsonPath('data.queued', true)
            ->assertJsonPath('code', getMsgCode('success'));

        Queue::assertPushed(\App\Jobs\SendWhatsAppMessageJob::class);
    }

    protected function makeActiveCompany()
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('companies')) {
            \Illuminate\Support\Facades\Schema::create('companies', function ($table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('password')->nullable();
                $table->string('phone')->nullable();
                $table->boolean('active')->default(1);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        return \App\Models\Company::query()->create([
            'name' => 'WhatsApp Test Co',
            'email' => 'whatsapp-test@example.com',
            'password' => bcrypt('secret'),
            'phone' => '0500000000',
            'active' => 1,
        ]);
    }
}
