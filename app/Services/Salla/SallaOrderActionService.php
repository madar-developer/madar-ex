<?php

namespace App\Services\Salla;

use App\Exceptions\SallaApiException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SallaOrderActionService
{
    public function __construct(
        protected SallaAuthService $authService
    ) {}

    public function execute(array $operations, array $filters = [], ?int $merchantId = null): array
    {
        $payload = [
            'operations' => $operations,
            'filters' => $filters,
        ];

        $response = $this->client($merchantId)->post('/orders/actions', $payload);

        if ($response->failed()) {
            throw new SallaApiException(
                responseData: $response->json() ?? [],
                message: 'Salla order actions request failed',
                code: $response->status()
            );
        }

        return $response->json();
    }

    public function changeStatus(
        array $orderIds,
        int $statusId,
        bool $sendStatusSms = false,
        bool $returnPolicy = false,
        bool $restoreItems = false,
        ?string $note = null,
        ?int $branchId = null,
        ?int $merchantId = null
    ): array {
        $operation = [
            'action_name' => 'change_status',
            'value' => array_filter([
                'status' => $statusId,
                'send_status_sms' => $sendStatusSms,
                'return_police' => $returnPolicy, // نفس الاسم الموجود في docs
                'restore_items' => $restoreItems,
                'note' => $note,
                'branch_id' => $branchId,
            ], fn ($value) => $value !== null),
        ];

        return $this->execute(
            operations: [$operation],
            filters: ['order_ids' => $orderIds],
            merchantId: $merchantId
        );
    }

    public function assignUsers(array $orderIds, array $userIds, ?int $merchantId = null): array
    {
        return $this->execute(
            operations: [
                [
                    'action_name' => 'assign_users',
                    'value' => $userIds,
                ]
            ],
            filters: ['order_ids' => $orderIds],
            merchantId: $merchantId
        );
    }

    public function assignTags(array $orderIds, array $tags, ?int $merchantId = null): array
    {
        return $this->execute(
            operations: [
                [
                    'action_name' => 'assign_tags',
                    'value' => $tags,
                ]
            ],
            filters: ['order_ids' => $orderIds],
            merchantId: $merchantId
        );
    }

    public function printPrepareList(array $orderIds, ?int $merchantId = null): array
    {
        return $this->execute(
            operations: [
                ['action_name' => 'print_prepare_list']
            ],
            filters: ['order_ids' => $orderIds],
            merchantId: $merchantId
        );
    }

    public function updateSingleOrderStatus(
        int|string $orderId,
        array $statusPayload,
        ?int $merchantId = null
    ): array {
        $response = $this->client($merchantId)->post("/orders/{$orderId}/status", $statusPayload);

        if ($response->failed()) {
            throw new SallaApiException(
                responseData: $response->json() ?? [],
                message: 'Salla single order status update failed',
                code: $response->status()
            );
        }

        return $response->json();
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
}