<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'max:255',
            // 'email'         => 'email|unique:companies,email,'.$company->id.'|max:255',
            // 'phone'         => 'unique:companies,phone,'.$company->id.'|max:255',
            // 'password'      => 'confirmed|max:255',
            // 'image'         => 'nullable|image',
        ];
    }
}
