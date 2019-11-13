<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipmentSent as shipping;
use App\ShipmentSent;
use Illuminate\Http\Request;
use App\Address;
use App\Helpers\PaginationHelper;

use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use App\User;
use App\Order;
use Illuminate\Support\Facades\Auth;
use App\BusinessInfo;
use App\HubDeliverySent;
use App\OrderReturnedOrder;
use App\OrderStatusChangeRequest;
use App\ReceivePickup;
use App\ShipmentReceive;
use Carbon\Carbon;

class ShipmentSentController extends Controller
{
    use PaginationHelper;
    //
    public function store(shipping $request)
    {

        if (Auth()->user()->hasRole('admin')) {
            $from  = $request->from;
        } else {
            $from  = auth()->id();
        }
        $to  = $request->to;

        if (BusinessInfo::where('user_id', $from)->get()->count() <= 0) {
            return response()->json(['error' => 'Please Enter Your Business Information']);
        }
        if (BusinessInfo::where('user_id', $to)->get()->count() <= 0) {
            return response()->json(['error' => 'No Business Information Is Added By  Receiving Hub']);
        }

        $sames = ShipmentSent::where('to', $to)->get();
        foreach ($sames as $same) {
            $common = array_intersect(json_decode($same->order_id), $request->order_id);
            if (count($common) > 0) {
                return response()->json(['error' => 'Order Id Duplicate']);
            }
        }
        $request['order_id'] = json_encode($request->order_id);

        $shipment = ShipmentSent::create([
            'user_id' => auth()->id(),
            'shipment_officer_id' => $request->shipment_officer_id,
            'description' => $request->description,
            'from' => $from,
            'to' => $to,
            'shipment_date' => $request->shipment_date,
            'shipment_cost' => $request->shipment_cost,
            'expected_arrival_date' => $request->expected_arrival_date,
            'reference' => $request->reference,
            'order_id' => $request->order_id,

        ]);


        $fromHub = Address::where('id', BusinessInfo::where('user_id', $from)->first()->district)->first() ? Address::where('id', BusinessInfo::where('user_id', $from)->first()->district)->first()->short_address : '';
        $toHub = Address::where('id', BusinessInfo::where('user_id', $to)->first()->district)->first() ?  Address::where('id', BusinessInfo::where('user_id', $to)->first()->district)->first()->short_address : '';

        $shipment->shipment_id =  $fromHub . '-' .   $toHub . '-' . $shipment->id;
        $shipment->barcode = $this->generateBarcodeNumber();
        $shipment->update();
        return response()->json(['success' => 'Added']);
    }
    public function update(shipping $request, $id)
    {


        $shipment = ShipmentSent::findOrfail($id);
        $from  = Address::find($request->from)->short_address;
        $to  = Address::find($request->to)->short_address;
        $request['order_id'] = json_encode($request->order_id);
        $shipment = $shipment->update([
            'user_id' => auth()->id(),
            'shipment_officer_id' => $request->shipment_officer_id,
            'description' => $request->description,
            'from' => $request->from,
            'to' => $request->to,
            'shipment_date' => $request->shipment_date,
            'expected_arrival_date' => $request->expected_arrival_date,
            'reference' => $request->reference,
            'shipment_cost' => $request->shipment_cost,
            'order_id' => $request->order_id,
            ///yo thau ma hub ko  dsitrick ko short address hunxa
            'shipment_id' => $from . '-' . $to . '-' . $id,
        ]);

        return response()->json(['success' => 'Added']);
    }
    function generateBarcodeNumber()
    {
        $number = mt_rand(1000000000, mt_getrandmax());
        if ($this->barcodeNumberExists($number)) {
            return $this->generateBarcodeNumber();
        }

        return $number;
    }
    function barcodeNumberExists($number)
    {
        return ShipmentSent::whereBarcode($number)->exists();
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $shipments = ShipmentSent::orderby('id', 'DESC');
        } else {
            $shipments = ShipmentSent::where('user_id', $user->id)->orderby('id', 'DESC');
        }
        // dd($shipments);
        if ($request->has('order_id') && $request['order_id'] != null) {

            $shipments = $shipments->where('order_id', 'like', '%' . $request->order_id . '%');
        }
        $shipments = $shipments->get();
        foreach ($shipments as $item) {
            $item->order_id = json_decode($item->order_id);

            $item->hub_from = User::findorfail($item->from)->name;
            $item->hub_to = User::findorfail($item->to)->name;
            $item->status = $item->received == 1 ? 'Received' : 'Pending';
        }

        if ($request->has('paginate')) {
            $p = $request->input('paginate');
        } else {
            $p = 10;
        }
        $shipments = $this->paginateHelper($shipments, $p);
        return response()->json($shipments);
    }
    public function incoming(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $shipments = ShipmentSent::all();
        } else {
            $shipments = ShipmentSent::where('to', $user->id)->where('received', 0)->get();
        }
        // dd($shipments);
        foreach ($shipments as $item) {
            $item->order_id = json_decode($item->order_id);

            $item->hub_from = User::findorfail($item->from)->name;
            $item->hub_to = User::findorfail($item->to)->name;
        }
        // dd($shipments);

        if ($request->has('paginate')) {
            $p = $request->input('paginate');
        } else {
            $p = 10;
        }
        $shipments = $this->paginateHelper($shipments, $p);
        return response()->json($shipments);
    }

    public function getshippingid(Request $request)
    {
        $shipments = ShipmentSent::all('barcode', 'shipment_id');

        if ($request->has('barcode')) {
            $shipments = $shipments->where('barcode', $request->barcode);
        }

        return response()->json($shipments);
    }
    public function dropshipmentsent($id)
    {
        $shipment = ShipmentSent::findOrfail($id);
        $shipment->delete($id);
        return response()->json(['success' => 'Deleted']);
    }
    public function getShipmentOfficer()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $users = User::where('active', 1)->whereHas('roles', function ($q) {
                $q->where('name', 'shipment_officer');
            })->get();
            $is_admin = 1;
        } else {
            $users = User::where('active', 1)->where('parent_id', $user->id)->whereHas('roles', function ($q) {
                $q->where('name', 'shipment_officer');
            })->get();
            $is_admin = 0;
        }
        return response()->json(['users' => $users, 'is_admin' => $is_admin]);
    }

    public function getHubList()
    {
        $hubs = User::where('active', 1)->whereHas('roles', function ($q) {
            $q->where('name', 'hub');
        })->get();

        return response()->json($hubs);
    }
    public function getOrder()
    {

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $orders = Order::where('order_status', 1)->get();
        } else {

            $id = auth()->id();
            // dd($id);
            $order_id = [];
            //to ma aako shipment
            $shipment1 = ShipmentSent::where('to', $id)->where('received',1)->get();
            foreach ($shipment1 as $shipment) {
                $ship = json_decode($shipment->order_id);
                foreach ($ship as $s) {
                    $order = Order::where('order_id', $s)->first();
                    if ($order) {

                        if ($order->order_status == 1) {
                            $order_id[] = $order->order_id;
                        }
                    }
                }
            }
            //


            // dd($order_id);
            //yaha pickup vako matra dekaune bnauna milxa
            $receivedP = ReceivePickup::where('user_id', auth()->id())->pluck('order_id')->toArray();
            $shipment2 = [];
            foreach ($receivedP as $p) {
                $pOrder = Order::where('order_id', $p)->first();
                if ($pOrder->order_status == 1 && $pOrder->hub_id != $pOrder->pickup_hub) {
                    $shipment2[] = $p;
                }
            }


            $halforders = OrderStatusChangeRequest::where('status_id', 4)->orwhere('status_id', 8)->get();
            $halforders = $halforders->where('vendor_id', auth()->id())->where('request_status', 1)->pluck('order_id')->toArray();
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
            $merged = array_merge($shipment2, $returningid);

            $shipment3 = ShipmentSent::where('user_id', auth()->id())->pluck('order_id')->toArray();
            $abc = [];
            foreach ($shipment3 as $shipment) {
                $orderjsons = json_decode($shipment);
                foreach ($orderjsons as $id) {
                    $abc[] = $id;
                }
            }
            $HubDeliverySent = HubDeliverySent::where('user_id', auth()->id())->pluck('order_id')->toArray();
            $xyz = [];
            foreach ($HubDeliverySent as $hub) {
                $orderjsons = json_decode($hub);
                foreach ($orderjsons as $Id) {
                    $xyz[] = $Id;
                }
            }
            $abc = array_merge($abc, $xyz);

            $o = array_merge($merged, $order_id);

            $orders = array_diff($o, $abc);
        }
        // dd($orders);


        $shipment_orders = Order::whereIn('order_id', $orders)->where('inquiry', 1)->select('order_id', 'pickup_hub', 'hub_id', 'order_created_as')->get();

        if ($shipment_orders) {
            foreach ($shipment_orders as $s) {

                $s->vendor_name = User::where('id', $s->hub_id)->first() ? User::where('id', $s->hub_id)->first()->name : '';
                if ($s->order_created_as != "NEW") {
                    $s->tag_created_as = substr($s->order_created_as, 0, 1);
                }
                if ($s->order_created_as == "RETURN") {
                    $s->vendor_name = User::where('id', $s->pickup_hub)->first() ? User::where('id', $s->pickup_hub)->first()->name : '';
                }
            }
        }

        return response()->json($shipment_orders);
    }
    public function getEditOrder(Request $request)
    {

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $orders = Order::where('order_status', 1)->get();
        } else {

            $id = auth()->id();
            // dd($id);
            $order_id = [];
            //to ma aako shipment
            $shipment1 = ShipmentSent::where('to', $id)->get();
            foreach ($shipment1 as $shipment) {
                $ship = json_decode($shipment->order_id);
                foreach ($ship as $s) {
                    $order = Order::where('order_id', $s)->first();
                    if ($order) {

                        if ($order->order_status == 1) {
                            $order_id[] = $order->order_id;
                        }
                    }
                }
            }
            //


            // dd($order_id);
            //yaha pickup vako matra dekaune bnauna milxa
            $receivedP = ReceivePickup::where('user_id', auth()->id())->pluck('order_id')->toArray();
            $shipment2 = [];
            foreach ($receivedP as $p) {
                $pOrder = Order::where('order_id', $p)->first();
                if ($pOrder->order_status == 1) {
                    $shipment2[] = $p;
                }
            }


            $halforders = OrderStatusChangeRequest::where('status_id', 4)->orwhere('status_id', 8)->get();
            $halforders = $halforders->where('vendor_id', auth()->id())->where('request_status', 1)->pluck('order_id')->toArray();
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
            $merged = array_merge($shipment2, $returningid);

            $shipment3 = ShipmentSent::where('user_id', auth()->id())->pluck('order_id')->toArray();
            $abc = [];
            foreach ($shipment3 as $shipment) {
                $orderjsons = json_decode($shipment);
                foreach ($orderjsons as $id) {
                    $abc[] = $id;
                }
            }
            $o = array_merge($merged, $order_id);

            $orders = array_diff($o, $abc);
        }
        $idOrders = json_decode(ShipmentSent::where('id', $request->id)->first()->order_id);
        // dd($orders);
        $orders2 = array_merge($orders, $idOrders);
        $shipment_orders = [];
        foreach ($orders2 as $order) {
            $shipment_orders[] = Order::where('order_id', $order)->select('order_id', 'pickup_hub', 'hub_id', 'order_created_as')->first();
        }
        // dd($shipment_orders);
        foreach ($shipment_orders as $s) {

            $s->vendor_name = User::where('id', $s->hub_id)->first() ? User::where('id', $s->hub_id)->first()->name : '';
            if ($s->order_created_as != "NEW") {
                $s->tag_created_as = substr($s->order_created_as, 0, 1);
            }
            if ($s->order_created_as == "RETURN") {
                $s->vendor_name = User::where('id', $s->pickup_hub)->first() ? User::where('id', $s->pickup_hub)->first()->name : '';
            }
        }
        return response()->json($shipment_orders);
    }
    public function shipmentstore(Request $request)
    {
        // dd(Auth::user()->id);
        $shipment = ShipmentSent::findOrfail($request->id);
        $shipmentreceived = ShipmentReceive::where('shipment_id', $shipment->shipment_id)->first();
        if ($shipmentreceived) {
            return response()->json(['error' => 'Already Added']);
        } else {

            ShipmentReceive::create([
                'shipment_id' => $shipment->shipment_id,
                'arrival_date' => Carbon::now(),
                'user_id' => Auth::user()->id
            ]);
            $shipment->received = 1;
            $shipment->update();
        }
        return response()->json(['success' => 'Received'], 200);
    }
    public function changeLog(Request $request)
    {
        if ($request->id) {
            $shipment = ShipmentSent::findorfail($request->id);
            if ($shipment->locked == 1) {
                $shipment->locked = 0;
            } else {
                $shipment->locked = 1;
            }
            $shipment->update();
            return response()->json(['success' => 'Changed '], 200);
        } else {
            return response()->json(['error' => 'No Shipment']);
        }
    }
}
