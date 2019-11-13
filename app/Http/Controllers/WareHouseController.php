<?php

namespace App\Http\Controllers;

use App\Address;
use App\HubDeliverySent;
use App\Order;
use App\OrderReturnedOrder;
use App\OrderStatusChangeRequest;
use App\PickUpOrder;
use App\ReceivePickup;
use App\ShipmentSent;
use App\User;
use App\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WareHouseController extends Controller
{
    public function getShipmentOrders()
    {
        $id = auth()->id();
        $order_id = [];
        $return_order_id = [];
        $shipment1 = ShipmentSent::where('to',$id)->get();
        foreach ($shipment1 as $shipment) {
            $ship = json_decode($shipment->order_id);
            foreach ($ship as $s) {
                $order = Order::where('order_id', $s)->first();
                if ($order) {

                    if ($order->order_status == 1  && $order->order_created_as != 'RETURN') {
                        $order_id[] = $order->order_id;
                    }
                    if ($order->order_status == 1  && $order->order_created_as == 'RETURN') {
                        $return_order_id[] = $order->order_id;
                    }
                }
            }
        }




        $receivedP = ReceivePickup::where('user_id', $id)->pluck('order_id')->toArray();
        $shipment2 = [];
        $return_shipment2 = [];
        foreach ($receivedP as $p) {
            $pOrder = Order::where('order_id', $p)->first();
            if ($pOrder->order_status == 1  && $pOrder->order_created_as != 'RETURN') {
                $shipment2[] = $p;
            }
            if ($pOrder->order_status == 1  && $pOrder->order_created_as  == 'RETURN') {
                $return_shipment2[] = $p;
            }
        }


        $shipment3 = ShipmentSent::where('user_id', $id)->pluck('order_id')->toArray();
        $abc = [];
        foreach ($shipment3 as $shipment) {
            $orderjsons = json_decode($shipment);
            foreach ($orderjsons as $id) {
                $abc[] = $id;
            }
        }
        $o = array_merge($shipment2, $order_id);
        $return_o = array_merge($return_shipment2, $return_order_id);
        $halforders = OrderStatusChangeRequest::where('status_id', 4)->orwhere('status_id', 8)->get();
        $halforders = $halforders->where('vendor_id', $id)->where('request_status', 1)->pluck('order_id')->toArray();
        $returningOrders = [];
        foreach ($halforders as $ho) {
            $inReceivedPickup = ReceivePickup::where('order_id', $ho)->first();
            if ($inReceivedPickup) {
                $returningOrders[] = $ho;
            }
        }
        $returningid = [];
        foreach ($returningOrders as $ho) {
            $returningid[] = OrderReturnedOrder::where('old_order_id', $ho)->first()->new_order_id;
        }
        $returning_half_merge = array_merge($return_o, $returningid);
        $returning_orders = array_diff($returning_half_merge, $abc);
        $orders = array_diff($o, $abc);
        //normal shipment
        $sOrders = Order::whereIn('order_id', $orders)->select('hub_id', 'order_id')->get();
        $branches = array_unique($sOrders->pluck('hub_id')->toArray());
        $shipmentOrders = User::whereIn('id', $branches)->select('name', 'id')->get();


        foreach ($shipmentOrders as $branch) {
            $branch->orders = $sOrders->where('hub_id', $branch->id)->pluck('order_id')->toArray();
            $branch->shipmentTotal = count($branch->orders);
            $branch->shipmentCod = 0;
            foreach ($branch->orders as $order) {
                $orderCod = Order::where('order_id', $order)->first()->cod;

                $branch->shipmentCod = $branch->shipmentCod + $orderCod;
            }
        }
        //Return Shipment
        $return_sOrders = Order::whereIn('order_id', $returning_orders)->select('hub_id', 'order_id')->get();
        $return_branches = array_unique($return_sOrders->pluck('hub_id')->toArray());
        $return_shipmentOrders = User::whereIn('id', $return_branches)->select('name','id')->get();



        foreach ($return_shipmentOrders as $return_branch) {
            $return_branch->orders = $return_sOrders->where('hub_id', $return_branch->id)->pluck('order_id')->toArray();
            $return_branch->shipmentCod = 0;
            $return_branch->shipmentTotal = count($return_branch->orders);

            foreach ($return_branch->orders as $return_order) {
                $return_orderCod = Order::where('order_id', $return_order)->first()->cod;

                $return_branch->shipmentCod = $return_branch->shipmentCod + $return_orderCod;
            }
        }
        return response()->json(['shipmentOrders' => $shipmentOrders, 'return_shipmentOrders' => $return_shipmentOrders]);
    }

    public function getDeliveryOrders()
    {
        $id = auth()->id();
        $deliverys = HubDeliverySent::orderBy('id', 'Desc')->where('user_id', $id)->get();

        $shipment_rec = ShipmentSent::where('to', $id)->where('received', 1)->get();

        $orders_id = [];
        foreach ($shipment_rec as $rec) {

            $js_rec = json_decode($rec->order_id);
            foreach ($js_rec as $rec) {
                $ord = Order::where('order_id', $rec)->first();
                if ($ord->hub_id == $id) {

                    $orders_id[] = $rec;
                }
            }
        }
        $orders = Order::whereIn('order_id', $orders_id)->select('order_id', 'order_created_as', 'expected_date')->get();
        $returnToVendorOrders = $orders->where('order_created_as', 'RETURN')->pluck('order_id')->toArray();
        $deliveryToCustomersId = $orders->where('order_created_as', '!=', 'RETURN');
        $todayDelivery = $deliveryToCustomersId->where('expected_date', Carbon::today()->toDateString())->pluck('order_id')->toArray();
        $futureDelivery = $deliveryToCustomersId->where('expected_date', '!=', Carbon::today()->toDateString())->pluck('order_id')->toArray();

        $halforders = OrderStatusChangeRequest::where('status_id', 4)->orwhere('status_id', 8)->get();
        $halforders = $halforders->where('vendor_id', $id)->where('request_status', 1)->pluck('order_id')->toArray();
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


        $returningid = [];
        foreach ($returningOrders as $ho) {
            $returningid[] = OrderReturnedOrder::where('old_order_id', $ho)->first()->new_order_id;
        }
        $returningtoVendor = array_merge($returnToVendorOrders, $returningid);

        $abc = [];
        foreach ($deliverys as $delivery) {
            $orderjsons = json_decode($delivery->order_id);
            foreach ($orderjsons as $id) {
                $abc[] = $id;
            }
        }
        $customerDeliveryOrders = array_diff($todayDelivery, $abc);
        $vendorDeliveryOrders = array_diff($returningtoVendor, $abc);
        $futureDelivery = array_diff($futureDelivery, $abc);
        $true_future=Order::whereIn('order_id',$futureDelivery)->where('expected_date','>',Carbon::today()->todateString())->pluck('order_id')->toArray();
        $thrash=Order::whereIn('order_id',$futureDelivery)->where('expected_date','<',Carbon::today()->toDateString())->pluck('order_id')->toArray();

        $thrashCount=count($thrash);
        $thrashTotal = 0;
    foreach ($thrash as $t) {
            $thrashTotal = $thrashTotal + Order::where('order_id', $t)->first()->cod;
        }
        $futureDeliveryCount = count($true_future);
        $futureDeliveryTotal = 0;
        foreach ($true_future as $f) {
            $futureDeliveryTotal = $futureDeliveryTotal + Order::where('order_id', $f)->first()->cod;
        }


        $customerOrdersWard = Order::whereIn('order_id', $customerDeliveryOrders)->select('receiver_id', 'order_id')->get();
        $useraddress = UserAddress::whereIn('id', $customerOrdersWard->pluck('receiver_id')->toArray())->select('id', 'ward_no')->get();
        $wardno = array_unique($useraddress->pluck('ward_no')->toArray());
        $address = Address::whereIn('id', $wardno)->select('address', 'id', 'parent_id')->get();
        foreach ($address as $add) {
            $muni = Address::where('id', $add->parent_id)->first();
            $add->municipality = $muni->address;
            $add->district = Address::where('id', $muni->parent_id)->first()->address;
            $add->orders = $customerOrdersWard->whereIn('receiver_id', $useraddress->where('ward_no', $add->id)->pluck('id')->toArray())->pluck('order_id')->toArray();
            $add->cod = 0;
            $add->total = count($add->orders);
            foreach ($add->orders as $orde) {
                $add->cod = $add->cod + Order::where('order_id', $orde)->first()->cod;
            }
        }
        $vendorOrders = Order::whereIn('order_id', $vendorDeliveryOrders)->select('sender_id', 'order_id', 'cod')->get();
        $vendors = $vendorOrders->pluck('sender_id')->toArray();
        $uniqueVendors = array_unique($vendors);
        $allVendor = User::whereIn('id', $uniqueVendors)->select('id', 'name')->get();

        foreach ($allVendor as $vendor) {

            $vendor->orders = $vendorOrders->where('sender_id', $vendor->id)->pluck('order_id')->toArray();
            $vendor->cod = 0;
            $vendor->total = count($vendor->orders);
            foreach ($vendor->orders as $orde) {
                $vendor->cod = $vendor->cod + $vendorOrders->where('order_id', $orde)->first()->cod;
            }
        }
        return response()->json(['returnToVendor' => $allVendor, 'futureDeliveries' => $true_future, 'todayDelivery' => $address, 'futureDeliveryTotal' => $futureDeliveryTotal, 'futureDeliveryCount' => $futureDeliveryCount,'thrashOrders'=>$thrash,'thrashCount'=>$thrashCount,'thrashTotal'=>$thrashTotal]);
    }
    public function incompleteAddress()
    {
        $id = auth()->id();
        $orders = Order::orderBy('id', 'DESC')->where('order_status', 0)->where('pickup_hub', $id)->select('order_id', 'cod')->get();
        $cod = 0;
        $total = $orders->count();
        foreach ($orders as $order) {
            $cod = $cod + $order->cod;
        }
        return  response()->json(['orders' => $orders, 'cod' => $cod, 'total' => $total]);
    }
    public function getHoldOrders()
    {
        $id = auth()->id();
        $orders = Order::orderBy('id', 'DESC')->where('order_status', 7)->where('hub_id', $id)->select('order_id', 'cod')->get();
        $cod = 0;
        $total = $orders->count();
        foreach ($orders as $order) {
            $cod = $cod + $order->cod;
        }
        return  response()->json(['orders' => $orders, 'cod' => $cod, 'total' => $total]);
    }
    public function getpickupOrders(){
        $id=auth()->id();
        $receive=ReceivePickup::where('user_id',$id)->whereDate('created_at',Carbon::today())->pluck('order_id')->toArray();
        $vendorList=Order::whereIn('order_id',$receive)->where('order_created_as','!','RETURN')->select('order_id','cod','sender_id')->get();
        $unique=array_unique($vendorList->pluck('sender_id')->toArray());
        $users=User::whereIn('id',$unique)->select('name','id')->get();
        foreach($users as $user){
            $user->orders=$vendorList->where('sender_id',$user->id)->pluck('order_id')->toArray();
            $user->total=count($user->orders);
            $user->cod=0;
            foreach($user->orders as $order){
                $user->cod=$user->cod+ $vendorList->where('order_id',$order)->first()->cod;
            }
        }
        return response()->json(['pickupOrders'=>$users]);


    }


}
