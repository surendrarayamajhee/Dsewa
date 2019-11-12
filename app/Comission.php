<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comission extends Model
{
    protected $fillable = ['is_vendor_paid', 'is_delivery_hub_paid',
    'is_pickup_hub_paid', 'is_delivery_paid', 'is_admin_paid',
    'order_id', 'delivery_hub', 'dsewa', 'pickup_hub','shipping_cost','cod','delivery_boy_comission'];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
