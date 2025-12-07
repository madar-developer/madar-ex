<?php
namespace App\Traits\Admin;

use App\Mail\Api\AdminVerify as AdminVerifyMail;
use Illuminate\Http\Request;
use App\Models\DeliveryAdmin;
use App\Models\Admin;
use Mail;
use DB;

trait AdminOperations
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
        $data['password'] = bcrypt($data['password']);
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request);
        }
        DB::beginTransaction();
        $Admin = Admin::create($data);
        if ($request->has('cities')) {
            foreach ($request->get('cities') as $item) {
                $Admin->BranchCity()->create(['city_id' => $item]);
            }
        }
        DB::commit();
        return $Admin;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Admin $Admin,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Admin->image));
            // 
            $data['image'] = uploadFile($request);
        }
        if ($request->has('password') && $data['password'] != '') {
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }
        if ($request->has('cities')) {
            $Admin->BranchCity()->delete();
            foreach ($request->get('cities') as $item) {
                $Admin->BranchCity()->create(['city_id' => $item]);
            }
        }
        $Admin->update($data);
        return $Admin;
    }

    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function StorOrUpdateCost(Admin $Admin,$request)
    {
        $data = $request->all();
        DeliveryAdmin::updateOrcreate(['Admin_id' => $Admin->id], $data);
        return $Admin;
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