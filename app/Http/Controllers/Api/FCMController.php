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
     * @return mixed
     */
    public static function Push($title, $content, $token, $data, $activity = '')
    {
        try {
            $messaging = app(Messaging::class);

            $tokens = is_array($token)
                ? array_values(array_filter(array_map('strval', $token), static fn (string $t): bool => $t !== ''))
                : [trim((string) $token)];
            $tokens = array_values(array_unique($tokens));
            if ($tokens === []) {
                return null;
            }

            $dataPayload = [];
            foreach ((array) $data as $key => $value) {
                $dataPayload[(string) $key] = is_scalar($value) ? (string) $value : json_encode($value);
            }
            if ($activity !== '') {
                $dataPayload['click_action'] = $activity;
            }

            $androidNotification = [
                'channel_id' => 'com.madar_al_reyadah.algeri_client',
                'sound' => 'sound',
            ];
            if ($activity !== '') {
                $androidNotification['click_action'] = $activity;
            }

            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $content))
                ->withData($dataPayload)
                ->withAndroidConfig([
                    'notification' => $androidNotification,
                ]);

            if (count($tokens) === 1) {
                return $messaging->send($message->toToken($tokens[0]));
            }

            return $messaging->sendMulticast($message, $tokens);
        } catch (\Exception $e) {
            report($e);

            return null;
        }
    }
}
