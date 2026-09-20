<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\SallaToken;
use App\Models\User;
use App\Models\Driver;
use App\Models\City;
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
        $companyId = auth('company')->id();
        $orders = [];//Order::latest();
        $search = array();
        $order_statuses_chart = [];
        $order_statuses_colors = [];
        $sallaToken = SallaToken::where('company_id', $companyId)
            ->latest('id')
            ->first();
        foreach (OrderStatus::get() as $item)
        {
            $i = new \stdClass;
            $i->label = trans('words.'.$item->key);
            $i->value = Order::where('status','=',$item->key)->where('company_id', $companyId )->count();
            $order_statuses_chart[] = $i;
            $order_statuses_colors[] = $item->color ?? '#dddddd';
        }
        $order_statuses_chart = json_encode($order_statuses_chart);
        $order_statuses_colors = json_encode($order_statuses_colors);

        $today = Carbon::today()->toDateString();
        $companyOrderToday = function ($q) use ($today, $companyId) {
            $q->where('company_id', $companyId)
                ->whereDate('updated_at', $today);
        };

        $activeDriversStats = Driver::query()
            ->whereHas('Order', $companyOrderToday)
            ->withCount([
                'Order as orders_count' => $companyOrderToday,
                'Order as processing_count' => function ($q) use ($today, $companyId) {
                    $q->where('company_id', $companyId)
                        ->whereDate('updated_at', $today)
                        ->where('status', 'at_madar');
                },
                'Order as delivering_count' => function ($q) use ($today, $companyId) {
                    $q->where('company_id', $companyId)
                        ->whereDate('updated_at', $today)
                        ->where('status', 'at_office');
                },
                'Order as reschedule_count' => function ($q) use ($today, $companyId) {
                    $q->where('company_id', $companyId)
                        ->whereDate('updated_at', $today)
                        ->where('status', 'reschedule');
                },
                'Order as delivered_count' => function ($q) use ($today, $companyId) {
                    $q->where('company_id', $companyId)
                        ->whereDate('updated_at', $today)
                        ->where('status', 'delivered');
                },
                'Order as failed_count' => function ($q) use ($today, $companyId) {
                    $q->where('company_id', $companyId)
                        ->whereDate('updated_at', $today)
                        ->where('status', 'deliver_failed');
                },
            ])
            ->orderByDesc('orders_count')
            ->get(['id', 'first_name', 'last_name', 'phone']);

        $citiesStatsFrom = Carbon::now()->subDays(30);
        $companyOrderPeriod = function ($q) use ($citiesStatsFrom, $companyId) {
            $q->where('company_id', $companyId)
                ->where('created_at', '>=', $citiesStatsFrom);
        };

        $citiesStats = City::query()
            ->whereHas('Order', $companyOrderPeriod)
            ->withCount([
                'Order as orders_count' => function ($q) use ($citiesStatsFrom, $companyId) {
                    $q->where('company_id', $companyId)
                        ->where('created_at', '>=', $citiesStatsFrom)
                        ->where('status', '<>', 'returned')->where('collected', '<>', 1);
                },
                'Order as processing_count' => function ($q) use ($citiesStatsFrom, $companyId) {
                    $q->where('company_id', $companyId)
                        ->where('created_at', '>=', $citiesStatsFrom)
                        ->where('status', 'at_madar');
                },
                'Order as delivering_count' => function ($q) use ($citiesStatsFrom, $companyId) {
                    $q->where('company_id', $companyId)
                        ->where('created_at', '>=', $citiesStatsFrom)
                        ->where('status', 'at_office');
                },
                'Order as reschedule_count' => function ($q) use ($citiesStatsFrom, $companyId) {
                    $q->where('company_id', $companyId)
                        ->where('created_at', '>=', $citiesStatsFrom)
                        ->where('status', 'reschedule');
                },
                'Order as delivered_count' => function ($q) use ($citiesStatsFrom, $companyId) {
                    $q->where('company_id', $companyId)
                        ->where('created_at', '>=', $citiesStatsFrom)
                        ->where('status', 'delivered');
                },
                'Order as failed_count' => function ($q) use ($citiesStatsFrom, $companyId) {
                    $q->where('company_id', $companyId)
                        ->where('created_at', '>=', $citiesStatsFrom)
                        ->where('status', 'deliver_failed');
                },
            ])
            ->orderByDesc('orders_count')
            ->get(['id', 'name']);

    	$title = "الرئيسية";
        // $orders = $orders->paginate(10);
    	return view('company.main', compact(
            'search',
            'users_chart',
            'orders_chart',
            'orders',
            'title',
            'order_statuses_chart',
            'order_statuses_colors',
            'sallaToken',
            'activeDriversStats',
            'citiesStats'
        ));
    }
}
