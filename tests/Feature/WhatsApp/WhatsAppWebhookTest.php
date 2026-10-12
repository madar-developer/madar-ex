<?php

namespace Tests\Feature\WhatsApp;

use App\Jobs\ProcessWhatsAppWebhookJob;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppWebhookEvent;
use Illuminate\Support\Facades\Queue;
use Tests\Concerns\CreatesWhatsAppSchema;
use Tests\TestCase;

class WhatsAppWebhookTest extends TestCase
{
    use CreatesWhatsAppSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createWhatsAppSchema();

        config([
            'whatsapp.webhook_verify_token' => 'verify-token-test',
            'whatsapp.app_secret' => 'app-secret-test',
            'whatsapp.enabled' => true,
        ]);
    }

    public function testWebhookVerificationSucceeds(): void
    {
        $response = $this->get('/api/webhooks/whatsapp?'.http_build_query([
            'hub.mode' => 'subscribe',
            'hub.verify_token' => 'verify-token-test',
            'hub.challenge' => '1234567890',
        ]));

        $response->assertOk();
        $this->assertSame('1234567890', $response->getContent());
    }

    public function testWebhookVerificationFailsWithWrongToken(): void
    {
        $response = $this->get('/api/webhooks/whatsapp?'.http_build_query([
            'hub.mode' => 'subscribe',
            'hub.verify_token' => 'wrong',
            'hub.challenge' => '1234567890',
        ]));

        $response->assertForbidden();
    }

    public function testInvalidWebhookSignatureIsRejected(): void
    {
        $payload = json_encode(['object' => 'whatsapp_business_account', 'entry' => []]);

        $response = $this->call(
            'POST',
            '/api/webhooks/whatsapp',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Hub-Signature-256' => 'sha256=invalid',
            ],
            $payload
        );

        $response->assertForbidden();
    }

    public function testValidWebhookSignatureQueuesProcessing(): void
    {
        Queue::fake();

        $payload = json_encode([
            'object' => 'whatsapp_business_account',
            'entry' => [
                [
                    'changes' => [
                        [
                            'value' => [
                                'statuses' => [
                                    [
                                        'id' => 'wamid.ABC',
                                        'status' => 'delivered',
                                        'timestamp' => '1710000000',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $signature = 'sha256='.hash_hmac('sha256', $payload, 'app-secret-test');

        $response = $this->call(
            'POST',
            '/api/webhooks/whatsapp',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Hub-Signature-256' => $signature,
            ],
            $payload
        );

        $response->assertOk();
        Queue::assertPushed(ProcessWhatsAppWebhookJob::class);
    }

    public function testDuplicateWebhookEventsAreIdempotent(): void
    {
        WhatsAppMessage::query()->create([
            'wa_message_id' => 'wamid.DUP',
            'phone' => '966500000000',
            'direction' => 'outbound',
            'type' => 'template',
            'status' => 'accepted',
        ]);

        $payload = [
            'entry' => [
                [
                    'changes' => [
                        [
                            'value' => [
                                'statuses' => [
                                    [
                                        'id' => 'wamid.DUP',
                                        'status' => 'delivered',
                                        'timestamp' => '1710000001',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $job = new ProcessWhatsAppWebhookJob($payload);
        $job->handle(app(\App\Services\WhatsApp\WhatsAppService::class));
        $job->handle(app(\App\Services\WhatsApp\WhatsAppService::class));

        $this->assertSame(1, WhatsAppWebhookEvent::query()->count());
        $this->assertSame('delivered', WhatsAppMessage::query()->where('wa_message_id', 'wamid.DUP')->value('status'));
    }

    public function testInboundMessageOpensServiceWindow(): void
    {
        $payload = [
            'entry' => [
                [
                    'changes' => [
                        [
                            'value' => [
                                'messages' => [
                                    [
                                        'from' => '966538825803',
                                        'id' => 'wamid.INBOUND1',
                                        'timestamp' => '1710000002',
                                        'type' => 'text',
                                        'text' => ['body' => 'hi'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        (new ProcessWhatsAppWebhookJob($payload))
            ->handle(app(\App\Services\WhatsApp\WhatsAppService::class));

        $conversation = WhatsAppConversation::query()->where('phone', '966538825803')->first();
        $this->assertNotNull($conversation);
        $this->assertNotNull($conversation->last_inbound_at);
    }
}
