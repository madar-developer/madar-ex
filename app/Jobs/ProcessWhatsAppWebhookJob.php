<?php

namespace App\Jobs;

use App\Models\WhatsAppWebhookEvent;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessWhatsAppWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public array $payload
    ) {
        $this->onQueue((string) config('whatsapp.queue', 'whatsapp'));
    }

    public function handle(WhatsAppService $whatsApp): void
    {
        $entries = $this->payload['entry'] ?? [];

        if (! is_array($entries)) {
            return;
        }

        foreach ($entries as $entry) {
            $changes = $entry['changes'] ?? [];
            if (! is_array($changes)) {
                continue;
            }

            foreach ($changes as $change) {
                $value = $change['value'] ?? [];
                if (! is_array($value)) {
                    continue;
                }

                $this->processStatuses($whatsApp, $value['statuses'] ?? []);
                $this->processInboundMessages($whatsApp, $value['messages'] ?? []);
            }
        }
    }

    /**
     * @param  mixed  $statuses
     */
    protected function processStatuses(WhatsAppService $whatsApp, $statuses): void
    {
        if (! is_array($statuses)) {
            return;
        }

        foreach ($statuses as $statusRow) {
            if (! is_array($statusRow)) {
                continue;
            }

            $waMessageId = (string) ($statusRow['id'] ?? '');
            $status = (string) ($statusRow['status'] ?? '');
            $timestamp = (string) ($statusRow['timestamp'] ?? '');

            if ($waMessageId === '' || $status === '') {
                continue;
            }

            $eventKey = 'status:'.$waMessageId.':'.$status.':'.$timestamp;
            if (! $this->claimEvent($eventKey, 'status')) {
                continue;
            }

            $error = null;
            if ($status === 'failed') {
                $error = [
                    'code' => data_get($statusRow, 'errors.0.code'),
                    'message' => data_get($statusRow, 'errors.0.title')
                        ?? data_get($statusRow, 'errors.0.message'),
                ];
            }

            if (in_array($status, ['sent', 'delivered', 'read', 'failed'], true)) {
                $whatsApp->updateMessageStatus($waMessageId, $status, $error);
            }
        }
    }

    /**
     * @param  mixed  $messages
     */
    protected function processInboundMessages(WhatsAppService $whatsApp, $messages): void
    {
        if (! is_array($messages)) {
            return;
        }

        foreach ($messages as $message) {
            if (! is_array($message)) {
                continue;
            }

            $waMessageId = (string) ($message['id'] ?? '');
            $from = (string) ($message['from'] ?? '');

            if ($waMessageId === '' || $from === '') {
                continue;
            }

            $eventKey = 'inbound:'.$waMessageId;
            if (! $this->claimEvent($eventKey, 'inbound')) {
                continue;
            }

            // Open / refresh the 24h customer service window. Do not persist message body.
            $whatsApp->recordInboundMessage('+'.$from, $waMessageId);

            Log::info('WhatsApp inbound message recorded', [
                'wa_message_id' => $waMessageId,
                'type' => $message['type'] ?? null,
            ]);
        }
    }

    protected function claimEvent(string $eventKey, string $eventType): bool
    {
        if (WhatsAppWebhookEvent::query()->where('event_key', $eventKey)->exists()) {
            return false;
        }

        try {
            WhatsAppWebhookEvent::query()->create([
                'event_key' => $eventKey,
                'event_type' => $eventType,
                'processed_at' => now(),
            ]);

            return true;
        } catch (\Throwable) {
            // Unique constraint race — treat as duplicate.
            return false;
        }
    }
}
