<?php

namespace App\Http\Controllers\pickuporder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PickUpOrder;
use App\Order;
use App\Helpers\TrackingId;
use App\Helpers\Barcode;
use App\Helpers\PaginationHelper;
use App\UserAddress;
use  App\Http\Requests\PickupOrderRequest;
use Carbon\Carbon;
use App\HubArea;
use App\HubCharge;
use Auth;
use App\VendorPickup;
use App\Vendor_Info;

class PickuporderController extends Controller
{
    use TrackingId, Barcode, PaginationHelper;
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function pickupStore(PickupOrderRequest $request)
    {

        $request->merge(['vendor_id' => auth()->id()]);
        $request['product_type'] = json_encode($request->product_type);
        $pickup = PickUpOrder::create($request->all());
        $ward = UserAddress::where('id', $pickup->useraddress_id)->first()->ward_no;
        // dd($ward);
        if ($ward == '') {
            $pickup->is_ward_status = 1;
            $pickup->update();
        }

        return response()->json(['success' => 'Pickup Order Created'], 200);
    }


    public function getpicuporder(Request $request)
    {
      
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $orders = PickUpOrder::orderBy('id', 'DESC')->where('packed',0)->get();
        } elseif ($user->hasRole('vendor')) {

            $orders = PickUpOrder::where('vendor_id', auth()->id())->where('packed',0)->get();
        }

        // $orders->transform(function ($item, $key) {

        //     return $item;
        // });
        foreach ($orders as $order) {
            $order->useraddress = UserAddress::where('id', $order->useraddress_id)->first();
            $order->expected_date =  $order->expected_date;
            $date = Carbon::parse($order->expected_date);
            $order->product_type = json_decode($order->product_type);
            $order->expecteddate =  $date->isoFormat('YYYY MMMM Do');
        }

        if ($request->input('paginate') != null){
            $p = $request->input('paginate');
        } else {
            $p = 10;
        }
        $orders = $this->paginateHelper($orders, $p);
        return response()->json($orders);
    }

    public function sendpicorderrequest(Request $request)
    {

        if ($request->order_pickup_point == Null) {
            return response()->json(['error' => 'Select Pickup Address']);
        }
        $profile = Vendor_Info::where('vendor_id',auth()->id())->first();
        if(!$profile)
        {
            return response()->json(['error' => 'Please Fill Your Profile']);

        }
        if (count($request->checkbox) > 0) {

            foreach ($request->input('checkbox') as $check) {
                // $order = Order::all();
                $pickup = PickUpOrder::findOrfail($check);

                $trackingid = $this->generateid();
                $barcode = $this->generateBarcodeNumber();
               if( HubArea::where('address_id',VendorPickup::where('id', $request->order_pickup_point)->first()->ward_id)->first() == null)
               {
                return response()->json(['error' => 'Sorry!! Your Pickup Point Is Not Assigned to any Branch, Please Contact Admin.']);

               }
                if (isset($pickup)) {

                    $order = Order::create([
                        'tracking_id' => $trackingid,
                        'handling' => $pickup->handling,
                        'order_description' => $pickup->description,
                        'cod' => $pickup->cod,
                        'expected_date' => $pickup->expected_date,
                        'sender_id' => $pickup->vendor_id,
                        'receiver_id' => $pickup->useraddress_id,
                        'bar_code' => $barcode,
                        'product_type' => $pickup->product_type,
                        'weight' => $pickup->weight,
                        'vendor_order_id'=> $pickup->vendor_order_id,
                        'order_pickup_point' => $request->order_pickup_point,
                        'hub_id' =>HubArea::where('address_id', UserAddress::where('id', $pickup->useraddress_id)->first()->ward_no)->first() ? HubArea::where('address_id', UserAddress::where('id', $pickup->useraddress_id)->first()->ward_no)->first()->hub_id : null,
                        'pickup_hub' => HubArea::where('address_id',VendorPickup::where('id', $request->order_pickup_point)->first()->ward_id)->first()->hub_id,
                    ]);
                    $order->order_id = $order->id;
                    $useraddress = UserAddress::where('id', $pickup->useraddress_id)->select('id', 'district', 'municipality', 'ward_no')->first();
                    if ($useraddress->ward_no) {
                        $hubcharge = HubCharge::where('ward_id',  $useraddress->ward_no)->first();
                        if(isset($hubcharge))
                        {
                            if ($order->handling == 'FRAGILE') {
                                $order->shipment_charge = $hubcharge->fragile_charge;
                            } else {
                                $order->shipment_charge = $hubcharge->non_fragile_charge;
                            }
                        }
                    } else {

                        $order->shipment_charge = 0;
                    }

                    $order->shipment_charge =  $order->shipment_charge * $pickup->weight;
                    $order->update();
                    $pickup->packed = 1;
                    $pickup->update();
                    $ward = UserAddress::where('id', $pickup->useraddress_id)->first()->ward_no;
                    if ($ward == null) {
                        $order->order_status = 0;
                        $order->update();
                    }
                }
                $order = PickUpOrder::findOrfail($check);
                $order = $order->delete();
            }

            return response()->json(['success' => 'Added to Order'], 200);
        }
        return response()->json(['error' => 'Checkbox is empty']);
    }

    public function updatepicuporder(Request $request, $id)
    {
        $order = PickUpOrder::findOrfail($id);
        $request['product_type'] = json_encode($request->product_type);
        $order = $order->update($request->all());
        $order = PickUpOrder::findOrfail($id);
        $order->useraddress = UserAddress::where('id', $order->useraddress_id)->first();
        $order->expected_date =  $order->expected_date;
        $date = Carbon::parse($order->expected_date);
        $order->product_type = json_decode($order->product_type);
        $order->expecteddate =  $date->isoFormat('YYYY MMMM Do');
        return response()->json(['success' => 'Pick-Up Order Updated','order'=>$order], 200);
    }
    public function droppickup($id)
    {
        $order = PickUpOrder::findOrfail($id);
        $order = $order->delete($id);
        return response()->json(['success' => 'Pick-Up Order Deleted'], 200);
    }
    public function dropall(Request $request)
    {
        # code...
        // dd($request->all());
        if($request['checkbox'] == null)
        {
            return response()->json(['error' => 'Please Select Order ID']);

        }
        foreach($request->checkbox as $check)
        {
            $order = PickUpOrder::findOrfail($check);
            $order = $order->delete();
        }
        return response()->json(['success' => 'Pick-Up Order Deleted'], 200);


    }
}
