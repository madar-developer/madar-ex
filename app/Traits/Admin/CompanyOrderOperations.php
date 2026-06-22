<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\OrderStatus;
use App\Notifications\AdminNotification;

trait CompanyOrderOperations
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
        if ($request->has('delivered_order')) {
            $data['delivered_order'] = Carbon::parse($request->get('delivered_order'));
        }
        if ($request->has('refrence_no')) {
            $data['refrence_no'] = $request->get('refrence_no');
            if($c = Order::where('refrence_no', $data['refrence_no'])->where('company_id', $data['company_id'])->count() ){
                $data['refrence_no_repeated'] = $data['refrence_no'];
                $repeated_count = $c + Order::where('refrence_no_repeated', $data['refrence_no'])->where('company_id', $data['company_id'])->count();
                $data['refrence_no'] = $data['refrence_no'] . "-$repeated_count";
                $data['price'] = 0;
            }
        }
        $data['company_id'] = auth('company')->id();
        $data['status'] = 'new';
        ///////////////////////////////////////////////////////

        DB::beginTransaction();
        $Order = Order::create($data);
        $status_data = OrderStatus::where('key', 'new')->first();
            $log_data = [
                'status' => 'new',
                'details' => $status_data->details
                // 'details' =>  trans('words.'.$request->get('status')) . ' , ' . $request->get('notes')
            ];
        $Order->OrderLog()->create($log_data);
        $admin = Admin::first();
        $message = 'تم اضافة طلب جديد : '.$Order->id;
        if($admin)
        {
            $admin->notify(new GeneralNotification($message, '/dashboard/orders/'.$Order->id ) );
        }
        $serial = 'mx-'.str_replace(' ', '',date('Y m').$Order->id);
        $Order->update(['serial' => $serial]);
        DB::commit();
        return $Order;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Order $Order,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Order->image));
            //
            $data['image'] = uploadImage($request->file('image'));
        }
        /////////////////////////////////////////////////////////////////////////////////
        if ($request->has('status') && $request->get('status') != 'init' && $request->get('status') != $Order->status) {
            $Order->OrderLog()->create([
                'status' => $request->get('status'),
                'details' => 'تم تغيير حالة الطلب الي : '. trans('words.'.$request->get('status'))
            ]);
            }
        $Order->update($data);
        return $Order;
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
