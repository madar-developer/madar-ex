<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateCompanyInvoiceRequest;
use App\Http\Requests\Admin\StoreCompanyInvoiceRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\InvoiceOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Invoice;
use App\Models\Order;
use Auth;

class CompanyInvoiceController extends Controller
{
    use InvoiceOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:company_invoice_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:company_invoice_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:company_invoice_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:company_invoice_delete'  , ['only' => 'destroy']);
    }
    public function index(Request $request)
    {
        $invoices =  Invoice::latest();
        $company_id = auth('company')->id();
        $invoices = $invoices->whereHas('Order', function($q)use($company_id){
            $q->where('company_id'  ,$company_id);
        });
        $search = array();
        
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
            $invoices = $invoices->whereHas('Order', function($q)use($date_from){
                $q->whereDate('created_at', '>=', $date_from);
            });
            if (!$request->has('date_to') || $request->get('date_to') == '') {
                $invoices = $invoices->whereHas('Order', function($q)use($date_from){
                    $q->whereDate('created_at', '=', $date_from);
                });
            }
        }
        if ($request->has('date_to') && $request->get('date_to') != '') {
            $date_to = Carbon::parse($request->get('date_to') );
            $search['date_to'] = $request->get('date_to');
            $invoices = $invoices->whereHas('Order', function($q)use($date_to){
                $q->whereDate('created_at', '<=', $date_to);
            });
        }
        $invoices = $invoices->paginate(40);
        $title = 'الفواتير';
        return view('company.invoices.index', compact('invoices', 'title', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة قسم';
        return view('company.company_invoices.add', compact('title'));
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
        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $title = 'عرض قسم';
        return view('company.company_invoices.show', compact('invoice', 'title'));
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
        $title = 'تعديل قسم';
        return view('company.company_invoices.edit', compact('invoice', 'title'));
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
      
        return redirect()->back()->with('success', 'data added successfully');
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
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
