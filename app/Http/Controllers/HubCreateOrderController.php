<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\TrackingId;
use App\Helpers\Barcode;
use App\Helpers\PaginationHelper;
use App\Order;
use App\ReceivePickup;
use App\SendPickUp;
use App\UserAddress;

class HubCreateOrderController extends Controller
{
    //
    use TrackingId, Barcode, PaginationHelper;

    public function store(Request $request)
    {
        // dd($request->cod);
        $request->validate([

            'product_type' => 'required',
            'hub' => 'required',
            'handling' => 'required',
            'delivery_charge' => 'required',
            'useraddress_id' => 'required',


        ]);
        $order = Order::create([
            'tracking_id' => $this->generateid(),
            'handling' => $request->handling,
            'bar_code' => $this->generateBarcodeNumber(),
            'product_type' => json_encode([$request->product_type]),
            'weight' => $request->weight,
            'order_status' => 1,
            'cod'=> $request->cod,
            'order_description' => $request->description,
            'order_pickup_point' => $request->order_pickup_point,
            'hub_id' => $request->hub,
            'pickup_hub' => auth()->id(),
            'sender_id' => auth()->id(),
            'receiver_id' => $request->useraddress_id,
            'expected_date' => $request->expected_date,
            'shipment_charge' => $request->delivery_charge,
            'inquiry' => 1,

        ]);
        $ward = UserAddress::where('id', $request->useraddress_id)->first()->ward_no;
        if ($ward == '') {
            $order->is_ward_status = 1;
            $order->update();
        }
        $order->order_id = $order->id;
        $order->update();

        $this->pickupsendreceive($order->sender_id, $order->pickup_hub, $order->order_id);

        return response()->json(['success' => 'Added']);
    }
    public function pickupsendreceive($vendor_id, $pickup_hub, $order_id)
    {
        $pickUp = new SendPickUp();
        $pickUp->vendor_id = $vendor_id;
        //here stays  auth user
        $pickUp->user_id =  $pickup_hub;
        $pickUp->orders = json_encode([$order_id]);
        $pickUp->pickup_logistic_officer = $pickup_hub;
        $pickUp->save();
        ReceivePickup::create([
            'order_id' => $order_id,
            'user_id' => $pickup_hub,
        ]);
    }
}
