<?php

namespace Tests\Unit;

use App\Exceptions\SallaApiException;
use App\Models\Order;
use App\Services\Salla\SallaAuthService;
use App\Services\Salla\SallaOrderService;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class SallaOrderServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreatePostsOrderPayloadToSallaAndReturnsResponse(): void
    {
        config()->set('salla.base_url', 'https://api.salla.dev/admin/v2');

        $payload = [
            'customer' => [
                'name' => 'Test Customer',
                'mobile' => '966500000000',
            ],
            'items' => [
                ['name' => 'Test Item', 'quantity' => 1, 'price' => 100],
            ],
        ];

        $apiResponse = [
            'success' => true,
            'data' => [
                'id' => 12345,
            ],
        ];

        Http::fake([
            'https://api.salla.dev/admin/v2/orders' => Http::response($apiResponse, 201),
        ]);

        $authService = Mockery::mock(SallaAuthService::class);
        $authService->shouldReceive('getValidAccessToken')
            ->once()
            ->with(null)
            ->andReturn('fake_access_token');

        $service = new SallaOrderService($authService);
        $result = $service->create($payload);

        fwrite(STDOUT, PHP_EOL.'Salla create success response: '.json_encode($result, JSON_UNESCAPED_UNICODE).PHP_EOL);

        $this->assertSame($apiResponse, $result);

        Http::assertSent(function ($request) use ($payload) {
            return $request->method() === 'POST'
                && $request->url() === 'https://api.salla.dev/admin/v2/orders'
                && $request['customer']['name'] === $payload['customer']['name']
                && $request['customer']['mobile'] === $payload['customer']['mobile']
                && $request->hasHeader('Authorization', 'Bearer fake_access_token');
        });
    }

    public function testCreateThrowsSallaApiExceptionWhenSallaReturnsError(): void
    {
        config()->set('salla.base_url', 'https://api.salla.dev/admin/v2');

        Http::fake([
            'https://api.salla.dev/admin/v2/orders' => Http::response([
                'message' => 'Validation failed',
            ], 422),
        ]);

        $authService = Mockery::mock(SallaAuthService::class);
        $authService->shouldReceive('getValidAccessToken')
            ->once()
            ->with(null)
            ->andReturn('fake_access_token');

        $service = new SallaOrderService($authService);

        $this->expectException(SallaApiException::class);
        $this->expectExceptionCode(422);
        $this->expectExceptionMessage('Salla create order failed');

        fwrite(STDOUT, PHP_EOL.'Salla create error response: '.json_encode(['message' => 'Validation failed'], JSON_UNESCAPED_UNICODE).PHP_EOL);

        $service->create([
            'customer' => ['name' => 'Bad Request Customer'],
        ]);
    }

    public function testStatusUpdatePayloadUsesExistingShippingNumberAndOmitsWaybillFields(): void
    {
        config()->set('salla.base_url', 'https://api.salla.dev/admin/v2');

        Http::fake([
            'https://api.salla.dev/admin/v2/shipments/1645456555' => Http::response([
                'success' => true,
                'data' => [
                    'id' => 1645456555,
                    'shipping_number' => 'mx-20260969925',
                    'tracking_number' => 'mx-20260969925',
                ],
            ], 200),
        ]);

        $authService = Mockery::mock(SallaAuthService::class);
        $authService->shouldReceive('getValidAccessToken')
            ->once()
            ->with(null)
            ->andReturn('fake_access_token');

        $order = new Order();
        $order->serial = 'mx-20260969925';
        $order->shipment_ref_id = '1645456555';
        $order->refrence_no = '284449016';

        $service = new SallaOrderService($authService);
        $payload = $service->statusUpdatePayload($order, 'in_transit');

        $this->assertSame([
            'status' => 'in_transit',
            'shipment_number' => 'mx-20260969925',
        ], $payload);
        $this->assertArrayNotHasKey('order_id', $payload);
        $this->assertArrayNotHasKey('tracking_number', $payload);
    }

    public function testStatusUpdatePayloadFallsBackToSerialWhenShipmentLookupFails(): void
    {
        config()->set('salla.base_url', 'https://api.salla.dev/admin/v2');

        Http::fake([
            'https://api.salla.dev/admin/v2/shipments/1645456555' => Http::response([
                'status' => 422,
                'success' => false,
            ], 422),
        ]);

        $authService = Mockery::mock(SallaAuthService::class);
        $authService->shouldReceive('getValidAccessToken')
            ->once()
            ->with(null)
            ->andReturn('fake_access_token');

        $order = new Order();
        $order->serial = 'mx-20260969925';
        $order->shipment_ref_id = '1645456555';
        $order->refrence_no = '284449016';

        $service = new SallaOrderService($authService);
        $payload = $service->statusUpdatePayload($order, 'in_transit');

        $this->assertSame('in_transit', $payload['status']);
        $this->assertSame('mx-20260969925', $payload['shipment_number']);
        $this->assertArrayNotHasKey('order_id', $payload);
        $this->assertArrayNotHasKey('tracking_number', $payload);
    }
}
