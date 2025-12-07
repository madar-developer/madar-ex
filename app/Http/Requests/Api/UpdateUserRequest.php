<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiFormRequest;
use Auth;

class UpdateUserRequest extends ApiFormRequest
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
        $id = Auth::guard('api')->id();
        return [
            'user_name'          => 'max:255',
            'e_mail'         => 'email|unique:users_1,email,'.$id.'|max:255',
            'mobile_number'         => 'unique:users_1,phone,'.$id.'|max:255',
            'password'      => 'max:255|min:6',
            // 'image'         => 'nullable|image',
        ];
    }
}
