<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateInvoiceRequest;
use App\Http\Requests\Admin\StoreInvoiceRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\InvoiceOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Company;
use App\Models\Transfer;
use Auth;
use Excel;
use App\Exports\GeneralExport;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    use InvoiceOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:finance_show'    , ['except' => 'destroy']);
        // $this->middleware('Permission:invoice_show'    , ['only' => 'index', 'show']);
        // $this->middleware('Permission:invoice_add'     , ['only' => 'create', 'store']);
        // $this->middleware('Permission:invoice_edit'    , ['only' => 'edit', 'update']);
        // $this->middleware('Permission:invoice_delete'  , ['only' => 'destroy']);
    }
    public function index(Request $request)
    {
        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            //
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
                $invoices = Invoice::whereHas('BranchData', function($q) use( $branch_id ){
                    $q->where('admin_id', $branch_id);
                })->latest();
            } else {
                $invoices = Invoice::latest();

            }
        $companies = Company::where('active', '1')->latest();
        $search = array();

        if (Request()->has('company_id') && Request()->get('company_id') != '') {
            $company_id = Request()->get('company_id');
            $search['company_id'] = $company_id;
            $invoices = $invoices->whereHas('Order', function($q)use($company_id){
                $q->where('company_id'  ,$company_id);
            });
        }
        if (Request()->has('refrence_no') && Request()->get('refrence_no') != '') {
            $refrence_no = Request()->get('refrence_no');
            $search['refrence_no'] = $refrence_no;
            $invoices = $invoices->whereHas('Order', function($q)use($refrence_no){
                $q->where('refrence_no'  ,$refrence_no);
            });
        }
        if ($request->has('serial_from') && $request->get('serial_from') != '') {
            $serial_from = (int)str_replace('mx-', '', $request->get('serial_from') );
            $search['serial_from'] = $request->get('serial_from');
            $invoices = $invoices->whereHas('Order', function($q)use($serial_from){
                $q->where('serial_no', '>=', $serial_from);
            });
            if (!$request->has('serial_to') || $request->get('serial_to') == '') {
                $invoices = $invoices->whereHas('Order', function($q)use($serial_from){
                    $q->where('serial_no', '=', $serial_from);
                });
            }
        }
        if ($request->has('serial_to') && $request->get('serial_to') != '') {
            $serial_to = (int)str_replace('mx-', '', $request->get('serial_to') );
            $search['serial_to'] = $request->get('serial_to');
            $invoices = $invoices->whereHas('Order', function($q)use($serial_to){
                $q->where('serial_no', '<=', $serial_to);
            });
        }
        if ($request->has('date_from') && $request->get('date_from') != '') {
            $date_from = Carbon::parse($request->get('date_from') );
            $search['date_from'] = $request->get('date_from');
            $invoices = $invoices->whereDate('created_at', '>=', $date_from);
            if (!$request->has('date_to') || $request->get('date_to') == '') {
                $invoices = $invoices->whereDate('created_at', '=', $date_from);
            }
        }
        if ($request->has('date_to') && $request->get('date_to') != '') {
            $date_to = Carbon::parse($request->get('date_to') );
            $search['date_to'] = $request->get('date_to');
            $invoices = $invoices->whereDate('created_at', '<=', $date_to);
        }
        $companiess = $companies;
        $companies = $companies->pluck('name', 'id')->toArray();
        if (sizeof($search) == 0) {
            $company_id = $companiess->first()->id;
            $invoices = $invoices->whereHas('Order', function($q)use($company_id){
                $q->where('company_id'  ,$company_id);
            });
        }

        if (Request()->has('excel') && Request()->get('excel') != '') {
            $invoices = $invoices->get();
            return Excel::download(new GeneralExport('admin.reports.invoices-excel', $invoices), 'invoices-'.Carbon::now()->toDateString().'.xlsx');
        }
        $invoices = $invoices->paginate(40);
        $title = 'الفواتير';
        return view('admin.invoices.index', compact('invoices', 'title', 'search', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.invoices.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/invoices')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $title = 'عرض ';
        $order = $invoice->Order()->first();
        if (!$order) {
            abort(404);
        }
        $show = 1;
        return view('admin.orders.new-print', compact('order', 'title', 'show'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::find($id);
        $title = 'تعديل ';
        return view('admin.invoices.edit', compact('invoice', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoiceRequest $request, $id)
    {

        $invoice = Invoice::find($id);
        $this->UpdateRecords($invoice, $request);

        return redirect('/dashboard/invoices')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {

        $invoice->delete();
        return 'success';
    }
    public function Transfer(Request $request)
    {
        $invoices = Invoice::whereIn('id', $request->get('ids') )->where('active', '0');
        if ($request->has('date_from') && $request->get('date_from') != '') {
            $date_from = Carbon::parse($request->get('date_from') );
        } else {
            $date_from = null;
        }
        if ($request->has('date_to') && $request->get('date_to') != '') {
            $date_to = Carbon::parse($request->get('date_to') );
        } else {
            $date_to = null;
        }
        if (Invoice::whereIn('id', $request->get('ids') )->where('active', '0')->count() > 0) {
            # code...
            $transfer = Transfer::create([
                'total_price' => $invoices->sum('total_price'),
                'company_price' => $invoices->sum('company_price'),
            'madar_price' => $invoices->sum('madar_price'),
            'active' => '0',
            'company_id' => $request->get('company_id'),
            'date_from' => $date_from,
            'date_to' => $date_to,
            ]);
            $invoices->update(['active' => '1', 'transfer_id' => $transfer->id]);
        }
            return 'success';
    }
}
