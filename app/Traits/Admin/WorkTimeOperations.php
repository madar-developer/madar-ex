<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait WorkTimeOperations
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
        if ($request->has('time_from')) {
            $data['time_from'] = Carbon::parse($request->get('time_from'));
        }
        if ($request->has('time_to')) {
            $data['time_to'] = Carbon::parse($request->get('time_to'));
        }
        DB::beginTransaction();
        $work_times = WorkTime::create($data);
        DB::commit();
        return $work_times;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */

    public function UpdateRecords(WorkTime $work_times,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$work_times->image));
            // 
            $data['image'] = uploadFile($request);
        }
        $work_times->update($data);
        return $work_times;
    }
    /**
     * delete Record
     * @param $truck
     * @param $request
     */
  
}