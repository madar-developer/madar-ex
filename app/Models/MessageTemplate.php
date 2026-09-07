<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MessageTemplate extends Model
{
    protected $fillable = [
        'name', 'status', 'body', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static function placeholders(): array
    {
        return [
            'recipient_name' => 'اسم المستلم',
            'serial' => 'رقم الشحنة',
            'refrence_no' => 'رقم المرجع',
            'company_name' => 'اسم المتجر',
            'order_id' => 'رقم الطلب',
            'status' => 'الحالة',
            'phone' => 'جوال المستلم',
            'address' => 'العنوان',
        ];
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'message_template_company');
    }

    public static function forCompanyStatus(?int $companyId, string $status): ?self
    {
        if (!$companyId) {
            return null;
        }

        return static::query()
            ->where('status', $status)
            ->where('active', true)
            ->whereHas('companies', function ($q) use ($companyId) {
                $q->where('companies.id', $companyId);
            })
            ->latest('id')
            ->first();
    }

    public static function overlappingCompanyIds(string $status, array $companyIds, ?int $exceptId = null): array
    {
        $companyIds = array_values(array_filter(array_map('intval', $companyIds)));
        if ($companyIds === [] || $status === '') {
            return [];
        }

        return static::query()
            ->when($exceptId, function ($q) use ($exceptId) {
                $q->where('id', '<>', $exceptId);
            })
            ->where('status', $status)
            ->whereHas('companies', function ($q) use ($companyIds) {
                $q->whereIn('companies.id', $companyIds);
            })
            ->with(['companies' => function ($q) use ($companyIds) {
                $q->whereIn('companies.id', $companyIds);
            }])
            ->get()
            ->pluck('companies')
            ->flatten()
            ->pluck('name', 'id')
            ->all();
    }

    public function render(Order $order, string $status): string
    {
        $replace = [
            'recipient_name' => $order->recipent_name,
            'serial' => $order->serial,
            'refrence_no' => $order->refrence_no ?: $order->serial,
            'company_name' => optional($order->Company)->name,
            'order_id' => $order->id,
            'status' => trans('words.'.$status),
            'phone' => $order->phone,
            'address' => $order->adress_details,
        ];

        $body = $this->body;
        foreach ($replace as $key => $value) {
            $body = str_replace('{'.$key.'}', (string) $value, $body);
        }

        return $body;
    }
}
