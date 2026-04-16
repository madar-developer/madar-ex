<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\OrderStatus;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrderImport implements ToModel, WithHeadingRow
{
    protected int $companyId;

    public function __construct(int $companyId)
    {
        $this->companyId = $companyId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $recipientName = trim((string) ($row['recipent_name'] ?? ''));
        $phone = trim((string) ($row['phone'] ?? ''));
        if ($recipientName === '' && $phone === '') {
            return null;
        }

        $city = $row['city_id'] ?? null;
        $district = $row['district_id'] ?? null;
        $city = $city !== null && $city !== '' ? (int) $city : null;

        $paymentMethod = $this->parsePaymentMethod($row['payment_method_id'] ?? null);

        $packagesNumber = $row['packages_number'] ?? null;
        $packagesNumber = $packagesNumber !== null && $packagesNumber !== '' ? (int) $packagesNumber : null;

        $price = $row['price'] ?? null;
        $price = $price !== null && $price !== '' ? (float) $price : 0;

        $Order =  Order::create([
            'recipent_name' => $recipientName,
            'adress_details'  => $row['adress_details'] ?? null,
            'phone' => $phone,
            'notes'  => $row['notes'] ?? null,
            'city_id'  => $city,
            'district_id' => $district,
            'refrence_no' => $row['refrence_no'] ?? null,
            'packages_number'  => $packagesNumber,
            'price'   => $price,
            'payment_method_id'  => $paymentMethod,
            'include_delivery_cost' => $this->parseYesNoFlag($row['include_delivery_cost'] ?? null),
            'can_open' => $this->parseYesNoFlag($row['can_open'] ?? null),
            'company_id'  => $this->companyId,
            'status'  => 'new',

        ]);
        $s = str_replace(' ', '',date('Y m').$Order->id);
        $serial = 'mx-'.$s;
        $status_data = OrderStatus::where('key', 'new')->first();
            $log_data = [
                'status' => 'new',
                'details' => $status_data->details
                // 'details' =>  trans('words.'.$request->get('status')) . ' , ' . $request->get('notes')
            ];
        $Order->OrderLog()->create($log_data);
        $Order->update(['serial' => $serial, 'serial_no' => (int)$s]);
        return $Order;
    }

    private function parsePaymentMethod($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        $v = strtoupper(trim((string) $value));
        return match ($v) {
            'COD' => 1,
            'PAID' => 4,
            'BT' => 5,
            default => null,
        };
    }

    private function parseYesNoFlag($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return ((int) $value) === 1 ? 1 : 0;
        }

        $v = trim((string) $value);
        $vLower = function_exists('mb_strtolower') ? mb_strtolower($v, 'UTF-8') : strtolower($v);

        if (in_array($vLower, ['نعم', 'yes', 'y', 'true', '1'], true)) {
            return 1;
        }
        if (in_array($vLower, ['لا', 'no', 'n', 'false', '0'], true)) {
            return 0;
        }

        return null;
    }
}
