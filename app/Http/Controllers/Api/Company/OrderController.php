<?php

namespace App\Http\Controllers\Api\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OrderResource;
use App\Http\Resources\Api\StatusResource;
use Auth;
use App\Traits\Api\OrderOperations;
use App\Models\Order;
use App\Models\OrderStatus;
use Carbon\Carbon;

class OrderController extends Controller
{
    use OrderOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $company = Auth::guard('api-company')->user();
      if (Request()->has('type') && Request()->get('type') == 'receiver') {
        //
            $orders = Order::where('phone', $company->phone)->latest();
        } else {
            $orders = $company->Order()->latest();
            //
        }
        if (Request()->has('date') && Request()->get('date') != '') {
            $date = Carbon::parse( Request()->get('date') );
            $search['date'] = Request()->get('date');
            $orders = $orders->whereDate('created_at', '=', $date);
        }
        if (Request()->has('date_from') && Request()->get('date_from') != '') {
            $date_from = Request()->get('date_from');
            $search['date_from'] = $date_from;
            $date_from = Carbon::parse(Request()->get('date_from'));
            $orders = $orders->whereDate('created_at', '>=', $date_from);
            if (!Request()->has('date_to') || Request()->get('date_to') == '') {
                $orders = $orders->whereDate('created_at', '=', $date_from);
            }
        }
        if (Request()->has('date_to') && Request()->get('date_to') != '') {
            $date_to = Request()->get('date_to');
            $search['date_to'] = $date_to;
            $date_to = Carbon::parse(Request()->get('date_to'));
            $orders = $orders->whereDate('created_at', '<=', $date_to);
        }
        if (Request()->has('status') && Request()->get('status') != '') {
            $status = Request()->get('status');
            $search['status'] = $status;
            $orders = $orders->where('status'     ,$status);
        }
        if (Request()->has('id') && Request()->get('id') != '') {
            $id = Request()->get('id');
            $search['id'] = $id;
            $orders = $orders->where('id'     ,$id);
        }
        if (Request()->has('city_id') && Request()->get('city_id') != '') {
            $city_id = Request()->get('city_id');
            $search['city_id'] = $city_id;
            $orders = $orders->where('city_id'     ,$city_id);
        }
        if (Request()->has('district_id') && Request()->get('district_id') != '') {
            $district_id = Request()->get('district_id');
            $search['district_id'] = $district_id;
            $orders = $orders->where('district_id'     ,$district_id);
        }
        if (Request()->has('keyword') && Request()->get('keyword') != '') {
            $keyword = Request()->get('keyword');
            $search['keyword'] = $keyword;
            $orders = $orders->where(function($q)use($keyword){
                $q->where('recipent_name'  , 'LIKE'   ,"%$keyword%")
                    ->Orwhere('phone' , 'LIKE'    ,"%$keyword%");
            });
        }
        $count = $orders->count();
        $orders = $orders->paginate(40);
        $orders->data = OrderResource::collection($orders);

      return Response()->json([
                'data' => [
                    'orders' => $orders,
                    'count' => $count,
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
       $order =  $this->register($request);
       $order = new OrderResource($order);
       return Response()->json([
        'data' => [
            'order' => $order
        ],
        'message' => 'success',
        'code' => getMsgCode('success')
        ]);

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
        $order = $company->Order()->where('id', $id)->first();
        if (in_array($order->status, ['init', 'at_madar'])) {
            $step = 1;
        // } else if (in_array($order->status, [ 'deliver_failed', 'reschedule'])) {
        } else if (in_array($order->status, [ 'reschedule'])) {
            $step = 2;
        } else if (in_array($order->status, ['at_office'])) {
            $step = 3;
        } else if (in_array($order->status, ['delivered'])) {
            $step = 4;
        } else if (in_array($order->status, ['deliver_failed'])) {
            $step = 5;
        } else {
            $step = 0;
        }
        $statuses = OrderStatus::whereIn('key', ['new', 'at_madar', 'at_office', 'delivered'])->get();
        $statuses = StatusResource::collection($statuses);
        $order = new OrderResource($order);
        $logs = $order->OrderLog()->get();
        return Response()->json([
                'data' => [
                    'order' => $order,
                    'statuses' => $statuses,
                    'step' => $step,
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
        $company = Auth::guard('api-company')->user();
        $order = $company->Order()->where('id', $id)->first();
        if($order->status != 'new')
        {
            return Response()->json([
                'data' => new \stdClass,
                'message' => 'Not Allowed',
                'code' => 500
                ]);
        }
        $order =  $this->register($request);
       $order = new OrderResource($order);
       return Response()->json([
        'data' => [
            'order' => $order
        ],
        'message' => 'success',
        'code' => getMsgCode('success')
        ]);
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
