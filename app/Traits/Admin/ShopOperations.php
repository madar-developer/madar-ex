<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\Shop;
use DB;

trait ShopOperations
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
        $Shop = Shop::create($data);
        DB::commit();
        return $Shop;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Shop $Shop,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Shop->image));
            // 
            $data['image'] = uploadFile($request);
           
        }
        if(isset($data['password']) && !empty($data['password'])){
            $data['password']=  bcrypt($data['password']);
           } else{
            
                unset($data['password']);
           }
        $Shop->update($data);
        return $Shop;
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