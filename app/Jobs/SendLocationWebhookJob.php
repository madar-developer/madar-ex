<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * POST driver location update to a company's location_notify_url.
 *
 * @see docs/company-order-tracking.md
 */
class SendLocationWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 10;

    protected $webhook_url;
    protected $refrence_no;
    protected $serial;
    protected $order_id;
    protected $driver_id;
    protected $lat;
    protected $lng;
    protected $timestamp;
    protected $status;

    public function __construct(
        $webhook_url,
        $refrence_no,
        $serial,
        $order_id,
        $driver_id,
        $lat,
        $lng,
        $timestamp,
        $status
    ) {
        $this->webhook_url = $webhook_url;
        $this->refrence_no = $refrence_no;
        $this->serial = $serial;
        $this->order_id = $order_id;
        $this->driver_id = $driver_id;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->timestamp = $timestamp;
        $this->status = $status;
    }

    public function handle()
    {
        try {
            $payload = http_build_query([
                'refrence_no' => $this->refrence_no,
                'serial' => $this->serial,
                'order_id' => $this->order_id,
                'driver_id' => $this->driver_id,
                'lat' => $this->lat,
                'lng' => $this->lng,
                'timestamp' => $this->timestamp,
                'status' => $this->status,
            ]);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => rtrim($this->webhook_url, '?'),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_FAILONERROR => false,
            ]);

            $output = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new Exception($error);
            }

            Log::info('Location Webhook Sent', [
                'webhook_url' => $this->webhook_url,
                'refrence_no' => $this->refrence_no,
                'order_id' => $this->order_id,
                'lat' => $this->lat,
                'lng' => $this->lng,
                'http_code' => $httpCode,
                'response' => $output,
            ]);
        } catch (Exception $e) {
            Log::error('SendLocationWebhookJob failed', [
                'webhook_url' => $this->webhook_url,
                'refrence_no' => $this->refrence_no,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
