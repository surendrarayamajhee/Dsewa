<?php

namespace App\Http\Controllers\order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use  App\Http\Requests\Orderequest;
use App\Order;
use App\User;
use App\Helpers\PaginationHelper;
use App\Helpers\TrackingId;
use App\Helpers\Barcode;
use Auth;
use App\ReceivePickup;
use App\Address;
use App\UserAddress;
use Carbon\Carbon;
use App\HubArea;
use App\HubCharge;
use App\VendorPickup;
use App\Vendor_Info;
use App\OrderComment;
use App\OrderStatus;
use App\OrderStatusChangeRequest;
use App\PickUpOrder;
use App\SendPickUp;
use  App\Delivered;
use App\HubDeliverySent;
use App\OrderReturnedOrder;
use App\ShipmentReceive;
use App\ShipmentSent;

class OrderController extends Controller
{
    use PaginationHelper, TrackingId, Barcode;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Orderequest $request)
    {
        // dd($request->all());
        $request->merge(['sender_id' => auth()->id(), 'pickup_hub' => VendorPickup::where('vendor_id', auth()->id())->where('is_default', 1)->first()->ward_id, 'order_status' => 1]);
        $request['product_type'] = json_encode($request->product_type);
        $order = Order::create($request->all());
        $order->order_id = $order->id; // order_id might change in future
        $order->bar_code = $this->generateBarcodeNumber();
        $order->hub_id =  HubArea::where('address_id', UserAddress::where('id', $request->receiver_id)->first()->ward_no)->first()->hub_id;
        $order->update();
        $ward = UserAddress::where('id', $request->receiver_id)->first()->ward_no;
        if ($ward == '') {
            $order->is_ward_status = 1;
            $order->update();
        }
        return response()->json(['success' => 'Added']);
    }
    public function updateorder(Orderequest $request, $id)
    {

        $order = Order::findOrfail($id);
        $request['product_type'] = json_encode($request->product_type);
        $order = $order->update($request->all());
        return response()->json(['success' => 'updated']);
    }
    public function trackingId()
    {
        $generated = $this->generateid();

        return response()->json($generated);
    }
    public function generateid()
    {
        return substr(md5(uniqid(rand(), true)), 0, 16);
    }
    public function gethub()
    {

        $users = User::whereHas('roles', function ($q) {
            $q->where('id', 3);
        })->select('id', 'name')->get();
        return response()->json($users);
    }

    public function getorder($id, $status)
    {

        $user = Auth::user();
        $order = Order::where('order_id', $id)->first();
        if ($order) { } else {
            return response()->json(['error' => "No Order Found"]);
        }
        if (OrderStatusChangeRequest::where('order_id', $id)->first()) {
            return response()->json(['error' => "Duplicate Order"]);
        }
        $track = '';
        $user = Auth::user();

        $pickup = ReceivePickup::all()->pluck('order_id')->toArray();
        $shipment = ShipmentSent::all()->pluck('order_id')->toArray();



        $pickup_OrderId = [];
        $pickup = ReceivePickup::all()->pluck('order_id')->toArray();

        foreach ($pickup as $orderid) {

            $pickup_OrderId[] = $orderid;
        }

        $shipment_OrderId = [];
        foreach ($shipment as $orderid) {
            $deCodeIds = json_decode($orderid);
            foreach ($deCodeIds as $idd) {
                $shipment_OrderId[] = $idd;
            }
        }

        // accept only order_id present in SendPickUp
        $onlyPickupOrder = array_diff($pickup_OrderId, $shipment_OrderId);
        if ($order->order_created_as == "RETURN") {
            $track = 'return';
        }
        // elseif ($order->order_status == 1) {
        //     $track = 'pending';
        // }
        elseif ($order->order_status == 6) {
            $track = 'delivered';
        }
        // elseif ($order->order_status == 7) {
        //     $track = 'hold';
        // }
        elseif (in_array($id, $shipment_OrderId)) {
            $track = 'shipped';
        } elseif (in_array($id, $onlyPickupOrder)) {
            $track = 'picked';
        } else {
            $track = 'new';
        }

        if ($user->hasRole('vendor')) {
            if ($order->sender_id != $user->id) {

                return response()->json(['error' => "NO Order Found"]);
            }
        } else {
            if ($order->hub_id == $user->id || $order->pickup_hub == $user->id) { } else {
                return response()->json(['error' => "NOT Your Order"]);
            }
        }
        // dd($track);
        switch ($status) {
                // case 1 is for pending
            case 1:
                // if ($track == 'pending') {
                //     return response()->json(['error' => "You Can't Change It To Pending"]);
                // }

                break;
                // refund
            case 2:
                if ($track != 'delivered') {
                    return response()->json(['error' => "You Can't Refund Order before Delivery"]);
                }

                break;
                // exchange
            case 3:

                if ($track != 'delivered') {
                    return response()->json(['error' => "You Can't Refund Order before Delivery"]);
                }
                break;
                // partial
            case 4:
                // dd($track);
                if ($track == 'delivered'  or $track == "new" or  $track == "return") {
                    return response()->json(['error' => "You Can't Partial This Order"]);
                }

                break;
                // cancel
            case 5:
                // dd($track);
                if ($track != 'new') {
                    // dd($track);

                    return response()->json(['error' => "You Can't Cancel Order"]);
                }
                break;
                // delivered
            case 6:

                break;
                // hold
            case 7:
                if ($track == "return") {
                    return response()->json(['error' => "You Can't Hold This Order"]);
                }
                break;
                // return
            case 8:
                if ($track == 'delivered'  or $track == "new" or $track == 'return') {
                    return response()->json(['error' => "You Can't Return This Order"]);
                }
                break;
        }

        $order->status_name = OrderStatus::where('id', $order->order_status)->first()->name;
        $product_type = json_decode($order->product_type);
        $order->product_type = json_decode($order->product_type);


        return response()->json(['order' => $order, 'type' => $product_type], 200);
    }
    public function getorderbyid($id)
    {
        $order = Order::findOrfail($id);
        $order->vendor_name = User::findOrfail($order->sender_id)->name;
        // $item->useraddress = UserAddress::where('id', $item->receiver_id)->select('id', 'first_name', 'last_name', 'state_no', 'district', 'municipality','ward_no','area', 'phone1', 'phone2')->first();
        $useraddress = UserAddress::where('id', $order->receiver_id)->first();
        $useraddress->state = Address::where('id', $useraddress->state_no)->first() ? Address::where('id', $useraddress->state_no)->first()->address : '';
        $useraddress->district = Address::where('id', $useraddress->district)->first() ? Address::where('id', $useraddress->district)->first()->address : '';
        $useraddress->municipality = Address::where('id', $useraddress->municipality)->first() ? Address::where('id', $useraddress->municipality)->first()->address : '';
        $useraddress->ward = Address::where('id', $useraddress->ward_no)->first() ? Address::where('id', $useraddress->ward_no)->first()->address : '';
        $useraddress->area = Address::where('id', $useraddress->area)->first() ? Address::where('id', $useraddress->area)->first()->address : '';

        $order->useraddress = $useraddress;

        $order->vendor_order_id = $order->vendor_order_id ? $order->vendor_order_id : '-';


        // vendor address
        $order->vendor = Vendor_Info::where('vendor_id', $order->sender_id)->select('phone1', 'phone2')->first();
        $pickup = VendorPickup::where('id', $order->order_pickup_point)->first();
        $pickup->state = Address::where('id', $pickup->state_id)->first() ? Address::where('id', $pickup->state_id)->first()->address : '';
        $pickup->district = Address::where('id', $pickup->district_id)->first() ?  Address::where('id', $pickup->district_id)->first()->address : '';
        $pickup->municipality = Address::where('id', $pickup->municipality_id)->first() ?  Address::where('id', $pickup->municipality_id)->first()->address : '';
        $pickup->ward = Address::where('id', $pickup->ward_id)->first() ?  Address::where('id', $pickup->ward_id)->first()->address : '';
        $pickup->area = Address::where('id', $pickup->area_id)->first() ? Address::where('id', $pickup->area_id)->first()->address : '';

        $order->Vendor_pickup_point = $pickup;
        $date = Carbon::parse($order->expected_date);
        $order->expecteddate =  $date->isoFormat('YYYY MMMM Do h:mm:ss a');
        $date = Carbon::parse($order->created_at);
        $order->orderdate =  $date->isoFormat('YYYY MMMM Do h:mm:ss a');
        $order->product_type = json_decode($order->product_type);
        return $order;
    }
    public function getorderbydesc(Request $request)
    {
        set_time_limit(6000);
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $orders = Order::orderBy('id', 'DESC')->get();
        } elseif ($user->hasRole('vendor')) {

            $orders = Order::orderBy('id', 'DESC')->where('sender_id', auth()->id())->get();
        } elseif ($user->hasRole('hub')) {

            $orders1 = collect(Order::where('hub_id', auth()->id())->pluck('order_id')->toArray());
            $orders2 = collect(Order::where('pickup_hub', auth()->id())->pluck('order_id')->toArray());
            $o = $orders1->merge($orders2)->unique();

            $orders = Order::orderBy('id', 'DESC')->whereIn('order_id', $o)->get();
        }

        //   $orders= $orders->get
        // dd($orders);
        if ($request->has('from') && $request->has('to')) {
            $orders = $orders->whereBetween('created_at', [$request->to, $request->from]);
        }
        if ($request->has('orderid')) {
            $orders = $orders->filter(function ($order) use ($request) {
                if ($order->order_id == $request->orderid || $order->vendor_order_id == $request->orderid) {
                    return $order;
                }
            });
        }
        // dd($orders);

        if ($request->has('vendor')) {
            $orders = $orders->where('sender_id', $request->vendor);
        }
        if ($request->has('hub')) {
            $orders = $orders->filter(function ($order) use ($request) {
                if ($order->hub_id == $request->hub || $order->pickup_hub == $request->hub) {
                    return $order;
                }
            });
        }
        if ($request->has('customer')) {
            $parts = explode(" ", $request->customer);
            $count = count($parts);
            if ($count ==  1) {
                $useraddressId = UserAddress::where('first_name', 'like', '%' . $request->customer . '%')->orWhere('last_name', 'like', '%' . $request->customer . '%')->pluck('id')->toArray();
            } else {
                $last_name = array_pop($parts);
                $first_name = implode(" ", $parts);
                $useraddressId = UserAddress::where('first_name', 'like', '%' . $first_name . '%')->orWhere('last_name', 'like', '%' . $last_name . '%')->pluck('id')->toArray();
            }


            // $orders = $orders->where('order_status', $request->status);
            $orders = $orders->whereIn('receiver_id', $useraddressId);
        }
        if ($request->has('status')) {
            $orders = $orders->where('order_status', $request->status);
        }
        if ($request->has('phone')) {
            $phones = UserAddress::where('phone1', 'like', '%' . $request->phone . '%')->orWhere('phone1', 'like', '%' . $request->phone . '%')->pluck('id')->toArray();
            if ($phones) {
                $orders = $orders->whereIn('receiver_id', $phones);
            }
        }
        if ($request->has('paginate')) {
            $p = $request->input('paginate');
        } else {
            $p = 20;
        }
        // $orders=$orders->get();
        $orders->transform(function ($item, $key) {
            $item->comment_count = OrderComment::where('order_id', $item->order_id)->get()->count();
            $item->vendor_name = User::findOrfail($item->sender_id) ? User::findOrfail($item->sender_id)->name : '';
            $item->useraddress = UserAddress::where('id', $item->receiver_id)->first();
            $child = OrderReturnedOrder::where('old_order_id', $item->order_id)->pluck('new_order_id')->toArray();
            $item->child_order = $child;
            // $item->statuss = $this->orderlog($item->order_id);
            $item->statuss = '-';

            $item->child = Order::whereIn('order_id', $child)->get();
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

            // dd( OrderStatus::where('id', $item->order_status)->first());
            $comment =  OrderComment::where('order_id', $item->order_id)->orderBy('id', 'desc')->first();
            // dd($comment->comment);
            $item->comment = $comment ? $comment->comment : '-';
            if ($comment) {
                $date = Carbon::parse($comment->created_at);
                $item->comment_date =  $date->isoFormat('MMMM Do');
            }


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

        $orders = $this->paginateHelper($orders, $p);

        return response()->json($orders);
    }
    public function orderlog($id)
    {

        $logs = Order::where('order_id', $id)->select('order_id')->first();
        // $date = Carbon::parse($logs->created_at);
        $log =  'In Process';

        $shipment_sent = ShipmentSent::all();
        foreach ($shipment_sent as $sent) {
            if (in_array($logs->order_id, json_decode($sent->order_id))) {

                $log = 'In Transit';
                if ($sent->received == 1) {
                    // $date = Carbon::parse($shipment_received->created_at);
                    $log = 'Ready for Delivery';
                }
            }
        }
        $deliverd = Delivered::all();
        foreach ($deliverd as $d) {
            if (in_array($logs->order_id, json_decode($d->order_id))) {
                $log = 'Delivered';

            }
        }

        return $log;
    }
    public function droporder($id)
    {
        // dd($id);
        // $order = Order::findOrfail($id);
        // $order->delete($id);
        // return response()->json(['success', 'Deleted']);
    }
    public function getshipmentprice(Request $request)
    {
        $shipment_charge = 0;
        if ($request->has('reciver') && $request->has('handling')) {

            $useraddress = UserAddress::where('id', $request->reciver)->select('id', 'district', 'municipality', 'ward_no')->first();
            if ($useraddress->ward_no) {
                $hubcharge = HubCharge::where('ward_id',  $useraddress->ward_no)->first();
                // dd($hubcharge);
                if ($request->handling == 'FRAGILE') {
                    $shipment_charge = $hubcharge->fragile_charge;
                } else {
                    $shipment_charge = $hubcharge->fragile_charge;
                }
            }
        } else {
            $shipment_charge = 0;
        }
        return response()->json($shipment_charge);
    }
}
