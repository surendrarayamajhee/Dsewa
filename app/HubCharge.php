<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HubCharge extends Model
{
    protected $fillable=['ward_id',
    'fragile_charge', 
    'non_fragile_charge','delivery_charge'];

}



