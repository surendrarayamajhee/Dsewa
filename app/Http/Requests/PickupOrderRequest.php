<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PickupOrderRequest extends FormRequest
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
            //
            'useraddress_id' => 'required',
            'description' => 'max:255',
            'handling' => 'required',
            'product_type' => 'required',
            'expected_date' => 'required|after:tomorrow',
            'cod' => 'required',
            'weight' => 'required',
            'order_pickup_point' => '',
            'vendor_order_id'=>''
        ];
    }
}
