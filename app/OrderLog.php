<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLog extends Model
{
  protected $fillable=['user_id','order_id','log'];
  public function order(){
      return $this->belongsTo(Order::class);

  }
}




