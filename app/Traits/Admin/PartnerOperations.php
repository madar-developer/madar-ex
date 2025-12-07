<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait PartnerOperations
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
        $partner = Partner::create($data);
        DB::commit();
        return $partner;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */

    public function UpdateRecords(Partner $Partner,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Partner->image));
            // 
            $data['image'] = uploadFile($request);
        }
        $Partner->update($data);
        return $Partner;
    }
    /**
     * delete Record
     * @param $truck
     * @param $request
     */
  
}