<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SentNotification extends Model
{
    public const TYPE_ADMIN = 'admin';

    public const TYPE_COMPANY = 'company';

    public const TYPE_DRIVER = 'driver';

    protected $fillable = [
        'title',
        'description',
        'type',
        'days_count',
    ];

    protected $casts = [
        'days_count' => 'integer',
    ];

    public function scopeActiveForDriver(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_DRIVER)
            ->where(function (Builder $q) {
                $q->where('days_count', 0)
                    ->orWhereRaw('DATE_ADD(created_at, INTERVAL days_count DAY) >= ?', [now()]);
            })
            ->latest();
    }

    public function scopeActiveForCompany(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_COMPANY)
            ->where(function (Builder $q) {
                $q->where('days_count', 0)
                    ->orWhereRaw('DATE_ADD(created_at, INTERVAL days_count DAY) >= ?', [now()]);
            })
            ->latest();
    }
}
