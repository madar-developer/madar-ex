<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\CompanyInvoice;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait CompanyInvoiceInvoiceOperations
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
        $company_invoice = CompanyInvoice::create($data);
        DB::commit();
        return $company_invoice;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(CompanyInvoice $company_invoice,$request)
    {
        $data = $request->all();
       
       $company_invoice->update($data);
        return $company_invoice;
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