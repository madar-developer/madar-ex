<?php

namespace App\Http\Requests\Api\WhatsApp;

use App\Http\Requests\ApiFormRequest;
use App\Models\OrderStatus;
use Illuminate\Validation\Rule;

class SendOrdersRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        $company = auth('api-company')->user();

        return $company && (bool) $company->active;
    }

    public function rules(): array
    {
        $statusKeys = OrderStatus::query()->pluck('key')->all();

        return [
            'order_ids' => ['required', 'array', 'min:1', 'max:200'],
            'order_ids.*' => ['integer', 'distinct'],
            'status' => ['nullable', 'string', Rule::in($statusKeys)],
            'async' => ['nullable', 'boolean'],
        ];
    }
}
