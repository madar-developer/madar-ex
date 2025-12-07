<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\Invoice;
use App\Models\PriceList;
use DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Notifications\AdminNotification;

trait TransferOperations
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
        if ($request->has('history_of')) {
            $data['history_of'] = Carbon::parse($request->get('history_of'));
        }
        if ($request->has('history_to')) {
            $data['history_to'] = Carbon::parse($request->get('history_to'));
        }
        if ($request->has('transfer_request_date')) {
            $data['transfer_request_date'] = Carbon::parse($request->get('transfer_request_date'));
        }
        if ($request->has('date_from')) {
            $data['date_from'] = Carbon::parse($request->get('date_from'));
        }
        if ($request->has('date_to')) {
            $data['date_to'] = Carbon::parse($request->get('date_to'));
        }
        DB::beginTransaction();
        if ($request->has('invoices')) {
            $data['amount'] = Invoice::whereIn('id', $request->get('invoices'))->sum('total_price');
        }
        $Transfer = Transfer::create($data);
        if ($request->has('invoices')) {
            foreach ($request->get('invoices') as $key) {
                $Transfer->TransferInvoice()->create([
                    'invoice_id' => $key
                    ]);
                }
            }
            if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
                //
                if (auth('admin')->user()->role == 'branch') {
                    $branch_id = auth('admin')->id();
                } else {
                    $branch_id = auth('admin')->user()->parent_id;
                }
                $Transfer->BranchData()->create(['admin_id' => $branch_id]);
            }

            //

            DB::commit();
            if($Transfer->Company()->first())
            {
                $message = "تم انشاء حوالة جديده لمتجرك.";
                $Transfer->Company()->first()->notify(new GeneralNotification($message, '/company/company-transfers/'.$Transfer->id ) );
            }
        return $Transfer;

    }



    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Transfer $Transfer,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Transfer->image));
            //
            $data['image'] = uploadImage($request->file('image'));
        }
        if ($request->has('history_of')) {
            $data['history_of'] = Carbon::parse($request->get('history_of'));
        }
        if ($request->has('history_to')) {
            $data['history_to'] = Carbon::parse($request->get('history_to'));
        }
        if ($request->has('transfer_request_date')) {
            $data['transfer_request_date'] = Carbon::parse($request->get('transfer_request_date'));
        }
        if ($request->has('date_from')) {
            $data['date_from'] = Carbon::parse($request->get('date_from'));
        }
        if ($request->has('date_to')) {
            $data['date_to'] = Carbon::parse($request->get('date_to'));
        }
        if ($request->has('invoices')) {
            $data['amount'] = Invoice::whereIn('id', $request->get('invoices'))->sum('total_price');
        }
        if ($request->has('invoices')) {
            $Transfer->TransferInvoice()->delete();
            foreach ($request->get('invoices') as $key) {
                $Transfer->TransferInvoice()->create([
                    'invoice_id' => $key
                    ]);
                }
            }
        $Transfer->update($data);
        return $Transfer;
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
