<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\CarMaintenance;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait CarMaintenanceOperations
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
            $data['image'] = uploadImage($request->file('image'));
        }
        DB::beginTransaction();

        $CarMaintenance = CarMaintenance::create($data);
        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            // 
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
            $CarMaintenance->BranchData()->create(['admin_id' => $branch_id]);
        }
            ///////////////// branch or admin
            $admin = Admin::first();
            $message = 'تم اضافة قسم صيانه  : '.$CarMaintenance->name;
            if($admin)
            {
                $admin->notify(new GeneralNotification($message, '/dashboard/carmaintaince/'.$CarMaintenance->id ) );
            }

        DB::commit();
        return $CarMaintenance;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(CarMaintenance $Car,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Car->image));
            // 
            $data['image'] = uploadImage($request->file('image'));
        }
        $Car->update($data);
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