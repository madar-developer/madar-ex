<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait CityOperations
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
        if ($request->has('delivered_City')) {
            $data['delivered_City'] = Carbon::parse($request->get('delivered_City'));
        }
        DB::beginTransaction();
        $City = City::create($data);
        DB::commit();
        return $City;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(City $City,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$City->image));
            // 
            $data['image'] = uploadImage($request->file('image'));
        }
        if ($request->has('driver_id') && $request->get('driver_id') != '' && $request->get('driver_id') != $City->driver_id) {
            $driver_id = $request->get('driver_id');
            $driver = Driver::find($driver_id);
            $City->CityLog()->create([
                'created_by_type' => 'admin',
                'created_by_id' => auth()->id(),
                'key' => 'driver_assigned',
                'key_id' => $driver_id,
                'notes' => trans('words.driver has been assigned by', ['by_name' => auth()->user()->name, 'driver_name' => $driver->name ]),
            ]);
            // send notification
            
            if( $driver->lang == 'ar')
                {
                    $title = "تم تخصيص طلب لك رقم #". $City->id;
                    $content = "تم تخصيص طلب لك رقم #". $City->id . ' الي '.trans('words.'.$request->status);

                }else{
                    $title = "You have new City number #".$City->id;
                    $content = "You have new City number #".$City->id . ' to '. $request->status;
                }
                $type = "City_assign_driver";
                
                $title_ar = "تم تخصيص طلب لك رقم #".$City->id;
                $title_en = "You have new City number #".$City->id;
                $content_ar = "تم تخصيص طلب لك رقم #".$City->id . ' الي '.trans('words.'.$request->status);
                $content_en = "You have new City number #".$City->id . ' to '. $request->status;
                $activity = "City_details";
                $data2 = [
                    'City' => $City,
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
                $admin->notify(new AdminNotification($content_ar . $City->id , route('Citys.show', $City->id)));
                // send notification to admin end
                // send notification end
        }
        if ($request->has('status') && $request->get('status') != 'init' && $request->get('status') != $City->status) {
            // check City type start
            if ($City->City_type == 'external' && $request->get('status') == 'finished' && $request->has('driver_id') && $request->get('driver_id') != '' ) {
                $driver_id = $request->get('driver_id');
                $driver = Driver::find($driver_id);
                $pricelist = PriceList::where('from_city_id', $City->source_city_id)
                                        ->where('to_city_id', $City->destination_city_id)
                                        ->where('car_type_id', $City->car_type_id)
                                        ->first();
                $driver->DriverPayment()->create([
                    'City_id' => $City->id,
                    'amount' => $pricelist->not_employee_price,
                    'type' => 'income'
                ]);
            }
            // check City type end
            // send notification
            
            if( $City->User->lang == 'ar')
                {
                    $title = "تم تغيير حالة الرحلة الخاصة الطلب رقم #". $City->id;
                    $content = "تم تغيير حالة الرحلة الخاصة الطلب رقم #". $City->id . ' الي '.trans('words.'.$request->status);

                }else{
                    $title = "Trip status has been changed for City #".$City->id;
                    $content = "Trip status has been changed for City #".$City->id . ' to '. $request->status;
                }
                $type = "City_details";
                
                $title_ar = "تم تغيير حالة الرحلة الخاصة الطلب رقم #".$City->id;
                $title_en = "Trip status has been changed for City #".$City->id;
                $content_ar = "تم تغيير حالة الرحلة الخاصة الطلب رقم #".$City->id . ' الي '.trans('words.'.$request->status);
                $content_en = "Trip status has been changed for City #".$City->id . ' to '. $request->status;
                $activity = "City_details";
                $data2 = [
                    'City' => $City,
                    'title_ar' => $title_ar,
                    'title_en' => $title_en,
                    'content_ar' => $content_ar,
                    'content_en' => $content_en,
                    'type' => $type,
                ];
                $notifiable = $City->User()->first();
                $token = $notifiable->PlayerId()->pluck('player_id')->toArray();
                FCMController::Push($title, $content,$token,$data2);
                $notifiable->notify(new GeneralNotification($title, $content, $type, $data2, $activity) );
                
                // send notification to admin start
                $admin = Admin::first();
                $admin->notify(new AdminNotification($content_ar . $City->id , route('Citys.show', $City->id)));
                // send notification to admin end
                
                // send notification end
                    $City->CityLog()->create([
                        'created_by_type' => 'admin',
                        'created_by_id' => auth()->id(),
                        'key' => 'status_changed',
                        'notes' => trans('words.City status has been changed by', ['by_name' => auth()->user()->name, 'status' => trans('words.'.$request->status) ]),
                    ]);
            }
        $City->update($data);
        return $City;
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