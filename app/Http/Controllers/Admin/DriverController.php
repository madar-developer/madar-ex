<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateDriverRequest;
use App\Http\Requests\Admin\StoreDriverRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\DriverOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Driver;
use App\Models\Order;
use Auth;
use Excel;
use App\Exports\GeneralExport;
use App\Models\DriverFianance;
use App\Models\Invoice;
use Carbon\Carbon;

class DriverController extends Controller
{
    use DriverOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:driver_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:driver_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:driver_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:driver_delete'  , ['only' => 'destroy']);
    }
    public function index(Request $request)
    {
        $title = 'السائقين ';
        ////////////////// branch or admin
        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            //
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
            $drivers = Driver::whereHas('BranchData', function($q)use($branch_id){
                $q->where('admin_id', $branch_id);
            })->latest();
        } else {
            $drivers = Driver::latest();

        }
        $search = array();
        if (Request()->has('created_at') && Request()->get('created_at') != '') {
            $created_at = Request()->get('created_at');
            $search['created_at'] = $created_at;
            $drivers = $drivers->whereDate('created_at'     ,$created_at);
        }
        if (Request()->has('driver') && Request()->get('driver') != '') {
            $driver = Request()->get('driver');
            $search['driver'] = $driver;
            $drivers = $drivers->where('phone'     , 'LIKE', '%'.$driver.'%')->orWhere('first_name'     , 'LIKE', '%'.$driver.'%')->orWhere('email'     , 'LIKE', '%'.$driver.'%');
        }
        if (Request()->has('excel') && Request()->get('excel') != '') {
            $drivers = $drivers->get();
            return Excel::download(new GeneralExport('admin.reports.drivers-excel', $drivers), 'drivers-'.Carbon::now()->toDateString().'.xlsx');
        }
        $drivers = $drivers->paginate(40);
        return view('admin.drivers.index', compact('drivers','title', 'search'));
    }
    public function charts(Request $request)
    {
        $title = 'السائقين ';
        ////////////////// branch or admin
        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            //
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
            $drivers = Driver::whereHas('BranchData', function($q)use($branch_id){
                $q->where('admin_id', $branch_id);
            })->latest();
        } else {
            $drivers = Driver::latest();

        }
        $search = array();
        if (Request()->has('created_at') && Request()->get('created_at') != '') {
            $created_at = Request()->get('created_at');
            $search['created_at'] = $created_at;
            $drivers = $drivers->whereDate('created_at'     ,$created_at);
        }
        if (Request()->has('driver') && Request()->get('driver') != '') {
            $driver = Request()->get('driver');
            $search['driver'] = $driver;
            $drivers = $drivers->where('phone'     , 'LIKE', '%'.$driver.'%')->orWhere('first_name'     , 'LIKE', '%'.$driver.'%')->orWhere('email'     , 'LIKE', '%'.$driver.'%');
        }
        if (Request()->has('excel') && Request()->get('excel') != '') {
            $drivers = $drivers->get();
            return Excel::download(new GeneralExport('admin.reports.drivers-excel', $drivers), 'drivers-'.Carbon::now()->toDateString().'.xlsx');
        }
        $drivers = $drivers->get();
        
         $driverIds = $drivers->pluck('id');

            // Optional date window for orders (comment out if not needed)
            $from = $request->input('orders_from') ?: Carbon::today()->toDateString();
            $to   = $request->input('orders_to')   ?: Carbon::today()->toDateString();
            $search['orders_from'] = $from;
            $search['orders_to'] = $to;
        
            $ordersJoin = \DB::table('drivers as d')
                ->leftJoin('orders as o', 'o.driver_id', '=', 'd.id')
                ->whereIn('d.id', $driverIds);
        
            if ($from) {
                $ordersJoin->whereDate('o.receive_date', '>=', $from);
            }
            if ($to) {
                $ordersJoin->whereDate('o.receive_date', '<=', $to);
            }
        
            // total = count of all orders; delivered = count where status = 'delivered'
            $chartRows = $ordersJoin
                ->groupBy('d.id', 'd.first_name', 'd.last_name')
                ->selectRaw('d.id, COALESCE(d.first_name, "") as first_name, COALESCE(d.last_name, "") as last_name')
                ->selectRaw('COUNT(o.id) as total')
                ->selectRaw('SUM(CASE WHEN o.status = "delivered" THEN 1 ELSE 0 END) as delivered')
                ->get();
        
            // Shape data for Morris: [{ y: "Driver Name", total: 12, delivered: 9 }, ...]
            $rows_chart = $chartRows->map(function($r) {
                $name = trim($r->first_name.' '.$r->last_name);
                if ($name === '') $name = 'بدون اسم';
                return [
                    'y'         => $name,
                    'total'     => (int) $r->total,
                    'delivered' => (int) $r->delivered,
                ];
            });
        // $rows_chart = [];
        return view('admin.drivers.charts', compact('drivers','title', 'search', 'rows_chart'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.drivers.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDriverRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/drivers')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        ini_set('memory_limit', -1);
        $title = 'عرض ';
        // $months_chart = Order::where('driver_id', $driver->id)->where('status', 'delivered')->whereDate('created_at', '>=', Carbon::now()->subMonths(6) )
        //                     ->whereDate('created_at', '<=', Carbon::now())
        //                     ->select( \DB::raw('COUNT(*) as a, DATE_FORMAT(created_at, "%Y-%m") as y'))
        //                     // ->groupBy('user_id')
        //                     ->groupBy('y')
        //                     ->get();
        // $days_chart = Order::where('driver_id', $driver->id)->where('status', 'delivered')->whereDate('created_at', '>=', Carbon::now()->subDays(15) )
        //                     ->whereDate('created_at', '<=', Carbon::now())
        //                     ->select( \DB::raw('COUNT(*) as a, DATE_FORMAT(created_at, "%Y-%m-%d") as y'))
        //                     // ->groupBy('user_id')
        //                     ->groupBy('y')
        //                     ->get();
        $months_chart = Order::where('driver_id', $driver->id)->where('status', 'delivered')->whereDate('delivery_date', '>=', Carbon::now()->subMonths(6) )
                            ->whereDate('delivery_date', '<=', Carbon::now())
                            ->select( \DB::raw('COUNT(*) as a, DATE_FORMAT(delivery_date, "%Y-%m") as y'))
                            // ->groupBy('user_id')
                            ->groupBy('y')
                            ->get();
        $days_chart = Order::where('driver_id', $driver->id)->where('status', 'delivered')->whereDate('delivery_date', '>=', Carbon::now()->subDays(15) )
                            ->whereDate('delivery_date', '<=', Carbon::now())
                            ->select( \DB::raw('COUNT(*) as a, DATE_FORMAT(delivery_date, "%Y-%m-%d") as y'))
                            // ->groupBy('user_id')
                            ->groupBy('y')
                            ->get();
        //     return view('admin.drivers.show', compact('driver', 'title', 'days_chart', 'months_chart'));
        // }
        // $orders_not_col = $driver->Order()->where('status', 'delivered')->where('collected', 0)->paginate(40);
        // $orders_col = $driver->Order()->where('status', 'delivered')->where('collected', 1)->paginate(40);
        $orders_not_col = $driver->Order()->whereNotIn('status', ['deliver_failed', 'reschedule'])/*->where('status', 'delivered')*/->where('collected','<>', 1)->with(['Company','PaymentMethod','City'])->paginate(20);
        $orders_col = $driver->Order()->where('status', 'delivered')->where('collected', 1)->with(['Company','PaymentMethod','City'])->paginate(20);
        $orders_not_delivered = $driver->Order()->whereNotIn('status', ['delivered', 'at_madar', 'returned'])->where('collected', 1)->with(['Company','PaymentMethod','City'])->paginate(20);
        $orders_drivers_not_get_paid = $driver->Order()->whereHas('Invoice', function($q){
                                            $q->where('driver_paied', '0');
                                        })
                                        ->where('status', 'delivered')
                                        ->where('collected', 0)
                                        ->with(['Company','PaymentMethod','City', 'Invoice'])
                                        ->paginate(100);
        $orders_drivers_get_paid = $driver->Order()->whereHas('Invoice', function($q){
                                            $q->where('driver_paied', '1');
                                        })
                                        ->where('status', 'delivered')
                                        ->where('collected', 0)
                                        ->with(['Company','PaymentMethod','City', 'Invoice'])
                                        ->paginate(40);
        $driver_finances = $driver->DriverFianance()->with(['Admin','Driver'])->paginate(40);
        $n_orders = $driver->Order()->with(['Company','PaymentMethod','City'])->paginate(20);//return ($n_orders);
        $n0_orders = $driver->Order()->where('status', 'delivered')->where('collected', 0)->with(['Company','PaymentMethod','City'])->paginate(20);
        return view('admin.drivers.show', compact('n_orders','n0_orders', 'orders_not_delivered', 'driver_finances', 'orders_drivers_not_get_paid', 'orders_drivers_get_paid', 'driver', 'title', 'days_chart', 'months_chart', 'orders_not_col', 'orders_col'));
    }

    public function DFOrders($id)
    {
        $row = DriverFianance::findOrfail($id);
        $orders = Order::whereIn('id', explode(',', $row->orders))->get();
        return view('admin.drivers.show-orders', compact('row', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $driver = Driver::find($id);
        $title = 'تعديل ';
        return view('admin.drivers.edit', compact('driver', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDriverRequest $request, $id)
    {

        $driver = Driver::find($id);
        $this->UpdateRecords($driver, $request);

        return redirect('/dashboard/drivers')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if ($driver->image) {
        //     @unlink(public_path('/cdn/'.$driver->image));
        // }
        $driver = Driver::find($id);
        $driver->delete();
        return 'success';
    }
    /*public function CollectOrders($id)
    {
        $driver = Driver::find($id);
        if ($driver->Order()->where('status', 'delivered')->where('collected', 0)->count() > 0) {
            $ids = $driver->Order()->where('status', 'delivered')->where('collected', 0)->pluck('id')->toArray();
            $total_amount = $driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('total_price');
            $driver_amount = $driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->count() * $driver->commission;
            $driver->DriverFianance()->create([
                'branch_id' => null,
                'driver_id' => $driver->id,
                'total_amount'  => $total_amount,
                'driver_amount'  => $driver_amount,
                'net_profit'  => $total_amount - $driver_amount,
                'orders'  => implode(',' ,$ids),
                'status'  => 'init',
                'verified'  => 0,
            ]);
            $driver->Order()->where('status', 'delivered')->where('collected', 0)->update(['collected' => 1]);
        }
        return redirect()->back()->with('success', '  successfully');
    }*/
    public function CollectOrders($id)
    {
        $driver = Driver::find($id);
        if ($driver->Order()->where('status', 'delivered')->where('collected', 0)->count() > 0) {
            $ids = Request()->get('ids'); //$driver->Order()->where('status', 'delivered')->where('collected', 0)->pluck('id')->toArray();
            $total_amount = $driver->Invoice()->whereHas('Order', function($q)use($ids){
                $q->whereIn('id', $ids);
            })->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('total_price');
            $driver_amount = $driver->Invoice()->whereHas('Order', function($q)use($ids){
                $q->whereIn('id', $ids);
            })->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('driver_cost');
            // $driver->DriverFianance()->create([
            //     'branch_id' => null,
            //     'driver_id' => $driver->id,
            //     'total_amount'  => $total_amount,
            //     'driver_amount'  => $driver_amount,
            //     'net_profit'  => $total_amount - $driver_amount,
            //     'orders'  => implode(',' ,$ids),
            //     'status'  => 'init',
            //     'verified'  => 0,
            // ]);
            $driver->Order()->whereIn('id', $ids)->where('status', 'delivered')->where('collected', 0)->update(['collected' => 1]);
        }
        return redirect()->back()->with('success', '  successfully');
    }
    public function CashedOrders($id)
    {
        $driver = Driver::find($id);
        if ($driver->Order()->where('status', 'delivered')->where('collected', 0)->count() > 0) {
            $ids = Request()->get('ids'); //$driver->Order()->where('status', 'delivered')->where('collected', 0)->pluck('id')->toArray();
            $total_amount = $driver->Invoice()->whereHas('Order', function($q)use($ids){
                $q->whereIn('id', $ids);
            })->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('total_price');
            $driver_amount = $driver->Invoice()->whereHas('Order', function($q)use($ids){
                $q->whereIn('id', $ids);
            })->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('driver_cost');
            $driver->DriverFianance()->create([
                'branch_id' => null,
                'driver_id' => $driver->id,
                'total_amount'  => $total_amount,
                'driver_amount'  => $driver_amount,
                'net_profit'  => $total_amount - $driver_amount,
                'orders'  => implode(',' ,$ids),
                'status'  => 'init',
                'verified'  => 0,
            ]);
            $driver->Order()->whereIn('id', $ids)->where('status', 'delivered')->where('collected', 0)->update(['collected' => 1]);
            Invoice::whereIn('order_id', $ids)->update(['driver_paied' => '1']);
        }
        return redirect()->back()->with('success', '  successfully');
    }
    public function files(Request $request, $id)
    {
        $company = Company::find($id);
        if ($request->hasFile('file')) {
            $name = uploadImage($request->file('file'));
            $company->Files()->create(['name' => $name]);
        }
        return back()->with('success', 'data added successfully');;
    }
    public function driverFinanceCollectExcel( $id)
    {
        $row = DriverFianance::findOrfail($id);
        $driver = $row->Driver()->first();
        $title = 'سائق : '. $driver->name;
        $data['driver'] = $driver;
        $data['row'] = $row;
        $data['orders'] = Order::whereIn('id', explode(',', $row->orders))->get();
        return Excel::download(new GeneralExport('admin.reports.driver-finance-collect-excel', $data), 'driver-finance-collect-'.Carbon::now()->toDateString().'.xlsx');

    }
}
