<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SallaOrderLog extends Model
{
    protected $table = 'salla_order_logs';

    protected $fillable = [
        'order_id',
        'direction',
        'action',
        'http_status',
        'success',
        'salla_status',
        'shipment_id',
        'tracking_number',
        'message',
        'payload',
    ];

    protected $casts = [
        'success' => 'boolean',
        'payload' => 'array',
        'http_status' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function actionLabel(): string
    {
        return match ($this->action) {
            'shipment.update_status' => 'تحديث حالة الشحنة',
            'shipment.details' => 'تفاصيل الشحنة',
            'shipment.update' => 'تحديث الشحنة',
            'order.details' => 'تفاصيل الطلب',
            'order.create' => 'إنشاء طلب',
            'order.list' => 'قائمة الطلبات',
            'order.actions' => 'إجراء على الطلب',
            'order.status' => 'تحديث حالة الطلب',
            'webhook.order' => 'Webhook طلب',
            'webhook.shipment.creating' => 'Webhook إنشاء شحنة',
            'webhook.shipment.updated' => 'Webhook تحديث شحنة',
            'webhook.cancel' => 'Webhook إلغاء',
            default => $this->action ?: '-',
        };
    }
}
