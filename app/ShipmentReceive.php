<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentReceive extends Model
{
    //
    protected $fillable = [
'description','arrival_date','user_id','shipment_id'];
public function shipmentSent()
{
    $this->belongsTo(ShipmentSent::class,'shipment_id');
}
}

