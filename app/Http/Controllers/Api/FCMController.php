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
            
            // Filter out null, empty strings, and non-string values - only keep valid token strings
            $tokens = array_filter($tokens, function($t) {
                return !empty($t) && is_string($t) && trim($t) !== '';
            });
            
            // Reset array keys after filtering
            $tokens = array_values($tokens);
            
            if (empty($tokens)) {
                // This is not an error - just means user has no registered devices
                // Use debug level instead of warning to reduce log noise
                Log::debug('No valid FCM tokens provided - user may not have registered devices');
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
