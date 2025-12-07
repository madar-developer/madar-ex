<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
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
            'frist_name'          => 'requird|max:255',
            'email'         => 'email|unique:drivers|max:255',
            'phone'         => 'required|unique:drivers|max:255',
            'password'      => 'nullable|max:255',
            'image'         => 'nullable|image',
        ];
    }
}
