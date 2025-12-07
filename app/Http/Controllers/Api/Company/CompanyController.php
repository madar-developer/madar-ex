<?php

namespace App\Http\Controllers\Api\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\Invoice;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
      $company = Auth::guard('api-company')->user();
      $orders_not_recieved = $company->Order()->where('status', '<>', 'completed')->sum('price');
      $money_waiting_for_transfer = $company->Transfer()->where('active', 0)->sum('company_price');
      $money_transfered = $company->Transfer()->where('active', 1)->sum('company_price');
      $delivery_cost = $company->Transfer()->where('active', 0)->sum('madar_price');
      $delivery_cost_collected = $company->Transfer()->where('active', 1)->sum('madar_price');
      $transfers = $company->Transfer()->latest()->where('active', 1)->select('created_at', 'total_price')->limit(2)->get();

      $invoices = Invoice::whereHas('Order', function($q)use($company){
                                $q->where('company_id', $company->id);
                            })
                            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(madar_price) as delivery_cost'), DB::raw('SUM(total_price) as total'))
                            ->groupBy('date')
                            ->get();
        $total_orders_count = $company->Order()->count();
        $total_orders_wait_for_shipping = $company->Order()->whereIn('status', ['new', 'not_received', ''])->count();
        $total_orders_delivered = $company->Order()->whereIn('status', ['delivered'])->count();
        $total_orders_in_progress = $company->Order()->whereIn('status', ['at_madar', 'at_office'])->count();
      return Response()->json([
                'data' => [
                    'orders_not_recieved' => (int)$orders_not_recieved,
                    'money_waiting_for_transfer' => (int)$money_waiting_for_transfer,
                    'money_transfered' => (int)$money_transfered,
                    'delivery_cost' => (int)$delivery_cost,
                    'delivery_cost_collected' => (int)$delivery_cost_collected,
                    'invoices' => $invoices,
                    'transfers' => $transfers,
                    'total_orders_count' => $total_orders_count,
                    'total_orders_wait_for_shipping' => $total_orders_wait_for_shipping,
                    'total_orders_delivered' => $total_orders_delivered,
                    'total_orders_in_progress' => $total_orders_in_progress,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);

    }
    public function invoices()
    {
      $company = Auth::guard('api-company')->user();
      $orders = $company->Order()->whereHas('Invoice')->latest()->pluck('id');
      $invoices = Invoice::whereIn('order_id', $orders)->latest()->paginate(30);
      return Response()->json([
                'data' => [
                    'invoices' => $invoices,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);

    }
    public function invoicesByDay()
    {
        $date = Request()->get('date');
      $company = Auth::guard('api-company')->user();
      $invoices = Invoice::whereHas('Order', function($q)use($company, $date){
                                if ($date) {
                                    $q->where('company_id', $company->id)->whereDate('created_at', $date);
                                } else {
                                    $q->where('company_id', $company->id);
                                }
                            })
                            ->with('Order')
                            ->latest()
                            ->get();
      return Response()->json([
                'data' => [
                    'invoices' => $invoices,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);

    }
    public function transfers()
    {
      $company = Auth::guard('api-company')->user();
      $transfers = $company->Transfer()->latest()->paginate(30);
      return Response()->json([
                'data' => [
                    'transfers' => $transfers,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);

    }
    public function transferInvoices($id)
    {
      $company = Auth::guard('api-company')->user();
      $transfer = $company->Transfer()->findOrfail($id);
      $invoices = $transfer->Invoice()->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(madar_price) as delivery_cost'), DB::raw('SUM(total_price) as total'))
                            ->groupBy('date')
                            ->get();
      return Response()->json([
                'data' => [
                    'transfer' => $transfer,
                    'invoices' => $invoices,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Auth::guard('api-company')->user();
        $order = $company->Order()->findOrfail($id);
        $logs = $order->OrderLog()->get();
        return Response()->json([
                'data' => [
                    'order' => $order,
                    'logs' => $logs,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
