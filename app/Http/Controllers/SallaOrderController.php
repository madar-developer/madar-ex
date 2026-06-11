<?php

namespace App\Http\Controllers;

use App\Services\Salla\SallaOrderActionService;
use App\Services\Salla\SallaOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SallaToken;
use App\Models\Order;
use App\Models\OrderStatus;
use PDF;

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

    public function cancel(Request $request, SallaOrderService $service)
    {
        $orderId = $request->get('order_id');
        $payload = $request->all();
        Log::channel('salla')->info('Salla order cancelled received', $payload);
        $data = $request->validate([
            'merchant_id' => ['nullable', 'integer'],
        ]);

        $order = Order::where('order_source', 'salla')
            ->where(function ($query) use ($orderId) {
                $query->where('refrence_no', (string) $orderId)
                    ->orWhere('shipment_ref_id', (string) $orderId);

                if (is_numeric($orderId)) {
                    $query->orWhere('id', (int) $orderId);
                }
            })
            ->first();

        $merchantId = $data['merchant_id'] ?? null;
        if (!$merchantId && $order && $order->company_id) {
            $merchantId = SallaToken::where('company_id', $order->company_id)
                ->whereNotNull('merchant_id')
                ->latest('id')
                ->value('merchant_id');
        }

        $shipmentId = ($order && $order->shipment_ref_id) ? $order->shipment_ref_id : $orderId;
        // $sallaResponse = $service->cancel($shipmentId, [], $merchantId ? (int) $merchantId : null);

        if ($order && $order->status !== 'cancelled') {
            $order->update(['status' => 'cancelled']);

            $statusData = OrderStatus::where('key', 'cancelled')->first();
            if ($statusData) {
                $order->OrderLog()->create([
                    'status' => 'cancelled',
                    'details' => $statusData->details,
                ]);
            }
        }

        Log::channel('salla')->info('Salla order cancelled', [
            'order_id' => $order?->id,
            'salla_order_id' => $orderId,
            'shipment_id' => $shipmentId,
            'merchant_id' => $merchantId,
        ]);

        return response()->json([
            'message' => 'Order cancelled successfully',
            'local_order_id' => $order?->id,
        ]);
    }

    public function createOrder (Request $request){
        $payload = $request->all();
        if ($request->has('order')) {
            $request->merge(['data' => $request->order]);
            $payload = $request->all();
        }

        Log::channel('salla')->info('Salla Webhook received', $payload);

        $sallaOrderId = data_get($payload, 'data.reference_id');
        if (empty($sallaOrderId)) {
            return response()->json(['message' => 'Webhook received'], 200);
        }
        if (data_get($payload, 'event') == 'app.installed'){
            return response()->json(['message' => 'Webhook received'], 200);
        }
        $merchantId = data_get($payload, 'data.store.id');
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
                'shipment_ref_id' => data_get($payload, 'data.shipping.shipment_reference'),
                'order_payload' => json_encode($request->get('order')),
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
        Log::channel('salla')->info('Salla Shipment created received', $payload);
        $order = Order::where('shipment_ref_id', $request->get('shipment_id'))->first();
        if(!$order){
            $order = Order::where('refrence_no', $request->get('order_id'))->first();
        }
        if(!$order){
            return response()->json(['message' => 'Order not found'], 404);
        }
        $pdf_url = $this->orderPdfDownloadUrl($order);
        return response()->json([
            'shipment_id' => $order->serial,
            'pdf_url' => $pdf_url,
            'invoice_url' => $pdf_url,
            'message' => 'Webhook received'
        ], 200);
    }

    public function downloadPdf($orderId)
    {
        $order = Order::findOrFail($orderId);

        return $this->orderPdfDownloadUrl($order);
    }

    protected function orderPdfDownloadUrl(Order $order): string
    {
        $title = 'طلب : ' . ($order->serial ?: $order->id);
        $filename = 'invoice-' . preg_replace('/[^A-Za-z0-9\-_]/', '-', (string) ($order->serial ?: $order->id)) . '.pdf';
        $path = public_path('cdn/' . $filename);

        if (!is_dir(public_path('cdn'))) {
            mkdir(public_path('cdn'), 0755, true);
        }

        ini_set('pcre.backtrack_limit', '5000000');
        ini_set('memory_limit', '512M');

        PDF::loadView('admin.reports.pdf.order', compact('order', 'title'))
            ->save($path);

        return url('/cdn/' . $filename);
    }

    // public function downloadPdf($orderId)
    // {
    //     $order = Order::findOrFail($orderId);
    //     $title = 'طلب : ' . ($order->serial ?: $order->id);

    //     ini_set('pcre.backtrack_limit', '5000000');
    //     ini_set('memory_limit', '512M');

    //     $pdf = PDF::loadView('admin.reports.pdf.order', compact('order', 'title'));

    //     return $pdf->download('invoice-' . ($order->serial ?: $order->id) . '.pdf');
    // }

    public function updateShipment (Request $request){
        $payload = $request->all();

        Log::channel('salla')->info('Salla Shipment updated received', $payload);

        return response()->json(['message' => 'Webhook received'], 200);
    }
}
