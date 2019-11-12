<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorPickup extends Model
{
    //
    protected $fillable = [
        'state_id','district_id','area_id','description','vendor_id','municipality_id','ward_id'
    ];
}
