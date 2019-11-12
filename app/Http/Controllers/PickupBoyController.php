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


class PickupBoyController extends Controller
{
    use PaginationHelper;
    public function newPickup(Request $request)
    {

        $pickups = SendPickUp::all();
        $allpickupOrders = [];
        //   get order list fron sendpickup orders
        foreach ($pickups as $pickupOrder) {
            $orders = json_decode($pickupOrder->orders);
            foreach ($orders as $pickupID) {
                $allpickupOrders[] = $pickupID;
            }
        }



        $user = Auth::user();

        $orders1 = Order::where('pickup_hub', $user->parent_id)->where('order_status', 0)->pluck('order_id')->toArray();
        $orders2 = Order::where('pickup_hub',  $user->parent_id)->where('order_status', 1)->pluck('order_id')->toArray();
        $orders = array_unique(array_merge($orders1, $orders2));

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
    public function store(Request $request)
    {
        if ($request->has('order_id') && $request->order_id == null) {

            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Select Order You Want To  PickUp'
            ]);
        }
        // dd($request->all());

        $pickup = new SendPickUp();
        $pickup->user_id = Auth::user()->parent_id;
        $pickup->pickup_logistic_officer = Auth::user()->id;
        $pickup->orders = json_encode($request->order_id);
        $pickup->save();
        return response()->json([
            'success' => 'Order has assigned to you'
        ]);
    }
    public function assignedorder(Request $request)
    {
        $order = SendPickUp::where('pickup_logistic_officer', Auth::user()->id)->pluck('orders')->toArray();
        $orderId = [];
        foreach ($order as $orders) {
            $orders = json_decode($orders);

            foreach ($orders as $ID) {
                $assigned = Order::where('is_picked', '0')->where('order_id', $ID)->first();
                if ($assigned) {
                    $orderId[] = $ID;
                }
            }
        }
        $receive = ReceivePickup::where('user_id', Auth::user()->parent_id)->pluck('order_id')->toArray();
        $receiveOrderId = [];
        foreach ($receive as $receiveId) {
            $receiveOrderId[] = $receiveId;
        }
        $onlyassigned = array_diff($orderId, $receiveOrderId);
        $onlyassignedcount = count($onlyassigned);
        $onlyassignedsum = (int) Order::whereIn('order_id', $onlyassigned)->sum('cod');
        return response()->json(['onlyassigned' => $onlyassigned, 'onlyassignedcount' => $onlyassignedcount, 'onlyassignedsum' => $onlyassignedsum]);
    }
    public function picked(Request $request)
    {
        // dd($request->all());
        $order = Order::findOrFail($request->order_id);
        $order->is_picked = 1;
        $order->update();
        return response()->json([
            'status' => 'success',
            'title' => 'success',
            'message' => "Order is Picked Up"
        ]);
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
        // dd($request->all());
        $Sends = SendPickUp::where('pickup_logistic_officer', Auth::user()->id)->select('id', 'orders')->get();
        foreach ($Sends as $Send) {
            $orderIDS = json_decode($Send->orders);
            //Checking the requested data has stored in array
            if (in_array($request->order_id, $orderIDS)) {
                $orderID =  $request->order_id;
                $request['order_id'] = [
                    $request->order_id
                ];
                $back = SendPickup::where('pickup_logistic_officer', Auth::user()->id)->where('id', $Send->id)->first();
                $neworder = array_diff(json_decode($back->orders), $request->order_id);
                if (count($neworder) == 0) {
                    $back->delete();
                } else {
                    $back->orders = json_encode(array_values($neworder));
                    $back->update();
                }
                $orderComment = new OrderComment();
                $orderComment->order_id = $orderID;
                $orderComment->user_id = auth()->id();
                $orderComment->comment = $request->comment;
                $orderComment->save();
                return response()->json();
            }
        }
    }
}
