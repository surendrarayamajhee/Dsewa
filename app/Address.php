<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'address','parent_id','short_address','type','zip_code','min_deposit','max_deposit'

    ];

    



    public function childs()
    {
        return $this->hasMany('App\Address','parent_id');
    }
}
