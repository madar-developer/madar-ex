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
    public function index(Request $request)
    {
        $all = Order::latest()->get();
        $orders =  Order::where("company_id", '=', auth()->id())->latest();
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
        $title = 'الطلبات';

        return view('company.orders.index', compact('orders', 'title' ,'search'));
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
}
