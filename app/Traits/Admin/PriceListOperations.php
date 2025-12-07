<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\PriceList;
use DB;

trait PriceListOperations
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
        $PriceList = PriceList::create($data);
        DB::commit();
        return $PriceList;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(PriceList $PriceList,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$PriceList->image));
            // 
            $data['image'] = uploadFile($request);
        }
        $PriceList->update($data);
        return $PriceList;
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