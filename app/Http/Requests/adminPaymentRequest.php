<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class adminPaymentRequest extends FormRequest
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
            'payment_type' => 'required',
            'bank_branch' => '',
            'bank_name' => '',
            'bank_account' =>'',
            'amount' => 'required',
            'image' => '',
            'date' => 'required',
            'is_approved' => '',
            'receiver_id' => 'required',
            'order_id' =>'required',
            'selected_name' => 'required',
            'outstanding_payment' => 'required'
        ];
    }
}
