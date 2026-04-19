<?php

namespace App\Http\Requests\Admin;

use App\Models\Circular;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCircularRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', 'string', Rule::in([
                Circular::TYPE_ADMIN,
                Circular::TYPE_COMPANY,
                Circular::TYPE_DRIVER,
            ])],
            'days_count' => 'required|integer|min:0',
        ];
    }
}
