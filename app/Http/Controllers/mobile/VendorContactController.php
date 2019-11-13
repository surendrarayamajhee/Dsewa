<?php

namespace App\Http\Controllers\mobile;

use App\Address;
use App\BusinessInfo;
use App\Delivered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Vendor_Info;
use App\Http\Resources\VendorContact;
use App\Http\Resources\LogResource;

use App\HubDeliverySent;
use App\Order;
use App\ReceivePickup;
use App\ShipmentReceive;
use App\ShipmentSent;
use App\UserAddress;
use App\VendorPickup;
use Carbon\Carbon;

class VendorContactController extends Controller
{
    //
    public function getvendorcontact($id)
    {

        $vendor = Vendor_Info::where('vendor_id', $id)->first();

        return new VendorContact($vendor);
    }

    public function getorder($id)
    {
        $order = Order::where('bar_code', $id)->orWhere('order_id', $id)->first();

        $from_vendor = array();
        $to_reciver = array();

        $from_vendor =  array(
            'name' => User::where('id', $order->sender_id)->first()->name,
            'state' => Address::where('id', VendorPickup::where('id', $order->order_pickup_point)->first()->state_id)->first() ?  Address::where('id', VendorPickup::where('id', $order->order_pickup_point)->first()->state_id)->first()->address : '',
            'district' => Address::where('id', VendorPickup::where('id', $order->order_pickup_point)->first()->district_id)->first() ? Address::where('id', VendorPickup::where('id', $order->order_pickup_point)->first()->district_id)->first()->address : '',
            'municipality' => Address::where('id', VendorPickup::where('id', $order->order_pickup_point)->first()->municipality_id)->first() ? Address::where('id', VendorPickup::where('id', $order->order_pickup_point)->first()->municipality_id)->first()->address : '',
            'ward' => Address::where('id', VendorPickup::where('id', $order->order_pickup_point)->first()->ward_no)->first() ?  Address::where('id', VendorPickup::where('id', $order->order_pickup_point)->first()->ward_id)->first()->address : '',
            'area' => Address::where('id', VendorPickup::where('id', $order->order_pickup_point)->first()->area_id)->first() ? Address::where('id', VendorPickup::where('id', $order->order_pickup_point)->first()->area_id)->first()->address : '',
            'address_description' =>  VendorPickup::where('id', $order->order_pickup_point)->first()->tole,

            'phone1' =>  Vendor_Info::where('vendor_id', $order->sender_id)->first()->phone1,
            'phone2' =>  Vendor_Info::where('vendor_id', $order->sender_id)->first()->phone2,

        );
        $to_reciver =  array(
            'first_name' => UserAddress::where('id', $order->receiver_id)->first()->first_name,
            'first_last' => UserAddress::where('id', $order->receiver_id)->first()->last_name,
            'phone1' =>  UserAddress::where('id', $order->receiver_id)->first()->phone1,
            'phone2' =>  UserAddress::where('id', $order->receiver_id)->first()->phone2,
            'state' => Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->state_no)->first() ?  Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->state_no)->first()->address : '',
            'district' => Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->district)->first() ? Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->district)->first()->address : '',
            'municipality' => Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->municipality)->first() ? Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->municipality)->first()->address : '',
            'ward' => Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->ward_no)->first() ?  Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->ward_no)->first()->address : '',
            'area' => Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->area)->first() ? Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->area)->first()->address : '',
            'address_description' => UserAddress::where('id', $order->receiver_id)->first()->description,

        );
        $order->product_type = json_decode($order->product_type);
        $order->from_vendor = $from_vendor;
        $order->to_reciver = $to_reciver;

        // $order->to_hub = User::where('id', $order->to)->first();
        return new VendorContact($order);
    }

    public function orderlog(Request $request, $id)
    {
        $logs = Order::where('order_id', $id)->select('created_at', 'order_id', 'hub_id', 'pickup_hub', 'sender_id', 'receiver_id')->first();
        //    dd($logs->created_at);
        $date = Carbon::parse($logs->created_at);
        $created_at =  $date->isoFormat('YYYY-MM-DD hh:mm:ss');
        $log[] = [
            'location' => BusinessInfo::where('user_id', $logs->sender_id)->first() ? BusinessInfo::where('user_id', $logs->sender_id)->first()->tole : '',
            'message' => 'created',
            'date'  => $created_at
        ];
        $logs->log = $log;


        $received = ReceivePickup::where('order_id', $logs->order_id)->select('user_id', 'created_at')->get();


        if ($received) {
            foreach ($received as $key => $value) {
                $date = Carbon::parse($value->created_at);

                $created_at =  $date->isoFormat('YYYY-MM-DD hh:mm:ss');
                $log[] = [
                    'location' => BusinessInfo::where('user_id', $logs->pickup_hub)->first() ?  BusinessInfo::where('user_id', $logs->pickup_hub)->first()->tole : '',
                    'message' => 'picked',
                    'date'  =>  $created_at,
                ];
                $logs->log = $log;
            }
        }

        $shipment_sent = ShipmentSent::all();
        foreach ($shipment_sent as $sent) {
            if (in_array($logs->order_id, json_decode($sent->order_id))) {
                $date = Carbon::parse($sent->created_at);
                $created_at =  $date->isoFormat('YYYY-MM-DD hh:mm:ss');

                $log[] = [
                    'location' =>  BusinessInfo::where('user_id', $logs->pickup_hub)->first() ?  BusinessInfo::where('user_id', $logs->pickup_hub)->first()->tole : '',
                    'message' => 'In Transit',
                    'date'  =>  $created_at
                ];

                $logs->log = $log;
                if ($sent->received == 1) {
                    $shipment_received = ShipmentReceive::where('shipment_id', $sent->shipment_id)->first();
                    $date = Carbon::parse($value->created_at);
                    $created_at =  $date->isoFormat('YYYY-MM-DD hh:mm:ss');
                    $log[] = [
                        'location' =>  BusinessInfo::where('user_id', $logs->hub_id)->first() ? BusinessInfo::where('user_id', $logs->hub_id)->first()->tole : '',
                        'message' => 'Delivery hub received',
                        'date'  =>  $created_at
                    ];
                    $logs->log = $log;
                }
            }
        }



        $hubdeliverysent = HubDeliverySent::all();
        foreach ($hubdeliverysent as $hubsent) {
            if (in_array($logs->order_id, json_decode($hubsent->order_id))) {
                // $date = Carbon::parse($hubsent->created_at);
                $date = Carbon::parse($hubsent->created_at);
                $created_at =  $date->isoFormat('YYYY-MM-DD hh:mm:ss');
                $log[] = [
                    'location' =>  BusinessInfo::where('user_id', $logs->hub_id)->first() ? BusinessInfo::where('user_id', $logs->hub_id)->first()->tole : '',
                    'message' => 'Delivery hub assigned delivery boy',
                    'date'  =>  $created_at,
                ];
                $logs->log = $log;
            }
        }


        $deliverd = Delivered::all();
        foreach ($deliverd as $d) {
            if (in_array($logs->order_id, json_decode($d->order_id))) {
                $date = Carbon::parse($d->created_at);
                $created_at =  $date->isoFormat('YYYY-MM-DD hh:mm:ss');
                $log[] = [
                    'location' => Address::where('id', UserAddress::where('id', $logs->receiver_id)->first() ? UserAddress::where('id', $logs->receiver_id)->first()->district : '')->first() ? Address::where('id', UserAddress::where('id', $logs->receiver_id)->first() ? UserAddress::where('id', $logs->receiver_id)->first()->district : '')->first()->address : "",
                    'message' => 'Delivered',
                    'date'  =>  $created_at,
                ];
                $logs->log = $log;
            }
        }

        return new LogResource($logs);
    }
}
