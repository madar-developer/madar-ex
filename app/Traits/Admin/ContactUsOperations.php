<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait ContactUsOperations
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
        if ($request->has('today')) {
            $data['today'] = Carbon::parse($request->get('today'));
        }
        DB::beginTransaction();
        $ContactUs = ContactUs::create($data);
        DB::commit();
        return $ContactUs;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(ContactUs $ContactUs,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$ContactUs->image));
            // 
            $data['image'] = uploadImage($request->file('image'));
        }
       $ContactUs->update($data);
        return $ContactUs;
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