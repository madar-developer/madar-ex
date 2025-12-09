<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $order_id,
        public string $status,
        public string $tracking_number,
        public string $delivery_date,
        public string $notes = ''
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            sendMadarxWebhook(
                $this->order_id,
                $this->status,
                $this->tracking_number,
                $this->delivery_date,
                $this->notes
            );
        } catch (\Exception $e) {
            Log::error('SendMadarxWebhookJob failed: ' . $e->getMessage(), [
                'order_id' => $this->order_id,
                'status' => $this->status,
                'exception' => $e
            ]);
            throw $e; // Re-throw to trigger retry mechanism
        }
    }
}
