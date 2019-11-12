<?php

namespace App;

use Illuminate\Database\Eloquent\Model;




class OrderComment extends Model
{
    protected $table = 'order_comments';
    protected $fillable = [
        'user_id',
        'order_id',
        'comment'
    ];
}
