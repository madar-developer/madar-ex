<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon; 
use App\Models\Order;
class StatisticsController extends Controller
{
    public function OrdersCompany(Request $request)
    {
        $search = $request->all();
        $start_date = $request->get('start_date');
        $start_date = Carbon::parse($start_date);
        $end_date = $request->get('end_date');
        $end_date = Carbon::parse($end_date);
        $company_id = $request->get('company_id');
        $orders = null;
        if ($request->has('company_id')) {
            $orders = Order::where('company_id', $company_id)
                        ->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date)
                        ->get();
        }
        $title = "";
        return view('admin.statistics.orders-company', compact('title', 'search', 'orders'));
    }
}
