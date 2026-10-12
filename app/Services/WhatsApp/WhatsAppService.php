<?php

namespace App\Services\WhatsApp;

use App\Exceptions\WhatsAppException;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsAppService
{
    public function __construct(
        protected PhoneNumberNormalizer $phoneNormalizer
    ) {}

    public function isEnabled(): bool
    {
        return (bool) config('whatsapp.enabled');
    }

    /**
     * Free-form text — only valid inside the customer service window.
     *
     * @return array{success: bool, message_id: ?string, status: string, error: ?array, http_status: ?int}
     */
    public function sendText(string $phone, string $message, ?string $idempotencyKey = null): array
    {
        $normalized = $this->phoneNormalizer->normalize($phone);
        $this->assertServiceWindow($normalized);

        return $this->send($normalized, [
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $message,
            ],
        ], 'text', [
            'idempotency_key' => $idempotencyKey,
        ]);
    }

    /**
     * Approved template: delivery_template_for_an_immediate_customer (ar).
     *
     * Body variables (in order):
     * 1. customer name
     * 2. order reference
     * 3. store / company name
     * 4. payment method
     * 5. amount
     * 6. tracking / serial
     *
     * @return array{success: bool, message_id: ?string, status: string, error: ?array, http_status: ?int}
     */
    public function sendImmediateDeliveryTemplate(
        string $phone,
        string $customerName,
        string $orderReference,
        string $storeName,
        string $paymentMethod,
        string $amount,
        string $trackingNumber,
        ?string $idempotencyKey = null
    ): array {
        return $this->sendTemplate(
            $phone,
            'delivery_template_for_an_immediate_customer',
            'ar',
            [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $customerName],
                        ['type' => 'text', 'text' => $orderReference],
                        ['type' => 'text', 'text' => $storeName],
                        ['type' => 'text', 'text' => $paymentMethod],
                        ['type' => 'text', 'text' => $amount],
                        ['type' => 'text', 'text' => $trackingNumber],
                    ],
                ],
            ],
            $idempotencyKey
        );
    }

    /**
     * Business-initiated template message.
     *
     * @return array{success: bool, message_id: ?string, status: string, error: ?array, http_status: ?int}
     */
    public function sendTemplate(
        string $phone,
        string $templateName,
        string $language = 'ar',
        array $components = [],
        ?string $idempotencyKey = null
    ): array {
        $normalized = $this->phoneNormalizer->normalize($phone);

        $template = [
            'name' => $templateName,
            'language' => [
                'code' => $language !== '' ? $language : (string) config('whatsapp.default_language', 'ar'),
            ],
        ];

        if ($components !== []) {
            $template['components'] = $components;
        }

        return $this->send($normalized, [
            'type' => 'template',
            'template' => $template,
        ], 'template', [
            'template_name' => $templateName,
            'language' => $template['language']['code'],
            'idempotency_key' => $idempotencyKey,
        ]);
    }

    /**
     * Image message — only valid inside the customer service window.
     *
     * @return array{success: bool, message_id: ?string, status: string, error: ?array, http_status: ?int}
     */
    public function sendImage(
        string $phone,
        string $imageUrl,
        ?string $caption = null,
        ?string $idempotencyKey = null
    ): array {
        $normalized = $this->phoneNormalizer->normalize($phone);
        $this->assertServiceWindow($normalized);

        $image = ['link' => $imageUrl];
        if ($caption !== null && $caption !== '') {
            $image['caption'] = $caption;
        }

        return $this->send($normalized, [
            'type' => 'image',
            'image' => $image,
        ], 'image', [
            'idempotency_key' => $idempotencyKey,
        ]);
    }

    /**
     * Document message — only valid inside the customer service window.
     *
     * @return array{success: bool, message_id: ?string, status: string, error: ?array, http_status: ?int}
     */
    public function sendDocument(
        string $phone,
        string $documentUrl,
        ?string $filename = null,
        ?string $idempotencyKey = null
    ): array {
        $normalized = $this->phoneNormalizer->normalize($phone);
        $this->assertServiceWindow($normalized);

        $document = ['link' => $documentUrl];
        if ($filename !== null && $filename !== '') {
            $document['filename'] = $filename;
        }

        return $this->send($normalized, [
            'type' => 'document',
            'document' => $document,
        ], 'document', [
            'idempotency_key' => $idempotencyKey,
        ]);
    }

    public function isWithinServiceWindow(string $phone): bool
    {
        try {
            $normalized = $this->phoneNormalizer->normalize($phone);
        } catch (WhatsAppException) {
            return false;
        }

        $conversation = WhatsAppConversation::query()
            ->where('phone', $normalized)
            ->first();

        if (! $conversation || ! $conversation->last_inbound_at) {
            return false;
        }

        $hours = (int) config('whatsapp.service_window_hours', 24);

        return $conversation->last_inbound_at->gt(now()->subHours($hours));
    }

    public function recordInboundMessage(string $phone, ?string $waMessageId = null): void
    {
        try {
            $normalized = $this->phoneNormalizer->normalize($phone);
        } catch (WhatsAppException) {
            return;
        }

        WhatsAppConversation::query()->updateOrCreate(
            ['phone' => $normalized],
            [
                'last_inbound_at' => now(),
                'last_inbound_wa_message_id' => $waMessageId,
            ]
        );
    }

    public function updateMessageStatus(string $waMessageId, string $status, ?array $error = null): void
    {
        $message = WhatsAppMessage::query()
            ->where('wa_message_id', $waMessageId)
            ->first();

        if (! $message) {
            return;
        }

        $message->status = $status;
        if ($error) {
            $message->error_code = isset($error['code']) ? (string) $error['code'] : $message->error_code;
            $message->error_message = isset($error['message'])
                ? Str::limit((string) $error['message'], 500, '')
                : $message->error_message;
        }
        $message->save();
    }

    /**
     * @param  array<string, mixed>  $typePayload
     * @param  array<string, mixed>  $meta
     * @return array{success: bool, message_id: ?string, status: string, error: ?array, http_status: ?int}
     */
    protected function send(string $normalizedPhone, array $typePayload, string $type, array $meta = []): array
    {
        $this->assertConfigured();

        $payload = array_merge([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $normalizedPhone,
        ], $typePayload);

        $record = $this->resolveMessageRecord($normalizedPhone, $type, $meta);

        try {
            $response = $this->client()->post($this->messagesEndpoint(), $payload);
        } catch (ConnectionException $e) {
            $record->update([
                'status' => 'failed',
                'error_code' => 'connection_error',
                'error_message' => 'Connection to Meta Graph API failed.',
            ]);

            Log::warning('WhatsApp connection error', [
                'message_db_id' => $record->id,
                'type' => $type,
            ]);

            throw new WhatsAppException(
                'WhatsApp API connection failed.',
                0,
                ['message_db_id' => $record->id],
                true,
                null
            );
        }

        return $this->normalizeResponse($response, $record, $type);
    }

    /**
     * @return array{success: bool, message_id: ?string, status: string, error: ?array, http_status: ?int}
     */
    protected function normalizeResponse(Response $response, WhatsAppMessage $record, string $type): array
    {
        $httpStatus = $response->status();
        $body = $response->json() ?? [];

        if ($response->successful()) {
            $waMessageId = data_get($body, 'messages.0.id');
            $record->update([
                'wa_message_id' => $waMessageId,
                'status' => 'accepted',
                'error_code' => null,
                'error_message' => null,
            ]);

            Log::info('WhatsApp message accepted', [
                'message_db_id' => $record->id,
                'wa_message_id' => $waMessageId,
                'type' => $type,
                'http_status' => $httpStatus,
            ]);

            return [
                'success' => true,
                'message_id' => $waMessageId,
                'status' => 'accepted',
                'error' => null,
                'http_status' => $httpStatus,
                'message_db_id' => $record->id,
            ];
        }

        $error = [
            'code' => data_get($body, 'error.code'),
            'type' => data_get($body, 'error.type'),
            'message' => data_get($body, 'error.message', 'WhatsApp API error'),
            'fbtrace_id' => data_get($body, 'error.fbtrace_id'),
        ];

        $record->update([
            'status' => 'failed',
            'error_code' => isset($error['code']) ? (string) $error['code'] : (string) $httpStatus,
            'error_message' => Str::limit((string) $error['message'], 500, ''),
        ]);

        Log::warning('WhatsApp API error', [
            'message_db_id' => $record->id,
            'type' => $type,
            'http_status' => $httpStatus,
            'error_code' => $error['code'],
        ]);

        $retryable = $this->isRetryableHttpStatus($httpStatus);

        throw new WhatsAppException(
            (string) $error['message'],
            (int) ($error['code'] ?? $httpStatus),
            [
                'message_db_id' => $record->id,
                'http_status' => $httpStatus,
                'error' => $error,
            ],
            $retryable,
            $httpStatus
        );
    }

    /**
     * @param  array<string, mixed>  $meta
     */
    protected function resolveMessageRecord(string $normalizedPhone, string $type, array $meta): WhatsAppMessage
    {
        $idempotencyKey = $meta['idempotency_key'] ?? null;

        if ($idempotencyKey) {
            $existing = WhatsAppMessage::query()
                ->where('idempotency_key', $idempotencyKey)
                ->first();

            if ($existing) {
                if (in_array($existing->status, ['accepted', 'sent', 'delivered', 'read'], true)) {
                    throw new WhatsAppException(
                        'Duplicate WhatsApp send blocked by idempotency key.',
                        409,
                        ['message_db_id' => $existing->id, 'wa_message_id' => $existing->wa_message_id],
                        false,
                        409
                    );
                }

                $existing->fill([
                    'phone' => $normalizedPhone,
                    'direction' => 'outbound',
                    'type' => $type,
                    'template_name' => $meta['template_name'] ?? null,
                    'language' => $meta['language'] ?? null,
                    'status' => 'queued',
                    'error_code' => null,
                    'error_message' => null,
                ])->save();

                return $existing;
            }
        }

        return WhatsAppMessage::query()->create([
            'phone' => $normalizedPhone,
            'direction' => 'outbound',
            'type' => $type,
            'template_name' => $meta['template_name'] ?? null,
            'language' => $meta['language'] ?? null,
            'status' => 'queued',
            'idempotency_key' => $idempotencyKey,
        ]);
    }

    protected function assertConfigured(): void
    {
        if (! $this->isEnabled()) {
            throw new WhatsAppException('WhatsApp integration is disabled.', 503, [], false, 503);
        }

        if (! config('whatsapp.access_token')) {
            throw new WhatsAppException('WhatsApp access token is not configured.', 503, [], false, 503);
        }

        if (! config('whatsapp.phone_number_id')) {
            throw new WhatsAppException('WhatsApp phone number ID is not configured.', 503, [], false, 503);
        }
    }

    protected function assertServiceWindow(string $normalizedPhone): void
    {
        $conversation = WhatsAppConversation::query()
            ->where('phone', $normalizedPhone)
            ->first();

        $hours = (int) config('whatsapp.service_window_hours', 24);

        if (
            ! $conversation
            || ! $conversation->last_inbound_at
            || $conversation->last_inbound_at->lte(now()->subHours($hours))
        ) {
            throw new WhatsAppException(
                'Free-form messages are only allowed within the customer service window. Use an approved template outside that window.',
                403,
                [],
                false,
                403
            );
        }
    }

    protected function client(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::baseUrl(rtrim((string) config('whatsapp.graph_base_url'), '/'))
            ->withToken((string) config('whatsapp.access_token'))
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('whatsapp.timeout', 15))
            ->connectTimeout((int) config('whatsapp.connect_timeout', 5));
    }

    protected function messagesEndpoint(): string
    {
        $version = trim((string) config('whatsapp.api_version', 'v25.0'), '/');
        $phoneNumberId = (string) config('whatsapp.phone_number_id');

        return "/{$version}/{$phoneNumberId}/messages";
    }

    protected function isRetryableHttpStatus(?int $status): bool
    {
        if ($status === null) {
            return true;
        }

        return in_array($status, [408, 429, 500, 502, 503, 504], true);
    }
}
