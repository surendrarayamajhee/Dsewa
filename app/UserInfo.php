<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $fillable =[
        'name',
        'user_id',
        'address',
        'email',
        'job_title',
        'citizenship_no',
        'image',

    ];
}
