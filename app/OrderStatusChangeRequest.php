<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatusChangeRequest extends Model
{
    //
    protected $fillable = [
        'order_id','vendor_id','status_id','comment_id','cod','product_type','refund_amt','request_status'];
}
