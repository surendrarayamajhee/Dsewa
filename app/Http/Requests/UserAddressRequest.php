<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
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

        if ($this->method() == 'PUT') {
            $ward = '';
            $phone = 'required';

        } else {
            $ward = '';
            $phone= 'required';
        }

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'district' => '',
            'municipality' => '',
            'ward_no' =>  $ward,
            'phone1' => $phone,
            'description' => '',

        ];
    }
}
