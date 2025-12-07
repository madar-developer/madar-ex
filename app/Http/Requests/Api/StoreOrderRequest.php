<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiFormRequest;

class StoreOrderRequest extends ApiFormRequest
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
            // 'user_id'           => 'required',
            'service_id'     => 'required',
            'description'              => 'required|max:255',
            'date'            => 'required|max:255',
            'time'       => 'required|max:255',
            'city'             => 'required|max:255',
            'longitude'             => 'required|max:255',
            'latitude'             => 'required|max:255',
            // 'image'             => 'nullable|image',
        ];
    }
}
