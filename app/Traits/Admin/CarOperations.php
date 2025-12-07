<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait CarOperations
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
        
        if ($request->hasFile('form_image')) {
            $data['form_image'] = uploadImage($request->file('form_image'));
        }
        DB::beginTransaction();
        $Car = Car::create($data);
        $admin = Admin::first();
        $message = 'تم اضافة سيارة  : '.$Car->name;
        if($admin)
        {
            $admin->notify(new GeneralNotification($message, '/dashboard/cars/'.$Car->id ) );
        }

        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            // 
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
            $Car->BranchData()->create(['admin_id' => $branch_id]);
        }
        DB::commit();
        return $Car;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Car $Car,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Car->image));
            // 
            $data['image'] = uploadImage($request->file('image'));
        }
        if ($request->hasFile('form_image')) {
            @unlink(public_path('/cdn/'.$Driver->form_image));
            $data['form_image'] = uploadImage($request->file('form_image'));
        }
        if($request->has('kms') && $request->get('kms') != $Car->kms)
        {
            $admin = Admin::first();
            $message = 'تم تغيير عدد الكيلو مترات للسياره : '.$Car->name;
            if($admin)
            {
                $admin->notify(new GeneralNotification($message, '/dashboard/cars/'.$Car->id ) );
            }
        }
        $Car->update($data);
        $admin = Admin::first();
        $message = 'تم تعديل بيانات السيارة  : '.$Car->name;
        if($admin)
        {
            $admin->notify(new GeneralNotification($message, '/dashboard/cars/'.$Car->id ) );
        }
        return $Car;
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