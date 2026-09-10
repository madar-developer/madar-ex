<?php

namespace App\Services\Salla;

use App\Exceptions\SallaApiException;
use App\Models\Order;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

// order ref id should be reference_id
class SallaOrderService
{
    public function __construct(
        protected SallaAuthService $authService
    ) {}

    public function create(array $payload, ?int $merchantId = null): array
    {
        $response = $this->client($merchantId)->post('/orders', $payload);

        return $this->handleResponse($response, 'Salla create order failed', [
            'action' => 'order.create',
            'request' => $payload,
        ]);
    }

    public function list(array $filters = [], ?int $merchantId = null): array
    {
        $response = $this->client($merchantId)->get('/orders', $this->cleanQuery($filters));

        return $this->handleResponse($response, 'Salla list orders failed', [
            'action' => 'order.list',
            'request' => $filters,
        ]);
    }

    public function details(int|string $orderId, array $query = [], ?int $merchantId = null): array
    {
        $response = $this->client($merchantId)->get("/orders/{$orderId}", $this->cleanQuery($query));

        return $this->handleResponse($response, 'Salla order details failed', [
            'action' => 'order.details',
            'order_ref' => $orderId,
        ]);
    }

    public function shipmentDetails(int|string $shipmentId, ?int $merchantId = null): array
    {
        $response = $this->client($merchantId)->get("/shipments/{$shipmentId}");

        return $this->handleResponse($response, 'Salla shipment details failed', [
            'action' => 'shipment.details',
            'shipment_id' => $shipmentId,
        ]);
    }

    public function update(int|string $shipmentId, array $payload, ?int $merchantId = null): array
    {
        $response = $this->client($merchantId)->put("/shipments/{$shipmentId}", $payload);

        return $this->handleResponse($response, 'Salla update shipment failed', [
            'action' => 'shipment.update',
            'shipment_id' => $shipmentId,
            'request' => $payload,
        ]);
    }

    public function actions(array $operations, array $filters = [], ?int $merchantId = null): array
    {
        $payload = [
            'operations' => $operations,
            'filters' => $filters,
        ];

        $response = $this->client($merchantId)->post('/orders/actions', $payload);

        return $this->handleResponse($response, 'Salla order actions failed', [
            'action' => 'order.actions',
            'request' => $payload,
        ]);
    }

    public function updateStatus(int|string $shipmentId, array $payload, ?int $merchantId = null): array
    {
        $response = $this->client($merchantId)->put("/shipments/{$shipmentId}", $payload);

        return $this->handleResponse($response, 'Salla update order status failed', [
            'action' => 'shipment.update_status',
            'shipment_id' => $shipmentId,
            'request' => $payload,
        ]);
    }

    public function statusUpdatePayload(Order $order, string $sallaSlug, ?int $merchantId = null): array
    {
        $payload = [
            'status' => $sallaSlug,
        ];

        $shipmentNumber = $this->resolveShipmentNumber($order, $merchantId);
        if ($shipmentNumber) {
            $payload['shipment_number'] = $shipmentNumber;
        }

        return $payload;
    }

    public function updateStatusForOrder(Order $order, string $sallaSlug, ?int $merchantId = null): array
    {
        $shipmentId = $this->resolveShipmentId($order);
        $payload = $this->statusUpdatePayload($order, $sallaSlug, $merchantId);

        try {
            $response = $this->updateStatus($shipmentId, $payload, $merchantId);
        } catch (SallaApiException $e) {
            if (! $this->rejectsShipmentNumber($e)) {
                throw $e;
            }

            $payload = ['status' => $sallaSlug];
            $response = $this->updateStatusWithoutShipmentNumber($shipmentId, $sallaSlug, $merchantId);
        }

        return [
            'response' => $response,
            'payload' => $payload,
            'shipment_id' => $shipmentId,
        ];
    }

    public function resolveShipmentId(Order $order): int|string|null
    {
        return $order->shipment_ref_id ?: $order->serial;
    }

    protected function updateStatusWithoutShipmentNumber(
        int|string $shipmentId,
        string $sallaSlug,
        ?int $merchantId = null
    ): array {
        $needsInProgressFirst = ! in_array($sallaSlug, ['created', 'in_progress', 'cancelled'], true);

        if ($needsInProgressFirst) {
            try {
                $this->updateStatus($shipmentId, ['status' => 'in_progress'], $merchantId);
            } catch (SallaApiException $e) {
                // Already past in_progress (e.g. delivering/delivered) — continue to the target status.
            }
        }

        return $this->updateStatus($shipmentId, ['status' => $sallaSlug], $merchantId);
    }

    protected function rejectsShipmentNumber(SallaApiException $e): bool
    {
        $fields = data_get($e->responseData, 'error.fields.shipment_number', []);
        $text = implode(' ', is_array($fields) ? $fields : [(string) $fields]);

        return str_contains($text, 'لا يتطلب إرسال رقم الشحنة')
            || str_contains($text, 'قيد المعالجة');
    }

    protected function resolveShipmentNumber(Order $order, ?int $merchantId = null): ?string
    {
        $shipmentId = $this->resolveShipmentId($order);

        if (! empty($shipmentId)) {
            try {
                $details = $this->shipmentDetails($shipmentId, $merchantId);
                $existing = data_get($details, 'data.shipping_number')
                    ?: data_get($details, 'data.tracking_number');

                if (! empty($existing) && (string) $existing !== '0') {
                    return (string) $existing;
                }

                // Salla already has the shipment but no AWB — some courier types reject shipment_number.
                return null;
            } catch (\Throwable $e) {
                // Unknown current number; keep a local fallback for waybill-based stores.
            }
        }

        $fallback = (string) ($order->serial ?: $order->shipment_ref_id);

        return $fallback !== '' ? $fallback : null;
    }

    public function cancel(int|string $shipmentId, array $payload = [], ?int $merchantId = null): array
    {
        return $this->updateStatus(
            $shipmentId,
            array_merge($payload, ['slug' => 'cancelled']),
            $merchantId
        );
    }

    public function changeStatusBulk(
        array $orderIds,
        int $statusId,
        bool $sendStatusSms = false,
        bool $returnPolice = false,
        bool $restoreItems = false,
        ?string $note = null,
        ?int $branchId = null,
        ?int $merchantId = null
    ): array {
        return $this->actions(
            operations: [[
                'action_name' => 'change_status',
                'value' => array_filter([
                    'status' => $statusId,
                    'send_status_sms' => $sendStatusSms,
                    'return_police' => $returnPolice,
                    'restore_items' => $restoreItems,
                    'note' => $note,
                    'branch_id' => $branchId,
                ], fn ($v) => $v !== null),
            ]],
            filters: [
                'order_ids' => $orderIds,
            ],
            merchantId: $merchantId
        );
    }

    public function assignUsersBulk(array $orderIds, array $userIds, ?int $merchantId = null): array
    {
        return $this->actions(
            operations: [[
                'action_name' => 'assign_users',
                'value' => $userIds,
            ]],
            filters: [
                'order_ids' => $orderIds,
            ],
            merchantId: $merchantId
        );
    }

    public function assignTagsBulk(array $orderIds, array $tags, ?int $merchantId = null): array
    {
        return $this->actions(
            operations: [[
                'action_name' => 'assign_tags',
                'value' => $tags,
            ]],
            filters: [
                'order_ids' => $orderIds,
            ],
            merchantId: $merchantId
        );
    }

    public function printPrepareListBulk(array $orderIds, ?int $merchantId = null): array
    {
        return $this->actions(
            operations: [[
                'action_name' => 'print_prepare_list',
            ]],
            filters: [
                'order_ids' => $orderIds,
            ],
            merchantId: $merchantId
        );
    }

    protected function client(?int $merchantId = null): PendingRequest
    {
        $token = $this->authService->getValidAccessToken($merchantId);

        return Http::baseUrl(rtrim(config('salla.base_url'), '/'))
            ->acceptJson()
            ->asJson()
            ->withToken($token)
            ->timeout(30);
    }

    protected function handleResponse($response, string $message, array $context = []): array
    {
        $body = $response->json();
        $body = is_array($body) ? $body : [];

        app(SallaResponseLogger::class)->record([
            'action' => $context['action'] ?? $message,
            'direction' => 'outbound',
            'http_status' => $response->status(),
            'success' => $response->successful(),
            'shipment_id' => $context['shipment_id'] ?? null,
            'order_ref' => $context['order_ref'] ?? null,
            'request' => $context['request'] ?? null,
            'response' => $body,
            'order' => $context['order'] ?? null,
        ]);

        if ($response->failed()) {
            throw new SallaApiException(
                responseData: $body,
                message: $message,
                code: $response->status()
            );
        }

        return $body;
    }

    protected function cleanQuery(array $filters): array
    {
        return array_filter($filters, fn ($v) => $v !== null && $v !== '');
    }
}