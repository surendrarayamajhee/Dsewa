<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCancel extends Model
{
    protected $fillable=[
        'order_id',
        'user_id',
        'comments'
    ];

    public function acceptBy(){

        return $this->belongsTo(User::class, 'user_id');
    }
}
