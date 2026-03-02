<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOrderWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;

    public $tries = 3;
    public $timeout = 10;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function handle()
{
    try {

        $client = new \GuzzleHttp\Client([
            'timeout' => 2,
            'connect_timeout' => 2,
        ]);

        $client->request('GET', $this->url, [
            'curl' => [
                CURLOPT_TIMEOUT_MS => 2000,
                CURLOPT_NOSIGNAL => 1,
            ],
        ]);

    } catch (\Throwable $e) {

        // لا تفشل الـ job
        \Log::warning('Webhook timeout (ignored)', [
            'url' => $this->url
        ]);
    }
}
}