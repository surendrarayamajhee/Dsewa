<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PickUpOrder extends Model
{
    //
    protected $fillable = [
       'vendor_order_id', 'weight','vendor_id', 'description', 'handling', 'product_type', 'expected_date', 'cod', 'useraddress_id','order_pickup_point'
    ];
    // protected $dates = ['expected_date'];
}
