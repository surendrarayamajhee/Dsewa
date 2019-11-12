<?php

namespace App\Http\Controllers\stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\SendPickUp;
use App\ShipmentSent;
use App\User;
use App\Helpers\PaginationHelper;

class StockController extends Controller
{
    //
    use PaginationHelper;


    public function outgoing(Request $request)
    {

        //    get hub order from pickup orders
        $pickupOrders = SendPickUp::where('user_id',auth()->id())->get();
        $allpickupOrders = [];
        //   get order list fron sendpickup orders
        foreach ($pickupOrders as $pickupOrder) {
            $orders = json_decode($pickupOrder->orders);
            foreach ($orders as $order) {
                $allpickupOrders[] = $order;
            }
        }
        //     get all hub orders from shipmentsents
        $allshipmentSentOrders = [];
        $shipmentSents = ShipmentSent::where('from',auth()->id())->get();
        foreach ($shipmentSents as $shipmentSent) {
            $orders = json_decode($shipmentSent->order_id);
            foreach ($orders as $order) {
                $allshipmentSentOrders[] = $order;
            }
        }
        // dd($allshipmentSentOrders);

        //  remove order id from pickuporder exist in shipment sent
        $orders_id = array_diff($allpickupOrders, $allshipmentSentOrders);
        $orders = Order::whereIn('order_id', $orders_id)->where('order_status',1)->get();


        $hubs = array_unique($orders->pluck('hub_id')->toArray());
        $outgoingHubs = [];
        foreach ($hubs as $hub) {
            $outgoingHubs[] = User::where('id',$hub)->select('name','id')->first();
        }
        $outgoingOrders=[];
        foreach($outgoingHubs as $outgoingHub)
        {
            foreach($orders as $order)
            {
                if($order->hub_id == $outgoingHub->id)
                {
                    $outgoingOrders[] =$order;
                    $outgoingHub->orders=$outgoingOrders;

                }
            }
            $outgoingOrders=[];

        }
        $outgoingHubs = $this->paginateHelper($outgoingHubs, 10);
        return response()->json($outgoingHubs);

    }
}
