<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CircularSendRecipient extends Model
{
    public const TYPE_COMPANY = 'company';
    public const TYPE_DRIVER = 'driver';
    public const TYPE_ADMIN = 'admin';

    protected $fillable = [
        'circular_send_id',
        'recipient_type',
        'recipient_id',
        'recipient_name',
    ];

    public function send(): BelongsTo
    {
        return $this->belongsTo(CircularSend::class, 'circular_send_id');
    }

    public static function typeLabels(): array
    {
        return [
            self::TYPE_COMPANY => 'متجر',
            self::TYPE_DRIVER => 'سائق',
            self::TYPE_ADMIN => 'إدارة',
        ];
    }
}
