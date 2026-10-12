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
            'whatsapp_enabled' => 'nullable|boolean',
            'whatsapp_template_name' => 'nullable|string|max:255',
            'whatsapp_template_id' => 'nullable|string|max:255',
            'whatsapp_language' => 'nullable|string|max:20',
            'whatsapp_variables' => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم القالب',
            'status' => 'حالة الطلب',
            'body' => 'نص الرسالة',
            'company_ids' => 'المتاجر',
            'whatsapp_template_name' => 'اسم قالب واتساب',
            'whatsapp_template_id' => 'معرّف قالب واتساب',
            'whatsapp_language' => 'لغة قالب واتساب',
            'whatsapp_variables' => 'متغيرات واتساب',
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

            if ($this->boolean('whatsapp_enabled') && ! $this->filled('whatsapp_template_name')) {
                $validator->errors()->add(
                    'whatsapp_template_name',
                    'اسم قالب واتساب مطلوب عند تفعيل واتساب'
                );
            }

            $rawVars = (string) $this->input('whatsapp_variables', '');
            if ($rawVars !== '') {
                $keys = array_values(array_filter(array_map('trim', preg_split('/[\s,]+/', $rawVars) ?: [])));
                $allowed = array_keys(MessageTemplate::placeholders());
                $invalid = array_values(array_diff($keys, $allowed));
                if ($invalid !== []) {
                    $validator->errors()->add(
                        'whatsapp_variables',
                        'متغيرات غير معروفة: '.implode(', ', $invalid)
                    );
                }
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
