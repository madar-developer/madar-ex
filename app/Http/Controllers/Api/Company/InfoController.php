<?php

namespace App\Http\Controllers\Api\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AMResource;
use App\Http\Resources\Api\CityResource;
use App\Http\Resources\Api\PMResource;
use Auth;
use App\Models\PaymentMethod;
use App\Models\City;
use App\Models\AvailableMethod;
use App\Traits\Api\OrderOperations;


class InfoController extends Controller
{
    use OrderOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //   $company = Auth::guard('api-company')->user();

    $cities = City::get();
    // $payment_methods = PaymentMethod::get();
    // $acceptable_payment_methods = AvailableMethod::get();
    $cities = CityResource::collection($cities);
    $payment_methods = PaymentMethod::get();
    $payment_methods = PMResource::collection($payment_methods);
    $acceptable_payment_methods = AvailableMethod::get();
    $acceptable_payment_methods = AMResource::collection($acceptable_payment_methods);
    return response()->json([
        'data' => [
            'cities' => $cities,
            'payment_methods' => $payment_methods,
            'acceptable_payment_methods' => $acceptable_payment_methods,
        ],
        'message' => 'allowed cities',
        'code' => 200
    ], 200);

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
