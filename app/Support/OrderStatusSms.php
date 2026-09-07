<?php

namespace App\Support;

use App\Models\MessageTemplate;
use App\Models\Order;

class OrderStatusSms
{
    /**
     * Send recipient SMS for an order status change.
     * Company templates win; legacy fallback is the old out-for-delivery text.
     */
    public static function sendFor(Order $order, string $status, bool $useLegacyFallback = false): void
    {
        $body = static::bodyFor($order, $status, $useLegacyFallback);
        if (!$body || !$order->phone) {
            return;
        }

        sendSMS(FormatPhone($order->phone), $body);
    }

    public static function bodyFor(Order $order, string $status, bool $useLegacyFallback = false): ?string
    {
        $template = MessageTemplate::forCompanyStatus($order->company_id, $status);
        if ($template) {
            return $template->render($order, $status);
        }

        if (!$useLegacyFallback) {
            return null;
        }

        return notificationMessage('order.sms.out_for_delivery', [
            'recipient_name' => $order->recipent_name,
            'serial' => $order->refrence_no ?: $order->serial,
            'company_name' => optional($order->Company)->name,
        ]);
    }
}
