<?php
namespace App\Traits\Admin;

use App\Mail\Api\UserVerify as UserVerifyMail;
use Illuminate\Http\Request;
use App\Models\DeliveryUser;
use App\Models\User;
use Mail;
use DB;

trait UserOperations
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
        if ($request->hasFile('business_image')) {
            $data['business']['image'] = uploadImage($request->file('business_image'));
        }
        // return dd($data);
        // $data['id'] = User::max('id') + 1;
        DB::beginTransaction();
        $user = User::create($data);
        if ($request->has('vip') && $data['vip'] == '1') {
            $user->BusinessAccount()->create($data['business']);
        }
        ///////////////// branch or admin
        $message = 'تم اضافة مستخدم  : '.$user->name;
        if($admin)
        
        {
            $admin->notify(new GeneralNotification($message, '/dashboard/users/'.$User->id ) );
        }
        if (auth('admin')->user()->role == 'branch') 
        //////////////////////////////
        $user->BranchData()->create(['admin_id' => auth('admin')->id()]);
        DB::commit();
        return $user;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(User $user,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$user->image));
            // 
            $data['image'] = uploadFile($request);
        }
        if ($request->hasFile('business_image')) {
            $data['business']['image'] = uploadImage($request->file('business_image'));
        }
        if ($request->has('password') && $data['password'] != '') {
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }
        
        $user->update($data);
        return $user;
    }

    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function StorOrUpdateCost(User $user,$request)
    {
        $data = $request->all();
        DeliveryUser::updateOrcreate(['user_id' => $user->id], $data);
        return $user;
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