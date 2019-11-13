<?php

namespace App\Http\Controllers\order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HubOrderController extends Controller
{
    //
    public function store(Orderequest $request)
    {
        // dd($request->all());
        $request->merge(['sender_id' => auth()->id(), 'pickup_hub' =>  auth()->id(), 'order_status' => 1]);
        $request['product_type'] = json_encode($request->product_type);
        $order = Order::create($request->all());
        $order->order_id = $order->id; // order_id might change in future
        $order->bar_code = $this->generateBarcodeNumber();
        $order->hub_id =  HubArea::where('address_id', UserAddress::where('id', $request->receiver_id)->first()->ward_no)->first()->hub_id;
        $order->update();
        $ward = UserAddress::where('id', $request->receiver_id)->first()->ward_no;
        if ($ward == '') {
            $order->is_ward_status = 1;
            $order->update();
        }
        return response()->json(['success' => 'Added']);
    }
}
