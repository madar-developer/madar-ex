<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Http\Requests\Admin\StoreOrderRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\OrderOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Order;
use Auth;
use Excel;
use App\Exports\GeneralExport;
use App\Models\OrderStatus;
use Carbon\Carbon;

class OrderController extends Controller
{
    use OrderOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:order_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:order_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:order_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:order_delete'  , ['only' => 'destroy']);
    }
    public function index(Request $request)
    {
        // $orders = Order::latest();
      //////////////////// branch or admin
      if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
        //
        if (auth('admin')->user()->role == 'branch') {
            $branch_id = auth('admin')->id();
        } else {
            $branch_id = auth('admin')->user()->parent_id;
        }
        $orders = Order::whereHas('BranchData', function($q)use($branch_id){
            $q->where('admin_id', $branch_id);
        })->latest();
    } else {
        $orders = Order::latest();

    }
    ///////////////////////////
// $search=[];


        $search = array();
        if (Request()->has('serial') && Request()->get('serial') != '') {
            $serial = Request()->get('serial');
            $search['serial'] = $serial;
            $orders = $orders->where('serial'  ,$serial);
        }
        if (Request()->has('company_id') && Request()->get('company_id') != '') {
            $company_id = Request()->get('company_id');
            $search['company_id'] = $company_id;
            $orders = $orders->where('company_id'  ,$company_id);
        }
        if (Request()->has('driver_id') && Request()->get('driver_id') != '') {
            $driver_id = Request()->get('driver_id');
            $search['driver_id'] = $driver_id;
            $orders = $orders->where('driver_id'  ,$driver_id);
        }
        if ($request->has('serial_from') && $request->get('serial_from') != '') {
            $serial_from = (int)str_replace('mx-', '', $request->get('serial_from') );
            $search['serial_from'] = $request->get('serial_from');
            $orders = $orders->where('serial_no', '>=', $serial_from);
            if (!$request->has('serial_to') || $request->get('serial_to') == '') {
                $orders = $orders->where('serial_no', '=', $serial_from);
            }
        }
        if ($request->has('serial_to') && $request->get('serial_to') != '') {
            $serial_to = (int)str_replace('mx-', '', $request->get('serial_to') );
            $search['serial_to'] = $request->get('serial_to');
            $orders = $orders->where('serial_no', '<=', $serial_to);
        }
        if (Request()->has('refrence_no') && Request()->get('refrence_no') != '') {
            $refrence_no = Request()->get('refrence_no');
            $search['refrence_no'] = $refrence_no;
            $orders = $orders->where('refrence_no'     ,$refrence_no);
        }
        if (Request()->has('payment_method_id') && Request()->get('payment_method_id') != '') {
            $payment_method_id = Request()->get('payment_method_id');
            $search['payment_method_id'] = $payment_method_id;
            $orders = $orders->where('payment_method_id'     ,$payment_method_id);
        }
        if (Request()->has('refrence_no') && Request()->get('refrence_no') != '') {
            $refrence_no = Request()->get('refrence_no');
            $search['refrence_no'] = $refrence_no;
            $orders = $orders->where('refrence_no'     ,$refrence_no);
        }
        if (Request()->has('company_phone') && Request()->get('company_phone') != '') {
            $company_phone = Request()->get('company_phone');
            $search['company_phone'] = $company_phone;
            $orders = $orders->wherehas('Company', function($q) use ($company_phone){
                $q->where('phone'     , 'LIKE', '%'.$company_phone.'%');
            });
        }
        if (Request()->has('deliver_failed') && Request()->get('deliver_failed') != '') {
            $deliver_failed = Request()->get('deliver_failed');
            $search['deliver_failed'] = $deliver_failed;
            $orders = $orders->wherehas('OrderLog', function($q) use ($deliver_failed){
                $q->where('active', '1')->where('reason'     , $deliver_failed);
            });
        }
        if (Request()->has('recipent_name') && Request()->get('recipent_name') != '') {
            $recipent_name = Request()->get('recipent_name');
            $search['recipent_name'] = $recipent_name;
            $orders = $orders->where('recipent_name'     ,$recipent_name);
        }
        if (Request()->has('phone') && Request()->get('phone') != '') {
            $phone = Request()->get('phone');
            $search['phone'] = $phone;
            $orders = $orders->where('phone'     ,$phone);
        }
        if (Request()->has('status') && Request()->get('status') != '') {
            $status = Request()->get('status');
            $search['status'] = $status;
            $orders = $orders->where('status'     ,$status);
        }
        if ($request->has('date_from') && $request->get('date_from') != '') {
            $date_from = $request->get('date_from');
            $search['date_from'] = $date_from;
            $date_from = Carbon::parse($request->get('date_from'));
            $orders = $orders->whereDate('created_at', '>=', $date_from);
            if (!$request->has('date_to') || $request->get('date_to') == '') {
                $orders = $orders->whereDate('created_at', '=', $date_from);
            }
        }
        if ($request->has('date_to') && $request->get('date_to') != '') {
            $date_to = $request->get('date_to');
            $search['date_to'] = $date_to;
            $date_to = Carbon::parse($request->get('date_to'));
            $orders = $orders->whereDate('created_at', '<=', $date_to);
        }
        if (Request()->has('excel') && Request()->get('excel') != '') {
            $orders = $orders->get();
            return Excel::download(new GeneralExport('admin.reports.orders-excel', $orders), 'orders-'.Carbon::now()->toDateString().'.xlsx');
        }
        $orders = $orders->paginate(40);
        $title = 'الطلبات';

        return view('admin.orders.index', compact('orders', 'title' ,'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة طلب';
        return view('admin.orders.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $this->register($request);
        // return view('admin.orders.add');
        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $title = 'عرض طلب';
        return view('admin.orders.show', compact('order', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        if($order->status == "delivered" && auth('admin')->user()->email != 'hussein@madarex.sa'){
            return back()->with('error', 'لا يمكن التعديل');
        }
        $title = 'تعديل طلب';
        return view('admin.orders.edit', compact('order', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, $id)
    {

        $order = Order::find($id);
        $this->UpdateRecords($order, $request);
        $item = Order::find($id);
        $color = $item->OrderStatus->color;
        if ($request->has('update_row')) {
            $html = view('admin.orders.ajax.row', compact('item'))->render();
            $data = [
                'html' => $html,
                'color' => $color,
            ];
            return response()->json($data, 200);
        }

        return redirect()->back()->with('success', 'data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->OrderLog()->delete();
        $order->Invoice()->delete();
        $order->delete();
        return 'success';
    }
    public function DownloadExcelTemp()
    {
        return url('/madar-express-template.xlsx');
    }

    public function bill( $id){
        $order = Order::findOrfail($id);
        return view('admin.orders.new-print', compact('order'));

    }

    public function companyBill( $id){
        $order = Order::findOrfail($id);
        return view('company.orders.print', compact('order'));

    }

    public function UAll(Request $request){
        $order = Order::whereIn('id', $request->get('ids') )->update(['status' => $request->get('status')]);
        $orders = Order::whereIn('id', $request->get('ids') )->get();
        $status_data = OrderStatus::where('key', $request->get('status'))->first();
        $log_data = [
            'status' => $request->get('status'),
            'details' => $status_data->details
            // 'details' =>  trans('words.'.$request->get('status')) . ' , ' . $request->get('notes')
        ];
        foreach ($orders as $Order) {
            if ($request->has('status') && $request->get('status') != 'new' && $request->get('status') != $Order->status) {
                $Order->OrderLog()->create($log_data);
            }
        }
        return redirect()->back()->with('success', 'data updated successfully');

    }public function invoice($id)
    {
        $order = Order::find($id);
        $title = 'عرض طلب';
        return view('admin.orders.ajax.invoice', compact('order', 'title'))->render();
    }
    public function invoicePost($id)
    {
        $order = Order::find($id);
        $cost = Request()->get('cost');
        if ($order->City()->first() && $order->Driver()->first()) {
            $driver_cost = $order->Driver->DriverCityPrice()->where('city_id', $order->city_id)->first();
            if ($driver_cost) {
                $driver_cost = $driver_cost->delivery_cost;
            } else {
                $driver_cost = 0;
            }
        } else {

            $driver_cost = 0;
        }
        $madar_price = $cost;
        $total_price = 0;
        $company_price = -1 * $madar_price;
        $order->Invoice()->create([
            'total_price'=>$total_price ,
            'company_price'=>$company_price ,
            'madar_price' => $madar_price ,
            'driver_cost' => $driver_cost ,
            'active' => 0,
        ]);
        return redirect()->back()->with('success', 'data added successfully');
    }
}
