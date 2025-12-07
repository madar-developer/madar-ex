<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiFormRequest;

class StoreDriverRequest extends ApiFormRequest
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
            'email'         => 'nullable|email|unique:drivers|max:255',
            'phone'         => 'nullable|unique:drivers|max:255',
            // 'phone'         => 'required|max:255',
            // 'image'         => 'nullable|image',
            // 'api_token'     => 'required|email|unique:drivers|max:255',
        ];
    }
}
