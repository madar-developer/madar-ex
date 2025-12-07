<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Notifications\GeneralNotification;
use App\Http\Controllers\Api\FCMController;
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
        if ($request->has('confirmed') && $request->has('order_id') && !in_array($request->get('order_id'), ['0', null]) ) {
            $Transaction->Order()->update(['paied' => '1']);
            // send notification
            $title = "تم قبول الدفع .";
            $content = ' تم قبول الدفع رقم : '. $Transaction->id;
            
            $title_ar = "تم قبول الدفع .";
            $title_en = "payment transaction accepted";
            $content_ar = ' تم قبول الدفع رقم : '. $Transaction->id;
            $content_en = "payment transaction accepted num " . $Transaction->id;
        	$activity = "subscription_details";
            $type = "transaction_accepted";
            $data2 = [
                'transaction' => $Transaction,
                'title_ar' => $title_ar,
                'title_en' => $title_en,
                'content_ar' => $content_ar,
                'content_en' => $content_en,
                'type' => $type,
            ];
            $notifiable = $Transaction->Parent()->first();
            $token = $notifiable->PlayerId()->pluck('player_id')->toArray();
            FCMController::Push($title, $content,$token,$data2, $activity);
            $notifiable->notify(new GeneralNotification($title, $content, $type, $data2) );
            // send notification
        }
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
}