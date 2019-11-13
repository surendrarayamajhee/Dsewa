<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HubDeliverySent extends Model
{
    protected $fillable=[
        'delivery_boy_id',
        'order_id',
        'date_time',
        'user_id',
        'comments'
    ];



    public function deliveryBoy(){
        return $this->belongsTo(User::class, 'delivery_boy_id') ;
    }

    public function assignBy(){
        return $this->belongsTo(User::class, 'user_id') ;
    }


}
