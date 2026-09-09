<?php

namespace App\Services\Salla;

use App\Models\Order;
use App\Models\SallaOrderLog;
use Illuminate\Support\Facades\Log;
use Throwable;

class SallaResponseLogger
{
    public function record(array $entry): ?SallaOrderLog
    {
        $response = is_array($entry['response'] ?? null) ? $entry['response'] : [];
        $sallaStatus = $entry['salla_status'] ?? $this->extractStatus($response);
        $shipmentId = $entry['shipment_id']
            ?? data_get($response, 'data.id')
            ?? data_get($response, 'data.shipping.shipment_reference');
        $trackingNumber = $entry['tracking_number']
            ?? data_get($response, 'data.tracking_number')
            ?? data_get($response, 'data.shipping_number')
            ?? data_get($response, 'data.shipping.tracking_number');
        $message = $entry['message'] ?? $this->extractMessage($response, (bool) ($entry['success'] ?? false));

        $context = [
            'action' => $entry['action'] ?? 'salla',
            'direction' => $entry['direction'] ?? 'outbound',
            'http_status' => $entry['http_status'] ?? null,
            'success' => (bool) ($entry['success'] ?? false),
            'salla_status' => $sallaStatus,
            'shipment_id' => $shipmentId ? (string) $shipmentId : null,
            'tracking_number' => $trackingNumber ? (string) $trackingNumber : null,
            'order_id' => data_get($entry, 'order.id'),
            'order_ref' => $entry['order_ref'] ?? null,
            'message' => $message,
        ];

        Log::channel('salla')->log(
            $context['success'] ? 'info' : 'error',
            'Salla response: ' . $context['action'],
            array_filter([
                ...$context,
                'request' => $entry['request'] ?? null,
                'response' => $response,
            ], fn ($value) => $value !== null && $value !== [])
        );

        if (app()->runningUnitTests()) {
            return null;
        }

        try {
            $order = $entry['order'] ?? $this->findOrder(
                $shipmentId,
                $entry['order_ref'] ?? null,
                $response
            );

            if (! $order) {
                return null;
            }

            $log = SallaOrderLog::create([
                'order_id' => $order->id,
                'direction' => $context['direction'],
                'action' => $context['action'],
                'http_status' => $context['http_status'],
                'success' => $context['success'],
                'salla_status' => $sallaStatus,
                'shipment_id' => $context['shipment_id'] ?: $order->shipment_ref_id,
                'tracking_number' => $context['tracking_number'] ?: $order->serial,
                'message' => $message,
                'payload' => [
                    'request' => $entry['request'] ?? null,
                    'response' => $response,
                ],
            ]);

            if ($sallaStatus && $sallaStatus !== $order->source_status) {
                $order->update(['source_status' => $sallaStatus]);
            }

            return $log;
        } catch (Throwable $e) {
            Log::channel('salla')->warning('Failed to persist Salla order log', [
                'error' => $e->getMessage(),
                'action' => $context['action'],
            ]);

            return null;
        }
    }

    public function extractStatus(array $payload): ?string
    {
        $candidates = [
            data_get($payload, 'data.status.slug'),
            data_get($payload, 'data.status.name'),
            data_get($payload, 'data.shipment.status.slug'),
            data_get($payload, 'data.shipment.status'),
            data_get($payload, 'data.status'),
            data_get($payload, 'status.slug'),
            data_get($payload, 'status.name'),
        ];

        foreach ($candidates as $value) {
            if (is_array($value)) {
                $value = $value['slug'] ?? $value['name'] ?? null;
            }

            if (is_string($value) && $value !== '' && ! is_numeric($value)) {
                return $value;
            }
        }

        return null;
    }

    protected function extractMessage(array $payload, bool $success): ?string
    {
        $message = data_get($payload, 'error.message')
            ?? data_get($payload, 'error.error.message')
            ?? data_get($payload, 'message');

        if (is_string($message) && $message !== '') {
            $fields = data_get($payload, 'error.fields');
            if (is_array($fields) && $fields) {
                $first = collect($fields)->flatten()->filter()->first();
                if ($first) {
                    $message .= ' — ' . $first;
                }
            }

            return mb_substr($message, 0, 250);
        }

        return $success ? 'نجاح' : 'فشل';
    }

    protected function findOrder(mixed $shipmentId, mixed $orderRef, array $response): ?Order
    {
        $refs = array_filter([
            $shipmentId,
            $orderRef,
            data_get($response, 'data.id'),
            data_get($response, 'data.order_id'),
            data_get($response, 'data.order_reference_id'),
            data_get($response, 'data.reference_id'),
            data_get($response, 'data.tracking_number'),
            data_get($response, 'data.shipping_number'),
        ], fn ($value) => $value !== null && $value !== '');

        if (! $refs) {
            return null;
        }

        return Order::query()
            ->where('order_source', 'salla')
            ->where(function ($query) use ($refs) {
                foreach ($refs as $ref) {
                    $value = (string) $ref;
                    $query->orWhere('shipment_ref_id', $value)
                        ->orWhere('refrence_no', $value)
                        ->orWhere('serial', $value);

                    if (is_numeric($ref)) {
                        $query->orWhere('id', (int) $ref);
                    }
                }
            })
            ->latest('id')
            ->first();
    }
}
