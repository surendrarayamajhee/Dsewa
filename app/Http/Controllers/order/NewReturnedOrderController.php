<?php

namespace App\Http\Controllers\order;

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

class NewReturnedOrderController extends Controller
{
    use TrackingId, Barcode;
    //
    public function new_returned_order(Request $request)
    {
        //   dd($request->all());
        if (count($request->checkbox) > 0) {
            foreach ($request->input('checkbox') as $id) {
                $orderrequest1 = OrderStatusChangeRequest::where('id', $id)->where('request_status', 1)->first();
                if ($orderrequest1) {
                    return response()->json(['error' => "Your Order Is Already Approved"]);
                }
            }
            foreach ($request->input('checkbox') as $id) {
                $orderrequest = OrderStatusChangeRequest::where('id', $id)->first();
                $orderrequest->vendor_id = auth()->id();
                // updating the old order's status first
                // for exchange
                if ($orderrequest->status_id == 3) {
                    $order = Order::where('order_id', $orderrequest->order_id)->first();
                    $order->order_status = $orderrequest->status_id;
                    $order->update();
                    OrderComment::create(
                        [
                            'user_id' => $orderrequest->vendor_id,
                            'order_id' => $orderrequest->order_id,
                            'comment' => $orderrequest->comment_id,
                        ]
                    );
                    $request['product_type'] = json_encode($request->product_type);
                    // creating another  new  order acc to vendor's request
                    if (isset($order)) {
                        $trackingid = $this->generateid();
                        $barcode = $this->generateBarcodeNumber();
                        $neworder = Order::create([
                            'tracking_id' => $trackingid,
                            'handling' => $order->handling,
                            'bar_code' => $barcode,
                            'order_description' => $order->order_description,
                            //request ko product type
                            'product_type' => $orderrequest->product_type,
                            'weight' => $order->weight,
                            'cod' => $orderrequest->cod ? $orderrequest->cod:0,
                            'order_pickup_point' => $order->order_pickup_point,
                            'hub_id' => $order->hub_id,
                            'pickup_hub' => $order->pickup_hub,
                            'shipment_charge' => $order->shipment_charge,
                            'order_created_as' => 'EXCHANGE',
                            'is_ward_status' => $order->is_ward_status,
                            'sender_id' => $order->sender_id,
                            'receiver_id' => $order->receiver_id,
                            'payment_type' => $order->payment_type,
                            'instruction' => $order->instruction,
                            'order_date' => $order->order_date,
                            'is_payed' => $order->is_payed,
                            'expected_date' => $order->expected_date,
                            // 'order_status' => 1,
                        ]);
                        $neworder->order_id = $neworder->id;
                        $neworder->update();
                    }
                    if (isset($order)) {
                        $trackingid = $this->generateid();
                        $barcode = $this->generateBarcodeNumber();
                        $returnOrder = Order::create([
                            'tracking_id' => $trackingid,
                            'handling' => $order->handling,
                            'cod' => $orderrequest->cod ? $orderrequest->cod:0,
                            'bar_code' => $barcode,
                            'product_type' => $orderrequest->product_type,
                            'weight' => $order->weight,
                            // 'order_status' => 1,
                            'order_description' => $order->order_description,
                            'order_pickup_point' => $order->order_pickup_point,
                            'hub_id' => $order->pickup_hub,
                            'pickup_hub' => $order->hub_id,
                            'shipment_charge' => 0,
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
                        $returnOrder->update();
                    }
                    // add to returned table and order_returned_order Table
                    Returned::create(['new_id' => $returnOrder->id]);
                    OrderReturnedOrder::create([
                        'new_order_id' => $neworder->id,
                        'old_order_id' => $order->id
                    ]);
                    OrderReturnedOrder::create([
                        'new_order_id' => $returnOrder->id,
                        'old_order_id' => $order->id
                    ]);
                    $orderrequest->request_status = 1;
                    $orderrequest->update();
                }
                // for partial
                else if ($orderrequest->status_id == 4) {
                    $pickup = SendPickUp::all()->pluck('orders')->toArray();
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
                    // accept only order_id present in SendPickUp removes all order_id present in ShipmentSent
                    $onlyPickupOrder = array_diff($pickup_OrderId, $shipment_OrderId);

                    // // updating the old order's status first
                    $order = Order::where('order_id', $orderrequest->order_id)->first();
                    // dd($order);
                    $order->order_created_as = 'PARTIAL';
                    $order->cod =  $orderrequest->cod;
                    // $order->product_type = $orderrequest->product_type;
                    $order->update();
                    $order_product_type = json_decode($order->product_type);
                    $requested_product_type = json_decode($orderrequest->product_type);
                    // dd( $order_product_type);

                    $newtype = array_diff($order_product_type,$requested_product_type);

                    OrderComment::create(
                        [
                            'user_id' => $orderrequest->vendor_id,
                            'order_id' => $orderrequest->order_id,
                            'comment' =>  $orderrequest->comment_id,
                        ]
                    );
                    // new order
                    if (isset($order)) {
                        $trackingid = $this->generateid();
                        $barcode = $this->generateBarcodeNumber();
                        $returnOrder = Order::create([
                            'tracking_id' => $trackingid,
                            'handling' => $order->handling,
                            'cod' =>  0,
                            'expected_date' => $order->expected_date,
                            'order_description' => $order->order_description,
                            'order_id' => $order->order_id,
                            'receiver_id' => $order->useraddress_id,
                            'bar_code' => $barcode,
                            'product_type' => json_encode(array_values($newtype)),
                            'weight' => $order->weight,
                            'order_status' => 1,
                            'order_pickup_point' => $order->order_pickup_point,
                            // 'hub_id' => $order->pickup_hub,
                            // 'pickup_hub' => $order->hub_id,
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
                            $returnOrder->shipment_charge = $order->shipment_charge;
                            $returnOrder->pickup_hub = $order->hub_id;
                            $returnOrder->hub_id = $order->pickup_hub;

                        } elseif (in_array($order->order_id, $onlyPickupOrder)) {
                            $returnOrder->shipment_charge = 0.2 * $order->shipment_charge;
                            $returnOrder->pickup_hub = $order->pickup_hub;
                            $returnOrder->hub_id = $order->hub_id;

                        }
                        $returnOrder->update();
                        $this->pickupsendreceive($returnOrder->vendor_id, $returnOrder->pickup_hub, $returnOrder->order_id);


                    }

                    // add to returned table and order_returned_order Table
                    Returned::create(['new_id' => $returnOrder->id]);
                    OrderReturnedOrder::create([
                        'new_order_id' => $returnOrder->id,
                        'old_order_id' => $order->id,
                    ]);
                    $orderrequest->request_status = 1;
                    $orderrequest->update();
                    $order->product_type = $orderrequest->product_type;
                    $order->update();
                }
                // Refund
                else if ($orderrequest->status_id == 2) {
                    $order = Order::where('order_id', $orderrequest->order_id)->first();
                    $order->order_status = $orderrequest->status_id;

                    $order->update();
                    OrderComment::create(
                        [
                            'user_id' => $orderrequest->vendor_id,
                            'order_id' => $orderrequest->order_id,
                            'comment' =>  $orderrequest->comment_id,
                        ]
                    );
                    // creating another  new  order acc to vendor's request
                    if (isset($order)) {
                        $trackingid = $this->generateid();
                        $barcode = $this->generateBarcodeNumber();
                        $neworder = Order::create([
                            'tracking_id' => $trackingid,
                            'handling' => $order->handling,
                            'cod' =>  -1 * ($orderrequest->refund_amt),
                            'bar_code' => $barcode,
                            'order_description' => $order->order_description,
                            'product_type' => $orderrequest->product_type,
                            'weight' => $order->weight,
                            'order_pickup_point' => $order->order_pickup_point,
                            'hub_id' => $order->pickup_hub,
                            'pickup_hub' => $order->hub_id,
                            //abhay sir lai sodnaa
                            'shipment_charge' => 0.50 * $order->shipment_charge,
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
                        $neworder->order_id = $neworder->id;
                        $neworder->update();
                        $this->pickupsendreceive($neworder->vendor_id, $neworder->pickup_hub, $neworder->order_id);
                        // $pickUp = new SendPickUp();
                        // $pickUp->vendor_id = $neworder->vendor_id;
                        // //here stays  auth user
                        // $pickUp->user_id =  $neworder->pickup_hub;
                        // $pickUp->orders = json_encode([$neworder->order_id]);
                        // $pickUp->pickup_logistic_officer =  $neworder->pickup_hub;
                        // $pickUp->save();
                        // ReceivePickup::create([
                        //     'order_id' => $neworder->order_id,
                        //     'user_id' =>  $neworder->pickup_hub,
                        // ]);
                    }
                    // add to returned table and order_returned_order Table
                    Returned::create(['new_id' => $neworder->id]);
                    OrderReturnedOrder::create([
                        'new_order_id' => $neworder->id,
                        'old_order_id' => $order->id,
                        'refund_amt' => $orderrequest->refund_amt
                    ]);
                    $orderrequest->request_status = 1;
                    $orderrequest->update();
                }
                // ?delivered
                else if ($orderrequest->status_id == 6) {
                    $order = Order::where('order_id', $orderrequest->order_id)->first();
                    $order->order_status = $orderrequest->status_id;
                    $order->update();
                    $orderrequest->request_status = 1;
                    $orderrequest->update();
                }
                // canceled
                else if ($orderrequest->status_id == 5) {
                    $order = Order::where('order_id', $orderrequest->order_id)->first();
                    $order->order_status = $orderrequest->status_id;
                    $order->update();
                    $orderrequest->request_status = 1;
                    $orderrequest->update();
                }
                // Hold
                else if ($orderrequest->status_id == 7) {
                    $order = Order::where('order_id', $orderrequest->order_id)->first();
                    $order->order_status = $orderrequest->status_id;
                    $order->update();
                    $orderrequest->request_status = 1;
                    $orderrequest->update();
                }
                else if ($orderrequest->status_id == 8) {
                    $pickup = SendPickUp::all()->pluck('orders')->toArray();
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
                    // accept only order_id present in SendPickUp removes all order_id present in ShipmentSent
                    $onlyPickupOrder = array_diff($pickup_OrderId, $shipment_OrderId);

                    $order = Order::where('order_id', $orderrequest->order_id)->first();


                    $order->order_status = $orderrequest->status_id;
                    $order->update();
                    OrderComment::create(
                        [
                            'user_id' => $orderrequest->vendor_id,
                            'order_id' => $orderrequest->order_id,
                            'comment' =>  $orderrequest->comment_id,
                        ]
                    );
                    $request['product_type'] = json_encode($request->product_type);
                    // creating another  new  order acc to vendor's request
                    // if (isset($order)) {
                    //     $trackingid = $this->generateid();
                    //     $barcode = $this->generateBarcodeNumber();
                    //     $oldOrder = $order->update([
                    //         'order_created_as' => 'NEW',
                    //         'order_status' => 8,
                    //     ]);
                    // }


                    if (isset($order)) {
                        $trackingid = $this->generateid();
                        $barcode = $this->generateBarcodeNumber();
                        $returnOrder = Order::create([
                            'tracking_id' => $trackingid,
                            'handling' => $order->handling,
                            'bar_code' => $barcode,
                            'product_type' => $orderrequest->product_type,
                            'weight' => $order->weight,
                            'order_status' => 1,
                            'order_description' => $order->order_description,
                            'order_pickup_point' => $order->order_pickup_point,
                            'hub_id' => $order->pickup_hub,
                            'pickup_hub' => $order->hub_id,
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
                        $this->pickupsendreceive($returnOrder->sender_id, $returnOrder->pickup_hub, $returnOrder->order_id);


                    }
                    // add to returned table and order_returned_order Table
                    Returned::create(['new_id' => $returnOrder->id]);
                    OrderReturnedOrder::create([
                        'new_order_id' => $returnOrder->id,
                        'old_order_id' => $order->id
                    ]);
                    $orderrequest->request_status = 1;
                    $orderrequest->update();
                }
            }

            return response()->json(['success' => 'Order created'], 200);
        }
        return response()->json(['error' => 'Empty']);
    }
    public function pickupsendreceive($vendor_id,$pickup_hub,$order_id )
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
