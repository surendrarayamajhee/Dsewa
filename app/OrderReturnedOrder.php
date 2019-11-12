<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderReturnedOrder extends Model
{
    //
    protected $fillable=[
        'new_order_id',
        'old_order_id'

    ];
}
