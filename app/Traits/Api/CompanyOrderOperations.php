<?php
namespace App\Traits\Api;

use Illuminate\Http\Request;
use App\Models\CompanyOrder;
use App\Models\User;
use App\Models\Driver;
use Carbon\Carbon;
use Auth;
use DB;
use App\Http\Controllers\Api\FCMController;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait CompanyOrderOperations
{
    /**
     * Register a New .
     *
     * @param $request
     * @return \App\
     */
    public function register ($request, $user )
    {
        DB::beginTransaction();
        $data = $request->all();
        $CompanyOrder  = $user->CompanyOrder()->create($data);
        foreach ($data['items'] as $item ) {
            $CompanyOrder->CompanyOrderItem()->create($item);
        }
        if ($request->has('images')) {
            foreach ($request->images as $image) {
                // $image = uploadImage($image);
                $CompanyOrder->Files()->create(['name' => $image]);
            } 
        }
        // send notification to admin start
        $admin = Admin::first();
        // $admin->notify(new AdminNotification('طلب جديد #' . $CompanyOrder->id , route('company-orders.show', $CompanyOrder->id)));
        // send notification to admin end
        DB::commit();
        return $CompanyOrder;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateCompanyOrderStatus(CompanyOrder $CompanyOrder,$request)
    {
        $CompanyOrder->update(['status' => $request->get('status')]);
        return $CompanyOrder;
    }


}