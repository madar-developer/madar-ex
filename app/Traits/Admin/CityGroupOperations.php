<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\CityGroup;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait CityGroupOperations
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
        DB::beginTransaction();
        $row = CityGroup::create($data);
        if ($request->has('cities')) {
            foreach ($request->cities as $key ) {
                $row->CityGroupCity()->create(['city_id' => $key]);
            }
        }
        DB::commit();
        return $row;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(CityGroup $row,$request)
    {
        $data = $request->all();
        if ($request->has('cities')) {
            $row->CityGroupCity()->delete();
            foreach ($request->cities as $key ) {
                $row->CityGroupCity()->create(['city_id' => $key]);
            }
        }
        $row->update($data);
        return $row;
    }
    /**
     * delete Record
     * @param $truck
     * @param $request
     */
}