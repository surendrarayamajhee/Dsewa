<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendPickUp extends Model
{
    protected $fillable=[
        'vendor_id',
        'user_id',
        'orders',
        'pickup_logistic_officer',
        'received'
    ];
}




