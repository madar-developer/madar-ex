<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\SallaApiException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\SallaToken;
use App\Services\Salla\SallaOrderService;
use Illuminate\Http\Request;
use Throwable;

class SallaDevController extends Controller
{
    protected array $statusMap = [
        'new' => 'created',
        'init' => 'in_progress',
        'at_madar' => 'in_transit',
        'at_office' => 'delivering',
        'reschedule' => 'to_be_reattempted',
        'deliver_failed' => 'unable_to_deliver',
        'delivered' => 'delivered',
        'returned' => 'return_to_origin',
        'cancelled' => 'cancelled',
    ];

    public function index()
    {
        $title = 'Salla Dev';
        $statuses = $this->statusOptions();

        return view('admin.salla-dev.index', compact('title', 'statuses'));
    }

    public function updateStatus(Request $request, SallaOrderService $service)
    {
        $data = $request->validate([
            'order_id' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $order = $this->findOrder($data['order_id']);
        if (! $order) {
            return response()->json([
                'ok' => false,
                'message' => 'Local order not found',
            ], 404);
        }

        $merchantId = $this->merchantIdFor($order);
        $sallaSlug = $this->statusMap[$data['status']] ?? $data['status'];

        try {
            $result = $service->updateStatusForOrder($order, $sallaSlug, $merchantId);

            return response()->json([
                'ok' => true,
                'local_order' => $this->orderSummary($order, $merchantId),
                'request' => [
                    'shipment_id' => $result['shipment_id'],
                    'local_status' => $data['status'],
                    'salla_slug' => $sallaSlug,
                    'merchant_id' => $merchantId,
                    'payload' => $result['payload'],
                ],
                'salla' => $result['response'],
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, [
                'local_order' => $this->orderSummary($order, $merchantId),
                'request' => [
                    'shipment_id' => $service->resolveShipmentId($order),
                    'local_status' => $data['status'],
                    'salla_slug' => $sallaSlug,
                    'merchant_id' => $merchantId,
                ],
            ]);
        }
    }

    public function orderInfo(Request $request, SallaOrderService $service)
    {
        $data = $request->validate([
            'order_id' => ['required', 'string'],
        ]);

        $order = $this->findOrder($data['order_id']);
        if (! $order) {
            return response()->json([
                'ok' => false,
                'message' => 'Local order not found',
            ], 404);
        }

        $merchantId = $this->merchantIdFor($order);
        $shipmentId = $service->resolveShipmentId($order);
        $sallaOrderId = $this->sallaOrderId($order) ?: $data['order_id'];

        // Shipping-company tokens have shipping.read_write, not orders.read.
        // GET /orders will 401; GET /shipments is the supported lookup.
        $shipment = $this->trySallaCall(
            fn () => $shipmentId ? $service->shipmentDetails($shipmentId, $merchantId) : null
        );
        $sallaOrder = $this->trySallaCall(
            fn () => $service->details($sallaOrderId, [], $merchantId)
        );

        $ok = $shipment['data'] !== null || $sallaOrder['data'] !== null;

        return response()->json([
            'ok' => $ok,
            'message' => $ok
                ? 'Salla lookup completed'
                : ($shipment['error']['message'] ?? $sallaOrder['error']['message'] ?? 'Salla lookup failed'),
            'local_order' => $this->orderSummary($order, $merchantId),
            'request' => [
                'salla_order_id' => $sallaOrderId,
                'shipment_id' => $shipmentId,
                'merchant_id' => $merchantId,
            ],
            'salla_shipment' => $shipment['data'],
            'salla_order' => $sallaOrder['data'],
            'salla_shipment_error' => $shipment['error'],
            'salla_order_error' => $sallaOrder['error'],
        ], $ok ? 200 : (($shipment['error']['status_code'] ?? $sallaOrder['error']['status_code'] ?? 422)));
    }

    protected function statusOptions(): array
    {
        $statusRows = OrderStatus::query()->get(['key', 'name']);
        $options = [];

        foreach ($this->statusMap as $local => $salla) {
            $row = $statusRows->firstWhere('key', $local);
            $label = $row ? $row->name : $local;
            $options[$local] = "{$label} ({$local} → {$salla})";
        }

        return $options;
    }

    protected function findOrder(string $orderId): ?Order
    {
        return Order::query()
            ->where(function ($query) use ($orderId) {
                $query->where('refrence_no', $orderId)
                    ->orWhere('serial', $orderId)
                    ->orWhere('shipment_ref_id', $orderId);

                if (is_numeric($orderId)) {
                    $query->orWhere('id', (int) $orderId);
                }
            })
            ->first();
    }

    protected function merchantIdFor(Order $order): ?int
    {
        $merchantId = SallaToken::where('company_id', $order->company_id)
            ->whereNotNull('merchant_id')
            ->latest('id')
            ->value('merchant_id');

        return $merchantId ? (int) $merchantId : null;
    }

    protected function sallaOrderId(?Order $order): ?string
    {
        if (! $order) {
            return null;
        }

        $payload = json_decode((string) $order->order_payload, true);
        $sallaId = data_get($payload, 'id') ?: data_get($payload, 'data.id');

        return $sallaId ? (string) $sallaId : ($order->refrence_no ?: null);
    }

    protected function orderSummary(Order $order, ?int $merchantId): array
    {
        return [
            'id' => $order->id,
            'serial' => $order->serial,
            'refrence_no' => $order->refrence_no,
            'shipment_ref_id' => $order->shipment_ref_id,
            'status' => $order->status,
            'order_source' => $order->order_source,
            'company_id' => $order->company_id,
            'merchant_id' => $merchantId,
        ];
    }

    protected function trySallaCall(callable $callback): array
    {
        try {
            return [
                'data' => $callback(),
                'error' => null,
            ];
        } catch (Throwable $e) {
            $status = $e->getCode() > 0 ? (int) $e->getCode() : 422;

            return [
                'data' => null,
                'error' => [
                    'message' => $e->getMessage(),
                    'status_code' => $status >= 400 && $status < 600 ? $status : 422,
                    'salla_error' => $e instanceof SallaApiException ? $e->responseData : null,
                ],
            ];
        }
    }

    protected function errorResponse(Throwable $e, array $extra = [])
    {
        $status = $e->getCode() > 0 ? (int) $e->getCode() : 422;

        return response()->json(array_merge([
            'ok' => false,
            'message' => $e->getMessage(),
            'status_code' => $status,
            'salla_error' => $e instanceof SallaApiException ? $e->responseData : null,
        ], $extra), $status >= 400 && $status < 600 ? $status : 422);
    }
}
