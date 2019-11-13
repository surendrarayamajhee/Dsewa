<?php

namespace App\Http\Controllers\Hub;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\PaginationHelper;
use App\Order;
use App\SendPickUp;
use App\ShipmentSent;
use App\HubArea;
use App\Address;
use App\UserAddress;

class HubOrderController extends Controller
{
    use PaginationHelper;
    public function getordersList(Request $request)
    {

        //    get hub order from pickup orders
        $pickupOrders = SendPickUp::all();
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
        $shipmentSents = ShipmentSent::all();
        foreach ($shipmentSents as $shipmentSent) {
            $orders = json_decode($shipmentSent->order_id);
            foreach ($orders as $order) {
                $allshipmentSentOrders[] = $order;
            }
        }
        //  remove order id from pickuporder exist in shipment sent
        $orders_id = array_diff($allpickupOrders, $allshipmentSentOrders);
        $orders = Order::whereIn('order_id', $orders_id)->get();
        foreach ($orders as $order) {
            $userAddress = UserAddress::where('id', $order->receiver_id)->first();
            $order->address = Address::where('id', $userAddress->ward_no)->pluck('id')->first();
        }

        // getting hub and address relation
        $hubAreas = [];
        $hubs = array_unique($orders->pluck('hub_id')->toArray());
        foreach ($hubs as $hub) {
            $hubAreas[] = HubArea::where('hub_id', $hub)->first();
        }
        $hubAddresses = [];
        //    getting address from hub address relation
        foreach ($hubAreas as $hubArea) {
            $hubAddresses[] = Address::where('id', $hubArea->address_id)->first();
        }

        //    adding municipality name in hubaddress
        foreach ($hubAddresses as $hubAddress) {
            $hubAddress->parent_name =   Address::where('id', $hubAddress->parent_id)->pluck('address')->first();
            $hubAddress->orders = $orders->where('address', $hubAddress->id);
        }
        //    $orders = $this->paginateHelper($orders, 2);
        return response()->json([
            'hubAddresses' => $hubAddresses
        ]);
    }
    
}
