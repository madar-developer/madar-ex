<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateTransferRequest;
use App\Http\Requests\Admin\StoreTransferRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\TransferOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Transfer;
use App\Models\Company;
use App\Models\Invoice;
use Auth;
use Excel;
use App\Exports\GeneralExport;
use Carbon\Carbon;

class TransferController extends Controller
{
    use TransferOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:finance_show'    , ['except' => 'destroy']);
        // $this->middleware('Permission:transfer_show'    , ['only' => 'index', 'show']);
        // $this->middleware('Permission:transfer_add'     , ['only' => 'create', 'store']);
        // $this->middleware('Permission:transfer_edit'    , ['only' => 'edit', 'update']);
        // $this->middleware('Permission:transfer_delete'  , ['only' => 'destroy']);
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
                $transfers = Transfer::whereHas('BranchData', function($q) use( $branch_id ){
                    $q->where('admin_id', $branch_id);
                })->latest();
            } else {
                $transfers = Transfer::latest();

            }

        $search = array();
        if (Request()->has('company_id') && Request()->get('company_id') != '') {
            $company_id = Request()->get('company_id');
            $search['company_id'] = $company_id;
            $transfers = $transfers->where('company_id'  ,$company_id);
        }

        if ($request->has('date_from') && $request->get('date_from') != '') {
            $date_from = Carbon::parse($request->get('date_from') );
            $search['date_from'] = $request->get('date_from');
            $transfers = $transfers->whereDate('created_at', '>=', $date_from);
            if (!$request->has('date_to') || $request->get('date_to') == '') {
                $transfers = $transfers->whereDate('created_at', '=', $date_from);
            }
        }
        if ($request->has('date_to') && $request->get('date_to') != '') {
            $date_to = Carbon::parse($request->get('date_to') );
            $search['date_to'] = $request->get('date_to');
            $transfers = $transfers->whereDate('created_at', '<=', $date_to);
        }
        if (Request()->has('excel') && Request()->get('excel') != '') {
            $transfers = $transfers->get();
            return Excel::download(new GeneralExport('admin.reports.transfers-excel', $transfers), 'transfers-'.Carbon::now()->toDateString().'.xlsx');
        }
        $transfers = $transfers->paginate(40);

        $title = 'الحولات';
        return view('admin.transfers.index', compact('transfers', 'title', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.transfers.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransferRequest $request)
    {
        // return $request->all();
        $this->register($request);

        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transfer $transfere)
    {
        $title = 'عرض ';
        return view('admin.transfers.show', compact('transfere', 'title'));
    }
    public function Report(Transfer $transfere)
    {
        $title = 'عرض ';
        return view('admin.transfers.show', compact('transfere', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transfere = Transfer::find($id);
        $title = 'تعديل ';
        return view('admin.transfers.edit', compact('transfere', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $transfere = Transfer::find($id);
        $this->UpdateRecords($transfere, $request);

        return redirect('/dashboard/transfers')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $transfere = Transfer::find($id);
        $transfere->Invoice()->delete();
        if ($transfere->image) {
            @unlink(public_path('/cdn/'.$transfere->image));
        }
        $transfere->delete();
        return 'success';
    }


    public function showInvoices(Request $request){
        // return $request->all();
        $company_id = $request->get('company_id');
        // $date_from = $request->get('date_from');
        // $date_to = $request->get('date_to');


            if ($request->has('date_from')) {
                $date_from =Carbon::parse($request->get('date_from'));
            }
            $ids = array();
            if ($request->has('t_id')) {
                $transfere = Transfer::find($request->get('t_id'));
                $ids = $transfere->TransferInvoice()->pluck('invoice_id')->toArray();
            }
            if ($request->has('date_to')) {
                $date_to = Carbon::parse($request->get('date_to'));
            }
            $invoices=Invoice::whereHas('Order', function($q) use($company_id){
                $q->where('company_id', $company_id);
            })
                                ->whereDate('created_at', '>=', $date_from->todatestring())
                                ->whereDate('created_at', '<=', $date_to->todatestring())
                                ->get();


            return view('admin.transfers.show-transfer',compact('invoices', 'ids'));
    }
    public function transferInvoices(Request $request , $id ){
        // return $request->all();

        $transfer = Transfer::find($id);
        $invoices = $transfer->Invoice()->get();


            return view('admin.transfers.show-invoices',compact('invoices', 'transfer'));
    }
    public function recalculate(Transfer $transfer)
    {
        $invoices = $transfer->Invoice()->get();
        $transfer->update([
            'total_price' => $invoices->sum('total_price'),
            'company_price' => $invoices->sum('company_price'),
            'madar_price' => $invoices->sum('madar_price'),
        ]);
        return back()->with('success', 'data added successfully');
    }

}
