<?php

namespace App\Http\Controllers\Hub;

use App\BulkAssignedOrder;
use App\CashOnCounter;
use App\CollectDeliveryPayment;
use App\Delivered;
use App\Helpers\PaginationHelper;
use App\HubDeliverySent;
use App\UserAddress;
use App\Address;
use App\Order;
use App\OrderCancel;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\HubCharge;
use App\Comission;
use App\OrderReturnedOrder;
use App\OrderStatusChangeRequest;
use App\ShipmentReceive;
use App\ShipmentSent;
use App\VendorPickup;
use App\SendPickUp;
use App\ReceivePickup;

class DeliveryController extends Controller
{
    use PaginationHelper;

    public function getAssignOrderList(Request $request)
    {
        // dd($abc);
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $deliverys = HubDeliverySent::orderBy('id', 'Desc')->get();
            $deliveryBoys = User::where('active', 1)->whereHas('roles', function ($q) {
                $q->where('name', 'delivery_officer');
            })->get();
            $orders_id = Order::where('hub_id', $user->id)->where('order_status', 1, 7)->pluck('order_id')->toArray();
        } else {
            $deliverys = HubDeliverySent::orderBy('id', 'Desc')->where('user_id', $user->id)->get();

            $deliveryBoys = User::where('active', 1)->where('parent_id', $user->id)->whereHas('roles', function ($q) {
                $q->where('name', 'delivery_officer');
            })->get();


            // $orders_id = Order::where('order_status', 1)->pluck('order_id')->toArray();
            // old
            $shipment_rec = ShipmentSent::where('to', auth()->id())->where('received', 1)->get();

            $orders_id = [];
            foreach ($shipment_rec as $rec) {

                $js_rec = json_decode($rec->order_id);
                foreach ($js_rec as $rec) {
                    $ord = Order::where('order_id', $rec)->first();
                    if ($ord->hub_id == auth()->id() && $ord->inquiry == 1) {

                        $orders_id[] = $rec;
                    }
                }
            }
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

            $merged = array_merge($orders_id, $returningid);
            $pickDeliverSame = Order::where('pickup_hub', auth()->id())->where('hub_id', auth()->id())->where('inquiry', 1)->pluck('order_id')->toArray();
            $merged2 = array_unique(array_merge($merged, $pickDeliverSame));

            $abc = [];
            foreach ($deliverys as $delivery) {
                $orderjsons = json_decode($delivery->order_id);
                foreach ($orderjsons as $id) {
                    $abc[] = $id;
                }
            }


            if ($request->ajax()) {
                if ($request->has('delivery_boy') && $request['delivery_boy'] != null) {
                    $deliverys  = $deliverys->where('delivery_boy_id', $request->delivery_boy);
                }
                if ($request['startdate'] && $request['enddate'] != null) {
                    $startdate = Carbon::parse($request['startdate'])->format('Y-m-d h:m:s');
                    $enddate = Carbon::parse($request['enddate'])->format('Y-m-d h:m:s');
                    // dd($deliverys);
                    $deliverys  = $deliverys->where('date_time', '>=', $startdate)->where('date_time', '<=', $enddate);
                }
            }

            foreach ($deliverys as $list) {
                $list->delivery_boy_name = $list->deliveryBoy->name;
                $list->assigner_name = $list->assignBy->name;
                $list->orders = json_decode($list->order_id);
                $date = Carbon::parse($list->date_time);
                $list->differdate = $date->format('d, F');
            }
            $deliverys = $this->paginateHelper($deliverys, 10);

            $Ordersid = array_diff($merged2, $abc);
            $xyz = [];
            $deliveries = BulkAssignedOrder::all();
            foreach ($deliveries as $delivery) {
                $orders = json_decode($delivery->order_id);
                foreach ($orders as $orderid) {
                    $xyz[] = $orderid;
                }
            }
            $orders_id = array_diff($Ordersid, $xyz);
            // dd($orders_id);
            // $orders = [];
            $orders = Order::whereIn('order_id', $orders_id)->select('order_id', 'sender_id', 'order_created_as', 'receiver_id')->get();

            // foreach ($orders_id as $order) {

            //     $orders[] = Order::where('order_id', $order)->select('order_id', 'sender_id', 'order_created_as', 'receiver_id')->first();
            // }
            // dd($orders);
            foreach ($orders as $s) {
                // dd($s);
                if ($s->order_created_as == 'RETURN') {
                    $s->vendor_name = User::where('id', $s->sender_id)->first()->name;
                    $s->tag_created_as = substr($s->order_created_as, 0, 1);
                } else {
                    // dd($s->receiver_id);
                    $s->vendor_name = Address::where('id', UserAddress::where('id', $s->receiver_id)->first()->ward_no)->first() ? Address::where('id', UserAddress::where('id', $s->receiver_id)->first()->ward_no)->first()->address : "";
                    if ($s->order_created_as == 'RETURN') {

                        $s->tag_created_as = substr($s->order_created_as, 0, 1);
                    }
                }
            }

            return response()->json([
                'deliveryBoy' => $deliveryBoys,
                'orders' => $orders,
                'assignOrderList' => $deliverys,
            ]);
        }
    }

    public function deliveryAssignStore(Request $request)
    {
        if ($request->has('status')) {
            $validatedData = $request->validate([

                'delivery_boy_id' => '',
                'comments' => '',
                'order_id' => '',

            ]);
            $request['delivery_boy_id'] = auth()->id();
            $user_id = Auth::user()->parent_id;

            if ($request->has('order_id') && $request->order_id == null) {

                return response()->json([
                    'status' => 'error',
                    'title' => 'Error',
                    'message' => 'Select Order You Want To  Delivered'
                ]);
            }
        } else {
            $validatedData = $request->validate([

                'delivery_boy_id' => 'required',
                'comments' => '',
                'order_id' => 'required',

            ]);
            $user_id = auth()->id();
        }

        $assignOrders = HubDeliverySent::all();
        foreach ($assignOrders as $item) {
            $orderJsons = json_decode($item->order_id);
            foreach ($request->order_id as $orderJson) {
                if (in_array($orderJson, $orderJsons, true)) {
                    return response()->json([
                        'status' => 'error',
                        'title' => 'Error',
                        'message' => 'Order ID ' . $orderJson . ' Already Assign !!'
                    ]);
                }
            }
        }
        $assignOrder = new HubDeliverySent();
        $time = Carbon::parse($request['date_time'])->format('Y-m-d h:m:s');
        $assignOrder->delivery_boy_id = $request['delivery_boy_id'];

        $assignOrder->order_id = json_encode($request->order_id);
        $assignOrder->date_time = $time;
        $assignOrder->user_id = $user_id;
        $assignOrder->comments = $request['comments'];
        $success = $assignOrder->save();


        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Assign.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Assigning !!'
            ]);
        }
    }

    public function EditAssignOrder($id)
    {
        $assignOrder = HubDeliverySent::find($id);

        return response()->json($assignOrder);
    }

    public function deleteAssignOrder($id)
    {

        $assignOrder = HubDeliverySent::find($id);
        $success = $assignOrder->delete();

        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Deleted.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Deleting'
            ]);
        }
    }

    public function deliveryAssignUpdate(Request $request, $id)
    {
        $assignOrder =  HubDeliverySent::where('id', $id)->first();

        $assignOrders = HubDeliverySent::all();
        foreach ($assignOrders as $item) {
            $orderJsons = json_decode($item->order_id);
            foreach ($request->order_id as $orderJson) {
                if (in_array($orderJson, $orderJsons, true)) {
                    if (!in_array($orderJson, json_decode($assignOrder->order_id), true)) {
                        return response()->json([
                            'status' => 'error',
                            'title' => 'Error',
                            'message' => 'Order ID ' . $orderJson . ' Already Assign !!'
                        ]);
                    }
                }
            }
        }
        $time = Carbon::parse($request['date_time'])->format('Y-m-d h:m:s');
        $assignOrder->delivery_boy_id = $request['delivery_boy_id'];
        $assignOrder->order_id = json_encode($request->order_id);
        $assignOrder->date_time = $time;
        $assignOrder->comments = $request['comments'];
        $success = $assignOrder->update();

        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Updated.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Updating !!'
            ]);
        }
    }

    public function printAssignOrder($id)
    {
        // dd($id);
        $printOrder = User::findorfail($id);

        $printOrder->deliveryBoy = $printOrder->name;;
        $deliveredOrders = Delivered::where('user_id', auth()->id())->pluck('order_id')->toArray();
        $DeliveredOrderId = [];

        foreach ($deliveredOrders as $deliveredId) {
            $deliveredId = json_decode($deliveredId);

            foreach ($deliveredId as $DID) {
                $DeliveredOrderId[] = $DID;
            }
        }

        $boy_orders = HubDeliverySent::where('user_id', auth()->id())->where('delivery_boy_id', $id)->pluck('order_id')->toArray();
        $bo = [];
        foreach ($boy_orders as $bOrder) {
            $do = json_decode($bOrder);
            foreach ($do as $de) {
                $bo[] = $de;
            }
        }
        $orders = array_diff($bo, $DeliveredOrderId);
        $printOrder->orders = Order::whereIn('order_id', $orders)->get();
        foreach ($printOrder->orders as $order) {
            $reciverDetail = UserAddress::where('id', $order->receiver_id)->first();
            $order->receiverName = $reciverDetail->first_name . ' ' . $reciverDetail->last_name;
            $order->receiverPhone1 = $reciverDetail->phone1;
            $order->receiverPhone2 = $reciverDetail->phone2;
            $order->receiverMunicipality = Address::where('id', $reciverDetail->municipality)->pluck('address')->first();
            $order->receiverAarea = Address::where('id', $reciverDetail->area)->pluck('address')->first();
            $order->receiverWard = Address::where('id', $reciverDetail->ward_no)->pluck('address')->first();
        }
        // dd($printOrder);
        return view('hubs.print-order', compact('printOrder'));
        return response()->json($printOrder);
    }

    //    Hub Delivery Success

    public function getSuccessOrderList(Request $request)
    {


        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $successDelivereds = Delivered::orderBy('id', 'Desc')->get();
            $deliveryBoys = User::whereHas('roles', function ($q) {
                $q->where('name', 'delivery_officer');
            })->get();
            $orders_id = Order::where('order_status', 1)->pluck('order_id')->toArray();
        } else {
            $successDelivereds = Delivered::orderBy('id', 'Desc')->where('user_id', $user->id)->get();
            // dd( $successDelivereds );
            $deliveryBoys = User::where('parent_id', $user->id)->whereHas('roles', function ($q) {
                $q->where('name', 'delivery_officer');
            })->get();
            $shipment_rec = HubDeliverySent::where('user_id', auth()->id())->get();
            if ($request->has('id') && $request['id'] != null) {
                $shipment_rec = HubDeliverySent::where('user_id', auth()->id())->where('delivery_boy_id', $request->id)->get();
            }

            $orders_id = [];
            foreach ($shipment_rec as $rec) {

                $js_rec = json_decode($rec->order_id);
                $orders_id = array_merge($orders_id, $js_rec);
            }


            // $orders_id = Order::where('hub_id', $user->id)->where('order_status', 1)->pluck('order_id')->toArray();
        }
        $abc = [];

        foreach ($successDelivereds as $delivery) {
            $orderjsons = json_decode($delivery->order_id);
            foreach ($orderjsons as $id) {
                $abc[] = $id;
            }
        }
        if ($request->ajax()) {
            if ($request->has('delivery_boy') && $request['delivery_boy'] != null) {
                $successDelivereds  = $successDelivereds->where('delivery_boy_id', $request->delivery_boy);
            }
            if ($request['startdate'] && $request['enddate'] != null) {
                $startdate = Carbon::parse($request['startdate'])->format('Y-m-d h:m:s');
                $enddate = Carbon::parse($request['enddate'])->format('Y-m-d h:m:s');
                // dd($lists);
                $successDelivereds  = $successDelivereds->where('created_at', '>=', $startdate)->where('created_at', '<=', $enddate);
            }
        }



        foreach ($successDelivereds as $successDelivered) {
            $successDelivered->delivery_boy_name = $successDelivered->deliveryBoy->name;
            $successDelivered->approved_by = $successDelivered->approvedby->name;
            $successDelivered->orders = json_decode($successDelivered->order_id);
            $successDelivered->differdate = $successDelivered->created_at->format('d, F');
        }
        $successDelivereds = $this->paginateHelper($successDelivereds, 10);

        $orders_id = array_diff($orders_id, $abc);
        $orders = [];
        foreach ($orders_id as $order) {
            $orders[] = Order::where('order_id', $order)->first();
        }
        foreach ($orders as $s) {
            // $s->vendor_name = User::where('id', $s->sender_id)->first()->name;
            if ($s->order_created_as != "NEW") {
                $s->tag_created_as = substr($s->order_created_as, 0, 1);
            }
        }

        return response()->json([
            'deliveryBoy' => $deliveryBoys,
            'orders' => $orders,
            'successDelivereds' => $successDelivereds,
        ]);
    }


    public function deliverySuccessStore(Request $request)
    {


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
        }
        // dd($request->all());

        $successOrders = Delivered::all();
        foreach ($successOrders as $item) {
            $orderJsons = json_decode($item->order_id);
            foreach ($request->order_id as $orderJson) {
                if (in_array($orderJson, $orderJsons, true)) {
                    return response()->json([
                        'status' => 'error',
                        'title' => 'Error',
                        'message' => 'Order ID ' . $orderJson . ' Already Added !!'
                    ]);
                }
            }
        }
        // dd( json_encode($request->order_id));


        // true


        foreach ($request->order_id as $order) {
            // dd($order);
            $order = Order::where('order_id', $order)->first();

            $order->order_status = 6;
            $order->update();

            $delivery_boy_charge = HubCharge::where('ward_id', $order->address->ward_no)->first() ? HubCharge::where('ward_id', $order->address->ward_no)->first()->delivery_charge : '0';
            $shipping_cost = 0;
            $shipment_sent = ShipmentSent::where('received', 1)->get();
            foreach ($shipment_sent as $shipment) {

                if (in_array($order->order_id, json_decode($shipment->order_id), true)) {

                    $total = count(json_decode($shipment->order_id));
                    $perOrderCost = $shipment->shipment_cost / $total;
                    $shipping_cost = $shipping_cost + $perOrderCost;
                }
            }

            $operationalCost = $delivery_boy_charge + $shipping_cost;
            $shipping_charge = $order->shipment_charge;
            $net_amt = $shipping_charge - $operationalCost;

            $comission = new Comission();
            +$comission->order_id = $order->order_id;
            // dd($shipping_cost);
            $comission->shipping_cost = $shipping_cost;
            if ($order->order_created_as == 'RETURN') {
                //yaha arko  table bata aauxa hai
                $comission->cod = $order->cod;
                $comission->delivery_boy_comission = '0';
                $comission->pickup_hub = '0';
                if ((0.20 *  Order::where('order_id', OrderReturnedOrder::where('new_order_id', $order->order_id)->first()->old_order_id)->first()->shipment_charge == $order->shipment_charge)) {
                    $comission->pickup_hub = 15;

                    $comission->dsewa = ($order->shipping_charge - $operationalCost) - $comission->pickup_hub;
                } else {
                    $comission->pickup_hub = 0.20 * $net_amt;
                    $comission->dsewa = ($order->shipping_charge - $operationalCost) - $comission->delivery_hub;
                }
            } else {
                $comission->cod = $order->cod;
                $comission->delivery_boy_comission = $delivery_boy_charge;
                $comission->pickup_hub = (20 / 100) * $net_amt;
                $comission->delivery_hub = (30 / 100) * $net_amt;
                $comission->dsewa = (50 / 100) * $net_amt;
            }
            $comission->save();
        }
        $successOrder = new Delivered();
        $successOrder->delivery_boy_id = $request['delivery_boy_id'];
        $successOrder->order_id = json_encode($request->order_id);
        $successOrder->user_id = auth()->id();
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

    public function deliverySuccessUpdate(Request $request, $id)
    {
        $successOrder =  Delivered::where('id', $id)->first();
        $successOrders = Delivered::all();
        foreach ($successOrders as $item) {
            $orderJsons = json_decode($item->order_id);
            foreach ($request->order_id as $orderJson) {
                if (in_array($orderJson, $orderJsons, true)) {
                    if (!in_array($orderJson, json_decode($successOrder->order_id), true)) {
                        return response()->json([
                            'status' => 'error',
                            'title' => 'Error',
                            'message' => 'Order ID ' . $orderJson . ' Already Assign !!'
                        ]);
                    }
                }
            }
        }
        $successOrder->delivery_boy_id = $request['delivery_boy_id'];
        $successOrder->order_id = json_encode($request->order_id);
        $successOrder->comments = $request['comments'];
        $success = $successOrder->update();

        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Updated.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Updating !!'
            ]);
        }
    }


    public function deleteSuccessOrder($id)
    {

        $successOrder = Delivered::find($id);
        $success = $successOrder->delete();

        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Deleted.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',

                'message' => 'Error While Deleting'
            ]);
        }
    }





    //    Hub collect payment



    public function getCollectPayment(Request $request)
    {


        $collectPayments = CollectDeliveryPayment::where('user_id', auth()->id())->get();
        if (Auth::User()->hasRole('admin')) {
            $collectPayments = CollectDeliveryPayment::all();
        }
        if ($request->ajax()) {
            if ($request->has('delivery_boy') && $request['delivery_boy'] != null) {
                $collectPayments  = $collectPayments->where('delivery_boy_id', $request->delivery_boy);
            }

            if ($request['startdate'] && $request['enddate'] != null) {
                $startdate = Carbon::parse($request['startdate'])->format('Y-m-d h:m:s');
                $enddate = Carbon::parse($request['enddate'])->format('Y-m-d h:m:s');
                // dd($lists);
                $collectPayments  = $collectPayments->where('collection_date', '>=', $startdate)->where('collection_date', '<=', $enddate);
            }
        }



        foreach ($collectPayments as $collectPayment) {
            $collectPayment->delivery_boy_name = $collectPayment->deliveryBoy->name;
            $collectPayment->approve_by = $collectPayment->acceptBy->name;

            $ordersJson[] = json_decode($collectPayment->order_id);
            foreach ($ordersJson as $id) {
                $collectPayment->ordersJson = $id;
            }

            // $ordertbls = Order::whereIn('order_id', $ordersJson)->select('id', 'order_id', 'cod')->get();
            // foreach ($ordertbls as $ordertbl) {
            //     $ordertbl->shippingCharge = "100";
            //     $ordertbl->deliveryBoyCommission = "50";
            // }
            // $collectPayment->orders = $ordertbls;

            // $collectPayment->totalCOD = $ordertbls->sum('cod');
            // $collectPayment->totalCommission = $ordertbls->sum('deliveryBoyCommission');
            // $collectPayment->totalShippingCharge = $ordertbls->sum('shippingCharge');



            //                $collectPayment->orders
            $date = Carbon::parse($collectPayment->collection_date);
            $collectPayment->differdate = $date->format('d, F');
        }
        $collectPayments = $this->paginateHelper($collectPayments, 5);
        $deliveryBoys = User::where('active', 1)->where('parent_id', auth()->id())->whereHas('roles', function ($q) {
            $q->where('name', 'delivery_officer');
        })->get();

        if (Auth::User()->hasRole('admin')) {

            $order_id = Order::where('order_status', 6)->pluck('order_id')->toArray();
        } else {

            $collectPayment = CollectDeliveryPayment::where('user_id', auth()->id())->pluck('order_id')->toArray();
            $collectpaymentid = [];
            foreach ($collectPayment as $collect) {
                $collectid = json_decode($collect);
                foreach ($collectid as $id) {
                    $collectpaymentid[] = $id;
                }
            }
            $order_id = [];

            $orders =  Delivered::where('user_id', auth()->id())->pluck('order_id')->toArray();
            if ($request->has('id') && $request['id'] != null) {
                $orders =  Delivered::where('user_id', auth()->id())->where('delivery_boy_id', $request->id)->pluck('order_id')->toArray();
            }
            foreach ($orders as $order) {
                $Ids = json_decode($order);
                foreach ($Ids as $id) {
                    $order_id[] = $id;
                }
            }
            $order_id = array_diff($order_id, $collectpaymentid);
        }


        return response()->json([
            'deliveryBoy' => $deliveryBoys,
            'orders' => $order_id,
            'collectPayments' => $collectPayments,
        ]);
    }


    public function collectPaymentStore(Request $request)
    {
        // dd($request->all());
        if ($request->has('r')) {
            $validatedData = $request->validate([

                'delivery_boy_id' => '',
                'comments' => '',
                'order_id' => '',
                'collection_mode' => '',
                'amount' => '',
                'collection_date' => '',


            ]);
            $user_id = Auth::user()->parent_id;
        } else {
            $validatedData = $request->validate([

                'delivery_boy_id' => 'required',
                'comments' => '',
                'order_id' => 'required',
                'collection_mode' => 'required',
                'amount' => 'required',
                'collection_date' => 'required',


            ]);
            $user_id = auth()->id();
        }

        // dd($request->all());
        $collectPayments = CollectDeliveryPayment::all();
        foreach ($collectPayments as $item) {
            $orderJsons = json_decode($item->order_id);
            foreach ($request->order_id as $orderJson) {
                if (in_array($orderJson, $orderJsons, true)) {
                    return response()->json([
                        'status' => 'error',
                        'title' => 'Error',
                        'message' => 'Order ID ' . $orderJson . ' Payment Already Collected !!'
                    ]);
                }
            }
        }
        foreach ($request->order_id as $orderJson) {
            $this->DeliveryStore($orderJson);
        }




        $collectPayment = new CollectDeliveryPayment();
        $collectPayment->delivery_boy_id = $request['delivery_boy_id'];
        $collectPayment->order_id = json_encode($request->order_id);
        $collectPayment->collection_mode = $request['collection_mode'];
        $collectPayment->amount = $request['amount'];
        $collectPayment->collection_date = $request['collection_date'];
        $collectPayment->user_id = $user_id;
        $collectPayment->comments = $request['comments'];
        $success = $collectPayment->save();


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
    public function DeliveryStore($id)
    {
        $Comission = Comission::where('order_id', $id)->first();
        if (!$Comission) {


            $order = Order::where('order_id', $id)->first();

            $delivery_boy_charge = HubCharge::where('ward_id', $order->address->ward_no)->first() ? HubCharge::where('ward_id', $order->address->ward_no)->first()->delivery_charge : '0';
            $shipping_cost = 0;
            $shipment_sent = ShipmentSent::where('received', 1)->get();
            foreach ($shipment_sent as $shipment) {

                if (in_array($order->order_id, json_decode($shipment->order_id), true)) {

                    $total = count(json_decode($shipment->order_id));
                    $perOrderCost = $shipment->shipment_cost / $total;
                    $shipping_cost = $shipping_cost + $perOrderCost;
                }
            }

            $operationalCost = $delivery_boy_charge + $shipping_cost;
            $shipping_charge = $order->shipment_charge;
            $net_amt = $shipping_charge - $operationalCost;

            $comission = new Comission();
            $comission->order_id = $order->order_id;
            // dd($shipping_cost);
            $comission->shipping_cost = $shipping_cost;
            if ($order->order_created_as == 'RETURN') {
                //yaha arko  table bata aauxa hai
                $comission->cod = $order->cod;
                $comission->delivery_boy_comission = '0';
                $comission->pickup_hub = '0';
                if ((0.20 *  Order::where('order_id', OrderReturnedOrder::where('new_order_id', $order->order_id)->first()->old_order_id)->first()->shipment_charge == $order->shipment_charge)) {
                    $comission->pickup_hub = 15;

                    $comission->dsewa = ($order->shipping_charge - $operationalCost) - $comission->pickup_hub;
                } else {
                    $comission->pickup_hub = 0.20 * $net_amt;
                    $comission->dsewa = ($order->shipping_charge - $operationalCost) - $comission->delivery_hub;
                }
            } else {
                $comission->cod = $order->cod;
                $comission->delivery_boy_comission = $delivery_boy_charge;
                $comission->pickup_hub = (20 / 100) * $net_amt;
                $comission->delivery_hub = (30 / 100) * $net_amt;
                $comission->dsewa = (50 / 100) * $net_amt;
            }
            $comission->save();
        }
    }

    public function collectPaymentUpdate(Request $request, $id)
    {

        $collectPayment =  CollectDeliveryPayment::where('id', $id)->first();

        $collectPayments = CollectDeliveryPayment::all();
        foreach ($collectPayments as $item) {
            $orderJsons = json_decode($item->order_id);
            foreach ($request->order_id as $orderJson) {
                if (in_array($orderJson, $orderJsons, true)) {
                    if (!in_array($orderJson, json_decode($collectPayment->order_id), true)) {
                        return response()->json([
                            'status' => 'error',
                            'title' => 'Error',
                            'message' => 'Order ID ' . $orderJson . ' Payment Already Collected !!'
                        ]);
                    }
                }
            }
        }



        $collectPayment->delivery_boy_id = $request['delivery_boy_id'];
        $collectPayment->order_id = json_encode($request->order_id);
        $collectPayment->collection_mode = $request['collection_mode'];
        $collectPayment->amount =  $request['amount'];
        $collectPayment->collection_date = $request['collection_date'];
        $collectPayment->comments = $request['comments'];
        $success = $collectPayment->update();

        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Updated.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Updating !!'
            ]);
        }
    }


    public function deletecolectPayment($id)
    {

        $collectPayment = CollectDeliveryPayment::find($id);
        $success = $collectPayment->delete();

        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Deleted.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Deleting'
            ]);
        }
    }



    //    Hub Delivery Collect Payment



    //cash on counter

    public function getCashOnCounter(Request $request)
    {

        $user = Auth::user();
        if ($user->hasRole('admin')) {

            $cashOnCounters = CashOnCounter::orderBy('id', 'Desc')->get();
        } else {
            $cashOnCounters = CashOnCounter::orderBy('id', 'Desc')->where('user_id', auth()->id())->get();
        }
        if ($request->ajax()) {

            if ($request['startdate'] && $request['enddate'] != null) {
                $startdate = Carbon::parse($request['startdate'])->format('Y-m-d h:m:s');
                $enddate = Carbon::parse($request['enddate'])->format('Y-m-d h:m:s');
                // dd($lists);
                $cashOnCounters  = $cashOnCounters->where('created_at', '>=', $startdate)->where('created_at', '<=', $enddate);
            }
        }



        foreach ($cashOnCounters as $cashOnCounter) {
            $cashOnCounter->approve_by = $cashOnCounter->acceptBy->name;
            $cashOnCounter->amount = $cashOnCounter->order->cod;


            //  $collectPayment->orders
            $date = Carbon::parse($cashOnCounter->created_at);
            $cashOnCounter->differdate = $date->format('d, F');
        }
        $cashOnCounters = $this->paginateHelper($cashOnCounters, 10);
        $deliveryBoys = User::where('active', 1)->where('parent_id', auth()->id())->whereHas('roles', function ($q) {
            $q->where('name', 'delivery_officer');
        })->get();

        if (Auth::User()->hasRole('admin')) {
            $orders = Order::where('order_status', 1)->get();
        } else {
            $orders = Order::where('order_status', 1)->where('hub_id', auth()->id())->get();
        }


        return response()->json([
            'deliveryBoy' => $deliveryBoys,
            'orders' => $orders,
            'cashOnCounters' => $cashOnCounters,
        ]);
    }

    public function getCashCounter($id)
    {
        if ($id == 0) {
            return response()->json(0);
        } else {
            $order = Order::find($id);
            if (isset($order)) {
                return response()->json($order->cod);
            }
        }
    }


    public function cashCounterStore(Request $request)
    {
        $validatedData = $request->validate([
            'comments' => '',
            'order_id' => 'required',
        ]);
        $orderExist = CashOnCounter::where('order_id', $request->order_id)->exists();
        if ($orderExist) {
            return response()->json([
                'status' => 'error',
                'title' => 'Errors',
                'message' => 'Already Paid for this Order.'
            ]);
        } else {
            $cashCounter = new CashOnCounter();
            $cashCounter->order_id = $request->order_id;
            $cashCounter->user_id = auth()->id();
            $cashCounter->comments = $request->comments;
            $success = $cashCounter->save();
            //
            $order = Order::where('order_id', $request->order_id)->first();
            $order->is_paid = 1;
            $order->payment_type = "COC";
            $order->update();
            $order = Order::where('order_id', $request->order_id)->first();
            $order->order_status = 6;
            $order->update();

            $delivery_boy_charge = 0;
            $shipping_cost = 0;
            $shipment_sent = ShipmentSent::where('received', 1)->get();
            foreach ($shipment_sent as $shipment) {

                if (in_array($order->order_id, json_decode($shipment->order_id), true)) {

                    $total = count(json_decode($shipment->order_id));
                    $perOrderCost = $shipment->shipment_cost / $total;
                    $shipping_cost = $shipping_cost + $perOrderCost;
                }
            }

            $operationalCost = $delivery_boy_charge + $shipping_cost;
            $shipping_charge = $order->shipment_charge;
            $net_amt = $shipping_charge - $operationalCost;

            $comission = new Comission();
            $comission->order_id = $order->order_id;
            // dd($shipping_cost);
            $comission->shipping_cost = $shipping_cost;
            if ($order->order_created_as == 'RETURN') {
                //yaha arko  table bata aauxa hai
                $comission->cod = $order->cod;
                $comission->delivery_boy_comission = '0';
                $comission->pickup_hub = '0';
                $comission->delivery_hub = '0';
                $comission->dsewa = $order->shipping_charge;
            } else {
                $comission->cod = $order->cod;
                $comission->delivery_boy_comission = $delivery_boy_charge;
                $comission->pickup_hub = (20 / 100) * $net_amt;
                $comission->delivery_hub = (30 / 100) * $net_amt;
                $comission->dsewa = (50 / 100) * $net_amt;
            }
            $comission->save();
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


    public function cashCounterUpdate(Request $request, $id)
    {

        $cashCounter = CashOnCounter::where('id', $id)->first();
        $cashCounter->order_id = $request->order_id;
        $cashCounter->comments = $request->comments;
        $success = $cashCounter->update();
        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Updated.'
            ]);
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Updating !!'
            ]);
        }
    }

    public function cashCounterDelete($id)
    {

        $cashCounter = CashOnCounter::find($id);
        $order = Order::where('order_id', $cashCounter->order_id)->first();
        $order->is_paid = 0;
        $order->payment_type = "COD";

        $order->update();
        $success = $cashCounter->delete();

        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Deleted.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Deleting'
            ]);
        }
    }

    //cash on counter



    //hub order cancel

    public function getcancelOrder(Request $request)
    {


        $cancelOrders = OrderCancel::orderBy('id', 'Desc')->get();
        if ($request->ajax()) {

            if ($request['startdate'] && $request['enddate'] != null) {
                $startdate = Carbon::parse($request['startdate'])->format('Y-m-d h:m:s');
                $enddate = Carbon::parse($request['enddate'])->format('Y-m-d h:m:s');
                // dd($lists);
                $cancelOrders  = $cancelOrders->where('created_at', '>=', $startdate)->where('created_at', '<=', $enddate);
            }
        }



        foreach ($cancelOrders as $cancelOrder) {
            $cancelOrder->approve_by = $cancelOrder->acceptBy->name;


            //                $collectPayment->orders
            $date = Carbon::parse($cancelOrder->created_at);
            $cancelOrder->differdate = $date->format('d, F');
        }
        $cancelOrders = $this->paginateHelper($cancelOrders, 5);

        $deliveryBoys = User::where('active', 1)->where('parent_id', auth()->id())->whereHas('roles', function ($q) {
            $q->where('name', 'delivery_officer');
        })->get();
        if (Auth::User()->hasRole('admin')) {
            $orders = Order::where('order_status', 1)->get();
        } else {
            $orders = Order::where('order_status', 1)->where('hub_id', auth()->id())->get();
        }

        return response()->json([
            'deliveryBoy' => $deliveryBoys,
            'orders' => $orders,
            'cancelOrders' => $cancelOrders,
        ]);
    }


    public function cancelOrderStore(Request $request)
    {
        $pickup = ReceivePickup::where('order_id', $request->order_id)->first();
        if ($pickup) {
            return response()->json([
                'status' => 'error',
                'title' => 'Errors',
                'message' => 'This Order is ALready  in shipment ..cannot cancel.'
            ]);
        }

        $orderExist = OrderCancel::where('order_id', $request->order_id)->exists();
        if ($orderExist) {
            return response()->json([
                'status' => 'error',
                'title' => 'Errors',
                'message' => 'This Order Has Been Alredy Canceled.'
            ]);
        } else {
            $order = Order::where('order_id', $request->order_id)->first();
            $order->order_status = 5;
            $order->update();
            $cancelOrder = new OrderCancel();
            $cancelOrder->order_id = $request->order_id;
            $cancelOrder->user_id = auth()->id();
            $cancelOrder->comments = $request->comments;
            $success = $cancelOrder->save();
            $comission = new Comission();
            $comission->order_id = $request->order_id;
            // dd($shipping_cost);

            //yaha arko  table bata aauxa hai
            $comission->cod = 0;
            $comission->delivery_boy_comission = '0';
            $comission->pickup_hub = '0';
            $comission->delivery_hub = '0';
            $comission->dsewa = '0';

            $comission->save();
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


    public function cancelOrderUpdate(Request $request, $id)
    {

        $cancelOrder = OrderCancel::find($id);
        $cancelOrder->comments = $request->comments;
        $success = $cancelOrder->update();
        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Updated.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Updating !!'
            ]);
        }
    }


    public function cancelOrderDelete($id)
    {

        $cancelOrder = OrderCancel::find($id);
        $success = $cancelOrder->delete();

        if ($success) {

            return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => 'Successfully Deleted.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Error While Deleting'
            ]);
        }
    }

    public function getcodamt(Request $request)
    {
        $total = 0;
        foreach ($request->order_id as $id) {
            $total = $total + (int) Order::where('order_id', $id)->first()->cod;
        }

        return response()->json($total);
    }
}
