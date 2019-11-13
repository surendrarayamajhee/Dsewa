<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\TrackingId;
use App\Helpers\Barcode;
use App\Order;
use App\Returned;
use App\OrderReturnedOrder;
use App\OrderStatusChangeRequest;
use App\OrderComment;
use App\Comment;
use App\ReceivePickup;
use App\SendPickUp;
use App\ShipmentSent;

class NewStatusChangeController extends Controller
{
    //
    use TrackingId, Barcode;
    //
    public function new_returned_order(Request $request)
    {
        // dd($request->all());
        if (count($request->checkbox) > 0) {

            foreach ($request->input('checkbox') as $checkid) {
// hold
                if ($request->status == 7) {


                    $order = Order::where('order_id', $checkid)->first();
                    $order->order_status = $request->status;
                    $order->update();
                }
                // return
                else if ($request->status == 8) {
                    // dd('return');
                    $pickup = SendPickUp::all()->pluck('orders')->toArray();
                    // dd( $pickup);
                    $shipment = ShipmentSent::all()->pluck('order_id')->toArray();
                    $pickup_OrderId = [];
                    foreach ($pickup as $orderid) {
                        $deCodeIds = json_decode($orderid);
                        foreach ($deCodeIds as $iD) {
                            $pickup_OrderId[] = $iD;
                        }
                    }
                    $shipment_OrderId = [];
                    foreach ($shipment as $orderid) {
                        $deCodeIds = json_decode($orderid);
                        foreach ($deCodeIds as $iD) {
                            $shipment_OrderId[] = $iD;
                        }
                    }
                    // dd($pickup_OrderId);

                    $onlyPickupOrder = array_diff($pickup_OrderId, $shipment_OrderId);
                    $order = Order::where('order_id', $checkid)->first();

                    $order->order_status = $request->status;
                    $order->update();
                    OrderStatusChangeRequest::create([
                        'order_id' => $order->order_id,
                        'vendor_id' => auth()->id(),
                        'status_id' => $request->status,
                        'product_type' =>  $order->product_type,
                        'request_status' =>1
                    ]);

                    if (isset($order)) {
                        $trackingid = $this->generateid();
                        $barcode = $this->generateBarcodeNumber();
                        $returnOrder = Order::create([
                            'tracking_id' => $trackingid,
                            'handling' => $order->handling,
                            'bar_code' => $barcode,
                            'product_type' => $order->product_type,
                            'weight' => $order->weight,
                            'order_status' => 1,
                            'order_description' => $order->order_description,
                            'order_pickup_point' => $order->order_pickup_point,
                            // 'hub_id' => $order->pickup_hub,
                            // 'pickup_hub' => $order->hub_id,
                            'cod' => 0,
                            'order_created_as' => 'RETURN',
                            'is_ward_status' => $order->is_ward_status,
                            'sender_id' => $order->sender_id,
                            'receiver_id' => $order->receiver_id,
                            'payment_type' => $order->payment_type,
                            'instruction' => $order->instruction,
                            'order_date' => $order->order_date,
                            'is_payed' => $order->is_payed,
                            'expected_date' => $order->expected_date,
                            'inquiry' => 1,

                        ]);

                        $returnOrder->order_id = $returnOrder->id;
                        if (in_array($order->order_id, $shipment_OrderId)) {
                            $returnOrder->hub_id = $order->pickup_hub;
                            $returnOrder->pickup_hub = $order->hub_id;
                            $returnOrder->shipment_charge = $order->shipment_charge;
                        } elseif (in_array($order->order_id, $onlyPickupOrder)) {
                            $returnOrder->hub_id = $order->pickup_hub;
                            $returnOrder->pickup_hub =  $order->hub_id;
                            $returnOrder->shipment_charge = 0.2 * $order->shipment_charge;
                        }
                        $returnOrder->update();

                        $orderidEnc= json_encode([$returnOrder->order_id]);

                        $pickUp = new SendPickUp();
                        $pickUp->vendor_id = $returnOrder->sender_id;
                        //here stays  auth user
                        $pickUp->user_id = $returnOrder->pickup_hub;
                        $pickUp->orders = $orderidEnc;
                        $pickUp->pickup_logistic_officer = $returnOrder->pickup_hub;
                        $pickUp->save();
                        ReceivePickup::create([
                            'order_id' => $returnOrder->order_id,
                            'user_id' => $returnOrder->pickup_hub,
                        ]);
                    }
                    // add to returned table and order_returned_order Table
                    Returned::create(['new_id' => $returnOrder->id]);
                    OrderReturnedOrder::create([
                        'new_order_id' => $returnOrder->id,
                        'old_order_id' => $order->id
                    ]);
                }
            }

            return response()->json(['success' => 'Order created'], 200);
        }
        return response()->json(['error' => 'Empty']);
    }
}
