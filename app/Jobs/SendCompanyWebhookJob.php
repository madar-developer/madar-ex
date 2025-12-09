<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCompanyWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $webhook_url,
        public string $refrence_no,
        public string $status
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->webhook_url . "refrence_no=" . $this->refrence_no . "&status=" . $this->status);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 second timeout
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 10 second connection timeout
            $output = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                Log::error('Company Webhook cURL Error: ' . $error, [
                    'webhook_url' => $this->webhook_url,
                    'refrence_no' => $this->refrence_no,
                    'status' => $this->status
                ]);
                throw new \Exception('cURL Error: ' . $error);
            }

            Log::info('Company Webhook Sent', [
                'webhook_url' => $this->webhook_url,
                'refrence_no' => $this->refrence_no,
                'status' => $this->status,
                'http_code' => $httpCode,
                'response' => $output
            ]);
        } catch (\Exception $e) {
            Log::error('SendCompanyWebhookJob failed: ' . $e->getMessage(), [
                'webhook_url' => $this->webhook_url,
                'refrence_no' => $this->refrence_no,
                'status' => $this->status,
                'exception' => $e
            ]);
            throw $e; // Re-throw to trigger retry mechanism
        }
    }
}
