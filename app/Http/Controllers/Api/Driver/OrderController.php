<?php

namespace App\Http\Controllers\Api\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OrderResource;
use App\Http\Resources\Api\InvoicesResource;
use App\Http\Resources\Api\InvoiceResource;
use App\Http\Resources\Api\StatusResource;
use App\Models\Admin;
use Auth;
use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Term;
use App\Notifications\GeneralNotification;
use App\Jobs\SendMadarxWebhookJob;
use Carbon\Carbon;
class OrderController extends Controller
{
    public function index()
    {
        $driver = Auth::guard('api-driver')->user();
        $orders = $driver->Order()->where('status', '<>', 'returned')->where('collected','<>', 1)->latest();
        if (Request()->has('status') && Request()->get('status') != '') {
            $orders = $orders->where('status', Request()->get('status') );
        }
        if (Request()->has('date') && Request()->get('date') != '') {
            $orders = $orders->whereDate('created_at', Carbon::parse(Request()->get('date')) );
        }
        $orders = $orders->paginate(20);
        $orders->data = OrderResource::collection($orders);
        return Response()->json([
                'data' => [
                    'orders' => $orders
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function searchOrders(Request $request)
    {
        $driver = Auth::guard('api-driver')->user();
        $orders = $driver->Order()->where('status', '<>', 'returned')->where('collected','<>', 1)->where('refrence_no', $request->get('refrence_no'));
        if (Request()->has('status') && Request()->get('status') != '') {
            $orders = $orders->where('status', Request()->get('status') );
        }
        if (Request()->has('date') && Request()->get('date') != '') {
            $orders = $orders->whereDate('created_at', Carbon::parse(Request()->get('date')) );
        }
        $orders = $orders->paginate(20);
        $orders->data = OrderResource::collection($orders);
        if(!$request->has('refrence_no') || $request->get('refrence_no') == ''){
            $orders = [];
        }
        return Response()->json([
                'data' => [
                    'orders' => $orders
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function show($id)
    {
        $driver = Auth::guard('api-driver')->user();
        $order = Order::where('id', $id)->orWhere('serial', $id)->first();
        if (!$order) {
            return Response()->json([
                'data' => [
                ],
                'message' => trans('words.no result'),
                'code' => getMsgCode('notFound')
            ]);
        }
        $levels = Order::getLevels($order->status);
        $order = new OrderResource($order);
        $statuses = OrderStatus::whereIn('key', $levels)->select('id', 'key', 'name')->get();
        $statuses = StatusResource::collection($statuses);
        return Response()->json([
                'data' => [
                    'order' => $order,
                    'statuses' => $statuses,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function accept(Request $request)
    {
        $driver = auth('api-driver')->user();
        $data = [];
        $data['status_id'] = OrderStatus::where('key', 'accepted')->first()->id;
        $data['status'] = OrderStatus::where('key', 'accepted')->first()->key;
        $order = Order::find($request->order_id);
        if (!$order) {
            return Response()->json([
                'data' => [
                ],
                'message' => trans('words.no result'),
                'code' => getMsgCode('notFound')
            ]);
        }
        $order->update($data);

        $order = new OrderResource($order);

        return Response()->json([
            'data'          => [
                'order'  => $order,
            ],
            'message'       => 'success',
            'code'          => getMsgCode('success'),
        ]);
    }

    public function refuse(Request $request)
    {
        $driver = auth('api-driver')->user();
        $data = [];
        $order = Order::find($request->order_id);
        if (!$order) {
            return Response()->json([
                'data' => [
                ],
                'message' => trans('words.no result'),
                'code' => getMsgCode('notFound')
            ]);
        }
        $order->driver_id = null;
        $order->save();

        $order = new OrderResource($order);
        return Response()->json([
            'data'          => [
                'order'  => $order,
            ],
            'message'       => 'success',
            'code'          => getMsgCode('success'),
        ]);
    }
    public function changestatus(Request $request)
    {
        $driver = auth('api-driver')->user();
        $data = [];
        $order = Order::where('id',$request->order_id)->orWhere('serial',$request->order_id)->first();
        $Order = $order;
        if (!$order) {
            $order = Order::where('serial', $request->order_id)->first();
        }
        if (!$order) {
            return Response()->json([
                'data' => [
                ],
                'message' => trans('words.no result'),
                'code' => getMsgCode('notFound')
            ]);
        }
        $status = $request->get('status');
        // $data['price'] = ;
        // $data['commission'] = ;
        $status_data = OrderStatus::where('key', $status)->first();
        $data['status_id'] = $status_data->id;
        $data['status'] = $status;
        if ($request->has('notes')) {
            $data['notes'] = $request->get('notes');
        }

        if ($request->has('signature')) {
            $data['signature'] = $request->get('signature');
        }
        if ($request->has('cash_type')) {
            $data['cash_type'] = $request->get('cash_type');
        }
        // if ($request->get('status') == 'at_office') {
            $data['driver_id'] = $driver->id;
        // }
        // ******************************************************
        if ($request->has('delivery_date')) {
            $data['delivery_date'] = Carbon::parse($request->get('delivery_date'));
            $rr = $Order->OrderLog()->latest()->first();
            if ($rr && $rr->status == 'reschedule') {
                $rr->details = $rr->details . ' (' .$request->delivery_date. ') ';
                $rr->added_by_type = 'driver';
                $rr->added_by_id = $driver->id;
                $rr->save();
            }
        }
        if ($request->has('status') && $request->get('status') != 'new' && $request->get('status') != $Order->status) {
            if ($request->get('status') == 'delivered') {
                $Order->update(['delivery_date' => Carbon::now()]);
                // fire update status on merchant side - dispatch to job queue
                $company = $Order->Company()->first();
                if($company && $company->id == 663){
                    try {
                        $orderRef = (strpos($Order->refrence_no, '-') !== false) ? $Order->refrence_no_repeated : $Order->refrence_no;
                        SendMadarxWebhookJob::dispatch(
                            $orderRef,
                            'delivered',
                            $Order->serial,
                            Carbon::now()->format('Y-m-d H:i:s'),
                            'Package delivered to customer successfully'
                        )->afterResponse(); // Run after HTTP response is sent
                    } catch (\Exception $e) {
                        \Log::error('Failed to dispatch Madarx webhook job: ' . $e->getMessage());
                    }
                }
                // fire update status on merchant side end
            }
            // webhook start
            if($Order->Company()->first() && $Order->Company()->first()->notify_url)
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $Order->Company()->first()->notify_url."refrence_no=$Order->refrence_no&status=$request->get('status')");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);

            }
            // webhook end
            // signature
            if($request->get('status') == 'delivered' && $request->has('signature'))
            {
                $order->signature = $request->get('signature');
                $order->save();
            }
            // signature
            $log_data = [
                'status'        => $request->get('status'),
                'added_by_type' => 'driver',
                'added_by_id'   => $driver->id,
                'details'       => $status_data->details
                // 'details' =>  trans('words.'.$request->get('status')) . ' , ' . $request->get('notes')
            ];
            if ($request->has('deliver_failed_id')) {
                $log_data['reason'] = $request->get('deliver_failed_id');
                $log_data['details'] = $log_data['details'] . ' (' . @Term::find($request->get('deliver_failed_id'))->description . ')';
            }
            $Order->OrderLog()->create($log_data);
            if ($request->has('delivery_date')) {
                $data['delivery_date'] = Carbon::parse($request->get('delivery_date'));
                $rr = $Order->OrderLog()->latest()->first();
                if ($rr && $rr->status == 'reschedule') {
                    $rr->details = $rr->details . ' (' .$request->delivery_date. ') ';
                    $rr->save();
                }
            }
            // Load company once to avoid multiple queries
            $company = $Order->Company()->first();
            
            $message = 'تم تغيير حالة الطلب  : '.$Order->id  . ' الي ' . trans('words.'.$request->get('status'));
            
            // Send notifications asynchronously - dispatch to background to prevent blocking
            // Since GeneralNotification implements ShouldQueue, it will be queued automatically
            try {
                $admin = Admin::first();
                if($admin) {
                    // Use sendNow() to bypass queue, or regular notify() to use queue
                    // For speed, we'll dispatch to queue but don't wait
                    \Illuminate\Support\Facades\Notification::send($admin, new GeneralNotification($message, '/dashboard/orders/'.$Order->id));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to notify admin: ' . $e->getMessage());
            }
            
            if($company) {
                try {
                    \Illuminate\Support\Facades\Notification::send($company, new GeneralNotification($message, '/company/company-orders/'.$Order->id));
                } catch (\Exception $e) {
                    \Log::error('Failed to notify company: ' . $e->getMessage());
                }
            }

            if ($request->get('status') == 'init') {
                try {
                    $msg = "تم خروج الطلب رقم $Order->refrence_no من المتجر و جاري توصيلها اليكم.";
                    $msg = "مرحبا $Order->recipent_name  ، شحنتك  $Order->refrence_no  من  $Order->Company->name  في طريقها إليك وسيتم التواصل معكم عند اتجاه المندوب للعنوان";
                    sendSMS(FormatPhone($Order->phone), $msg);
                } catch (\Exception $e) {
                    \Log::error('Failed to send SMS: ' . $e->getMessage());
                }
                $Order->update(['receive_date' => Carbon::now()]);
            }
        }


        //make invoice if status=delivered
        if ($request->has('status') && in_array( $request->get('status') , ['delivered', 'returned'] ) && $request->get('status') != $Order->status) {
            if ($request->get('status') == 'delivered') {
                $Order->update(['delivery_date' => Carbon::now()]);
            }
            if ($Order->city_id == $Order->Company->city_id) {
                $city_cost = $Order->Company->inside_price ?? 0;
            }else{
                $city_cost = $Order->Company->outside_price ?? 0;
            }
            $cost = 0;
            if ($Order->payment_method_id == '1') {
                $cost = 5;
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
                'active' => 0,
            ]);
            // here we will create invoice end
        }
        // ******************************************************
        $order->update($data);

        // Eager load relationships to avoid N+1 queries
        $order->load(['Company', 'PaymentMethod']);
        
        $order = new OrderResource($order);
        return Response()->json([
            'data'          => [
                'order'  => $order,
            ],
            'message'       => 'success',
            'code'          => getMsgCode('success'),
        ]);
    }
    public function changestatusArr(Request $request)
    {
        $driver = auth('api-driver')->user();
        $data = [];
        $orders = Order::whereIn('id',$request->order_ids)->orWhereIn('serial',$request->order_ids);
        $orders_1 = Order::whereIn('id',$request->order_ids)->orWhereIn('serial',$request->order_ids)->get();
        if ($orders->count() == 0) {
            $lst = [];
            foreach( $request->order_ids as $el){
                $lst[] = strtolower($el);
            }
            
            $orders = Order::whereIn('serial', $lst);
            $orders_1 = Order::whereIn('serial', $lst)->get();
        }
        if ($orders->count() == 0) {
            return Response()->json([
                'data' => [
                ],
                'message' => trans('words.no result'),
                'code' => getMsgCode('notFound')
            ]);
        }
        $status = $request->get('status');
        $status_data = OrderStatus::where('key', $status)->first();
        // $data['price'] = ;
        // $data['commission'] = ;
        // $data['status_id'] = OrderStatus::where('key', $status)->first()->id;
        $data['status'] = $status;
        if ($request->has('notes')) {
            $data['notes'] = $request->get('notes');
        }
        if ($request->has('signature')) {
            $data['signature'] = $request->get('signature');
        }
        // if ($request->get('status') == 'at_office') {
            $data['driver_id'] = $driver->id;
        // }
        // $orders->update($data);

        foreach ($orders_1 as $Order) {

            // ******************************************************
            if ($request->has('delivery_date')) {
                $data['delivery_date'] = Carbon::parse($request->get('delivery_date'));
                $rr = $Order->OrderLog()->latest()->first();
                if ($rr && $rr->status == 'reschedule') {
                    $rr->details = $rr->details . ' (' .$request->delivery_date. ') ';
                    $rr->save();
                }
            }
            if ($request->has('status') && $request->get('status') != 'new' && $request->get('status') != $Order->status) {
                // webhook start - with timeout to prevent hanging
                /*$company = $Order->Company()->first();
                if($company && $company->notify_url)
                {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $company->notify_url."refrence_no=$Order->refrence_no&status=$request->get('status')");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 second timeout
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // 3 second connection timeout
                    $output = curl_exec($ch);
                    curl_close($ch);*/
                }
                // webhook end
                $log_data = [
                    'status' => $request->get('status'),
                    'details' => $status_data->details,
                    'added_by_type' => 'driver',
                    'added_by_id' => $driver->id,
                    // 'details' =>  trans('words.'.$request->get('status')) . ' , ' . $request->get('notes')
                ];
                if ($request->has('deliver_failed_id')) {
                    $log_data['reason'] = $request->get('deliver_failed_id');
                    $log_data['details'] = $log_data['details'] . ' (' . @Term::find($request->get('deliver_failed_id'))->description . ')';
                }
                $Order->OrderLog()->create($log_data);
                if ($request->has('delivery_date')) {
                    $data['delivery_date'] = Carbon::parse($request->get('delivery_date'));
                    $rr = $Order->OrderLog()->latest()->first();
                    if ($rr && $rr->status == 'reschedule') {
                        $rr->details = $rr->details . ' (' .$request->delivery_date. ') ';
                        $rr->added_by_type = 'driver';
                        $rr->added_by_id = $driver->id;
                        $rr->save();
                    }
                }
                // Load company once to avoid multiple queries
                $company = $Order->Company()->first();
                
                $message = 'تم تغيير حالة الطلب  : '.$Order->id  . ' الي ' . trans('words.'.$request->get('status'));
                
                // Send notifications asynchronously to prevent blocking
                try {
                    $admin = Admin::first();
                    if($admin) {
                        $admin->notify(new GeneralNotification($message, '/dashboard/orders/'.$Order->id ) );
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to notify admin: ' . $e->getMessage());
                }
                
                if($company) {
                    try {
                        $company->notify(new GeneralNotification($message, '/company/company-orders/'.$Order->id ) );
                    } catch (\Exception $e) {
                        \Log::error('Failed to notify company: ' . $e->getMessage());
                    }
                }

                if ($request->get('status') == 'init') {
                    try {
                        $msg = "تم خروج الطلب رقم $Order->refrence_no من المتجر و جاري توصيلها اليكم.";
                        $msg = "مرحبا $Order->recipent_name  ، شحنتك  $Order->refrence_no  من  $Order->Company->name  في طريقها إليك وسيتم التواصل معكم عند اتجاه المندوب للعنوان";
                        sendSMS(FormatPhone($Order->phone), $msg);
                    } catch (\Exception $e) {
                        \Log::error('Failed to send SMS: ' . $e->getMessage());
                    }
                    $Order->update(['receive_date' => Carbon::now()]);
                }
            }


            //make invoice if status=delivered
            if ($request->has('status') && in_array( $request->get('status') , ['delivered', 'returned'] ) && $request->get('status') != $Order->status) {
                if ($request->get('status') == 'delivered') {
                    $Order->update(['delivery_date' => Carbon::now()]);
                }
                if ($Order->city_id == $Order->Company->city_id) {
                    $city_cost = $Order->Company->inside_price ?? 0;
                }else{
                    $city_cost = $Order->Company->outside_price ?? 0;
                }
                $cost = 0;
                if ($Order->payment_method_id == '1') {
                    $cost = 5;
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
                    'active' => 0,
                ]);
                // here we will create invoice end
            }
            // ******************************************************
        }
        $orders->update($data);


        $orders =  OrderResource::collection($orders_1);
        return Response()->json([
            'data'          => [
                'orders'  => $orders,
            ],
            'message'       => 'success',
            'code'          => getMsgCode('success'),
        ]);
    }
    public function reschedule(Request $request)
    {
        $driver = auth('api-driver')->user();
        $data = [];
        $order = Order::find($request->order_id);
        if (!$order) {
            return Response()->json([
                'data' => [
                ],
                'message' => trans('words.no result'),
                'code' => getMsgCode('notFound')
            ]);
        }
        $details = $request->get('details');
        // $data['price'] = ;
        // $data['commission'] = ;
        $status_data = OrderStatus::where('key', 'reschedule')->first();
        $data['status'] = 'reschedule';
        $order->status = 'reschedule';
        $order->save();
        $data['details'] = $details;
        $data['added_by_type'] = 'driver';
        $data['added_by_id'] = $driver->id;
        if ($request->has('date')) {
            $data['details'] = $status_data->details . ' (' .$request->date. ') ';
            $order->update(['delivery_date' => Carbon::parse($request->get('date'))]);
            $admin = Admin::first();
            $message = 'تم تغيير حالة الطلب  : '.$order->id  . ' الي ' . trans('words.reschedule');
            if($admin)
            {
                $admin->notify(new GeneralNotification($message, '/dashboard/orders/'.$order->id ) );
            }
            if($order->Company()->first())
            {
                $order->Company()->first()->notify(new GeneralNotification($message, '/company/company-orders/'.$order->id ) );
            }
        }
        $order->OrderLog()->create($data);


        $order = new OrderResource($order);
        return Response()->json([
            'data'          => [
                'order'  => $order,
            ],
            'message'       => 'success',
            'code'          => getMsgCode('success'),
        ]);
    }
    
    public function NotCollectedStatistics()
    {
        $driver = Auth::guard('api-driver')->user();
        $orders_drivers_not_get_paid = $driver->Order()->whereHas('Invoice', function($q){
                                            $q->where('driver_paied', '0');
                                        })
                                        ->where('status', 'delivered')
                                        ->where('collected', 0)
                                        ->with(['Company','PaymentMethod','City', 'Invoice'])
                                        ->paginate(40);
        $orders_drivers_not_get_paid->data = OrderResource::collection($orders_drivers_not_get_paid);
        
        $not_c = $driver->Order()->whereHas('Invoice', function($q){
                                            $q->where('driver_paied', '0');
                                        })
                                        ->where('status', 'delivered')
                                        ->where('collected', 0);
        return Response()->json([
                'data' => [
                    // 'orders_count' => $driver->Invoice()->where('orders.status', 'delivered')->count(),
                    // 'total_cost' => $driver->Invoice()->where('orders.status', 'delivered')->sum('orders.price'),
                    'orders_count' => $driver->Order()->whereHas('Invoice', function($q){
                                            $q->where('driver_paied', '0');
                                        })
                                        ->where('status', 'delivered')
                                        ->where('collected', 0)->count(),
                    // 'orders_count' => $driver->Order()->where('collected', '0')->where('status', 'delivered')->count(),
                    // 'total_cost' => $driver->Order()->where('collected', '0')->where('status', 'delivered')->sum('orders.price'),
                    'total_cost' => $driver->Order()->whereHas('Invoice', function($q){
                                            $q->where('driver_paied', '0');
                                        })
                                        ->where('status', 'delivered')
                                        ->where('collected', 0)->sum('orders.price'),
                    // 'total_network' => $driver->Invoice()->where('orders.status', 'delivered')->where('orders.cash_type', 'network')->sum('orders.price'),
                    // 'total_cash' => $driver->Invoice()->where('orders.status', 'delivered')->where('orders.cash_type', 'cash')->sum('orders.price') ,
                    // 'total_network' => (int) ((clone $not_c)->where(function ($q){
                    //                         $q->where('payment_method_id', '<>', '1')->orWhereNull('payment_method_id')->orwhere('cash_type', '=', 'network_cash');
                    //                     })->sum('price') - (clone $not_c)->sum('cash_amount')), 
                    'total_network' =>  ((clone $not_c)->where(function ($q){
                                            $q->where('payment_method_id', '=', '1')->orwhere('cash_type', '=', 'network_cash');
                                        })->where('cash_type', '=', 'network_cash')->sum('price')
                                                + (clone $not_c)->where('payment_method_id', '=', '1')->where('cash_type', '=', 'network')->sum('price')
                                                - (clone $not_c)->where(function ($q){
                                            $q->where('payment_method_id', '<>', '1')->orWhereNull('payment_method_id')->orwhere('cash_type', '=', 'network_cash');
                                        })->where('cash_type', '=', 'network_cash')->sum('cash_amount')), 
                                        
                                        
                    'total_cash' =>  ((clone $not_c)->where(function($q){
                        $q->where('cash_type', '!=', 'network_cash')->orWhereNull('cash_type');
                    })->where('payment_method_id', '1')->sum('price') + (clone $not_c)->sum('cash_amount') ) , 
                    'orders' => $orders_drivers_not_get_paid
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    
    public function InvoicesStatistics()
    {
        $driver = Auth::guard('api-driver')->user();
        $driver_finances = $driver->DriverFianance()->where('collected_from_driver', 0)->with(['Admin','Driver'])->paginate(40);
        $orders_ids = [];
        $ids = $driver->DriverFianance()->where('collected_from_driver', 0)->pluck('orders');
        foreach($ids as $ls){
            $orders_ids = array_merge($orders_ids, explode(',', $ls));
        }
        $driver_finances->data = InvoicesResource::collection($driver_finances);
        $t_amount = 0;
        foreach($driver->DriverFianance()->where('collected_from_driver', '0')->get() as $e){
            $t_amount += $e->OrdersNetProfit();
        }
        return Response()->json([
                'data' => [
                    // 'orders_count' => $driver->Invoice()->where('orders.status', 'delivered')->where('invoices.driver_paied', 1)->whereNotIn('orders.id', $orders_ids )->count(),
                    // 'total_cost' => $driver->Invoice()->where('orders.status', 'delivered')->where('invoices.driver_paied', 1)->whereNotIn('orders.id', $orders_ids )->sum('invoices.driver_cost'),
                    'orders_count' => $driver->Order()->whereIn('id', $orders_ids)->count(),
                    // 'total_cost' => $driver->DriverFianance()->where('collected_from_driver', '0')->sum('total_amount'),
                    'total_cost' => $t_amount,
                    'invoices' => $driver_finances
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    
    public function InvoiceShow($id)
    {
        $driver = Auth::guard('api-driver')->user();
        $row = $driver->DriverFianance()->find($id);
        $row = new InvoiceResource($row);
        return Response()->json([
                'data' => [
                    'invoice' => $row
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    
    public function changeCashType(Request $request)
    {
        $driver = auth('api-driver')->user();
        $data = [];
        $order = Order::find($request->order_id);
        if (!$order) {
            return Response()->json([
                'data' => [
                ],
                'message' => trans('words.no result'),
                'code' => getMsgCode('notFound')
            ]);
        }
        $details = $request->get('details');
        // $data['price'] = ;
        // $data['commission'] = ;
        if($request->has('cash_type')  && in_array($request->get('cash_type'), ['network', 'cash', 'network_cash'])){
            $order->cash_type = $request->get('cash_type');
        }
        if( $request->has('cash_amount') && $request->get('cash_amount') != '' ){
            $order->include_delivery_cost = 1;
            $order->cash_amount = $request->get('cash_amount');
        }
        $order->save();
            
        $data['status'] = 'cash_type';
        $data['details'] = $details;
        $data['added_by_type'] = 'driver';
        $data['added_by_id'] = $driver->id;
        $order->OrderLog()->create($data);


        $order = new OrderResource($order);
        return Response()->json([
            'data'          => [
                'order'  => $order,
            ],
            'message'       => 'success',
            'code'          => getMsgCode('success'),
        ]);
    }
    
    
    public function MenuCounts()
    {
        $driver = Auth::guard('api-driver')->user();
        
        $t_amount = 0;
        foreach($driver->DriverFianance()->where('collected_from_driver', '0')->get() as $e){
            $t_amount += $e->OrdersNetProfit();
        }
        return Response()->json([
                'data' => [
                    'processing_orders_count' => $driver->Order()->where('status', 'at_office' )->count(),
                    'not_collected_orders_count' => $driver->Order()->whereHas('Invoice', function($q){
                                            $q->where('driver_paied', '0');
                                        })
                                        ->where('status', 'delivered')
                                        ->where('collected', 0)->count(),
                    'finance_total_cost' => $t_amount,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    
}
