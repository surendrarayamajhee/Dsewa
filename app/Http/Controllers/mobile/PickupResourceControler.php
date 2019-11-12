<?php

namespace App\Http\Controllers\mobile;

use App\Address;
use App\Http\Controllers\Controller;
use App\Order;
use App\SendPickUp;
use App\User;
use App\UserAddress;
use Illuminate\Http\Request;
use Auth;
use App\Http\Resources\PickupResource;
use App\ReceivePickup;
use App\VendorPickup;

class PickupResourceControler extends Controller
{
    //
    public function orders()
    {
        $send = SendPickUp::where('pickup_logistic_officer', auth()->id())->pluck('orders')->toArray();

        $aa = [];
        foreach ($send as $s) {
            $o = json_decode($s);
            foreach ($o as $n) {
                $aa[] = $n;
            }
        }

        $receivePickup = ReceivePickup::all()->pluck('order_id')->toArray();

        $pickupsend = array_diff($aa, $receivePickup);
        $orders = Order::whereIn('order_id', $pickupsend)->select('sender_id', 'order_id')->get();
        $senderid = $orders->pluck('sender_id')->toArray();
        $senderid = array_unique($senderid);
        $users = User::whereIn('id', $senderid)->select('id', 'name')->get();
        foreach ($users as $user) {


            $user->order =  Order::whereIn('order_id', $orders->where('sender_id', $user->id)->pluck('order_id')->toArray())->select('order_pickup_point','order_id', 'product_type','handling','bar_code')->get();
            $pp = '';


            foreach ($user->order as $o) {
                $o->product_type = json_decode($o->product_type);
                $pp = $o->order_pickup_point;
            }

            $pickupaddress =  array();
            $pickupaddress =  array(
           'state' => Address::where('id', VendorPickup::where('id', $pp)->first()->state_id)->first() ?  Address::where('id', VendorPickup::where('id', $pp)->first()->state_id)->first()->address : '',
           'district' => Address::where('id', VendorPickup::where('id', $pp)->first()->district_id)->first() ? Address::where('id', VendorPickup::where('id', $pp)->first()->district_id)->first()->address : '',
           'municipality' => Address::where('id', VendorPickup::where('id', $pp)->first()->municipality_id)->first() ? Address::where('id', VendorPickup::where('id', $pp)->first()->municipality_id)->first()->address : '',
           'ward' => Address::where('id', VendorPickup::where('id', $pp)->first()->ward_id)->first() ?  Address::where('id', VendorPickup::where('id', $pp)->first()->ward_id)->first()->address : '',
           'area' => Address::where('id', VendorPickup::where('id', $pp)->first()->area_id)->first() ? Address::where('id', VendorPickup::where('id', $pp)->first()->area_id)->first()->address : '',
            );
           $user->pickupaddress = $pickupaddress;
        }
        // dd($users);
        return PickupResource::collection($users);
    }
    public function store(Request $request)
    {
dd($request->all());
    }

}
