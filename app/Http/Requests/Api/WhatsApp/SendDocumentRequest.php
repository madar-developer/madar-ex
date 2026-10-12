<?php

namespace App\Http\Requests\Api\WhatsApp;

use App\Http\Requests\ApiFormRequest;

class SendDocumentRequest extends ApiFormRequest
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
            'document_url' => ['required', 'url', 'max:2048'],
            'filename' => ['nullable', 'string', 'max:255'],
            'async' => ['nullable', 'boolean'],
            'idempotency_key' => ['nullable', 'string', 'max:100'],
        ];
    }
}
