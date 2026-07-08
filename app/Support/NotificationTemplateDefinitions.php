<?php

namespace App\Support;

class NotificationTemplateDefinitions
{
    public static function all(): array
    {
        return [
            [
                'key' => 'order.created',
                'category' => 'orders',
                'channel' => 'notification',
                'title' => 'طلب جديد',
                'body' => 'تم اضافة طلب جديد : {order_id}',
                'placeholders' => '{order_id}',
            ],
            [
                'key' => 'order.status_changed',
                'category' => 'orders',
                'channel' => 'notification',
                'title' => 'تغيير حالة الطلب',
                'body' => 'تم تغيير حالة الطلب  : {order_id} الي {status}',
                'placeholders' => '{order_id}, {status}',
            ],
            [
                'key' => 'order.sms.out_for_delivery',
                'category' => 'orders',
                'channel' => 'sms',
                'title' => 'خروج الطلب للتوصيل',
                'body' => 'مرحبا {recipient_name} ، شحنتك {serial} من {company_name} في طريقها إليك وسيتم التواصل معكم عند اتجاه المندوب للعنوان',
                'placeholders' => '{recipient_name}, {serial}, {company_name}',
            ],
            [
                'key' => 'order.sms.out_for_delivery_short',
                'category' => 'orders',
                'channel' => 'sms',
                'title' => 'خروج الطلب (مختصر)',
                'body' => 'تم خروج الطلب رقم {serial} من المتجر و جاري توصيلها اليكم.',
                'placeholders' => '{serial}',
            ],
            [
                'key' => 'order.reschedule',
                'category' => 'orders',
                'channel' => 'notification',
                'title' => 'إعادة جدولة الطلب',
                'body' => 'تم تغيير حالة الطلب  : {order_id} الي {status}',
                'placeholders' => '{order_id}, {status}',
            ],
            [
                'key' => 'company.created',
                'category' => 'companies',
                'channel' => 'notification',
                'title' => 'متجر جديد',
                'body' => 'تم اضافة شركه  : {company_name}',
                'placeholders' => '{company_name}',
            ],
            [
                'key' => 'driver.created',
                'category' => 'drivers',
                'channel' => 'notification',
                'title' => 'سائق جديد',
                'body' => 'تم اضافة سائق  : {driver_name}',
                'placeholders' => '{driver_name}',
            ],
            [
                'key' => 'driver.updated',
                'category' => 'drivers',
                'channel' => 'notification',
                'title' => 'تحديث سائق',
                'body' => 'تم تعديل بيانات السائق  : {driver_name}',
                'placeholders' => '{driver_name}',
            ],
            [
                'key' => 'car.created',
                'category' => 'cars',
                'channel' => 'notification',
                'title' => 'سيارة جديدة',
                'body' => 'تم اضافة سيارة  : {car_name}',
                'placeholders' => '{car_name}',
            ],
            [
                'key' => 'car.updated',
                'category' => 'cars',
                'channel' => 'notification',
                'title' => 'تحديث سيارة',
                'body' => 'تم تعديل بيانات السيارة  : {car_name}',
                'placeholders' => '{car_name}',
            ],
            [
                'key' => 'car.odometer_changed',
                'category' => 'cars',
                'channel' => 'notification',
                'title' => 'تحديث عداد السيارة',
                'body' => 'تم تغيير عدد الكيلو مترات للسياره : {car_name}',
                'placeholders' => '{car_name}',
            ],
            [
                'key' => 'user.created',
                'category' => 'users',
                'channel' => 'notification',
                'title' => 'مستخدم جديد',
                'body' => 'تم اضافة مستخدم  : {user_name}',
                'placeholders' => '{user_name}',
            ],
            [
                'key' => 'transfer.created',
                'category' => 'finance',
                'channel' => 'notification',
                'title' => 'حوالة جديدة',
                'body' => 'تم انشاء حوالة جديده لمتجرك.',
                'placeholders' => '',
            ],
            [
                'key' => 'car_maintenance.created',
                'category' => 'cars',
                'channel' => 'notification',
                'title' => 'قسم صيانة',
                'body' => 'تم اضافة قسم صيانه  : {name}',
                'placeholders' => '{name}',
            ],
            [
                'key' => 'transaction.payment_accepted',
                'category' => 'finance',
                'channel' => 'notification',
                'title' => 'قبول الدفع',
                'body' => 'تم قبول الدفع .',
                'placeholders' => '',
            ],
            [
                'key' => 'transaction.payment_accepted_detail',
                'category' => 'finance',
                'channel' => 'notification',
                'title' => 'تفاصيل قبول الدفع',
                'body' => 'تم قبول الدفع رقم : {transaction_id}',
                'placeholders' => '{transaction_id}',
            ],
            [
                'key' => 'account.approved',
                'category' => 'general',
                'channel' => 'notification',
                'title' => 'موافقة على الحساب',
                'body' => 'تم الموافقة علي الحساب الخاص بك',
                'placeholders' => '',
            ],
        ];
    }
}
