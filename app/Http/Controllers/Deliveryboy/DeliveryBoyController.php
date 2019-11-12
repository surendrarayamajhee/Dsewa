<?php

namespace App\Http\Controllers\Deliveryboy;

use App\Comission;
use App\Delivered;
use App\Helpers\PaginationHelper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HubCharge;
use App\HubDeliverySent;
use App\Order;
use App\OrderComment;
use App\OrderReturnedOrder;
use App\OrderStatus;
use App\OrderStatusChangeRequest;
use App\ReceivePickup;
use App\ShipmentSent;
use App\User;
use App\UserAddress;
use Auth;
use Carbon\Carbon;

class DeliveryBoyController extends Controller
{
    //
    use PaginationHelper;

    public function deliveryBoywarehouse(Request $request)
    {
        $user = Auth::user();

        $deliverys = HubDeliverySent::orderBy('id', 'Desc')->where('user_id', $user->parent_id)->get();


        $shipment_rec = ShipmentSent::where('to', $user->parent_id)->where('received', 1)->get();

        $orders_id = [];
        foreach ($shipment_rec as $rec) {

            $js_rec = json_decode($rec->order_id);
            foreach ($js_rec as $rec) {
                $ord = Order::where('order_id', $rec)->first();
                // if ($ord->hub_id == $user->parent_id) {

                $orders_id[] = $rec;
                // }
            }
        }
        $pickups = ReceivePickup::where('user_id', $user->parent_id)->pluck('order_id')->toArray();
        $orders_id = array_unique(array_merge($orders_id, $pickups));
        // dd($orders_id);


        //    $orders_id= $orders_id->merge($pickups)->unique();
        $halforders = OrderStatusChangeRequest::where('status_id', 4)->orwhere('status_id', 8)->get();
        $halforders = $halforders->where('vendor_id', $user->parent_id)->where('request_status', 1)->pluck('order_id')->toArray();
        $returningOrders = [];
        foreach ($halforders as $ho) {
            $inReceivedPickup = ReceivePickup::where('order_id', $ho)->first();
            if ($inReceivedPickup) {
                $shipments = ShipmentSent::all();
                foreach ($shipments as $s) {
                    if (in_array($ho, json_decode($s->order_id))) {
                        $inShipment = true;
                    } else {
                        $inShipment = false;
                    }
                }


                if ($inShipment == true) { } else {
                    $returningOrders[] = $ho;
                }
            }
        }


        // $returningid = [];
        // foreach ($returningOrders as $ho) {
            $returningid = OrderReturnedOrder::whereIn('old_order_id', $returningOrders)->pluck('new_order_id')->toArray();
        // }

        $merged =  array_unique(array_merge($orders_id, $returningid));
        $abc = [];
        foreach ($deliverys as $delivery) {
            $orderjsons = json_decode($delivery->order_id);
            foreach ($orderjsons as $id) {
                $abc[] = $id;
            }
        }

        $orders_id = array_diff($merged, $abc);
        $assigned = HubDeliverySent::all();
        $assign = [];
        foreach ($assigned as $a) {
            $aIds = json_decode($a->order_id);
            foreach ($aIds as $Id) {
                $assign[] = $Id;
            }
        }
        // dd($assign);

        $orders_id = array_diff($orders_id, $assign);

        $orders = Order::orderBy('id', 'DESC')->whereIn('order_id', $orders_id)->where('order_status', '!=', 8)->get();

        if ($request->has('orderid')) {
            $orders = $orders->filter(function ($order) use ($request) {
                if ($order->order_id == $request->orderid || $order->vendor_order_id == $request->orderid) {
                    return $order;
                }
            });
        }
        $orders->transform(function ($item, $key) {
        $item->comment_count = OrderComment::where('order_id', $item->order_id)->get()->count();
            $item->vendor_name = User::findOrfail($item->sender_id) ? User::findOrfail($item->sender_id)->name : '';
            $item->useraddress = UserAddress::where('id', $item->receiver_id)->first();

            $item->weight =  $item->weight ?  $item->weight : "";
            $item->o_status = OrderStatus::where('id', $item->order_status)->first() ? OrderStatus::where('id', $item->order_status)->first()->name : 'Incomplete Address';

            if ($item->order_created_as != 'NEW') {
                $item->created_as = '<span class="badge badge-' . getOrderCreatedAsClass($item->order_created_as) . '">' . $item->order_created_as . '</span>';
            } else {
                $item->created_as = '';
            }

            $item->vendor_order_id = $item->vendor_order_id ? $item->vendor_order_id : '-';
            $item->deliverHub = User::where('id', $item->hub_id)->first() ? User::where('id', $item->hub_id)->first()->name : '-';
            $item->pickupHub = User::where('id', $item->pickup_hub)->first() ? User::where('id', $item->pickup_hub)->first()->name : '-';


            $item->role = Auth::user()->roles()->first()->name;

            $date = Carbon::parse($item->expected_date);
            $d = $date->diffInDays();
            if ($date->toDateString() >  date("Y-m-d")) {
                $x =    ' From Now';
                $d += 1;
            } else {
                $x =
                    ' Ago';
            }
            $item->inquiry = $item->inquiry ? true : false;

            $item->expected =  $d == 0  ? 'Today' : $d . ' days ' . $x;

            $date = Carbon::parse($item->created_at);
            $item->orderdate =  $date->isoFormat('MMMM Do');
            $date = Carbon::parse($item->expected_date);
            $item->expecteddate =  $date->isoFormat('MMMM Do');
            $item->product_type = json_decode($item->product_type);
            return $item;
        });

        $orders = $this->paginateHelper($orders, 20);

        return response()->json($orders);
    }
    public function backtowarehouse(Request $request)
    {

        // dd($request->all());
        if ($request['comment'] == null) {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => "Please Add Comment"
            ]);
        }
        $Sents = HubDeliverySent::where('delivery_boy_id', $request->delivery_boy_id)->select('id', 'order_id')->get();
        foreach ($Sents as $Sent) {
            $orderIDS = json_decode($Sent->order_id);

            if (in_array($request->order_id, $orderIDS)) {
                $orderID =  $request->order_id;
                $request['order_id'] = [
                    $request->order_id
                ];
                $back =  HubDeliverySent::where('delivery_boy_id', $request->delivery_boy_id)->where('id', $Sent->id)->first();
                $neworder = array_diff(json_decode($back->order_id), $request->order_id);
                if (count($neworder) == 0) {
                    $back->delete();
                } else {
                    $back->order_id = json_encode(array_values($neworder));
                    $back->update();
                }
                $orderComment = new OrderComment();
                $orderComment->order_id = $orderID;
                $orderComment->user_id = $request->delivery_boy_id;
                $orderComment->comment = $request->comment;
                $orderComment->save();
            }
        }
    }
    public function deliverySuccessStore(Request $request)
    {
        $request['order_id'] = [
            $request->order_id
        ];


        if ($request->has('r')) {
            $validatedData = $request->validate([

                'delivery_boy_id' => '',
                'comments' => '',
                'order_id' => '',

            ]);
        } else {
            $validatedData = $request->validate([

                'delivery_boy_id' => 'required',
                'comments' => '',
                'order_id' => 'required',

            ]);
        }
        if ($request->has('r')) {
            if ($request->order_id == null) {
                return response()->json([
                    'status' => 'error',
                    'title' => 'Error',
                    'message' => "Select Order Id"
                ]);
            }
            if ($request->delivery_boy_id != auth()->id()) {
                return response()->json([
                    'status' => 'error',
                    'title' => 'Error',
                    'message' => "!HAHA Don't Cheat"
                ]);
            }
        }
        // dd($request->all());

        $successOrders = Delivered::all();
        foreach ($successOrders as $item) {
            $orderJsons = json_decode($item->order_id);

            if (in_array($request->order_id, $orderJsons, true)) {
                return response()->json([
                    'status' => 'error',
                    'title' => 'Error',
                    'message' => 'Order ID ' . $request->order_id . ' Already Added !!'
                ]);
            }
        }
        // dd( json_encode($request->order_id));


        // true



        // dd($order);
        $order = Order::where('order_id', $request->order_id)->first();

        $order->order_status = 6;
        $order->update();

        // $delivery_boy_charge = HubCharge::where('ward_id', $order->address->ward_no)->first() ? HubCharge::where('ward_id', $order->address->ward_no)->first()->delivery_charge : '0';
        // $shipping_cost = 0;
        // $shipment_sent = ShipmentSent::where('received', 1)->get();
        // foreach ($shipment_sent as $shipment) {

        //     if (in_array($order->order_id, json_decode($shipment->order_id), true)) {

        //         $total = count(json_decode($shipment->order_id));
        //         $perOrderCost = $shipment->shipment_cost / $total;
        //         $shipping_cost = $shipping_cost + $perOrderCost;
        //     }
        // }

        // $operationalCost = $delivery_boy_charge + $shipping_cost;
        // $shipping_charge = $order->shipment_charge;
        // $net_amt = $shipping_charge - $operationalCost;

        // $comission = new Comission();
        // $comission->order_id = $order->order_id;
        // // dd($shipping_cost);
        // $comission->shipping_cost = $shipping_cost;
        // if ($order->order_created_as == 'RETURN') {
        //     //yaha arko  table bata aauxa hai
        //     $comission->cod = $order->cod;
        //     $comission->delivery_boy_comission = '0';
        //     $comission->pickup_hub = '0';
        //     if ((0.20 *  Order::where('order_id', OrderReturnedOrder::where('new_order_id', $order->order_id)->first()->old_order_id)->first()->shipment_charge == $order->shipment_charge)) {
        //         $comission->pickup_hub = 15;

        //         $comission->dsewa = ($order->shipping_charge - $operationalCost) - $comission->pickup_hub;
        //     } else {
        //         $comission->pickup_hub = 0.20 * $net_amt;
        //         $comission->dsewa = ($order->shipping_charge - $operationalCost) - $comission->delivery_hub;
        //     }
        // } else {
        //     $comission->cod = $order->cod;
        //     $comission->delivery_boy_comission = $delivery_boy_charge;
        //     $comission->pickup_hub = (20 / 100) * $net_amt;
        //     $comission->delivery_hub = (30 / 100) * $net_amt;
        //     $comission->dsewa = (50 / 100) * $net_amt;
        // }
        // $comission->save();

        $successOrder = new Delivered();
        $successOrder->delivery_boy_id = $request['delivery_boy_id'];
        $successOrder->order_id = json_encode($request['order_id']);
        $successOrder->user_id = Auth::user()->parent_id;
        $successOrder->comments = $request['comments'];
        $success = $successOrder->save();

        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Added.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Adding !!'
            ]);
        }
    }
}
