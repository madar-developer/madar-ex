<?php

namespace App\Jobs;

use App\Exceptions\WhatsAppException;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public int $timeout = 45;

    /**
     * @var array<int, int>
     */
    public array $backoff = [30, 60, 120, 300];

    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public string $messageType,
        public string $phone,
        public array $payload = [],
        public ?string $idempotencyKey = null
    ) {
        $this->onQueue((string) config('whatsapp.queue', 'whatsapp'));
    }

    public function handle(WhatsAppService $whatsApp): void
    {
        if ($this->idempotencyKey) {
            $existing = WhatsAppMessage::query()
                ->where('idempotency_key', $this->idempotencyKey)
                ->whereIn('status', ['accepted', 'sent', 'delivered', 'read'])
                ->first();

            if ($existing) {
                Log::info('WhatsApp send skipped (idempotent)', [
                    'message_db_id' => $existing->id,
                    'wa_message_id' => $existing->wa_message_id,
                ]);

                return;
            }
        }

        try {
            match ($this->messageType) {
                'template' => $whatsApp->sendTemplate(
                    $this->phone,
                    (string) ($this->payload['template_name'] ?? ''),
                    (string) ($this->payload['language'] ?? config('whatsapp.default_language', 'ar')),
                    (array) ($this->payload['components'] ?? []),
                    $this->idempotencyKey
                ),
                'text' => $whatsApp->sendText(
                    $this->phone,
                    (string) ($this->payload['message'] ?? ''),
                    $this->idempotencyKey
                ),
                'image' => $whatsApp->sendImage(
                    $this->phone,
                    (string) ($this->payload['image_url'] ?? ''),
                    $this->payload['caption'] ?? null,
                    $this->idempotencyKey
                ),
                'document' => $whatsApp->sendDocument(
                    $this->phone,
                    (string) ($this->payload['document_url'] ?? ''),
                    $this->payload['filename'] ?? null,
                    $this->idempotencyKey
                ),
                default => throw new WhatsAppException(
                    'Unsupported WhatsApp message type.',
                    422,
                    ['type' => $this->messageType],
                    false,
                    422
                ),
            };
        } catch (WhatsAppException $e) {
            if (! $e->isRetryable()) {
                $this->fail($e);

                return;
            }

            throw $e;
        }
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('SendWhatsAppMessageJob failed permanently', [
            'type' => $this->messageType,
            'attempt' => $this->attempts(),
            'error' => $exception?->getMessage(),
        ]);
    }
}
