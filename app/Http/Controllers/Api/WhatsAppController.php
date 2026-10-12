<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\WhatsAppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WhatsApp\SendDocumentRequest;
use App\Http\Requests\Api\WhatsApp\SendImageRequest;
use App\Http\Requests\Api\WhatsApp\SendOrdersRequest;
use App\Http\Requests\Api\WhatsApp\SendTemplateRequest;
use App\Http\Requests\Api\WhatsApp\SendTextRequest;
use App\Jobs\SendWhatsAppMessageJob;
use App\Services\WhatsApp\WhatsAppService;
use App\Support\OrderWhatsApp;
use Illuminate\Http\JsonResponse;

class WhatsAppController extends Controller
{
    public function __construct(
        protected WhatsAppService $whatsApp
    ) {}

    /**
     * Send WhatsApp templates for many orders using each company's MessageTemplate
     * (matched by company_id + status, WhatsApp-enabled templates only).
     */
    public function sendOrders(SendOrdersRequest $request): JsonResponse
    {
        if (! config('whatsapp.enabled')) {
            return response()->json([
                'data' => new \stdClass,
                'errors' => ['whatsapp_disabled'],
                'message' => 'WhatsApp integration is disabled.',
                'code' => getMsgCode('error'),
            ], 200);
        }

        $company = auth('api-company')->user();
        $orderIds = array_map('intval', $request->input('order_ids', []));
        $async = $request->boolean('async', true);
        $status = $request->input('status');

        $results = OrderWhatsApp::sendForOrderIds(
            $orderIds,
            $status,
            $async,
            (int) $company->id
        );

        $queued = collect($results)->where('queued', true)->count();
        $skipped = collect($results)->whereNotNull('skipped')->count();

        return response()->json([
            'data' => [
                'total' => count($results),
                'queued' => $queued,
                'skipped' => $skipped,
                'results' => $results,
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    public function sendTemplate(SendTemplateRequest $request): JsonResponse
    {
        $async = (bool) $request->boolean('async', true);

        if ($async) {
            SendWhatsAppMessageJob::dispatch(
                'template',
                (string) $request->input('phone'),
                [
                    'template_name' => $request->input('template_name'),
                    'language' => $request->input('language', config('whatsapp.default_language', 'ar')),
                    'components' => $request->input('components', []),
                ],
                $request->input('idempotency_key')
            );

            return $this->queuedResponse();
        }

        return $this->runSend(fn () => $this->whatsApp->sendTemplate(
            (string) $request->input('phone'),
            (string) $request->input('template_name'),
            (string) $request->input('language', config('whatsapp.default_language', 'ar')),
            (array) $request->input('components', []),
            $request->input('idempotency_key')
        ));
    }

    public function sendText(SendTextRequest $request): JsonResponse
    {
        $async = (bool) $request->boolean('async', true);

        if ($async) {
            SendWhatsAppMessageJob::dispatch(
                'text',
                (string) $request->input('phone'),
                ['message' => $request->input('message')],
                $request->input('idempotency_key')
            );

            return $this->queuedResponse();
        }

        return $this->runSend(fn () => $this->whatsApp->sendText(
            (string) $request->input('phone'),
            (string) $request->input('message'),
            $request->input('idempotency_key')
        ));
    }

    public function sendImage(SendImageRequest $request): JsonResponse
    {
        $async = (bool) $request->boolean('async', true);

        if ($async) {
            SendWhatsAppMessageJob::dispatch(
                'image',
                (string) $request->input('phone'),
                [
                    'image_url' => $request->input('image_url'),
                    'caption' => $request->input('caption'),
                ],
                $request->input('idempotency_key')
            );

            return $this->queuedResponse();
        }

        return $this->runSend(fn () => $this->whatsApp->sendImage(
            (string) $request->input('phone'),
            (string) $request->input('image_url'),
            $request->input('caption'),
            $request->input('idempotency_key')
        ));
    }

    public function sendDocument(SendDocumentRequest $request): JsonResponse
    {
        $async = (bool) $request->boolean('async', true);

        if ($async) {
            SendWhatsAppMessageJob::dispatch(
                'document',
                (string) $request->input('phone'),
                [
                    'document_url' => $request->input('document_url'),
                    'filename' => $request->input('filename'),
                ],
                $request->input('idempotency_key')
            );

            return $this->queuedResponse();
        }

        return $this->runSend(fn () => $this->whatsApp->sendDocument(
            (string) $request->input('phone'),
            (string) $request->input('document_url'),
            $request->input('filename'),
            $request->input('idempotency_key')
        ));
    }

    /**
     * @param  callable(): array  $callback
     */
    protected function runSend(callable $callback): JsonResponse
    {
        try {
            $result = $callback();

            return response()->json([
                'data' => [
                    'message_id' => $result['message_id'] ?? null,
                    'status' => $result['status'] ?? 'accepted',
                    'message_db_id' => $result['message_db_id'] ?? null,
                ],
                'message' => 'success',
                'code' => getMsgCode('success'),
            ]);
        } catch (WhatsAppException $e) {
            $status = $e->httpStatus && $e->httpStatus >= 400 ? $e->httpStatus : 400;

            return response()->json([
                'data' => new \stdClass,
                'errors' => [$e->getMessage()],
                'message' => $e->getMessage(),
                'code' => getMsgCode('error'),
            ], $status >= 500 ? 503 : ($status === 401 || $status === 403 ? $status : 200));
        }
    }

    protected function queuedResponse(): JsonResponse
    {
        return response()->json([
            'data' => [
                'queued' => true,
            ],
            'message' => 'queued',
            'code' => getMsgCode('success'),
        ]);
    }
}
