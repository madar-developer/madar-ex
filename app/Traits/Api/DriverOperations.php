<?php
namespace App\Traits\Api;

// use App\Mail\Api\DriverVerify as DriverVerifyMail;
use Illuminate\Http\Request;
use App\Models\Driver;
use Mail;
use DB;

trait DriverOperations
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
        $api_token      = str_random(60);
        // while ( Driver::where('api_token',$api_token)->count() > 0 ) {                    
        //     $$api_token = str_random(60);
        // }
        $code = mt_rand(1000,9999);
        // while ( Driver::where('code',$code)->count() > 0 ) {                    
        //     $code = mt_rand(1000,9999);
        // }
        $data['type'] = 'not_employee';
        $data['password'] = bcrypt($request->get('password'));
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request);
        }
        if ($request->hasFile('license_image')) {
            $data['license_image'] = uploadImage($request->file('license_image'));
        }
        if ($request->hasFile('identity_image')) {
            $data['identity_image'] = uploadImage($request->file('identity_image'));
        }
        if ($request->hasFile('form_image')) {
            $data['form_image'] = uploadImage($request->file('form_image'));
        }
        // base64 for android
        if ( $request->has('os') && $request->get('os') == 'android' && $request->has('image')) {
            $data['image'] = uploadImageBase64($request->get('image'));
        }
        if ( $request->has('os') && $request->get('os') == 'android' && $request->has('license_image')) {
            $data['license_image'] = uploadImageBase64($request->get('license_image'));
        }
        if ( $request->has('os') && $request->get('os') == 'android' && $request->has('identity_image')) {
            $data['identity_image'] = uploadImageBase64($request->get('identity_image'));
        }
        if ( $request->has('os') && $request->get('os') == 'android' && $request->has('form_image')) {
            $data['form_image'] = uploadImageBase64($request->get('form_image'));
        }
        // for android
        DB::beginTransaction();
            $Driver = Driver::create($data);
            if ($request->has('player_id') && $request->get('player_id') != '') {
                $Driver->PlayerId()->create($data);
            }
            $verifyData['type'] = 'phone';
            $verifyData['code'] = mt_rand(1000,9999);
            // $Driver->DriverVerify()->create($verifyData);
        DB::commit();
        return $Driver;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Driver $Driver,$request)
    {
        $data = $request->all();
        // unset($data['token']);
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Driver->image));
            // 
            $data['image'] = uploadFile($request);
        }
        // if ($request->has('password') && $data['password'] != '') {
        //     $data['password'] = bcrypt($data['password']);
        // }else{
        //     unset($data['password']);
        // }
        if ($request->hasFile('license_image')) {
            $data['license_image'] = uploadImage($request->file('license_image'));
        }
        if ($request->hasFile('identity_image')) {
            $data['identity_image'] = uploadImage($request->file('identity_image'));
        }
        if ($request->hasFile('form_image')) {
            $data['form_image'] = uploadImage($request->file('form_image'));
        }
        // return dd($data);
        // foreach ($data as $key => $value) {
        //     $Driver->$key = $value;
        //     $Driver->save();
        // }
        // for android
        $Driver->update($data);
        return $Driver;
    }

    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function StorOrUpdateCost(Driver $Driver,$request)
    {
        $data = $request->all();
        DeliveryDriver::updateOrcreate(['Driver_id' => $Driver->id], $data);
        return $Driver;
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