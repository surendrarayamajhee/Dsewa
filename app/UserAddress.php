<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'vendor_id',
        'bulk',
        'state_no',
        'first_name',
        'last_name',
        'municipality',
        'ward_no',
        'area',
        'description',
        'phone1',
        'phone2',
        'district',
        'is_active'

    ];
}
