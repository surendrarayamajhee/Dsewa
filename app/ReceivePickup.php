<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceivePickup extends Model
{
    //
    protected $fillable = [
        'user_id','order_id'];
}
