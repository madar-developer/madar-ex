<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Order;
use App\Notifications\GeneralNotification;
use App\Http\Controllers\Api\FCMController;
use DB;
use Carbon\Carbon;

trait TripOperations
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
            $data['image'] = uploadFile($request);
        }
        if ($request->has('start_date')) {
            $data['start_date'] = Carbon::parse($request->start_date);
        }
        if ($request->has('end_date')) {
            $data['end_date'] = Carbon::parse($request->end_date);
        }
        DB::beginTransaction();
        $Trip = Trip::create($data);
        if ($request->has('orders')) {
            foreach ($request->orders as $order) {
                $order = Order::find($order);
                $order->update(['trip_id' => $Trip->id, 'trip_number' => $Trip->trip_number]);
                // trip log
                $order->OrderLog()->create([
                    'created_by_type' => 'admin',
                    'created_by_id' => auth()->id(),
                    'key' => 'trip_assigned',
                    'key_id' => $Trip->id,
                    'notes' => trans('words.order is on trip number', ['by_name' => auth()->user()->name, 'trip_number' => $request->trip_number ]),
                ]);
            } 
        }
        DB::commit();
        return $Trip;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Trip $Trip,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Trip->image));
            // 
            $data['image'] = uploadFile($request);
        }
        if ($request->has('orders')) {
            foreach ($request->orders as $order) {
                Order::find($order)->update(['trip_id' => $Trip->id, 'trip_number' => $Trip->trip_number]);
            } 
        }
        if ($request->has('status') && $request->get('status') != 'init' && $request->get('status') != $Trip->status) {
            // send notification
            foreach($Trip->Order()->get() as $Order)
            {
                if( $Order->User->lang == 'ar')
                {
                    $title = "تم تغيير حالة الرحلة الخاصة الطلب رقم #".$Order->id;
                    $content = "تم تغيير حالة الرحلة الخاصة الطلب رقم #".$Order->id . ' الي '.trans('words.'.$request->status);

                }else{
                    $title = "Trip status has been changed for order #".$Order->id;
                    $content = "Trip status has been changed for order #".$Order->id . ' to '. $request->status;
                }
                $type = "order_".$request->get('status');
                
                $title_ar = "تم تغيير حالة الرحلة الخاصة الطلب رقم #".$Order->id;
                $title_en = "Trip status has been changed for order #".$Order->id;
                $content_ar = "تم تغيير حالة الرحلة الخاصة الطلب رقم #".$Order->id . ' الي '.trans('words.'.$request->status);
                $content_en = "Trip status has been changed for order #".$Order->id . ' to '. $request->status;
                $activity = "order_".$request->get('status');
                $data2 = [
                    'order' => $Order,
                    'title_ar' => $title_ar,
                    'title_en' => $title_en,
                    'content_ar' => $content_ar,
                    'content_en' => $content_en,
                ];
                $notifiable = $Order->User()->first();
                $token = $notifiable->PlayerId()->pluck('player_id')->toArray();
                FCMController::Push($title, $content,$token,$data2);
                $notifiable->notify(new GeneralNotification($title, $content, $type, $data2, $activity) );
                // send notification end
                    $Order->OrderLog()->create([
                        'create_by_type' => 'admin',
                        'create_by_id' => auth()->id(),
                        'key' => 'status_changed',
                        'notes' => trans('words.order status has been changed by', ['by_name' => auth()->user()->name, 'status' => trans('words.'.$request->status) ]),
                    ]);
            }
            
        }
        $Trip->update($data);
        return $Trip;
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