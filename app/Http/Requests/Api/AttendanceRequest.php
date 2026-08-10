<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ];
    }

    public function messages()
    {
        return [
            'latitude.required' => 'خط العرض مطلوب',
            'longitude.required' => 'خط الطول مطلوب',
        ];
    }
}
