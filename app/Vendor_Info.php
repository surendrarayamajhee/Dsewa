<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor_Info extends Model
{
    //
    
    protected $fillable = [
        'phone1','phone2','vendor_id','email','website'
    ];
}
