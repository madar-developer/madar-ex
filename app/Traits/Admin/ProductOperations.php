<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait ProductOperations
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
        // $data['category_id'] = 1;;
        DB::beginTransaction();
        $Product = Product::create($data);
        if ($request->hasFile('images') ) {
            foreach ($request->file('images') as $image) {
                $data2['name'] = uploadImage($image);
                $Product->Files()->create($data2);
            }
        }
        DB::commit();
        return $Product;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Product $Product,$request)
    {
        $data = $request->all();
        
        if ($request->hasFile('images') ) {
            foreach ($request->file('images') as $image) {
                $data2['name'] = uploadImage($image);
                $Product->Files()->create($data2);
            }
        }
        $Product->update($data);
        return $Product;
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