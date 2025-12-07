<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiFormRequest;
use Auth;

class UpdateCompanyRequest extends ApiFormRequest
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
        $id = Auth::guard('api-company')->id();
        return [
            'name'          => 'max:255',
            'email'         => 'nullable|email|unique:companies,email,'.$id.'|max:255',
            'phone'         => 'unique:companies,phone,'.$id.'|max:255',
            // 'password'      => 'max:255|min:6',
            // 'image'         => 'nullable|image',
        ];
    }
}
