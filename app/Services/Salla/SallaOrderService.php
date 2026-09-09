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

        return $this->handleResponse($response, 'Salla create order failed');
    }

    public function list(array $filters = [], ?int $merchantId = null): array
    {
        $response = $this->client($merchantId)->get('/orders', $this->cleanQuery($filters));

        return $this->handleResponse($response, 'Salla list orders failed');
    }

    public function details(int|string $orderId, array $query = [], ?int $merchantId = null): array
    {
        $response = $this->client($merchantId)->get("/orders/{$orderId}", $this->cleanQuery($query));

        return $this->handleResponse($response, 'Salla order details failed');
    }

    public function shipmentDetails(int|string $shipmentId, ?int $merchantId = null): array
    {
        $response = $this->client($merchantId)->get("/shipments/{$shipmentId}");

        return $this->handleResponse($response, 'Salla shipment details failed');
    }

    public function update(int|string $shipmentId, array $payload, ?int $merchantId = null): array
    {
        // $response = $this->client($merchantId)->put("/orders/{$orderId}", $payload);
        $response = $this->client($merchantId)->put("/shipments/{$shipmentId}", $payload);
        // update shipment details  through shepment id

        return $this->handleResponse($response, 'Salla update shipment failed');
    }

    public function actions(array $operations, array $filters = [], ?int $merchantId = null): array
    {
        $payload = [
            'operations' => $operations,
            'filters' => $filters,
        ];

        $response = $this->client($merchantId)->post('/orders/actions', $payload);

        return $this->handleResponse($response, 'Salla order actions failed');
    }

    public function updateStatus(int|string $shipmentId, array $payload, ?int $merchantId = null): array
    {
        // Status-only updates must not resend order_id / tracking_number / pdf_label.
        // Salla treats those as issuing a new waybill and returns 422 if one already exists.
        $response = $this->client($merchantId)->put("/shipments/{$shipmentId}", $payload);

        return $this->handleResponse($response, 'Salla update order status failed');
    }

    public function statusUpdatePayload(Order $order, string $sallaSlug, ?int $merchantId = null): array
    {
        return [
            'status' => $sallaSlug,
            'shipment_number' => $this->resolveShipmentNumber($order, $merchantId),
        ];
    }

    public function resolveShipmentId(Order $order): int|string|null
    {
        return $order->shipment_ref_id ?: $order->serial;
    }

    protected function resolveShipmentNumber(Order $order, ?int $merchantId = null): string
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
            } catch (\Throwable $e) {
                // Use the local AWB. Salla requires this to match the first waybill request.
            }
        }

        return (string) ($order->serial ?: $order->shipment_ref_id);
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

    protected function handleResponse($response, string $message): array
    {
        if ($response->failed()) {
            throw new SallaApiException(
                responseData: $response->json() ?? [],
                message: $message,
                code: $response->status()
            );
        }

        return $response->json();
    }

    protected function cleanQuery(array $filters): array
    {
        return array_filter($filters, fn ($v) => $v !== null && $v !== '');
    }
}