<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectDeliveryPayment extends Model
{
    protected $fillable =[

        'delivery_boy_id',
        'order_id',
        'collection_mode',
        'amount',
        'collection_date',
        'user_id',
        'comments'
    ];


    public function deliveryBoy(){

        return $this->belongsTo(User::class, 'delivery_boy_id');
    }

    public function acceptBy(){

        return $this->belongsTo(User::class, 'user_id');
    }
}
