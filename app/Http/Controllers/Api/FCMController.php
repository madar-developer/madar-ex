<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\FirebaseCredentials;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
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
     * @return array{ok: bool, priority: string, credentials: array, token_count: int, error: ?string, report: mixed}
     */
    public static function Push($title, $content, $token, $data, $activity = '', $channelId = 'com.madar_al_reyadah.algeri_client')
    {
        $result = [
            'ok' => false,
            'priority' => 'high',
            'credentials' => FirebaseCredentials::status(),
            'token_count' => 0,
            'valid_tokens' => 0,
            'invalid_tokens' => 0,
            'unknown_tokens' => 0,
            'error' => null,
            'report' => null,
        ];

        try {
            $credentialsPath = FirebaseCredentials::path();
            if (!$credentialsPath) {
                $result['error'] = 'Firebase credentials file not found. Set FIREBASE_CREDENTIALS or place the service account JSON in storage/app.';
                \Log::error('FCM skipped: missing credentials', $result['credentials']);

                return $result;
            }

            if (empty($result['credentials']['valid_json'])) {
                $result['error'] = 'Firebase credentials JSON is invalid (missing client_email or private_key).';
                \Log::error('FCM skipped: invalid credentials', $result['credentials']);

                return $result;
            }

            $messaging = app(Messaging::class);

            $tokens = is_array($token)
                ? array_values(array_filter(array_map('strval', $token), static fn (string $t): bool => $t !== ''))
                : [trim((string) $token)];
            $tokens = array_values(array_unique($tokens));
            $result['token_count'] = count($tokens);
            if ($tokens === []) {
                $result['error'] = 'No device tokens';
                \Log::warning('FCM skipped: no device tokens');

                return $result;
            }

            if (method_exists($messaging, 'validateRegistrationTokens')) {
                $checks = $messaging->validateRegistrationTokens($tokens);
                $valid = array_values($checks['valid'] ?? []);
                $result['valid_tokens'] = count($valid);
                $result['invalid_tokens'] = count($checks['invalid'] ?? []);
                $result['unknown_tokens'] = count($checks['unknown'] ?? []);
                if ($valid !== []) {
                    $tokens = $valid;
                }
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
                // $dataPayload['click_action'] = $activity;
            }

            $androidNotification = [
                'title' => (string) $title,
                'body' => (string) $content,
                'sound' => $data['type'] ?? 'default',
            ];
            if ($channelId) {
                // $androidNotification['channel_id'] = $channelId;
                $androidNotification['channel_id'] = ($data['type'] ?? 'default'). '_notifications_channel';
            }
            if ($activity !== '') {
                // $androidNotification['click_action'] = $activity;
            }

            $android = AndroidConfig::fromArray([
                'priority' => 'high',
                'ttl' => '86400s',
                'notification' => $androidNotification,
            ]);
            if (method_exists($android, 'withHighPriority')) {
                $android = $android->withHighPriority();
            }

            $apns = ApnsConfig::fromArray([
                'headers' => [
                    'apns-priority' => '10',
                    'apns-push-type' => 'alert',
                ],
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => (string) $title,
                            'body' => (string) $content,
                        ],
                        'sound' => 'default',
                    ],
                ],
            ]);
            if (method_exists($apns, 'withImmediatePriority')) {
                $apns = $apns->withImmediatePriority();
            }

            $message = CloudMessage::new()
                ->withNotification(Notification::create((string) $title, (string) $content))
                ->withData($dataPayload)
                ->withAndroidConfig($android)
                ->withApnsConfig($apns)
                ->withHighestPossiblePriority();

            if (method_exists($message, 'withDefaultSounds')) {
                $message = $message->withDefaultSounds();
            }

            if (count($tokens) === 1) {
                $result['report'] = $messaging->send($message->toToken($tokens[0]));
                $result['ok'] = true;

                return $result;
            }

            $messages = [];
            foreach ($tokens as $deviceToken) {
                $messages[] = $message->toToken($deviceToken);
            }
            $report = $messaging->sendAll($messages);
            $result['report'] = self::summarizeReport($report);
            $result['ok'] = ($result['report']['success'] ?? 0) > 0;

            if (!$result['ok']) {
                $result['error'] = 'FCM sendAll reported zero successes';
            }

            return $result;
        } catch (MessagingException $e) {
            $result['error'] = $e->getMessage();
            \Log::error('FCM push failed', [
                'error' => $e->getMessage(),
                'credentials' => $result['credentials'],
            ]);

            return $result;
        } catch (\Throwable $e) {
            $result['error'] = $e->getMessage();
            \Log::error('FCM push failed', [
                'error' => $e->getMessage(),
                'credentials' => $result['credentials'],
            ]);

            if (! $e instanceof \Illuminate\Contracts\Container\BindingResolutionException) {
                report($e);
            }

            return $result;
        }
    }

    protected static function summarizeReport($report): array
    {
        if (!is_object($report)) {
            return ['raw' => $report];
        }

        $summary = [];
        if (method_exists($report, 'successes')) {
            $summary['success'] = count($report->successes());
        }
        if (method_exists($report, 'failures')) {
            $summary['failure'] = count($report->failures());
            $errors = [];
            foreach ($report->failures() as $failure) {
                $errors[] = method_exists($failure, 'error')
                    ? (string) $failure->error()->getMessage()
                    : (string) $failure;
            }
            $summary['errors'] = array_values(array_unique($errors));
        }
        if (method_exists($report, 'validTokens')) {
            $summary['valid_token_count'] = count($report->validTokens());
        }
        if (method_exists($report, 'unknownTokens')) {
            $summary['unknown_token_count'] = count($report->unknownTokens());
        }
        if (method_exists($report, 'invalidTokens')) {
            $summary['invalid_token_count'] = count($report->invalidTokens());
        }

        return $summary;
    }
}
