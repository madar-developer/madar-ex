<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\CompanyCacheType;
use App\Models\Admin;
use App\Notifications\GeneralNotification;
use DB;

trait CompanyCacheTypeOperations
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
        $row = CompanyCacheType::create($data);

        DB::commit();
        return $row;
    }

    public function UpdateRecords(CompanyCacheType $row,$request)
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