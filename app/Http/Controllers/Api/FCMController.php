<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FCMController extends Controller
{
    /**
     * Send FCM push notification to a device token.
     *
     * @param  string  $title
     * @param  string  $content
     * @param  string|array<int, string>  $token  FCM device token(s); callers often pass pluck()->toArray()
     * @param  array  $data  Optional data payload (values will be stringified for FCM)
     * @param  string  $activity  Click action / activity name
     * @param  string|null  $channelId  Android notification channel. Null omits channel_id (use app default).
     * @return mixed
     */
    public static function Push($title, $content, $token, $data, $activity = '', $channelId = 'com.madar_al_reyadah.algeri_client')
    {
        try {
            if (!app()->bound(Messaging::class)) {
                \Log::warning('FCM skipped: Messaging is not bound (check Firebase credentials file)');
                return null;
            }

            $messaging = app(Messaging::class);

            $tokens = is_array($token)
                ? array_values(array_filter(array_map('strval', $token), static fn (string $t): bool => $t !== ''))
                : [trim((string) $token)];
            $tokens = array_values(array_unique($tokens));
            if ($tokens === []) {
                \Log::warning('FCM skipped: no device tokens');
                return null;
            }

            $dataPayload = [];
            foreach ((array) $data as $key => $value) {
                if ($value === null) {
                    $dataPayload[(string) $key] = '';
                } elseif (is_scalar($value)) {
                    $dataPayload[(string) $key] = (string) $value;
                } else {
                    $dataPayload[(string) $key] = json_encode($value);
                }
            }
            if ($activity !== '') {
                $dataPayload['click_action'] = $activity;
            }

            $androidNotification = [
                'sound' => 'default',
            ];
            if ($channelId) {
                $androidNotification['channel_id'] = $channelId;
            }
            if ($activity !== '') {
                $androidNotification['click_action'] = $activity;
            }

            $message = CloudMessage::new()
                ->withNotification(Notification::create((string) $title, (string) $content))
                ->withData($dataPayload)
                ->withAndroidConfig([
                    'priority' => 'high',
                    'notification' => $androidNotification,
                ])
                ->withApnsConfig([
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'alert' => [
                                'title' => (string) $title,
                                'body' => (string) $content,
                            ],
                            'sound' => 'default',
                            'content-available' => 1,
                        ],
                    ],
                ]);

            if (count($tokens) === 1) {
                return $messaging->send($message->toToken($tokens[0]));
            }

            if (method_exists($messaging, 'sendAll')) {
                $messages = [];
                foreach ($tokens as $deviceToken) {
                    $messages[] = $message->toToken($deviceToken);
                }

                return $messaging->sendAll($messages);
            }

            if (method_exists($messaging, 'sendMulticast')) {
                return $messaging->sendMulticast($message, $tokens);
            }

            $results = [];
            foreach ($tokens as $deviceToken) {
                $results[] = $messaging->send($message->toToken($deviceToken));
            }

            return $results;
        } catch (\Throwable $e) {
            \Log::error('FCM push failed', [
                'error' => $e->getMessage(),
            ]);

            if (! $e instanceof \Illuminate\Contracts\Container\BindingResolutionException) {
                report($e);
            }

            return null;
        }
    }
}
