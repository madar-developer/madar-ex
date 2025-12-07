<?php
namespace App\Traits\Api;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Order;
use App\Models\Driver;
use App\Http\Controllers\Api\FCMController;
use Mail;
use DB;

trait TransactionOperations
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
        // base64
        if ($request->has('os') && $request->get('os') == 'android' && $request->has('image')) {
            $data['image'] = uploadImageBase64($request->get('image'));
            // 
        }
        // base64
        $data['parent_id']  = auth('api')->user()->id;
        DB::beginTransaction();
            $Transaction = Transaction::create($data);
        DB::commit();
        return $Transaction;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Transaction $Transaction,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Transaction->image));
            // 
            $data['image'] = uploadFile($request);
        }
        // base64
        if ($request->has('os') && $request->get('os') == 'android' && $request->has('image')) {
            $data['image'] = uploadImageBase64($request->get('image'));
            // 
        }
        // base64
        $Transaction->update($data);
        return $Transaction;
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
    public function SendOrderToDriver($order_id)
    {
        $Order = Order::find($order_id);
        $internal_drivers = Driver::where('type', '<>', 'not_employee')
                                    ->where('is_online', '1')
                                    ->doesnthave('Order', 'or', function($q){
                                        $q->where('status', 'finished');
                                    })
                                    ->get();
        $external_drivers = Driver::where('type', '=', 'not_employee')
                                    ->where('is_online', '1')
                                    ->doesntHave('Order', 'or', function($q){
                                        $q->where('status', 'finished');
                                    })
                                    ->get();
        // send notification to drivers start
        if ($internal_drivers->count() > 0) {
            foreach ($internal_drivers as $driver) {
                // send notification
                
                 if( $driver->lang == 'ar')
                {
                    $title = "طلب جديد";
                    $content = "طلب جديد#". $Order->id ;

                }else{
                    $title = "new Order ";
                    $content = "new Order #".$Order->id ;
                }
                $type = "new_order";
                
                $title_ar = "طلب جديد";
                $title_en = "new Order ";
                $content_ar = "طلب جديد#".$Order->id ;
                $content_en = "new Order #".$Order->id ;
                $activity = "new_order";
                $data2 = [
                    'order' => $Order,
                    'title_ar' => $title_ar,
                    'title_en' => $title_en,
                    'content_ar' => $content_ar,
                    'content_en' => $content_en,
                    'type' => $type,
                ];
                $notifiable = $driver;
                $token = $notifiable->PlayerId()->pluck('player_id')->toArray();
                FCMController::Push($title, $content,$token,$data2);
                // send notification end
            }
        } else {
            foreach ($external_drivers as $driver) {
                // send notification
                
                if( $driver->lang == 'ar')
                {
                    $title = "طلب جديد";
                    $content = "طلب جديد#". $Order->id ;

                }else{
                    $title = "new Order ";
                    $content = "new Order #".$Order->id ;
                }
                $type = "new_order";
                
                $title_ar = "طلب جديد";
                $title_en = "new Order ";
                $content_ar = "طلب جديد#".$Order->id ;
                $content_en = "new Order #".$Order->id ;
                $activity = "new_order";
                $data2 = [
                    'order' => $Order,
                    'title_ar' => $title_ar,
                    'title_en' => $title_en,
                    'content_ar' => $content_ar,
                    'content_en' => $content_en,
                    'type' => $type,
                ];
                $notifiable = $driver;
                $token = $notifiable->PlayerId()->pluck('player_id')->toArray();
                FCMController::Push($title, $content,$token,$data2);
                // send notification end
            }
        }
        
        // send notification to drivers end
        return $internal_drivers;
    }
}