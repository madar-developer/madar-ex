<?php

namespace App\Http\Controllers\Api\Company;

// use App\Http\Requests\Admin\UpdateCompanyCacheTypeRequest;
// use App\Http\Requests\Admin\StoreCompanyCacheTypeRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PayWayResource;
use App\Traits\Admin\CompanyCacheTypeOperations;
use Illuminate\Http\Request;
use App\Models\CompanyCacheType;
use Auth;
use Carbon\Carbon;


class PayWayController extends Controller
{
    use CompanyCacheTypeOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Auth::guard('api-company')->user();
        $company = auth('api-company')->user();
        $pay_ways = $company->CompanyCacheType()->with('AvailableMethod')->latest()->get();
        $pay_ways = PayWayResource::collection($pay_ways);
        return Response()->json([
                'data' => [
                    'pay_ways' => $pay_ways
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $company = Auth::guard('api-company')->user();
        $company->CompanyCacheType()->create( $request->all() );
        $pay_ways = $company->CompanyCacheType()->with('AvailableMethod')->latest()->get();
        $pay_ways = PayWayResource::collection($pay_ways);
        return Response()->json([
                'data' => [
                    'pay_ways' => $pay_ways
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
        return back()->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyCacheType $row)
    {

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
        $company = Auth::guard('api-company')->user();
        $company->CompanyCacheType()->where('id', $id)->update( $request->all() );
        $pay_ways = $company->CompanyCacheType()->with('AvailableMethod')->latest()->get();
        $pay_ways = PayWayResource::collection($pay_ways);
        return Response()->json([
                'data' => [
                    'pay_ways' => $pay_ways
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
    public function destroy( $id)
    {
        $company = Auth::guard('api-company')->user();
        $company->CompanyCacheType()->where('id', $id)->delete();
        $pay_ways = $company->CompanyCacheType()->with('AvailableMethod')->latest()->get();
        $pay_ways = PayWayResource::collection($pay_ways);
        return Response()->json([
                'data' => [
                    'pay_ways' => $pay_ways
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
}
