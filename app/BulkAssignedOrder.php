<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BulkAssignedOrder extends Model
{
    protected $table='bulk_assigned_orders';

    protected $fillable=[
        'order_id',
        'destination',

    ];
}
