<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\RequestDriver;
use DB;
use Carbon\Carbon;

trait RequestDriverOperations
{


    /**
     * Register a New .
     *
     * @param $request
     * @return \App\
     */
    public function register ($request)
    {
        $data = $request->all();
        if ($request->has('pickup_date')) {
            $data['pickup_date'] = Carbon::parse($request->get('pickup_date'));
        }
        $data['company_id'] = auth('company')->id();
        $data['status'] = 'pending';

        DB::beginTransaction();
        $RequestDriver = RequestDriver::create($data);
        DB::commit();
        return $RequestDriver;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(RequestDriver $RequestDriver,$request)
    {
        $data = $request->all();
        if ($request->has('pickup_date')) {
            $data['pickup_date'] = Carbon::parse($request->get('pickup_date'));
        }
        $RequestDriver->update($data);
        return $RequestDriver;
    }
}
