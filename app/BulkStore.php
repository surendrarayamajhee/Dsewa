<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BulkStore extends Model
{
    //
    protected $fillable = [
        'weight','vendor_id', 'description', 'handling', 'product_type', 'expected_date', 'cod', 'useraddress_id','order_pickup_point','vendor_order_id'
    ];
}
