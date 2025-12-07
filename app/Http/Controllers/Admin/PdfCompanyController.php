<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Transfer;
use App\Models\Order;
use App\Models\Invoice;
use PDF;

class PdfCompanyController extends Controller
{


    public function invoicePdf( $id)
    {
        $invoice = Invoice::find($id);
        $title = 'فاتورة : '. $invoice->name;
            $pdf = PDF::loadView('company.reports.pdf.invoice', compact('invoice', 'title'));
            return $pdf->download('document.pdf');
    }

    public function transferPdf( $id)
    {
        $transfer = Transfer::find($id);
        $title = 'حواله : '. $transfer->name;
            $pdf = PDF::loadView('company.reports.pdf.transfer', compact('transfer', 'title'));
            return $pdf->download('document.pdf');
    }
}
