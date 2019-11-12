<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddress extends FormRequest
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


            'address' => 'required',
            'parent_id' => 'nullable',
            'type' => 'required',
            'short_address' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'address.required' => 'A address is required',
            'type.required'  => 'A address type is required',
            'short_address.required'  => 'A short name of address is required',
        ];
    }
}
