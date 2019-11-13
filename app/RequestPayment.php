<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestPayment extends Model
{
    //
    protected $fillable=['bank_account','selected_name','outstanding_payment','order_id','sender_id','receiver_id','bank_branch','bank_name','date','amount','payment_type','image','is_approved'];

}
