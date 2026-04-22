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

        $headers = array_map(function ($field) {
            $fieldKey = strtolower(trim((string) $field));
            $normalizedFieldKey = preg_replace('/[^a-z0-9_]/', '', $fieldKey) ?? $fieldKey;

            $label = match (true) {
                str_contains($normalizedFieldKey, 'recipent_name') => 'اسم المستلم',
                str_contains($normalizedFieldKey, 'adress_details') => 'تفاصيل العنوان',
                str_contains($normalizedFieldKey, 'phone') => 'رقم الجوال',
                str_contains($normalizedFieldKey, 'notes') => 'ملاحظات',
                str_contains($normalizedFieldKey, 'city_id') => 'المدينة',
                str_contains($normalizedFieldKey, 'district_id') => 'المنطقة',
                str_contains($normalizedFieldKey, 'refrence_no') => 'الرقم المرجعي',
                str_contains($normalizedFieldKey, 'packages_number') => 'عدد الطرود',
                str_contains($normalizedFieldKey, 'price') => 'السعر',
                str_contains($normalizedFieldKey, 'payment_method_id') => 'طريقة الدفع',
                str_contains($normalizedFieldKey, 'include_delivery_cost') => 'يشمل تكلفة التوصيل',
                str_contains($normalizedFieldKey, 'can_open') => 'يسمح بالفتح',
                str_contains($normalizedFieldKey, 'description') => 'الوصف',
                str_contains($normalizedFieldKey, 'weight') => 'الوزن',
                default => $field,
            };
            return sprintf('%s (%s)', $label, $field);
        }, $fields);

        return [
            $headers,
            $hints,
        ];
    }
}
