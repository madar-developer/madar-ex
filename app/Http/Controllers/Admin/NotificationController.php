<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use App\Models\Company;
use App\Models\Circular;
use App\Models\Driver;
use App\Support\CircularSendRecorder;
use Notification;

class NotificationController extends Controller
{
    public function index()
    {
    	$title = 'ارسال تنبيهات';
    	return view('admin.notifications.add', compact('title'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $title = $request->get('title');
        $content = $request->get('content');
        $message = $title . ' : ' . $content;

        $companies = $this->selectedCompanies($request);
        $drivers = $this->selectedDrivers($request);

        if ($companies->isNotEmpty()) {
            Notification::send($companies, new GeneralNotification($message, '#'));
            $this->pushFirebase($companies, $title, $content, 'general');
        }

        if ($drivers->isNotEmpty()) {
            Notification::send($drivers, new GeneralNotification($message, '#'));
            $this->pushFirebase($drivers, $title, $content, 'general');
        }

        if ($request->boolean('send_circular')) {
            $this->storeCirculars($request, $title, $content);
            $this->recordCircularSend($request, $title, $content, $companies, $drivers);
            $this->pushFirebase($companies, $title, $content, 'circular');
            $this->pushFirebase($drivers, $title, $content, 'circular');
        }
         // Log firbase response and final object sent to firebase
        return redirect()->back()->with('success', 'تم الارسال بنجاح');
    }

    /**
     * Send a high-priority FCM push to driver/company device tokens.
     */
    protected function pushFirebase($notifiables, string $title, string $content, string $type): void
    {
        if (!$notifiables || $notifiables->isEmpty()) {
            return;
        }

        $driverTokens = [];
        $companyTokens = [];

        foreach ($notifiables as $notifiable) {
            if ($notifiable instanceof Driver) {
                $driverTokens = array_merge(
                    $driverTokens,
                    method_exists($notifiable, 'fcmTokens')
                        ? $notifiable->fcmTokens()
                        : $notifiable->PlayerId()->pluck('player_id')->all()
                );
                continue;
            }

            if (method_exists($notifiable, 'PlayerId')) {
                $companyTokens = array_merge(
                    $companyTokens,
                    $notifiable->PlayerId()->pluck('player_id')->all()
                );
            }
        }

        $payload = [
            'title_ar' => $title,
            'title_en' => $title,
            'content_ar' => $content,
            'content_en' => $content,
            'type' => $type,
        ];

        $this->pushFirebaseTokens($driverTokens, $title, $content, $payload, 'FLUTTER_NOTIFICATION_CLICK', null);
        $this->pushFirebaseTokens(
            $companyTokens,
            $title,
            $content,
            $payload,
            $type,
            'com.madar_al_reyadah.algeri_client'
        );
    }

    protected function pushFirebaseTokens(array $tokens, string $title, string $content, array $payload, string $activity, ?string $channelId): void
    {
        $tokens = array_values(array_unique(array_filter(array_map('strval', $tokens), static function ($token) {
            return trim($token) !== '';
        })));

        if ($tokens === []) {
            return;
        }

        foreach (array_chunk($tokens, 500) as $chunk) {
            $result = FCMController::Push($title, $content, $chunk, $payload, $activity, $channelId);
            if (empty($result['ok'])) {
                \Log::warning('Admin notification FCM failed', [
                    'type' => $payload['type'] ?? null,
                    'token_count' => count($chunk),
                    'error' => $result['error'] ?? null,
                ]);
            }
        }
    }

    protected function cleanAudience($values): array
    {
        return array_values(array_filter((array) $values, function ($value) {
            return $value !== '' && $value !== null && $value !== 'no';
        }));
    }

    protected function isAudienceSelected($values): bool
    {
        return $this->cleanAudience($values) !== [];
    }

    protected function selectedCompanies(Request $request)
    {
        $values = $this->cleanAudience($request->input('companies'));
        if ($values === []) {
            return collect();
        }
        if (in_array('all', $values, true)) {
            return Company::get();
        }

        return Company::whereIn('id', $values)->get();
    }

    protected function selectedDrivers(Request $request)
    {
        $values = $this->cleanAudience($request->input('drivers'));
        if ($values === []) {
            return collect();
        }
        if (in_array('all', $values, true)) {
            return Driver::get();
        }

        return Driver::whereIn('id', $values)->get();
    }

    protected function storeCirculars(Request $request, string $title, string $content): void
    {
        $payload = [
            'title' => $title,
            'description' => $content,
            'days_count' => 0,
        ];

        $driversSelected = $this->isAudienceSelected($request->input('drivers'));
        $companiesSelected = $this->isAudienceSelected($request->input('companies'));

        if ($driversSelected || ! $companiesSelected) {
            Circular::create($payload + ['type' => Circular::TYPE_DRIVER]);
        }

        if ($companiesSelected) {
            Circular::create($payload + ['type' => Circular::TYPE_COMPANY]);
        }
    }

    protected function recordCircularSend(Request $request, string $title, string $content, $companies, $drivers): void
    {
        $companyValues = $this->cleanAudience($request->input('companies'));
        $driverValues = $this->cleanAudience($request->input('drivers'));
        $driversSelected = $this->isAudienceSelected($request->input('drivers'));
        $companiesSelected = $this->isAudienceSelected($request->input('companies'));

        $allCompanies = $companiesSelected && (in_array('all', $companyValues, true) || $companies->isEmpty());
        $allDrivers = $driversSelected && (in_array('all', $driverValues, true) || $drivers->isEmpty() || ! $this->isAudienceSelected($request->input('drivers')));

        if ($allCompanies) {
            $companies = Company::query()->get(['id', 'name']);
        }
        if ($allDrivers) {
            $drivers = Driver::query()->get(['id', 'first_name', 'last_name']);
        }

        CircularSendRecorder::record(
            $title,
            $content,
            $companies,
            $drivers,
            [],
            [
                'all_companies' => $allCompanies,
                'all_drivers' => $allDrivers,
            ]
        );
    }
}
