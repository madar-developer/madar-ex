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
            $query = http_build_query([
                'refrence_no' => $this->refrence_no,
                'status'      => $this->status,
            ]);

            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL            => rtrim($this->webhook_url, '?') . '?' . $query,
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
