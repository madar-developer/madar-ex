<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FCMController extends Controller
{
    /**
     * Send push notification via Firebase Cloud Messaging
     * 
     * @param string $title Notification title
     * @param string $content Notification body/content
     * @param array|string $token FCM token(s) - can be single token string or array of tokens
     * @param array $data Additional data payload
     * @param string $activity Click action/activity
     * @return mixed
     */
    public static function Push($title, $content, $token, $data, $activity = '')
    {
        try {
            // Get FCM server key from environment
            $serverKey = config('services.fcm.server_key') ?? env('FCM_SERVER_KEY');
            
            if (empty($serverKey)) {
                Log::warning('FCM Server Key not configured');
                return false;
            }

            // Ensure token is an array
            $tokens = is_array($token) ? $token : [$token];
            
            // Remove empty tokens
            $tokens = array_filter($tokens);
            
            if (empty($tokens)) {
                Log::warning('No valid FCM tokens provided');
                return false;
            }

            // Build notification payload
            $notification = [
                'title' => $title,
                'body' => $content,
                'sound' => 'sound',
                'channel_id' => 'com.madar_al_reyadah.algeri_client',
            ];

            if (!empty($activity)) {
                $notification['click_action'] = $activity;
            }

            // Build the FCM message payload
            $payload = [
                'notification' => $notification,
                'data' => array_map('strval', $data), // FCM requires string values in data
                'priority' => 'high',
            ];

            // If multiple tokens, use multicast; otherwise single message
            if (count($tokens) === 1) {
                $payload['to'] = $tokens[0];
                $url = 'https://fcm.googleapis.com/fcm/send';
            } else {
                $payload['registration_ids'] = $tokens;
                $url = 'https://fcm.googleapis.com/fcm/send';
            }

            // Send HTTP request to FCM
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('FCM request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('FCM Push Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}
