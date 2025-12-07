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
use Carbon\Carbon;

class CompanyTransferController extends Controller
{
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
                $transfers = Transfer::where('company_id', auth('company')->id() )->latest();
                
        
        $search = array();
        
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
        $transfers = $transfers->paginate(40);
            
        $title = 'الحولات';
        return view('company.transfers.index', compact('transfers', 'title', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransferRequest $request)
    {
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
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transfer $transfere)
    {
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
        
        $transfere = Transfer::find($id);
        $invoices = $transfere->Invoice()->get();
         

            return view('admin.transfers.show-invoices',compact('invoices'));
    }
    
}
