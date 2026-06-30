<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateCompanyOrderRequest;
use App\Http\Requests\Admin\StoreCompanyOrderRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\CompanyOrderOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Order;
use Auth;
use Excel;
use Carbon\Carbon;
use App\Exports\GeneralExport;


class CompanyOrderController extends Controller
{
    use CompanyOrderOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
    }
    public function returnOrders(Request $request)
    {
        return $this->index($request, true);
    }

    public function index(Request $request, bool $returnedOnly = false)
    {
        // $all = Order::latest()->get();
        $orders =  Order::where("company_id", '=', auth()->id())->latest();
        if ($returnedOnly) {
            $orders = $orders->where('is_returned', 1);
        }else{
            $orders = $orders->where('is_returned', 0);
        }
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
        if (Request()->has('excel') && Request()->get('excel') != '') {
            $orders = $orders->get();
            return Excel::download(new GeneralExport('admin.reports.orders-excel', $orders), 'orders-'.Carbon::now()->toDateString().'.xlsx');
        }
        $orders = $orders->paginate(40);
        $title = $returnedOnly ? 'الطلبات المعاده' : 'الطلبات';

        return view('company.orders.index', compact('orders', 'title', 'search', 'returnedOnly'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة طلب ';
        return view('company.orders.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyOrderRequest $request)
    {
        $this->register($request);
        return redirect('/company/company-orders')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $company_order)
    {
        $title = 'عرض طلب';
        return view('company.orders.show', compact('company_order', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company_order = Order::find($id);
        $title = 'تعديل طلب';
        return view('company.orders.edit', compact('company_order', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyOrderRequest $request, $id)
    {

        $company_order = Order::find($id);
        $this->UpdateRecords($company_order, $request);


        return redirect('/company/company-orders')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $company_order)
    {


        $company_order->OrderLog()->delete();
        $company_order->delete();
        return 'success';
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
    public function notiCount()
    {
        return auth('company')->user()->unreadnotifications->count();
    }
    public function bill( $id){
        $order = Order::findOrfail($id);
        return view('admin.orders.new-print', compact('order'));

    }

    public function orderPdf($id)
    {
        Order::where('company_id', auth('company')->id())->findOrFail($id);

        return app(PdfController::class)->orderPdf($id);
    }

    public function returnToMerchant(Order $order)
    {
        if ($order->company_id !== auth('company')->id()) {
            abort(403);
        }

        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'يمكن ارجاع الطلبات التي تم تسليمها فقط');
        }

        $company = $order->Company()->first();
        if (!$company) {
            return redirect()->back()->with('error', 'لا يمكن ارجاع الطلب بدون بيانات متجر');
        }

        $data = [
            'recipent_name' => $order->recipent_name,
            'phone' => $order->phone,
            'city_id' => $order->city_id ?? '',
            'district_id' => $order->district_id ?? '',
            'adress_details' => $order->adress_details,
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
            'is_returned' => 1,
        ];

        $returnOrder = $this->register(new Request($data));

        return redirect('/company/company-orders/'.$returnOrder->id.'/edit')->with('success', 'تم انشاء طلب ارجاع جديد للتاجر');
    }
}
