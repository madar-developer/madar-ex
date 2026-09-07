<?php

namespace App\Http\Requests\Admin;

use App\Models\MessageTemplate;
use App\Models\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreMessageTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statusKeys = OrderStatus::query()->pluck('key')->all();

        return [
            'name' => 'required|string|max:255',
            'status' => ['required', 'string', Rule::in($statusKeys)],
            'body' => 'required|string',
            'company_ids' => 'required|array|min:1',
            'company_ids.*' => 'integer|exists:companies,id',
            'active' => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم القالب',
            'status' => 'حالة الطلب',
            'body' => 'نص الرسالة',
            'company_ids' => 'المتاجر',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $overlapping = MessageTemplate::overlappingCompanyIds(
                (string) $this->input('status'),
                (array) $this->input('company_ids', []),
                $this->exceptTemplateId()
            );

            if ($overlapping !== []) {
                $names = implode('، ', array_values($overlapping));
                $validator->errors()->add(
                    'company_ids',
                    'هذه المتاجر لديها قالب آخر لنفس الحالة: '.$names
                );
            }
        });
    }

    protected function exceptTemplateId(): ?int
    {
        $param = $this->route('message_template');
        if ($param instanceof MessageTemplate) {
            return (int) $param->id;
        }

        return $param ? (int) $param : null;
    }
}
