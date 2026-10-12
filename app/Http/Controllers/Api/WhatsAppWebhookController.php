<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWhatsAppWebhookJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Meta webhook verification challenge.
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode', $request->query('hub.mode'));
        $token = $request->query('hub_verify_token', $request->query('hub.verify_token'));
        $challenge = $request->query('hub_challenge', $request->query('hub.challenge'));

        $expected = (string) config('whatsapp.webhook_verify_token');

        if ($mode === 'subscribe' && $expected !== '' && hash_equals($expected, (string) $token)) {
            return response((string) $challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403);
    }

    /**
     * Inbound webhook events (messages + delivery statuses).
     */
    public function handle(Request $request): Response
    {
        if (! $this->signatureIsValid($request)) {
            Log::warning('WhatsApp webhook signature rejected');

            return response('Invalid signature', 403);
        }

        $payload = $request->all();

        if (! empty($payload)) {
            ProcessWhatsAppWebhookJob::dispatch($payload);
        }

        // Acknowledge quickly; heavy work runs on the queue.
        return response('EVENT_RECEIVED', 200);
    }

    protected function signatureIsValid(Request $request): bool
    {
        $appSecret = (string) config('whatsapp.app_secret');

        if ($appSecret === '') {
            return false;
        }

        $header = (string) $request->header('X-Hub-Signature-256', '');

        if ($header === '' || ! str_starts_with($header, 'sha256=')) {
            return false;
        }

        $provided = substr($header, 7);
        $rawBody = $request->getContent();
        $expected = hash_hmac('sha256', $rawBody, $appSecret);

        return hash_equals($expected, $provided);
    }
}
