<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
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
        $driver = $this->route('driver');
        return [
            'frist_name'          => 'max:255',
            'email'         => 'email|unique:drivers,email,'.$driver.'|max:255',
            'phone'         => 'unique:drivers,phone,'.$driver.'|max:255',
            'password'      => 'nullable|max:255',
            'image'         => 'nullable|image',
        ];
    }
}
