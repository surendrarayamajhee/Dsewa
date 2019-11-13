<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    protected $fillable =[

        'user_id',
        'citizenship',
        'pan_vat',
        'cheque',

    ];
}
