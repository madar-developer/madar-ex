<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyAddress;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Auth::guard('api-company')->user();
        $company = auth('api-company')->user();
        $addresses = $company->CompanyAddress()->latest()->get();
        return Response()->json([
                'data' => [
                    'addresses' => $addresses
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
        if ($request->has('main') && $request->get('main') != '') {
            $company->CompanyAddress()->update(['main' => '0']);
        }
        $company->CompanyAddress()->create( $request->all() );
        $addresses = $company->CompanyAddress()->latest()->get();
        return Response()->json([
                'data' => [
                    'addresses' => $addresses
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
    public function show(CompanyAddress $row)
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
        if ($request->has('main') && $request->get('main') != '') {
            $company->CompanyAddress()->update(['main' => '0']);
        }
        $company->CompanyAddress()->where('id', $id)->update( $request->all() );
        $addresses = $company->CompanyAddress()->latest()->get();
        return Response()->json([
                'data' => [
                    'addresses' => $addresses
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
        $company->CompanyAddress()->where('id', $id)->delete();
        $addresses = $company->CompanyAddress()->latest()->get();
        return Response()->json([
                'data' => [
                    'addresses' => $addresses
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
}
