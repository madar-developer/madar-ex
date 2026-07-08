<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'key', 'category', 'channel', 'title', 'body', 'placeholders', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static function channelOptions(): array
    {
        return [
            'notification' => 'تنبيه داخلي',
            'sms' => 'رسالة SMS',
        ];
    }

    public static function categoryOptions(): array
    {
        return [
            'orders' => 'الطلبات',
            'companies' => 'المتاجر',
            'drivers' => 'السائقين',
            'cars' => 'السيارات',
            'users' => 'المستخدمين',
            'finance' => 'المالية',
            'general' => 'عام',
        ];
    }
}
