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
use App\Models\City;
use App\Models\CompanyAddress;
use App\Models\Driver;
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
        $this->middleware('Permission:order_show'    , ['only' => 'index', 'show', 'ordersRegionMap', 'returnOrders']);
        $this->middleware('Permission:order_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:order_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:order_delete'  , ['only' => 'destroy']);
    }
    public function returnOrders(Request $request)
    {
        return $this->index($request, true);
    }

    public function index(Request $request, bool $returnedOnly = false)
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
        if ($returnedOnly) {
            $orders = $orders->where('is_returned', 1);
        }else{
            $orders = $orders->where('is_returned', 0);
        }
        ///////////////////////////
        // $search=[];

        // search for orders
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
        if (Request()->has('repeated') && Request()->get('repeated') != '') {
            $repeated = Request()->get('repeated');
            $search['repeated'] = $repeated;
            if ($repeated == '1') {
                $orders = $orders->where('refrence_no', 'REGEXP', '-[0-9]+$');
            }
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
        if (Request()->has('returned_pdf_exported') && Request()->get('returned_pdf_exported') != '') {
            $returned_pdf_exported = Request()->get('returned_pdf_exported');
            $search['returned_pdf_exported'] = $returned_pdf_exported;
            if ($returned_pdf_exported === '1') {
                $orders = $orders->whereNotNull('returned_pdf_exported_at');
            } elseif ($returned_pdf_exported === '0') {
                $orders = $orders->whereNull('returned_pdf_exported_at');
            }
        }
        if (Request()->has('excel') && Request()->get('excel') != '') {
            $orders = $orders->get();
            return Excel::download(new GeneralExport('admin.reports.orders-excel', $orders), 'orders-'.Carbon::now()->toDateString().'.xlsx');
        }
        $orders = $orders->paginate(100);
        $title = $returnedOnly ? 'الطلبات المعاده' : 'الطلبات';

        return view('admin.orders.index', compact('orders', 'title', 'search', 'returnedOnly'));
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
        $order = $this->register($request);
        if ($request->input('save_action') === 'stay') {
            return redirect('/dashboard/orders/'.$order->id.'/edit')->with('success', 'تم الحفظ بنجاح');
        }

        return redirect('/dashboard/orders')->with('success', 'تم الحفظ بنجاح');
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
        $order->load(['Company.City', 'City', 'Driver']);
        $orderLogs = $order->OrderLog()->orderBy('id', 'asc')->get();
        $lastLog = $order->OrderLog()->orderByDesc('id')->first();
        $statusNameMap = OrderStatus::whereIn('key', ['new', 'not_received', 'init', 'at_madar', 'at_office', 'delivered', 'returned'])
            ->get()
            ->keyBy('key');
        $nameFor = function (string $key) use ($statusNameMap): string {
            $status = $statusNameMap->get($key);
            if (!$status) {
                return $key;
            }

            return (string) ($status->getTranslation('name', 'ar') ?: $key);
        };
        $stepLabels = [
            $nameFor('new'),
            // $nameFor('not_received'),
            $nameFor('init'),
            $nameFor('at_madar'),
            $nameFor('at_office'),
            // $nameFor('at_office'),
            $nameFor('delivered'),
        ];
        $returnedStepLabel = $nameFor('returned');
        $logDriverIds = $orderLogs->where('added_by_type', 'driver')->pluck('added_by_id')->filter()->unique()->values();
        $driversById = $logDriverIds->isEmpty()
            ? collect()
            : Driver::whereIn('id', $logDriverIds)->get()->keyBy('id');

        return view('admin.orders.show', compact('order', 'title', 'orderLogs', 'lastLog', 'driversById', 'stepLabels', 'returnedStepLabel'));
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

        if ($request->input('save_action') === 'stay') {
            return redirect()->back()->with('success', 'تم الحفظ بنجاح');
        }

        return redirect('/dashboard/orders')->with('success', 'تم الحفظ بنجاح');
    }

    public function returnToMerchant(Order $order)
    {
        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'يمكن ارجاع الطلبات التي تم تسليمها فقط');
        }

        $company = $order->Company()->first();
        if (!$company) {
            return redirect()->back()->with('error', 'لا يمكن ارجاع الطلب بدون بيانات متجر');
        }

        $companyAddress = CompanyAddress::where('company_id', $company->id)
            ->orderByDesc('main')
            ->latest('id')
            ->first();

        $data = [
            'recipent_name' => $order->recipent_name,
            'phone' => $order->phone,
            'city_id' => $order->city_id ?? '',
            'district_id' => $order->district_id ?? '',
            'adress_details' =>  $order->adress_details,
            'latitude' => $order->latitude ?? null,
            'longitude' => $order->longitude ?? null,
            'notes' => trim('مرتجع من الطلب '.$order->serial.' - '.$order->recipent_name.' - '.$order->phone.' - '.$order->adress_details.($order->notes ? ' | '.$order->notes : '')),
            'company_id' => $order->company_id,
            'driver_id' => null,
            'status' => 'new',
            'refrence_no' => $order->refrence_no,
            'order_type' => $order->order_type,
            'packages_number' => $order->packages_number,
            'return_packages' => $order->return_packages,
            'price' => $order->price,
            'include_delivery_cost' => $order->include_delivery_cost,
            'weight' => $order->weight,
            'description' => $order->description,
            'payment_method_id' => $order->payment_method_id,
            'can_open' => $order->can_open,
            'cash_type' => $order->cash_type,
            'is_returned' => 1
        ];

        $returnOrder = $this->register(new Request($data));

        return redirect('/dashboard/orders/'.$returnOrder->id.'/edit')->with('success', 'تم انشاء طلب ارجاع جديد للتاجر');
    }
    /*
    public function returnToMerchant(Order $order)
    {
        try {
            $returnOrder = $this->createReturnOrderFromSource($order);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect('/dashboard/orders/'.$returnOrder->id.'/edit')->with('success', 'تم انشاء طلب ارجاع جديد للتاجر');
    }

    */
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
        if($order->is_returned == 1){
            return view('admin.orders.return-print', compact('order'));
        }
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

    }

    /**
     * Map + summary of orders grouped by city (region), scoped like the orders index.
     */
    public function ordersRegionMap( Request $request)
    {
        $search = [];
        $base = $this->ordersScopeQuery($request);

        $countRows = (clone $base)
            ->whereNotNull('city_id')
            ->selectRaw('city_id, COUNT(*) as c')
            ->groupBy('city_id')
            ->get();

        $positionRows = (clone $base)
            ->whereNotNull('city_id')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0)
            ->selectRaw('city_id, AVG(latitude) as avg_lat, AVG(longitude) as avg_lng')
            ->groupBy('city_id')
            ->get()
            ->keyBy('city_id');

        $nullCityCount = (clone $base)->whereNull('city_id')->count();

        $cityIds = $countRows->pluck('city_id')->filter()->unique()->values();
        $cities = City::whereIn('id', $cityIds)->get()->keyBy('id');

        $regions = [];
        foreach ($countRows as $row) {
            $city = $cities->get($row->city_id);
            $pos = $positionRows->get($row->city_id);
            $regions[] = [
                'city_id' => (int) $row->city_id,
                'name' => $city ? (string) $city->name : '—',
                'count' => (int) $row->c,
                'lat' => $pos ? round((float) $pos->avg_lat, 6) : null,
                'lng' => $pos ? round((float) $pos->avg_lng, 6) : null,
            ];
        }

        usort($regions, static function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        $totalInRegions = array_sum(array_column($regions, 'count'));
        $title = 'خريطة الطلبات حسب المنطقة';

        return view('admin.orders.region-map', compact('regions', 'nullCityCount', 'title', 'totalInRegions', 'search'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function ordersScopeQuery( Request $request)
    {
        if (in_array(auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0')) {
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }

            $orders = Order::whereHas('BranchData', function ($q) use ($branch_id) {
                $q->where('admin_id', $branch_id);
            });
        }else{
            $orders = Order::query();

        }
        // search for orders
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
        if (Request()->has('returned_pdf_exported') && Request()->get('returned_pdf_exported') != '') {
            $returned_pdf_exported = Request()->get('returned_pdf_exported');
            if ($returned_pdf_exported === '1') {
                $orders = $orders->whereNotNull('returned_pdf_exported_at');
            } elseif ($returned_pdf_exported === '0') {
                $orders = $orders->whereNull('returned_pdf_exported_at');
            }
        }
        return $orders;
    }

    public function invoice($id)
    {
        $order = Order::find($id);
        $title = 'عرض طلب';
        return view('admin.orders.ajax.invoice', compact('order', 'title'))->render();
    }
    public function invoicePost($id)
    {
        $order = Order::find($id);
        $cost = Request()->get('cost');
        $driver_cost = \App\Support\DriverFinance::driverCommission($order);
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
