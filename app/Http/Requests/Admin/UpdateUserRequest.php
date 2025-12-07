<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $user = $this->route('user');
        return [
            'user_name'          => 'max:255',
            'e_mail'         => 'email|unique:users_1,email,'.$user->id.'|max:255',
            'mobile_number'         => 'unique:users_1,phone,'.$user->id.'|max:255',
            'password'      => 'confirmed|max:255',
            'profile_image'         => 'nullable|image',
        ];
    }
}
