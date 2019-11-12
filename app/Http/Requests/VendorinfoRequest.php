<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorinfoRequest extends FormRequest
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

            'phone1' => 'required',
            'phone2' => 'required',
            'vendor_id' => '',
            'email' =>'required',
            'website'=>'required'
        ];
    }
}
