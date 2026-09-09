<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CircularSend extends Model
{
    public const SOURCE_NOTIFICATION = 'notification';
    public const SOURCE_CIRCULAR = 'circular';

    protected $fillable = [
        'admin_id',
        'title',
        'description',
        'source',
        'sent_to_all_companies',
        'sent_to_all_drivers',
        'sent_to_all_admins',
    ];

    protected $casts = [
        'sent_to_all_companies' => 'boolean',
        'sent_to_all_drivers' => 'boolean',
        'sent_to_all_admins' => 'boolean',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(CircularSendRecipient::class);
    }

    public function companyRecipients(): HasMany
    {
        return $this->hasMany(CircularSendRecipient::class)->where('recipient_type', CircularSendRecipient::TYPE_COMPANY);
    }

    public function driverRecipients(): HasMany
    {
        return $this->hasMany(CircularSendRecipient::class)->where('recipient_type', CircularSendRecipient::TYPE_DRIVER);
    }

    public function adminRecipients(): HasMany
    {
        return $this->hasMany(CircularSendRecipient::class)->where('recipient_type', CircularSendRecipient::TYPE_ADMIN);
    }

    public function audienceSummary(): string
    {
        $parts = [];

        $companyCount = $this->company_recipients_count
            ?? ($this->relationLoaded('recipients')
                ? $this->recipients->where('recipient_type', CircularSendRecipient::TYPE_COMPANY)->count()
                : $this->companyRecipients()->count());
        $driverCount = $this->driver_recipients_count
            ?? ($this->relationLoaded('recipients')
                ? $this->recipients->where('recipient_type', CircularSendRecipient::TYPE_DRIVER)->count()
                : $this->driverRecipients()->count());
        $adminCount = $this->admin_recipients_count
            ?? ($this->relationLoaded('recipients')
                ? $this->recipients->where('recipient_type', CircularSendRecipient::TYPE_ADMIN)->count()
                : $this->adminRecipients()->count());

        if ($this->sent_to_all_companies) {
            $parts[] = 'كل المتاجر ('.$companyCount.')';
        } elseif ($companyCount > 0) {
            $parts[] = 'متاجر ('.$companyCount.')';
        }

        if ($this->sent_to_all_drivers) {
            $parts[] = 'كل السائقين ('.$driverCount.')';
        } elseif ($driverCount > 0) {
            $parts[] = 'سائقين ('.$driverCount.')';
        }

        if ($this->sent_to_all_admins) {
            $parts[] = 'كل الإدارة ('.$adminCount.')';
        } elseif ($adminCount > 0) {
            $parts[] = 'إدارة ('.$adminCount.')';
        }

        return $parts !== [] ? implode(' ، ', $parts) : 'بدون مستلمين';
    }
}
