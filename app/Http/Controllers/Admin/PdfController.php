<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Driver;
use App\Models\DriverFianance;
use App\Models\Invoice;
use App\Models\Transfer;
use App\Models\Order;
use PDF;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function companyPdf( $id)
    {
        $company = Company::find($id);
        $title = 'شركة : '. $company->name;
        config(['pdf.format'                => 'A4']);
            $pdf1 = PDF::loadView('admin.reports.pdf.company', compact('company', 'title'));
            return $pdf1->download('document.pdf');
            $order = Order::find($id);
    }
    public function companyFinancePdf( $id)
    {
        $company = Company::find($id);
        $title = 'شركة : '. $company->name;
        config(['pdf.format'                => 'A4']);
            $pdf1 = PDF::loadView('admin.reports.pdf.company-finance', compact('company', 'title'));
            return $pdf1->download('document.pdf');
    }
    public function CcompanyFinancePdf()
    {
        $company = auth('company')->user();
        $title = 'شركة : '. $company->name;
        config(['pdf.format'                => 'A4']);
            $pdf1 = PDF::loadView('admin.reports.pdf.company-finance', compact('company', 'title'));
            return $pdf1->download('document.pdf');
    }


    public function driverPdf( $id)
    {
        $driver = Driver::find($id);
        $title = 'سائق : '. $driver->name;
        config(['pdf.format'                => 'A4']);
            $pdf = PDF::loadView('admin.reports.pdf.driver', compact('driver', 'title'));
            return $pdf->download(\Str::slug($driver->first_name . ' ' . $driver->last_name).'.pdf');
    }

    public function driverFinancePdf( $id)
    {
        $driver = Driver::find($id);
        $title = 'سائق : '. $driver->name;
        $summary = $driver->Order()
                        ->select( \DB::raw('COUNT(*) as total, DATE_FORMAT(created_at, "%Y-%m") as filter'))
                        ->groupBy('filter')
                        ->get()->each(function($p)use ($driver){
                            $x =$p->filter ;
                            $y =substr($x,0,4) ;
                            $m =substr($x,5,7) ;
                            $delivered = $driver->Order()->where('status', 'delivered')->whereYear('created_at', $y)->whereMonth('created_at', $m)->count();
                            $p->delivered = $delivered;
                            $returned = $driver->Order()->where('status', 'returned')->whereYear('created_at', $y)->whereMonth('created_at', $m)->count();
                            $p->returned = $returned;
                            // $total = $driver->Order()->whereYear('created_at', $y)->whereMonth('created_at', $m)->count();
                            // $p->total = $total;
                        });
        // return $driver->Order()->where('status', 'delivered')->get()->groupBy(function($date) {
        //                                                 //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
        //                                                 return Carbon::parse($date->created_at)->format('Y m'); // grouping by months
        //                                             });
        config(['pdf.format'                => 'A4']);
        $pdf = PDF::loadView('admin.reports.pdf.driver-finance', compact('driver', 'title', 'summary'));
        return $pdf->download('document.pdf');
    }
    public function driverFinanceCollectPdf( $id)
    {
        $row = DriverFianance::findOrfail($id);
        $driver = $row->Driver()->first();
        $title = 'سائق : '. $driver->name;
        $orders = Order::whereIn('id', explode(',', $row->orders))->get();
        config(['pdf.format'                => 'A4']);
        $pdf = PDF::loadView('admin.reports.pdf.driver-finance-collect', compact('driver', 'title', 'row', 'orders'));
        return $pdf->download('document.pdf');
    }

    public function transferPdf( $id)
    {
        $transfer = Transfer::find($id);

        $invoices = $transfer->Invoice()->get();
        $title = 'فاتورة : '. $transfer->Company->name ?? '';
        config(['pdf.format'                => 'A4']);
            $pdf = PDF::loadView('admin.reports.pdf.transfer', compact('transfer', 'title', 'invoices'));
            return $pdf->download(str_slug($transfer->Company->name).'.pdf');
    }


    public function orderPdf( $id)
    {
        $order = Order::find($id);
        $title = 'طلب : '. $order->name;
        // return view('admin.reports.pdf.order', compact('order', 'title'));
            $pdf = PDF::loadView('admin.reports.pdf.order', compact('order', 'title'));
            return $pdf->download('invoice.pdf');
    }

    public function invoicePdf( $id)
    {
        $invoice = Invoice::find($id);
        $title = 'فاتورة : '. $invoice->name;
        config(['pdf.format'                => 'A4']);
            $pdf = PDF::loadView('admin.reports.pdf.invoice', compact('invoice', 'title'));
            return $pdf->download('invoice-'.$invoice->id.'.pdf');
    }
}
