<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiFormRequest;
use Auth;

class StoreCompanyRequest extends ApiFormRequest
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
            'name'          => 'required|max:255',
            'email'         => 'required|email|unique:companies|max:255',
            'phone'         => 'required|unique:companies|max:255',
            'commercial_record'         => 'required|unique:companies|max:255',
            'password'      => 'required|max:255|min:6',
            'image'         => 'nullable|image',
        ];
    }
}
