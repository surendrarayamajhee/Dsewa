<?php

namespace App\Http\Controllers\log;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Order;
use App\ReceivePickup;
use App\User;
use App\ShipmentSent;
use App\HubDeliverySent;
use App\Delivered;
use App\UserAddress;
use App\OrderReturnedOrder;
use App\ShipmentReceive;

class OrderLogControler extends Controller
{
    //
    public function get_order_log(Request $request)
    {
        // $shipment = ShipmentSent::where('received',1)->get()->count();
        // $received = ShipmentReceive::all()->count();
        // dd($shipment);
        $log = Order::where('order_id', $request->id)->select('created_at', 'order_id')->get();
        $pickup_received_order = [];
        $ship_sent = [];
        $ship_sent_received = [];
        $log->transform(function ($item, $key) {
            $date = Carbon::parse($item->created_at);
            $item->created_date = 'Order Created At ' . '<span class="badge badge-light">' . $date->isoFormat('YYYY MMMM DD h:mm:ss  ') . '</span>';
            $received = ReceivePickup::where('order_id', $item->order_id)->select('user_id', 'created_at')->get();

            if ($received) {
                foreach ($received as $key => $value) {
                    $user_name = '<span class="badge badge-primary">' . User::where('id', $value->user_id)->first()->name . '</span>';
                    $date = Carbon::parse($value->created_at);
                    $pickup_received_order[] = 'Received by ' . $user_name . ' at ' . '<span class="badge badge-light">' . $date->isoFormat('YYYY MMMM DD h:mm:ss ') . '</span>';
                    $item->pickup_received_order = $pickup_received_order;
                }
            }

            $shipment_sent = ShipmentSent::all();
            foreach ($shipment_sent as $sent) {
                if (in_array($item->order_id, json_decode($sent->order_id))) {
                    $date = Carbon::parse($sent->created_at);
                    $ship_sent[] = 'Shipped From ' . '<span class="badge badge-primary">' . User::where('id', $sent->from)->first()->name . '</span>' . ' To ' . '<span class="badge badge-primary">' . User::where('id', $sent->to)->first()->name . '</span>' . ' in ' . '<span class="badge badge-light">' . $date->isoFormat('YYYY MMMM DD h:mm:ss ') . '</span>';
                    $item->ship_sent = $ship_sent;

                    if ($sent->received == 1) {

                        $shipment_received = ShipmentReceive::where('shipment_id', $sent->shipment_id)->first();
                        // dd($shipment_received);
                        $date = Carbon::parse($shipment_received->created_at);
                        $ship_sent[] = '<br>' . 'Shipment Received By ' . '<span class="badge badge-primary">' . User::where('id', $shipment_received->user_id)->first()->name . '</span>' . ' Shipped From ' . '<span class="badge badge-primary">' . User::where('id', $sent->from)->first()->name . '</span>' . ' AT ' . '<span class="badge badge-light">' . $date->isoFormat('YYYY MMMM DD h:mm:ss ') . '</span>' . '<br>';
                        $item->ship_sent = $ship_sent;
                    }
                }
            }



            $hub_delivery_sent = [];
            $hubdeliverysent = HubDeliverySent::all();
            foreach ($hubdeliverysent as $hubsent) {
                if (in_array($item->order_id, json_decode($hubsent->order_id))) {
                    $date = Carbon::parse($hubsent->created_at);

                    $hub_delivery_sent[] = 'Delivery Sent From ' . User::where('id', $hubsent->user_id)->first()->name .
                        ' To  Delivery officer ' . '<span class="badge badge-success">' . User::where('id', $hubsent->delivery_boy_id)->first()->name . '</span>' . ' in '
                        . '<span class="badge badge-light">' . $date->isoFormat('YYYY MMMM DD h:mm:ss ') . '</span>';
                    $item->hub_delivery_sent = $hub_delivery_sent;
                }
            }


            $delivered_list = [];
            $deliverd = Delivered::all();
            foreach ($deliverd as $d) {
                if (in_array($item->order_id, json_decode($d->order_id))) {
                    $date = Carbon::parse($hubsent->created_at);

                    $delivered_list[] = 'Order Successfully delivered by  ' . '<span class="badge badge-success">' .
                        User::where('id', $d->delivery_boy_id)->first()->name . '</span>' . ' Assigned By ' . '<span class="badge badge-primary">' . User::where('id', $d->user_id)->first()->name
                        . '</span>' . ' To Customer ' . '<span class="badge badge-secondary">' .
                        UserAddress::where('id', Order::where('order_id', $item->order_id)->first()->receiver_id)->first()->first_name . '</span>' . ' At ' . '<span class="badge badge-light">' . $date->isoFormat('YYYY MMMM DD h:mm:ss ') . '</span>';
                    $item->delivered_list = $delivered_list;
                }
            }

            $child = OrderReturnedOrder::where('old_order_id', $item->order_id)->pluck('new_order_id')->toArray();
            $item->child_order = $child;

            $parant = OrderReturnedOrder::where('new_order_id', $item->order_id)->pluck('old_order_id')->toArray();
            $item->parant = array_unique($parant);


            return $item;
        });
        return response()->json($log);
    }

}
