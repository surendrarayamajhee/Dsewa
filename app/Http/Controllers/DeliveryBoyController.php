<?php

namespace App\Http\Controllers;

use App\CollectDeliveryPayment;
use App\Delivered;
use App\HubDeliverySent;
use App\Order;
use App\User;
use App\UserAddress;
use Illuminate\Http\Request;


class DeliveryBoyController extends Controller
{
    //

    public function assignedDeliveryOrder(Request $request)
    {
        # code...
        $Delivery_boys = HubDeliverySent::where('delivery_boy_id', $request->user_id)->pluck('order_id')->toArray();
        $deliveryBoyOrderId = [];
        foreach ($Delivery_boys as $orderIds) {
            $orderIds = json_decode($orderIds);
            foreach ($orderIds as $ID)
            {
                $deliveryBoyOrderId[] = $ID;
            }
        }
        $deliveredall = Delivered::all()->pluck('order_id')->toArray();
        $DeliveredallOrderId = [];

        foreach ($deliveredall as $deliveredId) {
            $deliveredId = json_decode($deliveredId);

            foreach ($deliveredId as $DID) {
                $DeliveredallOrderId[] = $DID;
            }
        }
        $onlyassigned = array_diff($deliveryBoyOrderId, $DeliveredallOrderId);
        $onlyassignedcount = count($onlyassigned);
        $onlyassignedsum = (int) Order::whereIn('order_id', $onlyassigned)->sum('cod');



        $deliveredOrders = Delivered::where('delivery_boy_id', $request->user_id)->pluck('order_id')->toArray();
        $DeliveredOrderId = [];

        foreach ($deliveredOrders as $deliveredId) {
            $deliveredId = json_decode($deliveredId);

            foreach ($deliveredId as $DID) {
                $DeliveredOrderId[] = $DID;
            }
        }
        $collects = CollectDeliveryPayment::all()->pluck('order_id')->toArray();
        $CollectOrderId = [];
        foreach ($collects as $collectdId) {
            $collectdId = json_decode($collectdId);

            foreach ($collectdId as $cID) {
                $CollectOrderId[] = $cID;
            }
        }




        $onlydelivered = array_diff($DeliveredOrderId, $CollectOrderId);
        $onlydeliveredcount = count($onlydelivered);

        $onlydeliveredsum = (int) Order::whereIn('order_id', $onlydelivered)->sum('cod');



        return response()->json(['onlyassigned' => $onlyassigned, 'onlyassignedcount' => $onlyassignedcount, 'onlyassignedsum' => $onlyassignedsum, 'onlydeliveredcount' => $onlydeliveredcount, 'onlydeliveredsum' => $onlydeliveredsum, 'deliveredOrders' => $onlydelivered]);
    }
    public function backtowarehouse(Request $request)
    {
        if ($request['order_id'] == null) {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => "Select Order Id"
            ]);
        }
        $Sents = HubDeliverySent::where('delivery_boy_id', $request->delivery_boy_id)->select('id', 'order_id')->get();
        foreach ($Sents as $Sent) {
            $orderIDS = json_decode($Sent->order_id);

            foreach ($request->order_id as $orderId) {
                if (in_array($orderId, $orderIDS)) {

                    $back =  HubDeliverySent::where('delivery_boy_id', $request->delivery_boy_id)->where('id', $Sent->id)->first();
                    $neworder = array_diff(json_decode($back->order_id), $request->order_id);



                    if (count($neworder) == 0) {
                        $back->delete();
                        echo 'delete';
                    } else {
                        $back->order_id = json_encode(array_values($neworder));
                        $back->update();
                        dd($back);
                        echo 'save';
                    }
                }
            }
            // return response()->json([
            //     'status' => 'success',
            //     'title' => 'Success',
            //     'message' => "Sent Back To wareHouse"
            // ]);
        }
    }
    public  function deliverboyorders()
    {
        $deliveryBoys = User::where('active', 1)->where('parent_id', auth()->id())->whereHas('roles', function ($q) {
            $q->where('name', 'delivery_officer');
        })->get();
        $Delivery = HubDeliverySent::where('user_id', auth()->id())->get();
        $deliveryBoyOrderId = [];
        foreach ($Delivery as $orderIds) {
            $orderIds = json_decode($orderIds->order_id);
            foreach ($orderIds as $ID) {
                $deliveryBoyOrderId[] = $ID;
            }
        }
        $deliveredOrders = Delivered::where('user_id', auth()->id())->pluck('order_id')->toArray();
        $DeliveredOrderId = [];

        foreach ($deliveredOrders as $deliveredId) {
            $deliveredId = json_decode($deliveredId);

            foreach ($deliveredId as $DID) {
                $DeliveredOrderId[] = $DID;
            }
        }

        foreach ($deliveryBoys as $d) {
            $boy_orders = HubDeliverySent::where('user_id', auth()->id())->where('delivery_boy_id', $d->id)->pluck('order_id')->toArray();
            $bo = [];
            foreach ($boy_orders as $bOrder) {
                $do = json_decode($bOrder);
                foreach ($do as $de) {
                    $bo[] = $de;
                }
            }
            $orders = array_diff($bo, $DeliveredOrderId);
            $d->orders = $orders;
            $d->show = count($orders) > 0 ? 'true' : 'false';
        }
        return response()->json($deliveryBoys);
    }
    public function getdeliveryboyorderdetail($id)
    {
        // dd('vds');
        $order = Order::where('order_id', $id)->first();
        $order->product_type = json_decode($order->product_type);
        $order->vendor_name = User::where('id', $order->sender_id)->first()->name;
        $order->customer_name = UserAddress::where('id', $order->receiver_id)->first()->first_name . ' ' . UserAddress::where('id', $order->receiver_id)->first()->last_name;
        return response()->json($order);
    }
}
