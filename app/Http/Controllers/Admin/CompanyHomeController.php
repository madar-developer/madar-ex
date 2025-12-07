<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Auth;

class CompanyHomeController extends Controller
{
    public function index()
    {
        $users_chart = [];/*User::whereDate('created_at', '>=', Carbon::now()->subMonths(6) )
                            ->whereDate('created_at', '<=', Carbon::now())
                            ->select( \DB::raw('COUNT(*) as a, DATE_FORMAT(created_at, "%Y-%m") as y'))
                            // ->groupBy('user_id')
                            ->groupBy('y')
                            ->get();*/
        $orders_chart = [];/*Transaction::whereDate('created_at', '>=', Carbon::now()->subMonths(6) )
                            ->whereDate('created_at', '<=', Carbon::now())
                            ->select( \DB::raw('SUM(`amount`) as a, DATE_FORMAT(created_at, "%Y-%m") as y'))
                            // ->groupBy('user_id')
                            ->groupBy('y')
                            ->get()->each(function($p){
                                $x = Carbon::parse($p->y)->subYears(1)->format('Y-m');
                                $x = Transaction::whereDate('created_at', $x)->sum('amount');
                                $p->b = $x;
                            });*/

    	$now = Carbon::now();
        $orders = [];//Order::latest();
        $search = array();
        $order_statuses_chart = [];
        $order_statuses_colors = [];
        foreach (OrderStatus::get() as $item)
        {
            $i = new \stdClass;
            $i->label = trans('words.'.$item->key);
            $i->value = Order::where('status','=',$item->key)->where('company_id', auth('company')->id() )->count();
            $order_statuses_chart[] = $i;
            $order_statuses_colors[] = $item->color ?? '#dddddd';
        }
        $order_statuses_chart = json_encode($order_statuses_chart);
        $order_statuses_colors = json_encode($order_statuses_colors);
    	$title = "الرئيسية";
        // $orders = $orders->paginate(10);
    	return view('company.main', compact('search', 'users_chart', 'orders_chart', 'orders', 'title', 'order_statuses_chart', 'order_statuses_colors'));
    }
}
