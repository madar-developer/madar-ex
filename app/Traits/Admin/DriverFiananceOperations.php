<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use Illuminate\Http\Request;
use App\Models\DriverFianance;
use Carbon\Carbon;
use Mail;
use DB;
use App\Models\Admin;
use App\Notifications\GeneralNotification;

trait DriverFiananceOperations
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
        // $api_token      = str_random(60);
        // while ( Driver::where('api_token',$api_token)->count() > 0 ) {                    
        //     $$api_token = str_random(60);
        // }
        // $data['api_token'] = $api_token;
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request);
        }
        if ($request->hasFile('identity_image')) {
            $data['identity_image'] = uploadImage($request->file('identity_image'));
        }
        if ($request->hasFile('license_image')) {
            $data['license_image'] = uploadImage($request->file('license_image'));
        }
        if ($request->hasFile('form_image')) {
            $data['form_image'] = uploadImage($request->file('form_image'));
        }
        if ($request->has('license_date_expiration')) {
            $data['license_date_expiration'] = Carbon::parse($request->get('license_date_expiration'));
        }
       
       
        
        DB::beginTransaction();
        $Driver = DriverFianance::create($data);
        $admin = Admin::first();
        if ($request->has('cities')) {
            foreach ($request->cities as $key ) {
                $Driver->DriverCity()->create(['city_id' => $key]);
            }
        }
        $message = 'تم اضافة سائق  : '.$Driver->name;
        if($admin)
        
        {
            $admin->notify(new GeneralNotification($message, '/dashboard/drivers/'.$Driver->id ) );
        }
        
        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            // 
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
            $Driver->BranchData()->create(['admin_id' => $branch_id]);
        }
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
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Driver->image));
            // 
            $data['image'] = uploadFile($request);
        }
        if ($request->hasFile('identity_image')) {
            @unlink(public_path('/cdn/'.$Driver->identity_image));
            $data['identity_image'] = uploadImage($request->file('identity_image'));
        }
        if ($request->hasFile('license_image')) {
            @unlink(public_path('/cdn/'.$Driver->license_image));
            $data['license_image'] = uploadImage($request->file('license_image'));
        }
        if ($request->hasFile('form_image')) {
            @unlink(public_path('/cdn/'.$Driver->form_image));
            $data['form_image'] = uploadImage($request->file('form_image'));
        }
        if ($request->has('password') && $data['password'] != '') {
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }
        if ($request->has('cities')) {
            $Driver->DriverCity()->delete();
            foreach ($request->cities as $key ) {
                $Driver->DriverCity()->create(['city_id' => $key]);
            }
        }
        $admin = Admin::first();
        $message = 'تم تعديل بيانات السائق  : '.$Driver->name;
        if($admin)
        {
            $admin->notify(new GeneralNotification($message, '/dashboard/drivers/'.$Driver->id ) );
        }
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