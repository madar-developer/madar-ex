<?php
namespace App\Traits\Api;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Company;
use App\Models\OrderStatus;
use App\Notifications\AdminNotification;
use Auth;

trait OrderOperations
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
        if ($request->has('pick_up_date')) {
            $data['pick_up_date'] = Carbon::parse($request->get('pick_up_date'));
        }
        if ($request->has('delivered_order')) {
            $data['delivered_order'] = Carbon::parse($request->get('delivered_order'));
        }
        ////////////////////////// serial start/////////////////////////////

        DB::beginTransaction();
        $data['status'] = 'new';
        $data['company_id'] = auth()->id();
        $Order = Order::create($data);
        $s = str_replace(' ', '',date('Y m').$Order->id);
        $serial = 'mx-'.$s;
        $Order->update(['serial' => $serial, 'serial_no' => (int)$s]);
        //////////////////////// the date of creation order
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
        $company = Company::find($Order->company_id);
        if ($company) {
            $company->notify(new GeneralNotification($message, '/company/company-orders/'.$Order->id ) );

        }


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
        /////////////////////////////////////////////////////////// the date of first step from
        if ($request->has('status') && $request->get('status') != 'init' && $request->get('status') != $Order->status) {
            $Order->OrderLog()->create([
                'status' => $request->get('status'),
                'details' =>  trans('words.'.$request->get('status')) . ' , ' . $request->get('notes')
            ]);
            $admin = Admin::first();
            $message = 'تم تغيير حالة الطلب  : '.$Order->id  . ' الي ' . trans('words.'.$request->get('status'));
            if($admin)
            {
                $admin->notify(new GeneralNotification($message, '/dashboard/orders/'.$Order->id ) );
            }
            if($Order->Company()->first())
            {
                $Order->Company()->first()->notify(new GeneralNotification($message, '/company/company-orders/'.$Order->id ) );
            }
            if ($request->get('status') == 'init') {
                $msg = "تم خروج الطلب رقم $Order->refrence_no من المتجر و جاري توصيلها اليكم.";
                $Order->update(['receive_date' => Carbon::now()]);
                sendSMS($Order->phone, $msg);
            }
        }


        //make invoice if status=delivered
        if ($request->has('status') && in_array( $request->get('status') , ['delivered', 'returned'] ) && $request->get('status') != $Order->status) {
            // if ($Order->city_id == $Order->Company->city_id) {
            //     $city_cost = $Order->Company->inside_price ?? 0;
            // }else{
            //     $city_cost = $Order->Company->outside_price ?? 0;
            // }
            // **
            if ($Order->City()->first()) {
                $driver_cost = $Order->Driver->DriverCityPrice()->where('city_id', $Order->city_id)->first();
                if ($driver_cost) {
                    $driver_cost = $driver_cost->delivery_cost;
                } else {
                    $driver_cost = 0;
                }

                if ($Order->City()->first()->parent == '0') {
                    $city =  $Order->City()->first();
                } else {
                    $city =  $Order->City()->first()->Parent()->first();
                }



                if ($city) {
                    $row = $Order->Company()->first()->CompanyCity()->where('city_id', $city->id)->first();
                    if ($row) {
                        $city_cost = $row->delivery_cost;

                    } else {
                        $city_cost = 0;
                    }

                }else{
                    $city_cost = 0;
                }
            } else {
                $city_cost = 0;

                $driver_cost = 0;
            }
            // **
            $cost = 0;
            if ($Order->payment_method_id == '1') {
                // $cost = 5;
                $cost = $Order->Company->c_o_d_cost ?? 0;
            }
            if ($request->get('status') == 'returned') {
                $cost = 0;
                $city_cost = $Order->Company->return_cost ?? 0;
            }
            $madar_price = $city_cost + $cost;
            $total_price = $Order->price;
            $company_price = $Order->price - $madar_price;
            // here we will create invoice start
            $Order->Invoice()->create([
                'total_price'=>$total_price ,
                'company_price'=>$company_price ,
                'madar_price' => $madar_price ,
                'driver_cost' => $driver_cost ,
                'active' => 0,
            ]);
            // here we will create invoice end
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
