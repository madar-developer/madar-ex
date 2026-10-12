<?php

namespace App\Http\Requests\Api\WhatsApp;

use App\Http\Requests\ApiFormRequest;

class SendTemplateRequest extends ApiFormRequest
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
            'template_name' => ['required', 'string', 'max:100'],
            'language' => ['nullable', 'string', 'max:20'],
            'components' => ['nullable', 'array'],
            'async' => ['nullable', 'boolean'],
            'idempotency_key' => ['nullable', 'string', 'max:100'],
        ];
    }
}
