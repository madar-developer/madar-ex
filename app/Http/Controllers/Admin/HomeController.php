<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Invoice;
use App\Models\Company;
use Carbon\Carbon;
use Auth;
use App\Models\Files;

class HomeController extends Controller
{
    public function index()
    {
        $companies_chart = Company::whereDate('created_at', '>=', Carbon::now()->subMonths(6) )
                            ->whereDate('created_at', '<=', Carbon::now())
                            ->select( \DB::raw('COUNT(*) as a, DATE_FORMAT(created_at, "%Y-%m") as y'))
                            // ->groupBy('user_id')
                            ->groupBy('y')
                            ->get();
        $orders_chart = Order::whereDate('receive_date', '>=', Carbon::now()->subMonths(6) )
                            ->whereDate('receive_date', '<=', Carbon::now())
                            ->select( \DB::raw('COUNT(*) as a, DATE_FORMAT(receive_date, "%Y-%m") as y'))
                            // ->groupBy('user_id')
                            ->groupBy('y')
                            ->get();
        $payments_chart = Invoice::whereDate('created_at', '>=', Carbon::now()->subMonths(6) )
                            ->whereDate('created_at', '<=', Carbon::now())
                            ->select( \DB::raw('SUM(`madar_price`) as a, DATE_FORMAT(created_at, "%Y-%m") as y'))
                            // ->groupBy('user_id')
                            ->groupBy('y')
                            ->get()->each(function($p){
                                $x = Carbon::parse($p->y)->subYears(1)->format('Y-m');
                                $x = Invoice::whereDate('created_at', $x)->sum('madar_price');
                                $p->b = $x;
                            });
        $orderse = Order::where('status', 'at_office')->get();
        $o_list = [];
        foreach ($orderse as $i) {
            $o_list[] = ['Order #'.$i->id, $i->latitude, $i->longitude];
        }
        $o_list = json_encode($o_list);
        $order_statuses_chart = [];
        $order_statuses_colors = [];
        foreach (OrderStatus::get() as $item)
        {
            $i = new \stdClass;
            $i->label = trans('words.'.$item->key);
            $i->value = \DB::table('orders')->where('status','=',$item->key)->count();
            $order_statuses_chart[] = $i;
            $order_statuses_colors[] = $item->color ?? '#dddddd';
        }
        $order_statuses_chart = json_encode($order_statuses_chart);
        $order_statuses_colors = json_encode($order_statuses_colors);
    	$now = Carbon::now();
        $orders = Order::whereIn('status', ['at_office'])->latest();
    	$search = array();

        $orders = $orders->paginate(20);
        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            //
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
                $orders = Order::whereHas('BranchData', function($q) use( $branch_id ){
                    $q->where('admin_id', $branch_id);
                })->latest();
            return view('admin.branch_main', compact('search', 'companies_chart', 'orders_chart', 'orders', 'payments_chart', 'order_statuses_chart', 'order_statuses_colors'));
        }
        // return "1";
    	return view('admin.main', compact('search', 'o_list', 'companies_chart', 'orders_chart', 'orders', 'payments_chart', 'order_statuses_chart', 'order_statuses_colors'));
    }
    public function shoeInvoice(){

        return view('admin.invoices.table');

    }
    public function FDestroy($id){
        $item = Files::find($id);
        $item->delete();
        return 'success';
    }

}
