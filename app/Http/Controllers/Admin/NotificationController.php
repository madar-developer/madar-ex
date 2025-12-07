<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use App\Models\PlayerId;
use App\Models\Company;
use App\Models\NotificationHistory;
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
            if ($request->has('companies') && is_array($request->companies)) {
                if (in_array('' , $request->companies)) {
                    // 
                } else if(in_array('all', $request->companies)) {
                    // 
                    $companies = Company::get();
                    Notification::send($companies, new GeneralNotification($message, '#' ) );
                }else{
                    $companies = Company::whereIn('id', $request->companies)->get();
                    Notification::send($companies, new GeneralNotification($message, '#' ) );
                }
                
            }
            if ($request->has('drivers') && is_array($request->drivers)) {
                if (in_array('' , $request->drivers)) {
                    // 
                } else if(in_array('all', $request->drivers)) {
                    // 
                    $drivers = Driver::get();
                    Notification::send($drivers, new GeneralNotification($message, '#' ) );
                }else{
                    $drivers = Driver::whereIn('id', $request->drivers)->get();
                    Notification::send($drivers, new GeneralNotification($message, '#' ) );
                }
                
            }
            $data['companies'] = implode(',', $data['companies']);
            $data['drivers'] = implode(',', $data['drivers']);
            NotificationHistory::create($data);
            // send notification end
            return redirect()->back()->with('success', 'تم الارسال بنجاح');

    }
}
