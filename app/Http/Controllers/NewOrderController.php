<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationHelper;

use Illuminate\Http\Request;
use App\SendPickUp;
use App\Order;
use Auth;
use App\User;
use App\UserAddress;
use Carbon\Carbon;
use App\BusinessInfo;
use App\HubDeliverySent;
use App\OrderComment;
use App\OrderReturnedOrder;
use App\ReceivePickup;
use App\OrderStatus;
use App\OrderStatusChangeRequest;
use App\PickUpOrder;
use App\ShipmentSent;

class NewOrderController extends Controller
{
    use PaginationHelper;
    public function newPickup(Request $request)
    {
        $pickupOrders = ReceivePickup::all();
        $allpickupOrders = [];
        //   get order list fron sendpickup orders
        foreach ($pickupOrders as $pickupOrder) {
            // $orders = json_decode($pickupOrder->orders);
            // foreach ($orders as $pickupOrder->orders) {
            $allpickupOrders[] = $pickupOrder->order_id;
            // }
        }



        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $orders = Order::orderBy('id', 'DESC')->pluck('order_id')->toArray();
        }
        if ($user->hasRole('hub')) {
            $orders1 = Order::where('pickup_hub', auth()->id())->where('order_status', 0)->pluck('order_id')->toArray();
            $orders2 = Order::where('pickup_hub', auth()->id())->where('order_status', 1)->pluck('order_id')->toArray();
            $orders = array_unique(array_merge($orders1, $orders2));
        }
        $orders_id = array_diff($orders, $allpickupOrders);

        $orders = Order::orderBy('id', 'DESC')->whereIn('order_id', $orders_id)->get();

        if ($request->has('from') && $request->has('to')) {
            $orders =  $orders->whereBetween('order_date', [$request->from, $request->to]);
        }
        if ($request->has('orderid')) {
            $orders = $orders->where('order_id', $request->orderid);
        }
        if ($request->has('paginate')) {
            $p = $request->input('paginate');
        } else {
            $p = 10;
        }
        // dd($orders);
        foreach ($orders as $order) {



            $order->vendor_name = User::findOrfail($order->sender_id)->name;
            $order->useraddress = UserAddress::where('id', $order->receiver_id)->first();
            $order->role = Auth::user()->roles()->first()->name;


            $order->deliverHub = User::where('id', $order->hub_id)->first() ? User::where('id', $order->hub_id)->first()->name : '-';
            $order->pickupHub = User::where('id', $order->pickup_hub)->first() ? User::where('id', $order->pickup_hub)->first()->name : '-';
            // user address

            // vendor address
            // $pickup = VendorPickup::where('id',$order->order_pickup_point)->first();
            // $pickup->state = Address::where('id', $pickup->state_id)->first()->address;
            // $pickup->district = Address::where('id', $pickup->district_id)->first()->address;
            // $pickup->municipality = Address::where('id', $pickup->municipality_id)->first()->address;
            // $pickup->ward = Address::where('id', $pickup->ward_id)->first()->address;
            // $pickup->area = Address::where('id', $pickup->area_id)->first()->address;

            // $order->Vendor_pickup_point = $pickup;
            $date = Carbon::parse($order->expected_date);
            $order->expecteddate =  $date->isoFormat('YYYY MMMM Do h:mm:ss a');
            $date = Carbon::parse($order->created_at);
            $order->orderdate =  $date->isoFormat('MMMM Do');
            $order->product_type = json_decode($order->product_type);
        }

        $orders = $this->paginateHelper($orders, $p);
        return response()->json($orders);
    }
    public function currentOrder(Request $request)
    {

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $orders = Order::orderBy('id', 'DESC');
        } elseif ($user->hasRole('vendor')) {

            $orders = Order::orderBy('id', 'DESC')->where('sender_id', auth()->id());
        } elseif ($user->hasRole('hub')) {
            $orders0 = Order::orderBy('id', 'DESC')->where('hub_id', auth()->id());
            $orders1 = collect($orders0->where('order_status', 1)->get());
            $orders2 = collect($orders0->where('order_status', 7)->get());
            $orders = $orders1->merge($orders2)->unique()->sortByDesc('order_id');
        }

        if ($request->has('from') && $request->has('to')) {
            $orders =  $orders->whereBetween('order_date', [$request->from, $request->to]);
        }
        if ($request->has('orderid')) {
            $orders = $orders->where('order_id', $request->orderid);
        }
        if ($request->has('paginate')) {
            $p = $request->input('paginate');
        } else {
            $p = 10;
        }
        // $orders = $orders->get();

        $orders->transform(function ($item, $key) {
            $item->vendor_name = User::findOrfail($item->sender_id) ? User::findOrfail($item->sender_id)->name : '';
            $item->useraddress = UserAddress::where('id', $item->receiver_id)->first();
            $item->role = Auth::user()->roles()->first()->name;
            $item->o_status = OrderStatus::where('id', $item->order_status)->first() ? OrderStatus::where('id', $item->order_status)->first()->name : 'Incomplete Address';

            $item->deliverHub = User::where('id', $item->hub_id)->first() ? User::where('id', $item->hub_id)->first()->name : '-';
            $item->pickupHub = User::where('id', $item->pickup_hub)->first() ? User::where('id', $item->pickup_hub)->first()->name : '-';

            // user address

            // vendor address
            // $pickup = VendorPickup::where('id',$item->order_pickup_point)->first();
            // $pickup->state = Address::where('id', $pickup->state_id)->first()->address;
            // $pickup->district = Address::where('id', $pickup->district_id)->first()->address;
            // $pickup->municipality = Address::where('id', $pickup->municipality_id)->first()->address;
            // $pickup->ward = Address::where('id', $pickup->ward_id)->first()->address;
            // $pickup->area = Address::where('id', $pickup->area_id)->first()->address;

            // $item->Vendor_pickup_point = $pickup;
            $date = Carbon::parse($item->expected_date);
            $item->expecteddate =  $date->isoFormat('YYYY MMMM Do h:mm:ss a');
            $date = Carbon::parse($item->created_at);
            $item->orderdate =  $date->isoFormat('MMMM Do');
            $item->product_type = json_decode($item->product_type);
            return $item;
        });

        $orders = $this->paginateHelper($orders, $p);
        return response()->json($orders);
    }
    public function unSignedOrder(Request $request)
    {

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $orders = Order::orderBy('id', 'DESC')->where('order_status', 0);
        } elseif ($user->hasRole('vendor')) {

            $orders = Order::orderBy('id', 'DESC')->where('sender_id', auth()->id())->where('order_status', 0);
        } elseif ($user->hasRole('hub')) {

            // $useraddress =
            $orders = Order::orderBy('id', 'DESC')->where('order_status', 0)->where('pickup_hub', auth()->id());

            // $district_id=BusinessInfo::where('user_id',auth()->id())->first()->district;

            // foreach($orders as $order){

            // }
        }

        if ($request->has('from') && $request->has('to')) {
            $orders =  $orders->whereBetween('order_date', [$request->from, $request->to]);
        }
        if ($request->has('orderid')) {
            $orders = $orders->where('order_id', $request->orderid);
        }
        if ($request->has('paginate')) {
            $p = $request->input('paginate');
        } else {
            $p = 10;
        }
        $orders = $orders->get();
        $orders->transform(function ($item, $key) {
            $item->vendor_name = User::findOrfail($item->sender_id)->name;
            $item->useraddress = UserAddress::where('id', $item->receiver_id)->first();
            $item->o_status = OrderStatus::where('id', $item->order_status)->first() ? OrderStatus::where('id', $item->order_status)->first()->name : 'Incomplete Address';
            $item->deliverHub = User::where('id', $item->hub_id)->first() ? User::where('id', $item->hub_id)->first()->name : '-';
            $item->pickupHub = User::where('id', $item->pickup_hub)->first() ? User::where('id', $item->pickup_hub)->first()->name : '-';
            $item->role = Auth::user()->roles()->first()->name;


            // user address

            // vendor address
            // $pickup = VendorPickup::where('id',$item->order_pickup_point)->first();
            // $pickup->state = Address::where('id', $pickup->state_id)->first()->address;
            // $pickup->district = Address::where('id', $pickup->district_id)->first()->address;
            // $pickup->municipality = Address::where('id', $pickup->municipality_id)->first()->address;
            // $pickup->ward = Address::where('id', $pickup->ward_id)->first()->address;
            // $pickup->area = Address::where('id', $pickup->area_id)->first()->address;

            // $item->Vendor_pickup_point = $pickup;
            $date = Carbon::parse($item->expected_date);
            $item->expecteddate =  $date->isoFormat('YYYY MMMM Do h:mm:ss a');
            $date = Carbon::parse($item->order_date);
            $item->orderdate =  $date->isoFormat('YYYY MMMM Do h:mm:ss a');
            $item->product_type = json_decode($item->product_type);
            return $item;
        });

        $orders = $this->paginateHelper($orders, $p);
        return response()->json($orders);
    }
    public function failedtransaction()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $orders = Order::orderBy('id', 'DESC');
        } elseif ($user->hasRole('vendor')) {

            $orders = Order::orderBy('id', 'DESC')->where('sender_id', auth()->id());
        } elseif ($user->hasRole('hub')) {
            $orders0 = Order::orderBy('id', 'DESC')->where('hub_id', auth()->id());
            $orders1 = collect($orders0->where('order_status', 1)->get());
            $orders2 = collect($orders0->where('order_status', 7)->get());
            $orders = $orders1->merge($orders2)->unique()->sortByDesc('order_id');
        }
        $failed = [];
        foreach ($orders as $order) {
            if ($order->order_created_as == "RETURN") {
                if (Carbon::parse($order->created_at)->diffInDays() >= 6) {
                    $failed[] = $order->order_id;
                }
            } else {
                if (Carbon::parse($order->expected_date)->toDateString() >  date("Y-m-d") && Carbon::parse($order->expected_date)->diffInDays() >= 1) {
                    $failed[] = $order->order_id;
                }
            }
        }
        $failedOrders = Order::whereIn('order_id', $failed)->get();


        $failedOrders->transform(function ($item, $key) {
            $item->vendor_name = User::findOrfail($item->sender_id) ? User::findOrfail($item->sender_id)->name : '';
            $item->useraddress = UserAddress::where('id', $item->receiver_id)->first();
            $item->weight =  $item->weight ?  $item->weight : "";
            $item->o_status = OrderStatus::where('id', $item->order_status)->first() ? OrderStatus::where('id', $item->order_status)->first()->name : 'Incomplete Address';
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
            $item->expecteddate =  $d == 0  ? 'Today' : $d . ' days ' . $x;

            $date = Carbon::parse($item->created_at);
            $item->orderdate =  $date->isoFormat('MMMM Do');
            $date = Carbon::parse($item->expected_date);
            $item->expected_date =  $date->isoFormat('MMMM Do');
            $item->product_type = json_decode($item->product_type);
            return $item;
        });
        $failedOrders = $this->paginateHelper($failedOrders, 10);

        return response()->json($failedOrders);
    }
    public function warehouse2(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $deliverys = HubDeliverySent::orderBy('id', 'Desc')->get();
            $deliveryBoys = User::where('active', 1)->whereHas('roles', function ($q) {
                $q->where('name', 'delivery_officer');
            })->get();
            $orders_id = Order::where('hub_id', $user->id)->where('order_status', 1, 7)->pluck('order_id')->toArray();
        } else {
            $deliverys = HubDeliverySent::orderBy('id', 'Desc')->get();

            // $deliveryBoys = User::where('active', 1)->where('parent_id', $user->id)->whereHas('roles', function ($q) {
            //     $q->where('name', 'delivery_officer');
            // })->get();
            $shipment_rec = ShipmentSent::where('to', auth()->id())->where('received', 1)->get();

            $orders_id = [];
            foreach ($shipment_rec as $rec) {

                $js_rec = json_decode($rec->order_id);
                foreach ($js_rec as $rec) {
                    // $ord = Order::where('order_id', $rec)->where('order_status', '!=', 5)->where('order_status', '!=', 8)->first();
                    // if ($ord) {

                    $orders_id[] = $rec;
                    // }
                }
            }
            $pickups = [];
            $pickups = ReceivePickup::where('user_id', auth()->id())->pluck('order_id')->toArray();
            // foreach ($pickup as $pick) {
            //     if (Order::where('order_id', $pick)->where('order_status', '!=', 8)->where('order_status', '!=', 5)->first()) {
            //         $pickups[] = $pick;
            //     }
            // }
            $orders_id = array_unique(array_merge($orders_id, $pickups));
            // dd($orders_id);


            //    $orders_id= $orders_id->merge($pickups)->unique();
            $halforders = OrderStatusChangeRequest::where('status_id', 4)->orwhere('status_id', 8)->get();
            $halforders = $halforders->where('vendor_id', auth()->id())->where('request_status', 1)->pluck('order_id')->toArray();
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
            // dd($returningids);

            // $returningid =[];
            // foreach ($returningids as $ret) {
            //     if (Order::where('order_id', $ret)->where('order_status', '!=', 6)->where('order_status', '!=', 5)->first()) {
            //         $returningid[] = $ret;
            //     }
            // }

            $merged =  array_unique(array_merge($orders_id, $returningid));
            $abc = [];
            foreach ($deliverys as $delivery) {
                $orderjsons = json_decode($delivery->order_id);
                foreach ($orderjsons as $id) {
                    $abc[] = $id;
                }
            }

            // dd($abc);

            $orders_id = array_diff($merged, $abc);
            $orders = Order::orderBy('id', 'DESC')->whereIn('order_id', $orders_id)->where('order_status', '!=', 8)->where('order_status', '!=', 6)->where('order_status', '!=', 5)->get();
        }
        if ($request->has('orderid')) {
            $orders = $orders->filter(function ($order) use ($request) {
                if ($order->order_id == $request->orderid || $order->vendor_order_id == $request->orderid) {
                    return $order;
                }
            });
        }
        if ($request->has('status')) {
            $orders = $orders->where('order_status', $request->status);
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
            if ($item->pickup_hub == auth()->id()) {
                $item->is_pickup_hub = true;
                $item->is_delivery_hub = false;
                $item->is_admin = false;
            }
            if ($item->hub_id == auth()->id()) {
                $item->is_delivery_hub = true;
                $item->is_pickup_hub = false;
                $item->is_admin = false;
            }

            if (Auth::user()->hasRole('admin')) {
                $item->is_admin = true;
                $item->is_delivery_hub = false;
                $item->is_pickup_hub = false;
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
    public function DeliveryEnquiry(Request $request)
    {

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $deliverys = HubDeliverySent::orderBy('id', 'Desc')->get();
            $deliveryBoys = User::where('active', 1)->whereHas('roles', function ($q) {
                $q->where('name', 'delivery_officer');
            })->get();
            $orders_id = Order::where('hub_id', $user->id)->where('order_status', 1, 7)->pluck('order_id')->toArray();
        } else {
            $deliverys = HubDeliverySent::orderBy('id', 'Desc')->where('user_id', $user->id)->get();

            // $deliveryBoys = User::where('active', 1)->where('parent_id', $user->id)->whereHas('roles', function ($q) {
            //     $q->where('name', 'delivery_officer');
            // })->get();
            $shipment_rec = ShipmentSent::where('to', auth()->id())->where('received', 1)->get();

            $orders_id = [];
            foreach ($shipment_rec as $rec) {

                $js_rec = json_decode($rec->order_id);
                foreach ($js_rec as $rec) {
                    // $ord = Order::where('order_id', $rec)->first();

                    $ord = Order::where('order_id', $rec)->first();
                    // dd($ord);
                    // if($ord )
                    // {
                        if ($ord->hub_id == auth()->id()) {

                            $orders_id[] = $rec;
                        }
                    // }

                }
            }
            $pickups = [];
            $orders_id = array_unique(array_merge($orders_id, $pickups));
            // dd($orders_id);


            //    $orders_id= $orders_id->merge($pickups)->unique();
            $halforders = OrderStatusChangeRequest::where('status_id', 4)->orwhere('status_id', 8)->get();
            $halforders = $halforders->where('vendor_id', auth()->id())->where('request_status', 1)->pluck('order_id')->toArray();
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
            // $returningid =[];
            // foreach ($returningids as $ret) {
            //     if (Order::where('order_id', $ret)->where('order_status', '!=', 6)->first()) {
            //         $returningid[] = $ret;
            //     }
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
            $orders = Order::orderBy('id', 'DESC')->whereIn('order_id', $orders_id)->where('order_status', '!=', 6)->where('order_status','!=',8)->where('order_status','!=',5)->get();
        }

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
            if ($item->pickup_hub == auth()->id()) {
                $item->is_pickup_hub = true;
                $item->is_delivery_hub = false;
                $item->is_admin = false;
            }
            if ($item->hub_id == auth()->id()) {
                $item->is_delivery_hub = true;
                $item->is_pickup_hub = false;
                $item->is_admin = false;
            }

            if (Auth::user()->hasRole('admin')) {
                $item->is_admin = true;
                $item->is_delivery_hub = false;
                $item->is_pickup_hub = false;
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
    public function pickupEnquiry(Request $request)
    {
        // order receive bhayako matra
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $deliverys = HubDeliverySent::orderBy('id', 'Desc')->get();
            $deliveryBoys = User::where('active', 1)->whereHas('roles', function ($q) {
                $q->where('name', 'delivery_officer');
            })->get();
            $orders_id = Order::where('hub_id', $user->id)->where('order_status', 1, 7)->pluck('order_id')->toArray();
        } else {

            $shipment_rec = ShipmentSent::where('from', auth()->id())->get();

            $orders_id = [];

            $pickups = ReceivePickup::where('user_id', auth()->id())->pluck('order_id')->toArray();
            // dd($orders_id)
            $abc = [];
            foreach ($shipment_rec as $rec) {

                $js_rec = json_decode($rec->order_id);
                foreach ($js_rec as $rec) {

                    $abc[] = $rec;
                }
            }
            $orders_id = array_diff($pickups, $abc);

            $orders = Order::orderBy('id', 'DESC')->whereIn('order_id', $orders_id)->where('order_status', '!=', 5)->where('order_status', '!=', 6)->where('order_status', '!=', 8)->where('order_status', '!=', 6)->where('order_status', '!=', 5)->get();
        }
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
            if ($item->pickup_hub == auth()->id()) {
                $item->is_pickup_hub = true;
                $item->is_delivery_hub = false;
                $item->is_admin = false;
            }
            if ($item->hub_id == auth()->id()) {
                $item->is_delivery_hub = true;
                $item->is_pickup_hub = false;
                $item->is_admin = false;
            }

            if (Auth::user()->hasRole('admin')) {
                $item->is_admin = true;
                $item->is_delivery_hub = false;
                $item->is_pickup_hub = false;
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
}
