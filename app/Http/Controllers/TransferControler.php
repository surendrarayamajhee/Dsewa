<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;

class TransferControler extends Controller
{
    //
    public function transfer(Request $request)
    {
        if ($request['hub'] == null or $request['checkbox'] == null) {
            return response()->json(['error' => 'Please Select Hub Or Order That You Want To Transfer !!']);
        }
        $user = User::where('id', $request->hub)->first();

        if ($user->hasRole('hub')) {
            foreach ($request->checkbox as $check) {
                $order = Order::where('order_id', $check)->first();
                $order->hub_id = $request->hub;
                $order->update();
            }
            return response()->json(['success' => 'Successful Transfered To Another hub']);
        }
        return response()->json(['error' => 'Select Correct Hub']);
    }
}
