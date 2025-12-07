<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Driver;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait InvoiceOperations
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
        if ($request->has('reciever_date')) {
            $data['reciever_date'] = Carbon::parse($request->get('reciever_date'));
        }
        if ($request->has('sender_date')) {
            $data['sender_date'] = Carbon::parse($request->get('sender_date'));
        }

        DB::beginTransaction();
        $invoice = Invoice::create($data);
        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            // 
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
            $invoice->BranchData()->create(['admin_id' => $branch_id]);
        }
        DB::commit();
        return $invoice;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Invoice $invoice,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$invoice->image));
            // 
            $data['image'] = uploadImage($request->file('image'));
        }
       $invoice->update($data);
        return $invoice;
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