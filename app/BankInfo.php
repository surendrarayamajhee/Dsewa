<?php

namespace App;






use Illuminate\Database\Eloquent\Model;

class BankInfo extends Model
{
    protected $fillable=[
        'user_id',
        'bank_name',
        'bank_branch',
        'account_no',
        'account_name',
        'account_type'
    ];
}
