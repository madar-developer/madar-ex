<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use App\Models\PlayerId;
use App\Models\Company;
use App\Models\Circular;
use App\Models\Driver;
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
    	$title = '';
    	$data = $request->all();
    	// send notification
            $title =$data['title'];
            $content = $data['content'];
            $type = "general";
            $data2 = [
                'type' => $type,
            ];
            $message = $title . ' : ' . $content;

            // if ($request->has('driver_id')) {
            //     $data2 = [
            //         'type' => 'profile_error',
            //     ];
            //     $token = PlayerId::where('taggable_type', 'LIKE', '%Driver%')->where('taggable_id', $request->get('driver_id') )->pluck('player_id')->toArray();
            //     FCMController::Push($title, $content,$token,$data2);
            // }else{
            //     if ($data['type'] == 'all') {
            //         $token = PlayerId::pluck('player_id')->toArray();
            //     }else {
            //         $token = PlayerId::where('taggable_type', 'LIKE', '%'.$data['type'].'%')->pluck('player_id')->toArray();
            //     }
            //     FCMController::Push($title, $content,$token,$data2);
            // }
            $companies = $this->selectedCompanies($request);
            if ($companies->isNotEmpty()) {
                Notification::send($companies, new GeneralNotification($message, '#'));
            }

            $drivers = $this->selectedDrivers($request);
            if ($drivers->isNotEmpty()) {
                Notification::send($drivers, new GeneralNotification($message, '#'));
            }

            if ($request->boolean('send_circular')) {
                $this->storeCirculars($request, $title, $content);
            }
            // send notification end
            return redirect()->back()->with('success', 'تم الارسال بنجاح');

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
}
