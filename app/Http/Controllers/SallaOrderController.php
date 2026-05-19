<?php

namespace App\Http\Controllers;

use App\Services\Salla\SallaOrderActionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function createOrder (Request $request){
        $payload = $request->all();

        Log::info('Salla Webhook received', $payload);

        $sallaOrderId = data_get($payload, 'data.id');
        if (empty($sallaOrderId)) {
            return response()->json(['message' => 'Webhook received'], 200);
        }
        if (data_get($payload, 'event') == 'app.installed'){
            return response()->json(['message' => 'Webhook received'], 200);
        }
        $merchantId = data_get($payload, 'merchant');
        $companyId = null;
        if (!empty($merchantId)) {
            $companyId = SallaToken::where('merchant_id', (int) $merchantId)
                ->whereNotNull('company_id')
                ->latest('id')
                ->value('company_id');
        }

        $order = Order::where('refrence_no', (string) $sallaOrderId)->first();
        $incomingStatusName = data_get($payload, 'data.status.name')
            ?? data_get($payload, 'data.status.slug')
            ?? data_get($payload, 'event');

        if (!$order) {
            $order = Order::create([
                'refrence_no' => (string) $sallaOrderId,
                'recipent_name' => data_get($payload, 'data.customer.full_name', 'Salla Customer'),
                'phone' => (string) data_get($payload, 'data.customer.mobile', ''),
                'adress_details' => data_get($payload, 'data.shipping.address.shipping_address'),
                'notes' => data_get($payload, 'event'),
                'price' => (int) data_get($payload, 'data.amounts.total.amount', 0),
                'status' => 'new',
                'order_source' => 'salla',
                'source_status' => $incomingStatusName,
                'company_id' => $companyId,
            ]);

            $serialBase = str_replace(' ', '', date('Y m') . $order->id);
            $order->update([
                'serial' => 'mx-' . $serialBase,
                'serial_no' => (int) $serialBase,
            ]);

            $statusData = OrderStatus::where('key', 'new')->first();
            if ($statusData) {
                $order->OrderLog()->create([
                    'status' => 'new',
                    'details' => $statusData->details,
                ]);
            }
        } else {
            $updateData = [
                'order_source' => 'salla',
                'source_status' => $incomingStatusName,
            ];
            if (empty($order->company_id) && !empty($companyId)) {
                $updateData['company_id'] = $companyId;
            }
            $order->update($updateData);
        }

        return response()->json(['message' => 'Webhook received'], 200);
    }
    public function createShipment (Request $request){
        $payload = $request->all();

        Log::info('Salla Shipment created received', $payload);

        
        return response()->json(['message' => 'Webhook received'], 200);
    }
    public function updateShipment (Request $request){
        $payload = $request->all();

        Log::info('Salla Shipment updated received', $payload);

        return response()->json(['message' => 'Webhook received'], 200);
    }
}
