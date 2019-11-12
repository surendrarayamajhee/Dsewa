<?php

namespace App\Http\Controllers;

use App\Address;
use App\Order;
use App\OrderStatus;
use App\User;
use App\UserAddress;
use App\Vendor_Info;
use App\VendorPickup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class InquiryController extends Controller
{
    //
    public function inqueryConform(Request $request)
    {
        $order = Order::where('order_id', $request->in)->first();
        if ($order->inquiry) {
            $order->inquiry = 0;
        } else {
            $order->inquiry = 1;
        }
        $order->save();
        // $order->o_status = OrderStatus::where('id', $order->order_status)->first() ? OrderStatus::where('id', $order->order_status)->first()->name : 'Incomplete Address';

        // $order->vendor_name = User::findOrfail($order->sender_id)->name;
        // $useraddress = UserAddress::where('id', $order->receiver_id)->first();
        // $order->weight =  $order->weight ?  $order->weight : "";
        // $order->useraddress = $useraddress;
        // $order->o_status = OrderStatus::where('id', $order->order_status)->first() ? OrderStatus::where('id', $order->order_status)->first()->name : 'Incomplete Address';
        // if ($order->order_created_as != 'NEW') {
        //     $order->created_as = '<span class="badge badge-' . getOrderCreatedAsClass($order->order_created_as) . '">' . $order->order_created_as . '</span>';
        // } else {
        //     $order->created_as = '';
        // }
        // if ($order->pickup_hub == auth()->id()) {
        //     $order->is_pickup_hub = true;
        //     $order->is_delivery_hub = false;
        //     $order->is_admin = false;
        // }
        // if ($order->hub_id == auth()->id()) {
        //     $order->is_delivery_hub = true;
        //     $order->is_pickup_hub = false;
        //     $order->is_admin = false;
        // }

        // if (Auth::user()->hasRole('admin')) {
        //     $order->is_admin = true;
        //     $order->is_delivery_hub = false;
        //     $order->is_pickup_hub = false;
        // }
        // $order->inquiry = $order->inquiry ? true : false;

        // $order->vendor_order_id = $order->vendor_order_id ? $order->vendor_order_id : '-';
        // $order->deliverHub = User::where('id', $order->hub_id)->first() ? User::where('id', $order->hub_id)->first()->name : '-';
        // $order->pickupHub = User::where('id', $order->pickup_hub)->first() ? User::where('id', $order->pickup_hub)->first()->name : '-';
        // $date = Carbon::parse($order->expected_date);
        // $d = $date->diffInDays();
        // if ($date->toDateString() >  date("Y-m-d")) {
        //     $x =    ' From Now';
        //     $d += 1;
        // } else {
        //     $x =
        //         ' Ago';
        // }
        // $date = Carbon::parse($order->created_at);
        // $order->orderdate =  $date->isoFormat('MMMM Do');
        // $date = Carbon::parse($order->expected_date);
        // $order->expecteddate =  $date->isoFormat('MMMM Do');
        // $order->expected =  $d == 0  ? 'Today' : $d . ' days ' . $x;
        // $date = Carbon::parse($order->order_date);
        // $order->orderdate =  $date->isoFormat('YYYY MMMM Do h:mm:ss a');
        // $order->product_type = json_decode($order->product_type);
        return response()->json(['success' => "Successful"],200);
    }
}
