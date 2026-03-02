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

class SendMadarxWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * Job data
     */
    protected $order_id;
    protected $status;
    protected $tracking_number;
    protected $delivery_date;
    protected $notes;

    /**
     * Create a new job instance.
     */
    public function __construct(
        $order_id,
        $status,
        $tracking_number,
        $delivery_date,
        $notes = ''
    ) {
        $this->order_id        = $order_id;
        $this->status          = $status;
        $this->tracking_number = $tracking_number;
        $this->delivery_date   = $delivery_date;
        $this->notes           = $notes;
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
            sendMadarxWebhook(
                $this->order_id,
                $this->status,
                $this->tracking_number,
                $this->delivery_date,
                $this->notes
            );
        } catch (Exception $e) {

            Log::error('SendMadarxWebhookJob failed', [
                'order_id' => $this->order_id,
                'status'   => $this->status,
                'message'  => $e->getMessage(),
            ]);

            throw $e; // required so Laravel retries the job
        } finally {
            // IMPORTANT: prevent MySQL connection leaks
            DB::disconnect('mysql');
        }
    }
}
