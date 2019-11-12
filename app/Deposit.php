<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Deposit extends Model
{
 protected $fillable=['outstanding_payment','user_id','bank_branch','bank_name','date','amount','payment_type','image','is_verified'];
}
