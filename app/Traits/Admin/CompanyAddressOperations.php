<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\CompanyAddress;
use App\Models\Admin;
use App\Notifications\GeneralNotification;
use DB;

trait CompanyAddressOperations
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
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request);
        }
        DB::beginTransaction();
        $row = CompanyAddress::create($data);

        DB::commit();
        return $row;
    }

    public function UpdateRecords(CompanyAddress $row,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$row->image));
            // 
            $data['image'] = uploadFile($request);
           
        }
        $row->update($data);
        return $row;
    }
}