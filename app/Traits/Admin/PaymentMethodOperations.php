<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait PaymentMethodOperations
{
  

    /**
     * Register a New .
     *
     * @param $request
     * @return \App\
     */
    public function register ($request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'));
        }
        if ($request->has('time')) {
            $data['time'] = Carbon::parse($request->get('time'));
        }
        if ($request->has('time_form')) {
            $data['time_form'] = Carbon::parse($request->get('time_form'));
        }
        if ($request->has('time_to')) {
            $data['time_to'] = Carbon::parse($request->get('time_to'));
        }
        if ($request->has('delivered_PaymentMethod')) {
            $data['delivered_PaymentMethod'] = Carbon::parse($request->get('delivered_PaymentMethod'));
        }
        DB::beginTransaction();
        $PaymentMethod = PaymentMethod::create($data);
        DB::commit();
        return $PaymentMethod;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(PaymentMethod $PaymentMethod,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$PaymentMethod->image));
            // 
            $data['image'] = uploadImage($request->file('image'));
        }
        if ($request->has('driver_id') && $request->get('driver_id') != '' && $request->get('driver_id') != $PaymentMethod->driver_id) {
            $driver_id = $request->get('driver_id');
            $driver = Driver::find($driver_id);
            $PaymentMethod->PaymentMethodLog()->create([
                'created_by_type' => 'admin',
                'created_by_id' => auth()->id(),
                'key' => 'driver_assigned',
                'key_id' => $driver_id,
                'notes' => trans('words.driver has been assigned by', ['by_name' => auth()->user()->name, 'driver_name' => $driver->name ]),
            ]);
            // send notification
            
            if( $driver->lang == 'ar')
                {
                    $title = "تم تخصيص طلب لك رقم #". $PaymentMethod->id;
                    $content = "تم تخصيص طلب لك رقم #". $PaymentMethod->id . ' الي '.trans('words.'.$request->status);

                }else{
                    $title = "You have new PaymentMethod number #".$PaymentMethod->id;
                    $content = "You have new PaymentMethod number #".$PaymentMethod->id . ' to '. $request->status;
                }
                $type = "PaymentMethod_assign_driver";
                
                $title_ar = "تم تخصيص طلب لك رقم #".$PaymentMethod->id;
                $title_en = "You have new PaymentMethod number #".$PaymentMethod->id;
                $content_ar = "تم تخصيص طلب لك رقم #".$PaymentMethod->id . ' الي '.trans('words.'.$request->status);
                $content_en = "You have new PaymentMethod number #".$PaymentMethod->id . ' to '. $request->status;
                $activity = "PaymentMethod_details";
                $data2 = [
                    'PaymentMethod' => $PaymentMethod,
                    'title_ar' => $title_ar,
                    'title_en' => $title_en,
                    'content_ar' => $content_ar,
                    'content_en' => $content_en,
                    'type' => $type,
                ];
                $notifiable = $driver->first();
                $token = $notifiable->PlayerId()->pluck('player_id')->toArray();
                FCMController::Push($title, $content,$token,$data2);
                $notifiable->notify(new GeneralNotification($title, $content, $type, $data2, $activity) );
                
                // send notification to admin start
                $admin = Admin::first();
                $admin->notify(new AdminNotification($content_ar . $PaymentMethod->id , route('PaymentMethods.show', $PaymentMethod->id)));
                // send notification to admin end
                // send notification end
        }
        if ($request->has('status') && $request->get('status') != 'init' && $request->get('status') != $PaymentMethod->status) {
            // check PaymentMethod type start
            if ($PaymentMethod->PaymentMethod_type == 'external' && $request->get('status') == 'finished' && $request->has('driver_id') && $request->get('driver_id') != '' ) {
                $driver_id = $request->get('driver_id');
                $driver = Driver::find($driver_id);
                $pricelist = PriceList::where('from_PaymentMethod_id', $PaymentMethod->source_PaymentMethod_id)
                                        ->where('to_PaymentMethod_id', $PaymentMethod->destination_PaymentMethod_id)
                                        ->where('car_type_id', $PaymentMethod->car_type_id)
                                        ->first();
                $driver->DriverPayment()->create([
                    'PaymentMethod_id' => $PaymentMethod->id,
                    'amount' => $pricelist->not_employee_price,
                    'type' => 'income'
                ]);
            }
            // check PaymentMethod type end
            // send notification
            
            if( $PaymentMethod->User->lang == 'ar')
                {
                    $title = "تم تغيير حالة الرحلة الخاصة الطلب رقم #". $PaymentMethod->id;
                    $content = "تم تغيير حالة الرحلة الخاصة الطلب رقم #". $PaymentMethod->id . ' الي '.trans('words.'.$request->status);

                }else{
                    $title = "Trip status has been changed for PaymentMethod #".$PaymentMethod->id;
                    $content = "Trip status has been changed for PaymentMethod #".$PaymentMethod->id . ' to '. $request->status;
                }
                $type = "PaymentMethod_details";
                
                $title_ar = "تم تغيير حالة الرحلة الخاصة الطلب رقم #".$PaymentMethod->id;
                $title_en = "Trip status has been changed for PaymentMethod #".$PaymentMethod->id;
                $content_ar = "تم تغيير حالة الرحلة الخاصة الطلب رقم #".$PaymentMethod->id . ' الي '.trans('words.'.$request->status);
                $content_en = "Trip status has been changed for PaymentMethod #".$PaymentMethod->id . ' to '. $request->status;
                $activity = "PaymentMethod_details";
                $data2 = [
                    'PaymentMethod' => $PaymentMethod,
                    'title_ar' => $title_ar,
                    'title_en' => $title_en,
                    'content_ar' => $content_ar,
                    'content_en' => $content_en,
                    'type' => $type,
                ];
                $notifiable = $PaymentMethod->User()->first();
                $token = $notifiable->PlayerId()->pluck('player_id')->toArray();
                FCMController::Push($title, $content,$token,$data2);
                $notifiable->notify(new GeneralNotification($title, $content, $type, $data2, $activity) );
                
                // send notification to admin start
                $admin = Admin::first();
                $admin->notify(new AdminNotification($content_ar . $PaymentMethod->id , route('PaymentMethods.show', $PaymentMethod->id)));
                // send notification to admin end
                
                // send notification end
                    $PaymentMethod->PaymentMethodLog()->create([
                        'created_by_type' => 'admin',
                        'created_by_id' => auth()->id(),
                        'key' => 'status_changed',
                        'notes' => trans('words.PaymentMethod status has been changed by', ['by_name' => auth()->user()->name, 'status' => trans('words.'.$request->status) ]),
                    ]);
            }
        $PaymentMethod->update($data);
        return $PaymentMethod;
    }
    /**
     * delete Record
     * @param $truck
     * @param $request
     */
    public function DeleteRecord($id)
    {
        //
    }
}