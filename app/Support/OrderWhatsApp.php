<?php

namespace App\Support;

use App\Jobs\SendWhatsAppMessageJob;
use App\Models\MessageTemplate;
use App\Models\Order;
use App\Services\WhatsApp\WhatsAppService;

class OrderWhatsApp
{
    /**
     * Queue/send WhatsApp using the company+status MessageTemplate WhatsApp config.
     *
     * @return array{queued?: bool, success?: bool, skipped?: string, message_id?: string|null, template_id?: int|null, template_name?: string|null}
     */
    public static function sendForOrder(
        Order $order,
        ?string $status = null,
        bool $async = true,
        ?string $idempotencyKey = null
    ): array {
        if (! config('whatsapp.enabled')) {
            return ['skipped' => 'whatsapp_disabled'];
        }

        if (! $order->phone) {
            return ['skipped' => 'phone_missing', 'order_id' => $order->id];
        }

        $status = $status ?: (string) $order->status;
        $template = MessageTemplate::forCompanyStatusWhatsApp($order->company_id, $status);

        if (! $template) {
            return [
                'skipped' => 'template_not_found',
                'order_id' => $order->id,
                'company_id' => $order->company_id,
                'status' => $status,
            ];
        }

        return static::sendWithTemplate($order, $template, $status, $async, $idempotencyKey);
    }

    /**
     * @return array{queued?: bool, success?: bool, skipped?: string, message_id?: string|null, template_id?: int|null, template_name?: string|null}
     */
    public static function sendWithTemplate(
        Order $order,
        MessageTemplate $template,
        ?string $status = null,
        bool $async = true,
        ?string $idempotencyKey = null
    ): array {
        if (! config('whatsapp.enabled')) {
            return ['skipped' => 'whatsapp_disabled'];
        }

        if (! $template->supportsWhatsApp()) {
            return ['skipped' => 'whatsapp_not_configured', 'template_id' => $template->id];
        }

        if (! $order->phone) {
            return ['skipped' => 'phone_missing', 'order_id' => $order->id];
        }

        $status = $status ?: (string) $order->status;
        $phone = static::internationalPhone($order);
        $language = $template->whatsapp_language ?: (string) config('whatsapp.default_language', 'ar');
        $components = $template->buildWhatsAppComponents($order, $status);
        $payload = [
            'template_name' => $template->whatsapp_template_name,
            'language' => $language,
            'components' => $components,
        ];

        $idempotencyKey = $idempotencyKey
            ?: 'order-'.$order->id.'-tpl-'.$template->id.'-'.$status.'-'.now()->format('YmdHi');

        if ($async) {
            SendWhatsAppMessageJob::dispatch('template', $phone, $payload, $idempotencyKey);

            return [
                'queued' => true,
                'order_id' => $order->id,
                'phone' => $phone,
                'template_id' => $template->id,
                'template_name' => $template->whatsapp_template_name,
                'whatsapp_template_id' => $template->whatsapp_template_id,
                'status' => $status,
            ];
        }

        $result = app(WhatsAppService::class)->sendTemplate(
            $phone,
            $template->whatsapp_template_name,
            $language,
            $components,
            $idempotencyKey
        );

        return [
            'success' => (bool) ($result['success'] ?? false),
            'order_id' => $order->id,
            'phone' => $phone,
            'template_id' => $template->id,
            'template_name' => $template->whatsapp_template_name,
            'whatsapp_template_id' => $template->whatsapp_template_id,
            'message_id' => $result['message_id'] ?? null,
            'status' => $status,
        ];
    }

    /**
     * @param  list<int>  $orderIds
     * @return list<array<string, mixed>>
     */
    public static function sendForOrderIds(
        array $orderIds,
        ?string $status = null,
        bool $async = true,
        ?int $companyId = null
    ): array {
        $orders = Order::query()
            ->with(['Company', 'PaymentMethod'])
            ->whereIn('id', $orderIds)
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->get()
            ->keyBy('id');

        $results = [];

        foreach ($orderIds as $orderId) {
            $order = $orders->get((int) $orderId);

            if (! $order) {
                $results[] = [
                    'order_id' => (int) $orderId,
                    'skipped' => 'order_not_found',
                ];
                continue;
            }

            try {
                $results[] = static::sendForOrder($order, $status, $async);
            } catch (\Throwable $e) {
                $results[] = [
                    'order_id' => $order->id,
                    'skipped' => 'error',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Queue the approved delivery_template_for_an_immediate_customer template for an order.
     */
    public static function sendImmediateDelivery(Order $order, bool $async = true): ?array
    {
        if (! config('whatsapp.enabled') || ! $order->phone) {
            return null;
        }

        $phone = static::internationalPhone($order);
        $payload = static::immediateDeliveryPayload($order);

        if ($async) {
            SendWhatsAppMessageJob::dispatch(
                'template',
                $phone,
                $payload,
                'order-'.$order->id.'-immediate-delivery'
            );

            return ['queued' => true];
        }

        $params = array_column($payload['components'][0]['parameters'], 'text');

        return app(WhatsAppService::class)->sendImmediateDeliveryTemplate(
            $phone,
            $params[0],
            $params[1],
            $params[2],
            $params[3],
            $params[4],
            $params[5],
            'order-'.$order->id.'-immediate-delivery'
        );
    }

    /**
     * @return array{template_name: string, language: string, components: array<int, array<string, mixed>>}
     */
    public static function immediateDeliveryPayload(Order $order): array
    {
        $order->loadMissing(['Company', 'PaymentMethod']);

        return [
            'template_name' => 'delivery_template_for_an_immediate_customer',
            'language' => 'ar',
            'components' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => (string) ($order->recipent_name ?: '-')],
                        ['type' => 'text', 'text' => (string) ($order->refrence_no ?: $order->serial ?: '-')],
                        ['type' => 'text', 'text' => (string) (optional($order->Company)->name ?: '-')],
                        ['type' => 'text', 'text' => (string) (optional($order->PaymentMethod)->name ?: '-')],
                        ['type' => 'text', 'text' => number_format((float) $order->price, 2, '.', '')],
                        ['type' => 'text', 'text' => (string) ($order->serial ?: $order->refrence_no ?: '-')],
                    ],
                ],
            ],
        ];
    }

    /**
     * Build an international phone for WhatsApp from an order recipient number.
     * Prefers +966 when the stored value looks like a local Saudi mobile.
     */
    public static function internationalPhone(Order $order): string
    {
        $raw = (string) $order->getRawOriginal('phone');
        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        if (str_starts_with($digits, '00')) {
            return '+'.substr($digits, 2);
        }

        if (str_starts_with($digits, '966')) {
            return '+'.$digits;
        }

        // Local Saudi mobile stored as 05xxxxxxxx (after model accessor / DB).
        if (str_starts_with($digits, '05') && strlen($digits) === 10) {
            return '+966'.substr($digits, 1);
        }

        // Egyptian local 01xxxxxxxxx
        if (str_starts_with($digits, '01') && strlen($digits) === 11) {
            return '+20'.substr($digits, 1);
        }

        if (str_starts_with($raw, '+')) {
            return $raw;
        }

        return '+'.$digits;
    }
}
