<?php
namespace App\Traits\Api;

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
        $data = (array)$request->except('player_id');
        $api_token      = str_random(60);
        // while ( User::where('api_token',$api_token)->count() > 0 ) {                    
        //     $$api_token = str_random(60);
        // }
        $code = mt_rand(1000,9999);
        // while ( User::where('code',$code)->count() > 0 ) {                    
        //     $code = mt_rand(1000,9999);
        // }
        // $data['api_token'] = $api_token;
        $data['password'] = bcrypt($request->get('password'));
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request);
        }
        // base64
        if ($request->has('os') && $request->get('os') == 'android' && $request->has('image')) {
            $data['image'] = uploadImageBase64($request->get('image'));
            // 
        }
        // base64
        DB::beginTransaction();
            $user = User::create($data);
            if ($request->has('player_id') && $request->get('player_id') != '') {
                $user->PlayerId()->create(['player_id' => $request->player_id]);
            }
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
        // base64
        if ($request->has('os') && $request->get('os') == 'android' && $request->has('image')) {
            $data['image'] = uploadImageBase64($request->get('image'));
            // 
        }
        // base64
        if ($request->has('password') && $data['password'] != '') {
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }
        if ($request->has('phone') && $data['phone'] != '' && $request->has('code') && $data['code'] != '' && $user->verify_code ==  $data['code'] ) {
            // 
            $data['verify_code'] = null;
        }else{
            unset($data['phone']);
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