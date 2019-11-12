<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Orderequest extends FormRequest
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
            'tracking_id'  =>  '',
            'order_created_as' => 'required',
            'handling' => 'required',
            'payment_type' => 'required',
            'hub_id' => '',
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'order_description' => '',
            'expected_date' => 'required',
            'cod' => 'required',
            'instruction' => '',
            'order_date' => 'required',
            'order_id' => '',
            'bar_code' => '',
            'product_type' =>'required'
        ];
    }
}
