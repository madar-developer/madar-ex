<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Sends an order status webhook to a company's notify_url.
 *
 * POST application/x-www-form-urlencoded body:
 *   - refrence_no: merchant order reference (orders.refrence_no)
 *   - status:      new order status key
 *
 * Dispatched when order status changes (admin panel or driver API).
 * Retries up to 3 times with 10s backoff. Logs success/failure.
 *
 * @see docs/company-order-webhook.md
 */
class SendCompanyWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of attempts
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Seconds to wait before retry
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * Job data
     */
    protected $webhook_url;
    protected $refrence_no;
    protected $status;

    /**
     * Create a new job instance.
     */
    public function __construct($webhook_url, $refrence_no, $status)
    {
        $this->webhook_url = $webhook_url;
        $this->refrence_no = $refrence_no;
        $this->status      = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $payload = http_build_query([
                'refrence_no' => $this->refrence_no,
                'status'      => $this->status,
            ]);

            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL            => rtrim($this->webhook_url, '?'),
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $payload,
                CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_FAILONERROR    => false,
            ]);

            $output   = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error    = curl_error($ch);

            curl_close($ch);

            if ($error) {
                throw new Exception($error);
            }

            Log::info('Company Webhook Sent', [
                'webhook_url' => $this->webhook_url,
                'refrence_no' => $this->refrence_no,
                'status'      => $this->status,
                'http_code'   => $httpCode,
                'response'    => $output,
            ]);

        } catch (Exception $e) {

            Log::error('SendCompanyWebhookJob failed', [
                'webhook_url' => $this->webhook_url,
                'refrence_no' => $this->refrence_no,
                'status'      => $this->status,
                'message'     => $e->getMessage(),
            ]);

            throw $e; // required to trigger retries
        } finally {
            // VERY IMPORTANT for Laravel 5.8 queue workers
            // DB::disconnect('mysql');
        }
    }
}
