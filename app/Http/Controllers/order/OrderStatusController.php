<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrderStatus;
use Auth;

class OrderStatusController extends Controller
{
    public function get_order_ststus()
    {
        $user = Auth::user();
       if ($user->hasRole('vendor')) {

            $status = OrderStatus::where('id', 4)->orWhere('id', 3)->orWhere('id', 2)->orWhere('id',5)->orWhere('id',8)->orWhere('id',8)->select('id', 'name')->get();
        }
        else  {
            $status = OrderStatus::where('id', 2)->orWhere('id', 3)->orWhere('id', 4)->orWhere('id',5)->orWhere('id',7)->orWhere('id',8)->select('id', 'name')->get();
        }
        return response()->json($status);
    }
    public function get_all_status()
    {
        $status = OrderStatus::all('id', 'name');
        return response()->json($status);
    }
}
