<?php

namespace App\Http\Controllers;

use App\Services\Salla\SallaOrderActionService;
use Illuminate\Http\Request;

class SallaOrderController extends Controller
{
    public function bulkChangeStatus(Request $request, SallaOrderActionService $service)
    {
        $data = $request->validate([
            'order_ids' => ['required', 'array', 'min:1'],
            'order_ids.*' => ['required'],
            'status_id' => ['required', 'integer'],
            'send_status_sms' => ['nullable', 'boolean'],
            'return_police' => ['nullable', 'boolean'],
            'restore_items' => ['nullable', 'boolean'],
            'note' => ['nullable', 'string'],
            'branch_id' => ['nullable', 'integer'],
        ]);

        $result = $service->changeStatus(
            orderIds: $data['order_ids'],
            statusId: $data['status_id'],
            sendStatusSms: (bool) ($data['send_status_sms'] ?? false),
            returnPolicy: (bool) ($data['return_police'] ?? false),
            restoreItems: (bool) ($data['restore_items'] ?? false),
            note: $data['note'] ?? null,
            branchId: $data['branch_id'] ?? null,
        );

        return response()->json($result);
    }

    public function assignUsers(Request $request, SallaOrderActionService $service)
    {
        $data = $request->validate([
            'order_ids' => ['required', 'array', 'min:1'],
            'order_ids.*' => ['required'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'integer'],
        ]);

        return response()->json(
            $service->assignUsers($data['order_ids'], $data['user_ids'])
        );
    }

    public function updateSingleStatus(Request $request, $orderId, SallaOrderActionService $service)
    {
        $data = $request->validate([
            'slug' => ['nullable', 'string'],
            'status_id' => ['nullable', 'integer'],
            'restore_items' => ['nullable', 'boolean'],
        ]);

        return response()->json(
            $service->updateSingleOrderStatus($orderId, array_filter($data, fn ($v) => $v !== null))
        );
    }
}