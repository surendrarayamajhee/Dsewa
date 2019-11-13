<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashOnCounter extends Model
{
    protected $fillable=[
        'order_id',
        'user_id',
        'comments'
    ];

    public function order(){

        return $this->belongsTo(Order::class, 'order_id');
    }

    public function acceptBy(){

        return $this->belongsTo(User::class, 'user_id');
    }
}
