<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'is_picked', 'inquiry', 'vendor_order_id', 'pickup_hub', 'tracking_id', 'shipment_charge', 'order_created_as', 'handling', 'hub_id', 'payment_type', 'sender_id', 'receiver_id', 'order_description', 'shipment_type',
        'expected_date', 'cod', 'instruction', 'order_id', 'order_date', 'bar_code', 'product_type', 'order_pickup_point', 'order_status', 'order_log', 'barcode', 'weight'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class);
    }
    public function comments()
    {
        return $this->hasMany(OrderComment::class);
    }
    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'receiver_id');
    }
    public function account()
    {
        return $this->belongsTo(Comission::class, 'order_id');
    }
}
