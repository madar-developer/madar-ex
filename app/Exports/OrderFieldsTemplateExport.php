<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromArray;

class OrderFieldsTemplateExport implements FromArray
{
    public function array(): array
    {
        $excluded = [
            'user_id',
            'company_id',
            'driver_id',
            'status',
            'serial',
            'serial_no',
            'collected',
            'signature',
            'latitude',
            'longitude',
            'receive_date',
            'delivery_date',
            'company_address_id',
            // 'district_id',
            'pick_up_date',
            'cash_type',
            'order_type',
            'return_packages',
            'refrence_no_repeated',
        ];

        $fields = array_values(array_filter(
            array_unique((new Order())->getFillable()),
            fn ($field) => !in_array($field, $excluded, true)
        ));

        $hints = array_map(function ($field) {
            return match ($field) {
                'payment_method_id' => 'COD=1 | PAID=4 | BT=5',
                'include_delivery_cost' => 'نعم=1 | لا=0',
                'can_open' => 'نعم=1 | لا=0',
                default => '',
            };
        }, $fields);

        return [
            $fields,
            $hints,
        ];
    }
}
