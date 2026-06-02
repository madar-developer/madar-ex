<?php

namespace App\Services\Salla;

use App\Exceptions\SallaApiException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

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
        // $response = $this->client($merchantId)->post("/orders/{$orderId}/status", $payload);
        $response = $this->client($merchantId)->put("/shipments/{$shipmentId}", $payload);

        return $this->handleResponse($response, 'Salla update order status failed');
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