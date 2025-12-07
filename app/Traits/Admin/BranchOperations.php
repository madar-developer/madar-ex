<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\Branch;
use DB;

trait BranchOperations
{
  

    /**
     * Register a New .
     *
     * @param $request
     * @return \App\
     */
    public function register ($request)
    {
        $data = (array)$request->except('_token');
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request);
        }
        DB::beginTransaction();
        $Branch = Branch::create($data);
        DB::commit();
        return $Branch;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Branch $Branch,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Branch->image));
            // 
            $data['image'] = uploadFile($request);
        }
        $Branch->update($data);
        return $Branch;
    }

    /**
     * delete Record
     * @param $truck
     * @param $request
     */
    public function DeleteRecord($id)
    {
        //
    }
}