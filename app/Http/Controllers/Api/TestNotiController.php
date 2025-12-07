<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Setting;
use App\Models\OrderStatus;
use App\Models\FeedBack;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Term;
use App\Models\Transfer;
use App\Models\WorkTime;

class TestNotiController extends Controller
{
    public function index(Request $request, $type)
    {

        if ($type == 'order') {
            $order_id = $request->order_id;
            $data2 = [
                'order_id' => $order_id,
                'title_ar' => '#'.$order_id,
                'title_en' => '#'.$order_id,
                'content_ar' => 'test not-fication '. $type,
                'content_en' => 'test not-fication '. $type,
                'type' => 'order_details',
            ];
            $order = Order::find($order_id);
            $company = $order->Company()->find($request->company_id);
            $token = $company->PlayerId()->pluck('player_id')->toArray();
            $res =FCMController::Push('#'.$order_id, 'test not-fication '. $type,$token,$data2, 'order_details');
        }
        if ($type == 'transfer') {
            $transfer_id = $request->transfer_id;
            // dd($transfer_id);
            $data2 = [
                'transfer_id' => $transfer_id,
                'title_ar' => 'حوالة رقم '.$transfer_id,
                'title_en' => 'حوالة رقم '.$transfer_id,
                'content_ar' => 'test not-fication '. $type,
                'content_en' => 'test not-fication '. $type,
                'type' => 'transfer_details',
            ];
            $transfer = Transfer::find($transfer_id);
            $company = Company::find($request->company_id);
            $token = $company->PlayerId()->pluck('player_id')->toArray();
            $res = FCMController::Push('حوالة رقم '.$transfer_id, 'test not-fication '. $type,$token,$data2, 'transfer_details');
        }
        if ($type == 'invoice') {
            $invoice_id = $request->invoice_id;
            // dd($invoice_id);
            $data2 = [
                'invoice_id' => $invoice_id,
                'title_ar' => 'حوالة رقم '.$invoice_id,
                'title_en' => 'حوالة رقم '.$invoice_id,
                'content_ar' => 'test not-fication '. $type,
                'content_en' => 'test not-fication '. $type,
                'type' => 'invoice_details',
            ];
            $invoice = Invoice::find($invoice_id);
            $company = Company::find($request->company_id);
            $token = $company->PlayerId()->pluck('player_id')->toArray();
            $res = FCMController::Push('حوالة رقم '.$invoice_id, 'test not-fication '. $type,$token,$data2, 'transfer_details');
        }
        dd($res);
    }

}
