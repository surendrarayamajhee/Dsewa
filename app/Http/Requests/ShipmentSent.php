<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentSent extends FormRequest
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
                'from' =>'',
                'to' =>'required',
                'shipment_date' =>'',
                'expected_arrival_date' =>'',
                'reference' =>'',
                'description' =>'',
                'shipment_officer_id'=>'',
                'order_id'=>'required'

        ];
    }
}
