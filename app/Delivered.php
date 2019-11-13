<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivered extends Model
{
    protected $fillable=[
        'delivery_boy_id',
        'order_id',
        'user_id',
        'comments'
    ];

    public function deliveryBoy(){
        return $this->belongsTo(User::class, 'delivery_boy_id') ;
    }

    public function approvedby(){
        return $this->belongsTo(User::class, 'user_id') ;
    }
}
