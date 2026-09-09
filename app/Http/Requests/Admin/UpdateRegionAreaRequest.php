<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegionAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->coordinates)) {
            $this->merge([
                'coordinates' => json_decode($this->coordinates, true) ?: [],
            ]);
        }
    }

    public function rules(): array
    {
        $id = $this->route('region_area');

        return [
            'title' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('region_areas', 'code')->ignore($id),
            ],
            'coordinates' => 'required|array|min:3',
            'coordinates.*.lat' => 'required|numeric|between:-90,90',
            'coordinates.*.lng' => 'required|numeric|between:-180,180',
            'active' => 'nullable',
        ];
    }
}
