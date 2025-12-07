<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\OrderStatus;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait OrderStatusOperations
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
        if ($request->has('delivered_OrderStatus')) {
            $data['delivered_OrderStatus'] = Carbon::parse($request->get('delivered_OrderStatus'));
        }
        DB::beginTransaction();
        $OrderStatus = OrderStatus::create($data);
        DB::commit();
        return $OrderStatus;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(OrderStatus $OrderStatus,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$OrderStatus->image));
            // 
            $data['image'] = uploadImage($request->file('image'));
        }
        if ($request->has('driver_id') && $request->get('driver_id') != '' && $request->get('driver_id') != $OrderStatus->driver_id) {
            $driver_id = $request->get('driver_id');
            $driver = Driver::find($driver_id);
            $OrderStatus->OrderStatusLog()->create([
                'created_by_type' => 'admin',
                'created_by_id' => auth()->id(),
                'key' => 'driver_assigned',
                'key_id' => $driver_id,
                'notes' => trans('words.driver has been assigned by', ['by_name' => auth()->user()->name, 'driver_name' => $driver->name ]),
            ]);
            // send notification
            
            if( $driver->lang == 'ar')
                {
                    $title = "تم تخصيص طلب لك رقم #". $OrderStatus->id;
                    $content = "تم تخصيص طلب لك رقم #". $OrderStatus->id . ' الي '.trans('words.'.$request->status);

                }else{
                    $title = "You have new OrderStatus number #".$OrderStatus->id;
                    $content = "You have new OrderStatus number #".$OrderStatus->id . ' to '. $request->status;
                }
                $type = "OrderStatus_assign_driver";
                
                $title_ar = "تم تخصيص طلب لك رقم #".$OrderStatus->id;
                $title_en = "You have new OrderStatus number #".$OrderStatus->id;
                $content_ar = "تم تخصيص طلب لك رقم #".$OrderStatus->id . ' الي '.trans('words.'.$request->status);
                $content_en = "You have new OrderStatus number #".$OrderStatus->id . ' to '. $request->status;
                $activity = "OrderStatus_details";
                $data2 = [
                    'OrderStatus' => $OrderStatus,
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
                $admin->notify(new AdminNotification($content_ar . $OrderStatus->id , route('OrderStatuss.show', $OrderStatus->id)));
                // send notification to admin end
                // send notification end
        }
        if ($request->has('status') && $request->get('status') != 'init' && $request->get('status') != $OrderStatus->status) {
            // check OrderStatus type start
            if ($OrderStatus->OrderStatus_type == 'external' && $request->get('status') == 'finished' && $request->has('driver_id') && $request->get('driver_id') != '' ) {
                $driver_id = $request->get('driver_id');
                $driver = Driver::find($driver_id);
                $pricelist = PriceList::where('from_OrderStatus_id', $OrderStatus->source_OrderStatus_id)
                                        ->where('to_OrderStatus_id', $OrderStatus->destination_OrderStatus_id)
                                        ->where('car_type_id', $OrderStatus->car_type_id)
                                        ->first();
                $driver->DriverPayment()->create([
                    'OrderStatus_id' => $OrderStatus->id,
                    'amount' => $pricelist->not_employee_price,
                    'type' => 'income'
                ]);
            }
            // check OrderStatus type end
            // send notification
            
            if( $OrderStatus->User->lang == 'ar')
                {
                    $title = "تم تغيير حالة الرحلة الخاصة الطلب رقم #". $OrderStatus->id;
                    $content = "تم تغيير حالة الرحلة الخاصة الطلب رقم #". $OrderStatus->id . ' الي '.trans('words.'.$request->status);

                }else{
                    $title = "Trip status has been changed for OrderStatus #".$OrderStatus->id;
                    $content = "Trip status has been changed for OrderStatus #".$OrderStatus->id . ' to '. $request->status;
                }
                $type = "OrderStatus_details";
                
                $title_ar = "تم تغيير حالة الرحلة الخاصة الطلب رقم #".$OrderStatus->id;
                $title_en = "Trip status has been changed for OrderStatus #".$OrderStatus->id;
                $content_ar = "تم تغيير حالة الرحلة الخاصة الطلب رقم #".$OrderStatus->id . ' الي '.trans('words.'.$request->status);
                $content_en = "Trip status has been changed for OrderStatus #".$OrderStatus->id . ' to '. $request->status;
                $activity = "OrderStatus_details";
                $data2 = [
                    'OrderStatus' => $OrderStatus,
                    'title_ar' => $title_ar,
                    'title_en' => $title_en,
                    'content_ar' => $content_ar,
                    'content_en' => $content_en,
                    'type' => $type,
                ];
                $notifiable = $OrderStatus->User()->first();
                $token = $notifiable->PlayerId()->pluck('player_id')->toArray();
                FCMController::Push($title, $content,$token,$data2);
                $notifiable->notify(new GeneralNotification($title, $content, $type, $data2, $activity) );
                
                // send notification to admin start
                $admin = Admin::first();
                $admin->notify(new AdminNotification($content_ar . $OrderStatus->id , route('OrderStatuss.show', $OrderStatus->id)));
                // send notification to admin end
                
                // send notification end
                    $OrderStatus->OrderStatusLog()->create([
                        'created_by_type' => 'admin',
                        'created_by_id' => auth()->id(),
                        'key' => 'status_changed',
                        'notes' => trans('words.OrderStatus status has been changed by', ['by_name' => auth()->user()->name, 'status' => trans('words.'.$request->status) ]),
                    ]);
            }
        $OrderStatus->update($data);
        return $OrderStatus;
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