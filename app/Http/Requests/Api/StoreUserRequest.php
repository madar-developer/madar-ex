<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiFormRequest;

class StoreUserRequest extends ApiFormRequest
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
            'name'          => 'nullable|max:255',
            'email'         => 'nullable|email|unique:users|max:255',
            'phone'         => 'nullable|unique:users|max:255',
            // 'phone'         => 'required|max:255',
            'image'         => 'nullable|image',
            // 'api_token'     => 'required|email|unique:users_1|max:255',
        ];
    }
}
