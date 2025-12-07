<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\Category;
use DB;

trait CategoryOperations
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
        $Category = Category::create($data);
        DB::commit();
        return $Category;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Category $Category,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Category->image));
            // 
            $data['image'] = uploadFile($request);
        }
        $Category->update($data);
        return $Category;
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