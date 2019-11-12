<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class BusinessInfo extends Model
{
    protected $fillable=[
        'user_id',
        'business_name',
        'state',
        'district',
        'municipality_vdc',
        'ward',
        'tole',
        'pan_vat_no',
        'phone',
        'mobile',
        'fax',
        'business_email',
        'company_reg_no',
    ];
}
