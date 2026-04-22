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

        $arabicLabels = [
            'recipent_name' => 'اسم المستلم',
            'adress_details' => 'تفاصيل العنوان',
            'phone' => 'رقم الجوال',
            'notes' => 'ملاحظات',
            'city_id' => 'المدينة',
            'district_id' => 'المنطقة',
            'refrence_no' => 'الرقم المرجعي',
            'packages_number' => 'عدد الطرود',
            'price' => 'السعر',
            'payment_method_id' => 'طريقة الدفع',
            'include_delivery_cost' => 'يشمل تكلفة التوصيل',
            'can_open' => 'يسمح بالفتح',
            'description' => 'الوصف',
            'weight' => 'الوزن',
            'description' => 'الوصف',
            'weight' => 'الوزن',
        ];

        $headers = array_map(function ($field) use ($arabicLabels) {
            $label = $arabicLabels[$field] ?? $field;
            return sprintf('%s (%s)', $label, $field);
        }, $fields);
        // log header and fields to laravel log
        \Log::info('Headers and fields', ['headers' => $headers, 'fields' => $fields]);

        return [
            $headers,
            $hints,
        ];
    }
}
