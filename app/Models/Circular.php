<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Circular extends Model
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

    public static function typeLabels(): array
    {
        return [
            self::TYPE_ADMIN => 'الإدارة',
            self::TYPE_COMPANY => 'الشركة / المتجر',
            self::TYPE_DRIVER => 'السائق',
        ];
    }

    /**
     * Admin circulars that should still be shown (days_count = 0 means no expiry).
     */
    public function scopeActiveForAdmin(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_ADMIN)
            ->where(function (Builder $q) {
                $q->where('days_count', 0)
                    ->orWhereRaw('DATE_ADD(created_at, INTERVAL days_count DAY) >= ?', [now()]);
            })
            ->latest();
    }

    /**
     * Company circulars that should still be shown (days_count = 0 means no expiry).
     */
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
