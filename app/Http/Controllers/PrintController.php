<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use App\Order;
use App\UserAddress;
use App\Address;
use App\ShipmentSent;
use App\User;
use App\Vendor_Info;
use App\VendorPickup;
use Carbon\Carbon;
use App\SendPickUp;

class PrintController extends Controller
{
    public function orderPrint($id)
    {
        $order = Order::findorfail($id);
        $order->order_date = Carbon::parse($order->order_date)->isoFormat('YYYY MMMM Do ');
        $order->expected_date = Carbon::parse($order->expected_date)->isoFormat('YYYY MMMM Do ');

        $useraddress = UserAddress::where('id', $order->receiver_id)->first();
        $useraddress->state = Address::where('id', $useraddress->state_no)->first() ? Address::where('id', $useraddress->state_no)->first()->address : '';
        $useraddress->district = Address::where('id', $useraddress->district)->first() ? Address::where('id', $useraddress->district)->first()->address : '';
        $useraddress->municipality = Address::where('id', $useraddress->municipality)->first() ? Address::where('id', $useraddress->municipality)->first()->address : '';
        $useraddress->ward = Address::where('id', $useraddress->ward_no)->first() ? Address::where('id', $useraddress->ward_no)->first()->address : '';
        $useraddress->area = Address::where('id', $useraddress->area)->first() ? Address::where('id', $useraddress->area)->first()->address : '';

        $bar_code = $order->bar_code;

        $vendor_name = User::findorfail($order->sender_id)->name;
        $vendor_info = Vendor_Info::where('vendor_id', $order->sender_id)->select('phone1', 'phone2')->first();
        $pickup = VendorPickup::where('id', $order->order_pickup_point)->first();
        $pickup->state = Address::where('id', $pickup->state_id)->first() ?  Address::where('id', $pickup->state_id)->first()->address : '';
        $pickup->district = Address::where('id', $pickup->district_id)->first() ? Address::where('id', $pickup->district_id)->first()->address : '';
        $pickup->municipality = Address::where('id', $pickup->municipality_id)->first() ?  Address::where('id', $pickup->municipality_id)->first()->address : '';
        $pickup->ward = Address::where('id', $pickup->ward_id)->first() ?  Address::where('id', $pickup->ward_id)->first()->address : '';
        $pickup->area = Address::where('id', $pickup->area_id)->first() ? Address::where('id', $pickup->area_id)->first()->address : '';


        $barcode = new BarcodeGenerator();
        $barcode->setText(strval($bar_code));
        $barcode->setType(BarcodeGenerator::Code128);
        $barcode->setScale(4);
        $barcode->setThickness(40);
        $barcode->setFontSize(10);
        $barcode->setLabel($bar_code);
        $code = $barcode->generate();
        return view('print.order', compact('code', 'order', 'vendor_name', 'vendor_info', 'pickup', 'useraddress'));
    }





    public function  printPackage($id)
    {
        $package = SendPickUp::findorfail($id);
        $orders = json_decode($package->orders);

        return view('print.package', compact('package', 'orders'));
    }
    public function  printPackageOrder($id)
    {
        $package = SendPickUp::findorfail($id);
        $orders = json_decode($package->orders);
// dd($package);
        return view('print.package_order', compact('package', 'orders'));
    }
    public function  bulkprint(Request $request)
    {

        $orders = json_decode($request->order);

        return view('print.package_order', compact('orders'));
    }
    public function printAssignshipment($id)
    {


        $Assignshipments = ShipmentSent::where('user_id', auth()->id())->where('id', $id)->first();
        $AssignshipmentID = [];


        $AssignID = json_decode($Assignshipments->order_id);

        foreach ($AssignID as $DID) {
            $AssignshipmentID[] = $DID;
        }
        $user = User::where('id', $Assignshipments->shipment_officer_id)->first()->name;
        // dd($user);

        $orders = Order::whereIn('order_id', $AssignshipmentID)->get();
       $date=  Carbon::parse($Assignshipments->created_at)->isoFormat('YYYY MMMM dddd');

        foreach ($orders as $order) {
            $reciverDetail = UserAddress::where('id', $order->receiver_id)->first();
            $order->receiverName = $reciverDetail->first_name . ' ' . $reciverDetail->last_name;
            $order->receiverPhone1 = $reciverDetail->phone1;
            $order->receiverPhone2 = $reciverDetail->phone2;
            $order->receiverMunicipality = Address::where('id', $reciverDetail->municipality)->pluck('address')->first();
            $order->receiverAarea = Address::where('id', $reciverDetail->area)->pluck('address')->first();
            $order->receiverWard = Address::where('id', $reciverDetail->ward_no)->pluck('address')->first();
        }
        // dd($printOrder);
        return view('print.shipment-print-order', compact('orders','user','date'));
        // return response()->json($orders);
    }
}
