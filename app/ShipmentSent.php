<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentSent extends Model
{
    //
    protected $fillable = [
      'shipment_cost','locked', 'from', 'to', 'description', 'expected_arrival_date', 'reference', 'shipment_date', 'user_id', 'shipment_id', 'order_id', 'shipment_officer_id', 'barcode', 'received'
    ];
    public function received()
    {
        $this->hasOne(ShipmentReceive::class);
    }
}
