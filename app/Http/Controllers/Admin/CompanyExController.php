<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use App\Exports\GeneralExport;
use App\Models\Company;
use Carbon\Carbon;

class CompanyExController extends Controller
{
    public function companiesGet(Request $request)
    {
        $title = ' تقرير الشركات ';
        $search = [];
        return view('admin.reports.companies-view', compact('title', 'search') );
    }
    public function companiesPost(Request $request)
    {
        $companies = Company::latest();
        $search = array();
        if (Request()->has('serial') && Request()->get('serial') != '') {
            $serial = Request()->get('serial');
            $search['serial'] = $serial;
            $companies = $companies->where('serial'  ,$serial);
        }
        if ($request->has('serial_from') && $request->get('serial_from') != '') {
            $serial_from = (int)str_replace('mx-', '', $request->get('serial_from') );
            $search['serial_from'] = $request->get('serial_from');
            $companies = $companies->where('serial_no', '>=', $serial_from);
            if (!$request->has('serial_to') || $request->get('serial_to') == '') {
                $companies = $companies->where('serial_no', '=', $serial_from);
            }
        }
        if ($request->has('serial_to') && $request->get('serial_to') != '') {
            $serial_to = (int)str_replace('mx-', '', $request->get('serial_to') );
            $search['serial_to'] = $request->get('serial_to');
            $companies = $companies->where('serial_no', '<=', $serial_to);
        }
        if (Request()->has('refrence_no') && Request()->get('refrence_no') != '') {
            $refrence_no = Request()->get('refrence_no');
            $search['refrence_no'] = $refrence_no;
            $companies = $companies->where('refrence_no'     ,$refrence_no);
        }
        if (Request()->has('refrence_no') && Request()->get('refrence_no') != '') {
            $refrence_no = Request()->get('refrence_no');
            $search['refrence_no'] = $refrence_no;
            $companies = $companies->where('refrence_no'     ,$refrence_no);
        }
        if (Request()->has('company_phone') && Request()->get('company_phone') != '') {
            $company_phone = Request()->get('company_phone');
            $search['company_phone'] = $company_phone;
            $companies = $companies->wherehas('Company', function($q) use ($company_phone){
                $q->where('phone'     , 'LIKE', '%'.$company_phone.'%');
            });
        }
        if (Request()->has('recipent_name') && Request()->get('recipent_name') != '') {
            $recipent_name = Request()->get('recipent_name');
            $search['recipent_name'] = $recipent_name;
            $companies = $companies->where('recipent_name'     ,$recipent_name);
        }
        if (Request()->has('phone') && Request()->get('phone') != '') {
            $phone = Request()->get('phone');
            $search['phone'] = $phone;
            $companies = $companies->where('phone'     ,$phone);
        }
        if (Request()->has('status') && Request()->get('status') != '') {
            $status = Request()->get('status');
            $search['status'] = $status;
            $companies = $companies->where('status'     ,$status);
        }
        $companies = $companies->get();
        return Excel::download(new GeneralExport('admin.reports.companies-excel', $companies), 'companies-'.Carbon::now()->toDateString().'.xlsx');
    }
}
