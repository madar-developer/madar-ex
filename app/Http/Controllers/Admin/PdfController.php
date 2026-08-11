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
use Illuminate\Support\Str;
use PDF;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function companyPdf( $id)
    {
        $company = Company::find($id);
        $title = 'شركة : '. $company->name;
        config(['pdf.format'                => 'A4']);
        ini_set('pcre.backtrack_limit', '5000000');
ini_set('memory_limit', '512M');
            $pdf1 = PDF::loadView('admin.reports.pdf.company', compact('company', 'title'));
            return $pdf1->download('document.pdf');
            $order = Order::find($id);
    }
    public function companyFinancePdf( $id)
    {
        $company = Company::find($id);
        $title = 'شركة : '. $company->name;
        ini_set('pcre.backtrack_limit', '5000000');
ini_set('memory_limit', '512M');
        config(['pdf.format'                => 'A4']);
            $pdf1 = PDF::loadView('admin.reports.pdf.company-finance', compact('company', 'title'));
            return $pdf1->download('document.pdf');
    }
    public function CcompanyFinancePdf()
    {
        $company = auth('company')->user();
        $title = 'شركة : '. $company->name;
        ini_set('pcre.backtrack_limit', '5000000');
ini_set('memory_limit', '512M');
        config(['pdf.format'                => 'A4']);
            $pdf1 = PDF::loadView('admin.reports.pdf.company-finance', compact('company', 'title'));
            return $pdf1->download('document.pdf');
    }


    public function driverPdf( $id)
    {
        $driver = Driver::find($id);
        $title = 'سائق : '. $driver->name;
        ini_set('pcre.backtrack_limit', '5000000');
ini_set('memory_limit', '512M');
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
        ini_set('pcre.backtrack_limit', '5000000');
ini_set('memory_limit', '512M');
        $pdf = PDF::loadView('admin.reports.pdf.driver-finance', compact('driver', 'title', 'summary'));
        return $pdf->download('document.pdf');
    }
    public function driverFinanceCollectPdf( $id)
    {
        $row = DriverFianance::with('Driver')->findOrfail($id);
        $driver = $row->Driver;
        $title = 'سائق : '. $driver->name;
        $orders = Order::whereIn('id', explode(',', $row->orders))
            ->with(['Company', 'PaymentMethod', 'City.Parent', 'Driver', 'Invoice'])
            ->get();
        config(['pdf.format'                => 'A4']);ini_set('pcre.backtrack_limit', '5000000');
ini_set('memory_limit', '512M');
        $pdf = PDF::loadView('admin.reports.pdf.driver-finance-collect', compact('driver', 'title', 'row', 'orders'));
        return $pdf->download('document.pdf');
    }

    public function transferPdf( $id)
    {
        $transfer = Transfer::find($id);

        $invoices = $transfer->Invoice()->get();
        $title = 'فاتورة : '. $transfer->Company->name ?? '';
        config(['pdf.format'                => 'A4']);
        ini_set('pcre.backtrack_limit', '5000000');
ini_set('memory_limit', '512M');
            $pdf = PDF::loadView('admin.reports.pdf.transfer', compact('transfer', 'title', 'invoices'));
            return $pdf->download(Str::slug($transfer->Company->name).'.pdf');
    }


    public function orderPdf( $id)
    {
        $order = Order::find($id);
        $title = 'طلب : '. $order->name;
        // return view('admin.reports.pdf.order', compact('order', 'title'));
        ini_set('pcre.backtrack_limit', '5000000');
ini_set('memory_limit', '512M');
            $pdf = PDF::loadView('admin.reports.pdf.order', compact('order', 'title'));
            return $pdf->download('invoice.pdf');
    }

    public function invoicePdf( $id)
    {
        $invoice = Invoice::find($id);
        $title = 'فاتورة : '. $invoice->name;
        config(['pdf.format'                => 'A4']);
        ini_set('pcre.backtrack_limit', '5000000');
ini_set('memory_limit', '512M');
            $pdf = PDF::loadView('admin.reports.pdf.invoice', compact('invoice', 'title'));
            return $pdf->download('invoice-'.$invoice->id.'.pdf');
    }

    public function exportSelectedOrdersPdf(Request $request)
    {
        $ids = array_filter((array) $request->input('ids', []));
        if (empty($ids)) {
            return redirect()->back()->with('error', 'يرجى تحديد طلب واحد على الأقل للتصدير');
        }

        $orders = Order::whereIn('id', $ids)
            ->with([
                'Company',
                'PaymentMethod',
                'OrderLog' => function ($q) {
                    $q->where('status', 'deliver_failed')->latest();
                },
                'OrderLog.ReasonD',
            ])
            ->orderBy('company_id')
            ->orderBy('id')
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'لم يتم العثور على الطلبات المحددة');
        }

        $groups = $orders->groupBy('company_id')->map(function ($companyOrders) {
            $first = $companyOrders->first();
            $companyName = $first->Company->name ?? '';

            return [
                'company_name' => $companyName,
                'date' => Carbon::now()->format('j F Y'),
                'orders' => $companyOrders->values()->map(function ($order, $index) {
                    $paymentLabel = ((int) $order->payment_method_id === 1)
                        ? 'الدفع عند الاستلام'
                        : 'مدفوع';

                    $failedLog = $order->OrderLog->first();
                    $reasonText = '';
                    if ($failedLog && $failedLog->ReasonD && $failedLog->ReasonD->description) {
                        $reasonText = 'تعذر التسليم , ' . $failedLog->ReasonD->description;
                    } elseif ($order->status === 'deliver_failed') {
                        $reasonText = 'تعذر التسليم';
                    } elseif ($order->status === 'returned') {
                        $reasonText = 'تم ارجاع الطلب للتاجر';
                    } else {
                        $reasonText = $order->status_txt ?: '-';
                    }

                    return [
                        'index' => $index + 1,
                        'customer' => $order->recipent_name ?: '-',
                        'order_no' => $order->refrence_no ,
                        'serial' => $order->serial ,
                        'payment' => $paymentLabel,
                        'reason' => $reasonText,
                        // 'notes' => !empty($order->notes) ? $order->notes : '-',
                        'date' => $order->created_at ? $order->created_at->format('Y-m-d') : '',
                    ];
                }),
            ];
        })->values();

        $title = 'الطلبات المرتجعه';
        $letterhead = public_path('adminto/assets/images/official-letterhead.jpg');

        ini_set('pcre.backtrack_limit', '5000000');
        ini_set('memory_limit', '512M');

        $pdf = PDF::loadView('admin.reports.pdf.returned-orders', compact('groups', 'title'), [], [
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 12,
            'margin_right' => 12,
            'margin_top' => 42,
            'margin_bottom' => 42,
            'margin_header' => 0,
            'margin_footer' => 0,
            'instanceConfigurator' => function ($mpdf) use ($letterhead) {
                if (is_file($letterhead)) {
                    $mpdf->SetWatermarkImage($letterhead, 1, 'P', 'P');
                    $mpdf->showWatermarkImage = true;
                    $mpdf->watermarkImgBehind = true;
                }
                $mpdf->autoScriptToLang = true;
                $mpdf->autoLangToFont = true;
            },
        ]);

        Order::whereIn('id', $orders->pluck('id'))->update([
            'returned_pdf_exported_at' => Carbon::now(),
        ]);

        return $pdf->download('returned-orders-' . Carbon::now()->toDateString() . '.pdf');
    }
}
