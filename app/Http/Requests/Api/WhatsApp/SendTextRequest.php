<?php

namespace App\Http\Requests\Api\WhatsApp;

use App\Http\Requests\ApiFormRequest;

class SendTextRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        $company = auth('api-company')->user();

        return $company && (bool) $company->active;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:4096'],
            'async' => ['nullable', 'boolean'],
            'idempotency_key' => ['nullable', 'string', 'max:100'],
        ];
    }
}
