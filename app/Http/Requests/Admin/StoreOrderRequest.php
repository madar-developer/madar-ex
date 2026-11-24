<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
        $rules = [
            'cod_cash_amount' => 'nullable|numeric|min:0',
            'cod_online_amount' => 'nullable|numeric|min:0',
        ];

        $paymentMethod = \App\Models\PaymentMethod::find($this->input('payment_method_id'));
        if ($paymentMethod && $paymentMethod->required_action === 'cod_cash_and_online') {
            $rules['cod_cash_amount'] = 'required|numeric|min:0';
            $rules['cod_online_amount'] = 'required|numeric|min:0';
        }

        return $rules;
    }
}
