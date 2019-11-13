<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BulkOrder extends Model
{
    //
    protected $fillable=[
        'user_id',
        'status',
        'file',
        'code',

    ];
}
