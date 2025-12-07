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
use App\Models\Company;
use App\Models\OrderStatus;
use App\Models\Term;
use App\Notifications\AdminNotification;

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
        if ($request->has('refrence_no')) {
            $data['refrence_no'] = $request->get('refrence_no');
            if($c = Order::where('refrence_no', $data['refrence_no'])->where('company_id', $data['company_id'])->count() ){
                $data['refrence_no_repeated'] = $data['refrence_no'];
                $repeated_count = $c + Order::where('refrence_no_repeated', $data['refrence_no'])->where('company_id', $data['company_id'])->count();
                $data['refrence_no'] = $data['refrence_no'] . "-$repeated_count";
                $data['price'] = 0;
            }
        }
        ////////////////////////// serial start/////////////////////////////

        DB::beginTransaction();
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
        // $company = Company::find($Order->company_id);
        // if ($company) {
        //     $company->notify(new GeneralNotification($message, '/company/company-orders/'.$Order->id ) );

        // }

        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            //
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
            $Order->BranchData()->create(['admin_id' => $branch_id]);
        }
        DB::commit();
        $company = Company::find($Order->company_id);
        if ($company) {
            $company->notify(new GeneralNotification($message, '/company/company-orders/'.$Order->id ) );

        }
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
        if ($request->has('delivery_date')) {
            $data['delivery_date'] = Carbon::parse($request->get('delivery_date'));
            $rr = $Order->OrderLog()->latest()->first();

            if ($rr && $rr->status == 'reschedule') {
                $rr->details = $rr->details . ' (' .$request->delivery_date. ') ';
                $rr->save();
            }
        }
        if ($request->has('deliver_failed_id')) {
                $rr = $Order->OrderLog()->where('status', 'deliver_failed')->latest()->first();
                $rr->details = $rr->details . ' (' . @Term::find($request->get('deliver_failed_id'))->description . ')';
                $rr->reason = $request->get('deliver_failed_id');
                $rr->save();
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
        /////////////////////////////////////////////////////////// the date of first step from
        if ($request->has('status') && $request->get('status') != 'new' && $request->get('status') != $Order->status) {
            // webhook start
            if($Order->Company()->first() && $Order->Company()->first()->notify_url)
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $Order->Company()->first()->notify_url."refrence_no=$Order->serial&status=$request->get('status')");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);



            }
            // webhook end
            $status_data = OrderStatus::where('key', $request->get('status'))->first();
            $log_data = [
                'status' => $request->get('status'),
                'details' => $status_data->details
                // 'details' =>  trans('words.'.$request->get('status')) . ' , ' . $request->get('notes')
            ];
            $Order->OrderLog()->create($log_data);
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
            if ($request->get('status') == 'at_office') {
                $com_name = '';
                if($Order->Company()->first())
                {
                    $com_name = $Order->Company->name;
                }
                $msg = "تم خروج الطلب رقم $Order->serial من المتجر و جاري توصيلها اليكم.";
                $msg = "مرحبا $Order->recipent_name  ، شحنتك  $Order->serial  من  $com_name  في طريقها إليك وسيتم التواصل معكم عند اتجاه المندوب للعنوان";
                sendSMS(FormatPhone($Order->phone), $msg);
            }
            if ($request->get('status') == 'init') {
                $com_name = '';
                if($Order->Company()->first())
                {
                    $com_name = $Order->Company->name;
                }
                // $msg = "تم خروج الطلب رقم $Order->serial من المتجر و جاري توصيلها اليكم.";
                // $msg = "مرحبا $Order->recipent_name  ، شحنتك  $Order->serial  من  $com_name  في طريقها إليك وسيتم التواصل معكم عند اتجاه المندوب للعنوان";
                // sendSMS(FormatPhone($Order->phone), $msg);
                $Order->update(['receive_date' => Carbon::now()]);
            }
        }


        //make invoice if status=delivered
        if ($request->has('status') && in_array( $request->get('status') , ['delivered', 'returned'] ) && $request->get('status') != $Order->status) {
            if ($request->get('status') == 'delivered') {
                $Order->update(['delivery_date' => Carbon::now()]);
                // fire update status on merchant side
                if($Order->Company()->first() && $Order->Company()->first()->id == 663){
                    if (strpos($Order->refrence_no, '-') !== false) {
                        sendMadarxWebhook($Order->refrence_no_repeated, 'delivered', $Order->serial, Carbon::now()->format('Y-m-d H:i:s'), 'Package delivered to customer successfully'  );
                    } else {
                        sendMadarxWebhook($Order->refrence_no, 'delivered', $Order->serial, Carbon::now()->format('Y-m-d H:i:s'), 'Package delivered to customer successfully'  );
                    }
                }
                // fire update status on merchant side end
            }
            // if ($Order->city_id == $Order->Company->city_id) {
            //     $city_cost = $Order->Company->inside_price ?? 0;
            // }else{
            //     $city_cost = $Order->Company->outside_price ?? 0;
            // }
            // **
            if ($Order->City()->first()) {
                if ($Order->Driver()->first()) {
                    $driver_cost = $Order->Driver->DriverCityPrice()->where('city_id', $Order->city_id)->first();
                    if ($driver_cost) {
                        $driver_cost = $driver_cost->delivery_cost;
                    } else {
                        $driver_cost = 0;
                    }
                } else {
                    $driver_cost = 0;
                }

                if ($Order->City()->first()->delivery_cost == '1') {
                    $city =  $Order->City()->first();
                } else {
                    $city =  $Order->City()->first()->Parent()->first();
                }
                if ($city) {
                    $city_cost = $city->CityGroup()->select('city_groups.delivery_cost', 'city_groups.id')->first();
                } else {
                    $city_cost = false;

                }
                if ($city_cost) {
                    $row = $Order->Company()->first()->CompanyCityGroup()->where('city_group_id', $city_cost->id)->first();
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
                $cost = $Order->Company()->first()->c_o_d_cost ?? 0;
            }
            if ($request->get('status') == 'returned') {
                $cost = 0;
                $city_cost = $Order->Company()->first()->return_cost ?? 0;
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
