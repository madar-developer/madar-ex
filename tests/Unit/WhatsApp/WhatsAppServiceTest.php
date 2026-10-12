<?php

namespace Tests\Unit\WhatsApp;

use App\Exceptions\WhatsAppException;
use App\Jobs\SendWhatsAppMessageJob;
use App\Models\WhatsAppConversation;
use App\Services\WhatsApp\PhoneNumberNormalizer;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\Concerns\CreatesWhatsAppSchema;
use Tests\TestCase;

class WhatsAppServiceTest extends TestCase
{
    use CreatesWhatsAppSchema;

    protected WhatsAppService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createWhatsAppSchema();

        config([
            'whatsapp.enabled' => true,
            'whatsapp.access_token' => 'test-access-token',
            'whatsapp.phone_number_id' => '339316869258229',
            'whatsapp.business_account_id' => '304667792733449',
            'whatsapp.api_version' => 'v25.0',
            'whatsapp.graph_base_url' => 'https://graph.facebook.com',
            'whatsapp.default_language' => 'ar',
            'whatsapp.service_window_hours' => 24,
            'whatsapp.timeout' => 5,
            'whatsapp.connect_timeout' => 2,
        ]);

        $this->service = new WhatsAppService(new PhoneNumberNormalizer());
    }

    public function testSuccessfulTemplateMessage(): void
    {
        Http::fake([
            'https://graph.facebook.com/v25.0/339316869258229/messages' => Http::response([
                'messaging_product' => 'whatsapp',
                'messages' => [['id' => 'wamid.TEMPLATE123']],
            ], 200),
        ]);

        $result = $this->service->sendTemplate('+966538825803', 'hello_world', 'en_US');

        $this->assertTrue($result['success']);
        $this->assertSame('wamid.TEMPLATE123', $result['message_id']);
        $this->assertSame('accepted', $result['status']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://graph.facebook.com/v25.0/339316869258229/messages'
                && $request->hasHeader('Authorization', 'Bearer test-access-token')
                && $request['type'] === 'template'
                && $request['template']['name'] === 'hello_world'
                && $request['template']['language']['code'] === 'en_US'
                && $request['to'] === '966538825803'
                && ! str_contains(json_encode($request->data()), 'test-access-token');
        });
    }

    public function testSuccessfulTextMessageInsideServiceWindow(): void
    {
        WhatsAppConversation::query()->create([
            'phone' => '966538825803',
            'last_inbound_at' => now()->subHour(),
        ]);

        Http::fake([
            'https://graph.facebook.com/*' => Http::response([
                'messages' => [['id' => 'wamid.TEXT123']],
            ], 200),
        ]);

        $result = $this->service->sendText('+966538825803', 'Hello customer');

        $this->assertTrue($result['success']);
        $this->assertSame('wamid.TEXT123', $result['message_id']);
    }

    public function testTextMessageOutsideServiceWindowIsRejected(): void
    {
        $this->expectException(WhatsAppException::class);
        $this->expectExceptionMessage('customer service window');

        $this->service->sendText('+966538825803', 'Hello');
    }

    public function testInvalidPhoneNumber(): void
    {
        $this->expectException(WhatsAppException::class);

        $this->service->sendTemplate('0538825803', 'hello_world', 'en_US');
    }

    public function testMissingConfiguration(): void
    {
        config(['whatsapp.access_token' => null]);

        $this->expectException(WhatsAppException::class);
        $this->expectExceptionMessage('access token');

        $this->service->sendTemplate('+966538825803', 'hello_world', 'en_US');
    }

    public function testDisabledIntegration(): void
    {
        config(['whatsapp.enabled' => false]);

        $this->expectException(WhatsAppException::class);
        $this->expectExceptionMessage('disabled');

        $this->service->sendTemplate('+966538825803', 'hello_world', 'en_US');
    }

    public function testInvalidOrExpiredTokenReturns401(): void
    {
        Http::fake([
            'https://graph.facebook.com/*' => Http::response([
                'error' => [
                    'message' => 'Invalid OAuth access token.',
                    'type' => 'OAuthException',
                    'code' => 190,
                ],
            ], 401),
        ]);

        try {
            $this->service->sendTemplate('+966538825803', 'hello_world', 'en_US');
            $this->fail('Expected WhatsAppException');
        } catch (WhatsAppException $e) {
            $this->assertSame(401, $e->httpStatus);
            $this->assertFalse($e->isRetryable());
        }
    }

    /**
     * @dataProvider clientErrorProvider
     */
    public function testMetaClientErrorsAreNotRetryable(int $status, int $errorCode): void
    {
        Http::fake([
            'https://graph.facebook.com/*' => Http::response([
                'error' => [
                    'message' => 'Client error',
                    'code' => $errorCode,
                ],
            ], $status),
        ]);

        try {
            $this->service->sendTemplate('+966538825803', 'hello_world', 'en_US');
            $this->fail('Expected WhatsAppException');
        } catch (WhatsAppException $e) {
            $this->assertSame($status, $e->httpStatus);
            $this->assertFalse($e->isRetryable());
        }
    }

    public static function clientErrorProvider(): array
    {
        return [
            'bad_request' => [400, 100],
            'forbidden' => [403, 10],
        ];
    }

    public function testRateLimitIsRetryable(): void
    {
        Http::fake([
            'https://graph.facebook.com/*' => Http::response([
                'error' => [
                    'message' => 'Rate limit hit',
                    'code' => 80007,
                ],
            ], 429),
        ]);

        try {
            $this->service->sendTemplate('+966538825803', 'hello_world', 'en_US');
            $this->fail('Expected WhatsAppException');
        } catch (WhatsAppException $e) {
            $this->assertSame(429, $e->httpStatus);
            $this->assertTrue($e->isRetryable());
        }
    }

    /**
     * @dataProvider serverErrorProvider
     */
    public function testServerErrorsAreRetryable(int $status): void
    {
        Http::fake([
            'https://graph.facebook.com/*' => Http::response([
                'error' => [
                    'message' => 'Server error',
                    'code' => 2,
                ],
            ], $status),
        ]);

        try {
            $this->service->sendTemplate('+966538825803', 'hello_world', 'en_US');
            $this->fail('Expected WhatsAppException');
        } catch (WhatsAppException $e) {
            $this->assertSame($status, $e->httpStatus);
            $this->assertTrue($e->isRetryable());
        }
    }

    public static function serverErrorProvider(): array
    {
        return [
            'internal' => [500],
            'unavailable' => [503],
        ];
    }

    public function testQueueJobIsDispatchedForAsyncSend(): void
    {
        Queue::fake();

        SendWhatsAppMessageJob::dispatch('template', '+966538825803', [
            'template_name' => 'hello_world',
            'language' => 'en_US',
            'components' => [],
        ], 'idem-1');

        Queue::assertPushed(SendWhatsAppMessageJob::class, function (SendWhatsAppMessageJob $job) {
            return $job->messageType === 'template'
                && $job->phone === '+966538825803'
                && $job->idempotencyKey === 'idem-1';
        });
    }

    public function testJobDoesNotRetryPermanentErrors(): void
    {
        Http::fake([
            'https://graph.facebook.com/*' => Http::response([
                'error' => ['message' => 'Bad template', 'code' => 132000],
            ], 400),
        ]);

        $job = new SendWhatsAppMessageJob('template', '+966538825803', [
            'template_name' => 'hello_world',
            'language' => 'en_US',
        ]);

        // Permanent Meta errors are failed without rethrowing (no queue retry).
        $job->handle($this->service);

        $this->assertTrue(true);
    }
}
