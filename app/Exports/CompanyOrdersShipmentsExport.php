<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\Order;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompanyOrdersShipmentsExport
{
    /**
     * Build an .xlsx matching the column layout and formulas of the standard shipments export
     * (Receiver / shipment & invoice refs / dates / COD / location / payment / status / delivery fee + 15% tax).
     */
    public static function downloadResponse(Company $company, Collection $orders): BinaryFileResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Receiver Name',
            'Shipment No.',
            'Invoice No.',
            'Order Date',
            'COD Amount',
            'Receiver Location',
            'Sender Receive cash',
            'Shipment status',
            'قيمة التوصيل',
            'الضريبة',
            'اجمالي التوصيل',
            'م',
        ];
        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        $seq = 1;
        foreach ($orders as $order) {
            /** @var Order $order */
            $sheet->setCellValue('A'.$row, $order->recipent_name);
            $sheet->setCellValue('B'.$row, $order->serial ?? '');
            $sheet->setCellValue('C'.$row, $order->refrence_no ?? '');
            $sheet->setCellValue(
                'D'.$row,
                $order->created_at ? $order->created_at->format('Y-m-d') : '-'
            );
            $sheet->setCellValue('E'.$row, $order->price !== null && $order->price !== '' ? (float) $order->price : 0);
            $sheet->setCellValue('F'.$row, self::cityLabel($order));
            $sheet->setCellValue('G'.$row, self::paymentLabel($order));
            $sheet->setCellValue('H'.$row, self::statusEnglish($order->status));
            $fee = optional($order->Invoice)->madar_price;
            $sheet->setCellValue('I'.$row, $fee !== null && $fee !== '' ? (float) $fee : 0);
            $sheet->setCellValue('J'.$row, '=I'.$row.'*15%');
            $sheet->setCellValue('K'.$row, '=J'.$row.'+I'.$row);
            $sheet->setCellValue('L'.$row, $seq);
            $row++;
            $seq++;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'ship');
        if ($tmp === false) {
            abort(500, 'Could not create temp file');
        }
        $path = $tmp.'.xlsx';
        rename($tmp, $path);

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        $date = now()->format('Y-m-d');
        $filename = 'shipments-company-'.$company->id.'-'.$date.'.xlsx';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private static function cityLabel(Order $order): string
    {
        $city = $order->City;
        if (! $city) {
            return '';
        }
        $en = $city->getTranslation('name', 'en', false);
        if ($en) {
            return $en;
        }
        $ar = $city->getTranslation('name', 'ar', false);
        if ($ar) {
            return $ar;
        }

        return (string) $city->name;
    }

    private static function paymentLabel(Order $order): string
    {
        $pm = $order->PaymentMethod;
        if (! $pm) {
            return '';
        }
        $ar = $pm->getTranslation('name', 'ar', false);
        if ($ar) {
            return $ar;
        }

        return (string) $pm->name;
    }

    private static function statusEnglish(string $key): string
    {
        return match ($key) {
            'new' => 'New',
            'not_received' => 'Not received',
            'init' => 'Init',
            'at_madar' => 'At Madar',
            'at_office' => 'At office',
            'reschedule' => 'Reschedule',
            'deliver_failed' => 'Delivery failed',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'returned' => 'Returned',
            default => ucfirst(str_replace('_', ' ', $key)),
        };
    }
}
