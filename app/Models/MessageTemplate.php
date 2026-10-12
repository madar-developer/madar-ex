<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MessageTemplate extends Model
{
    protected $fillable = [
        'name',
        'status',
        'body',
        'active',
        'whatsapp_enabled',
        'whatsapp_template_name',
        'whatsapp_template_id',
        'whatsapp_language',
        'whatsapp_variables',
    ];

    protected $casts = [
        'active' => 'boolean',
        'whatsapp_enabled' => 'boolean',
        'whatsapp_variables' => 'array',
    ];

    public static function placeholders(): array
    {
        return [
            'recipient_name' => 'اسم المستلم',
            'serial' => 'رقم الشحنة',
            'refrence_no' => 'رقم الطلب',
            'company_name' => 'اسم المتجر',
            'status' => 'الحالة',
            'phone' => 'جوال المستلم',
            'address' => 'العنوان',
            'payment_method' => 'طريقة الدفع',
            'amount' => 'المبلغ',
        ];
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'message_template_company');
    }

    public static function forCompanyStatus(?int $companyId, string $status): ?self
    {
        if (! $companyId) {
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

    /**
     * Active company+status template that is configured for WhatsApp Cloud API.
     */
    public static function forCompanyStatusWhatsApp(?int $companyId, string $status): ?self
    {
        if (! $companyId) {
            return null;
        }

        return static::query()
            ->where('status', $status)
            ->where('active', true)
            ->where('whatsapp_enabled', true)
            ->whereNotNull('whatsapp_template_name')
            ->where('whatsapp_template_name', '!=', '')
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

    public function supportsWhatsApp(): bool
    {
        return (bool) $this->whatsapp_enabled
            && filled($this->whatsapp_template_name);
    }

    /**
     * Resolve placeholder values for an order.
     *
     * @return array<string, string>
     */
    public function placeholderValues(Order $order, ?string $status = null): array
    {
        $order->loadMissing(['Company', 'PaymentMethod']);
        $status = $status ?: (string) $order->status;

        return [
            'recipient_name' => (string) ($order->recipent_name ?: '-'),
            'serial' => (string) ($order->serial ?: '-'),
            'refrence_no' => (string) ($order->refrence_no ?: $order->serial ?: '-'),
            'company_name' => (string) (optional($order->Company)->name ?: '-'),
            'order_id' => (string) $order->id,
            'status' => (string) (trans('words.'.$status) ?: $status),
            'phone' => (string) ($order->phone ?: '-'),
            'address' => (string) ($order->adress_details ?: '-'),
            'payment_method' => (string) (optional($order->PaymentMethod)->name ?: '-'),
            'amount' => number_format((float) $order->price, 2, '.', ''),
        ];
    }

    /**
     * Ordered WhatsApp body variable keys configured on this template.
     *
     * @return list<string>
     */
    public function whatsappVariableKeys(): array
    {
        $vars = $this->whatsapp_variables;

        if (is_string($vars)) {
            $vars = array_filter(array_map('trim', explode(',', $vars)));
        }

        if (! is_array($vars)) {
            return [];
        }

        return array_values(array_filter(array_map(static function ($key) {
            return is_string($key) ? trim($key) : '';
        }, $vars)));
    }

    /**
     * Build Meta Cloud API template components from configured variables.
     *
     * @return list<array<string, mixed>>
     */
    public function buildWhatsAppComponents(Order $order, ?string $status = null): array
    {
        $keys = $this->whatsappVariableKeys();
        if ($keys === []) {
            return [];
        }

        $values = $this->placeholderValues($order, $status);
        $parameters = [];

        foreach ($keys as $key) {
            $parameters[] = [
                'type' => 'text',
                'text' => (string) ($values[$key] ?? '-'),
            ];
        }

        return [
            [
                'type' => 'body',
                'parameters' => $parameters,
            ],
        ];
    }

    public function render(Order $order, string $status): string
    {
        $replace = $this->placeholderValues($order, $status);
        $body = (string) $this->body;

        foreach ($replace as $key => $value) {
            $body = str_replace('{'.$key.'}', (string) $value, $body);
        }

        return $body;
    }
}
