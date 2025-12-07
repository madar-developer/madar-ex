<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use App\Exports\GeneralExport;
use App\Models\Driver;
use Carbon\Carbon;

class DriverExController extends Controller
{
    public function driversGet(Request $request)
    {
        $title = ' تقرير الشركات ';
        $search = [];
        return view('admin.reports.drivers-view', compact('title', 'search') );
    }
    public function driversPost(Request $request)
    {
        $drivers = Driver::latest();
        $search = array();
        if (Request()->has('serial') && Request()->get('serial') != '') {
            $serial = Request()->get('serial');
            $search['serial'] = $serial;
            $drivers = $drivers->where('serial'  ,$serial);
        }
        if ($request->has('serial_from') && $request->get('serial_from') != '') {
            $serial_from = (int)str_replace('mx-', '', $request->get('serial_from') );
            $search['serial_from'] = $request->get('serial_from');
            $drivers = $drivers->where('serial_no', '>=', $serial_from);
            if (!$request->has('serial_to') || $request->get('serial_to') == '') {
                $drivers = $drivers->where('serial_no', '=', $serial_from);
            }
        }
        if ($request->has('serial_to') && $request->get('serial_to') != '') {
            $serial_to = (int)str_replace('mx-', '', $request->get('serial_to') );
            $search['serial_to'] = $request->get('serial_to');
            $drivers = $drivers->where('serial_no', '<=', $serial_to);
        }
        if (Request()->has('refrence_no') && Request()->get('refrence_no') != '') {
            $refrence_no = Request()->get('refrence_no');
            $search['refrence_no'] = $refrence_no;
            $drivers = $drivers->where('refrence_no'     ,$refrence_no);
        }
        if (Request()->has('refrence_no') && Request()->get('refrence_no') != '') {
            $refrence_no = Request()->get('refrence_no');
            $search['refrence_no'] = $refrence_no;
            $drivers = $drivers->where('refrence_no'     ,$refrence_no);
        }
        if (Request()->has('driver_phone') && Request()->get('driver_phone') != '') {
            $driver_phone = Request()->get('driver_phone');
            $search['driver_phone'] = $driver_phone;
            $drivers = $drivers->wherehas('driver', function($q) use ($driver_phone){
                $q->where('phone'     , 'LIKE', '%'.$driver_phone.'%');
            });
        }
        if (Request()->has('recipent_name') && Request()->get('recipent_name') != '') {
            $recipent_name = Request()->get('recipent_name');
            $search['recipent_name'] = $recipent_name;
            $drivers = $drivers->where('recipent_name'     ,$recipent_name);
        }
        if (Request()->has('phone') && Request()->get('phone') != '') {
            $phone = Request()->get('phone');
            $search['phone'] = $phone;
            $drivers = $drivers->where('phone'     ,$phone);
        }
        if (Request()->has('status') && Request()->get('status') != '') {
            $status = Request()->get('status');
            $search['status'] = $status;
            $drivers = $drivers->where('status'     ,$status);
        }
        $drivers = $drivers->get();
        return Excel::download(new GeneralExport('admin.reports.drivers-excel', $drivers), 'drivers-'.Carbon::now()->toDateString().'.xlsx');
    }
}
