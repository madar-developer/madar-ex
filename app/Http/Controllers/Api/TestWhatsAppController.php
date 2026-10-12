<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\WhatsAppException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\WhatsApp\WhatsAppService;
use App\Support\OrderWhatsApp;
use Illuminate\Http\Request;

class TestWhatsAppController extends Controller
{
    /**
     * Manual smoke test: load an order and send delivery_template_for_an_immediate_customer.
     *
     * GET /api/v1/test-whatsapp/send-delivery
     * Optional query:
     *   - order_id=123
     *   - phone=+9665XXXXXXXX  (override recipient; useful for safe testing)
     *   - async=1              (queue instead of sync)
     */
    public function sendDelivery(Request $request, WhatsAppService $whatsApp)
    {
        if (! app()->environment('local') && ! config('app.debug')) {
            return response()->json([
                'data' => new \stdClass,
                'errors' => ['forbidden'],
                'message' => 'WhatsApp test endpoint is only available in local/debug.',
                'code' => getMsgCode('error'),
            ], 403);
        }

        if (! config('whatsapp.enabled')) {
            return response()->json([
                'data' => new \stdClass,
                'errors' => ['whatsapp_disabled'],
                'message' => 'Set WHATSAPP_ENABLED=true and configure WHATSAPP_ACCESS_TOKEN first.',
                'code' => getMsgCode('error'),
            ], 200);
        }

        $order = $request->filled('order_id')
            ? Order::with(['Company', 'PaymentMethod'])->find($request->integer('order_id'))
            : Order::with(['Company', 'PaymentMethod'])->orderBy('id')->first();

        if (! $order) {
            return response()->json([
                'data' => new \stdClass,
                'errors' => ['order_not_found'],
                'message' => 'No order found in database.',
                'code' => getMsgCode('notFound'),
            ], 200);
        }

        if (! $order->phone && ! $request->filled('phone')) {
            return response()->json([
                'data' => [
                    'order_id' => $order->id,
                ],
                'errors' => ['phone_missing'],
                'message' => 'Order has no phone. Pass ?phone=+9665XXXXXXXX to override.',
                'code' => getMsgCode('error'),
            ], 200);
        }

        $phone = $request->filled('phone')
            ? (string) $request->get('phone')
            : OrderWhatsApp::internationalPhone($order);

        $payload = OrderWhatsApp::immediateDeliveryPayload($order);
        $params = array_column($payload['components'][0]['parameters'], 'text');
        $async = $request->boolean('async', false);

        try {
            if ($async) {
                \App\Jobs\SendWhatsAppMessageJob::dispatch(
                    'template',
                    $phone,
                    $payload,
                    'test-order-'.$order->id.'-'.now()->timestamp
                );

                return response()->json([
                    'data' => [
                        'queued' => true,
                        'order_id' => $order->id,
                        'phone' => $phone,
                        'template' => $payload['template_name'],
                        'parameters' => $params,
                    ],
                    'message' => 'queued',
                    'code' => getMsgCode('success'),
                ]);
            }

            $result = $whatsApp->sendImmediateDeliveryTemplate(
                $phone,
                $params[0],
                $params[1],
                $params[2],
                $params[3],
                $params[4],
                $params[5],
                'test-order-'.$order->id.'-'.now()->timestamp
            );

            return response()->json([
                'data' => [
                    'order_id' => $order->id,
                    'phone' => $phone,
                    'template' => $payload['template_name'],
                    'parameters' => $params,
                    'result' => [
                        'message_id' => $result['message_id'] ?? null,
                        'status' => $result['status'] ?? null,
                        'message_db_id' => $result['message_db_id'] ?? null,
                    ],
                ],
                'message' => 'success',
                'code' => getMsgCode('success'),
            ]);
        } catch (WhatsAppException $e) {
            return response()->json([
                'data' => [
                    'order_id' => $order->id,
                    'phone' => $phone,
                    'template' => $payload['template_name'],
                    'parameters' => $params,
                    'http_status' => $e->httpStatus,
                    'context' => $e->context,
                ],
                'errors' => [$e->getMessage()],
                'message' => $e->getMessage(),
                'code' => getMsgCode('error'),
            ], 200);
        }
    }
}
