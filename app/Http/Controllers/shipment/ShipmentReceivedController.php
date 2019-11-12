<?php

namespace App\Http\Controllers\shipment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ShipmentReceive;
use App\ShipmentSent;
use App\Http\Requests\ShipmentReceived;
use Carbon\Carbon;
use App\Helpers\PaginationHelper;
use App\User;
use Auth;

class ShipmentReceivedController extends Controller
{
    use PaginationHelper;

    public function store(ShipmentReceived $request)
    {


        $shipmentsent = ShipmentSent::where('barcode', $request->barcode)->orWhere('shipment_id', $request->shipment_id)->first();

        if ($shipmentsent) {
            if ($shipmentsent->to != auth()->id()) {
                return response()->json(['error' => 'Shipment Not Found']);
            }
            $barcode = ShipmentReceive::where('shipment_id', $shipmentsent->shipment_id)->first();

            if ($barcode) {

                return response()->json(['error' => 'Already Added']);
            }

            if ($request->barcode) {
                $shipmentsent = ShipmentSent::where('barcode', $request->barcode)->first();
            } else {
                $shipmentsent = ShipmentSent::where('shipment_id', $request->shipment_id)->first();
            }
            if ($shipmentsent) {
                $recived = ShipmentReceive::create([
                    'user_id' => auth()->id(),
                    'description' => $request->description,
                    'arrival_date' => $request->arrival_date,
                    'shipment_id' => $shipmentsent->shipment_id,
                    'barcode' => $request->barcode
                ]);
                $shipmentsent->received_logistic_cost = $request->received_logistic_cost;
                $shipmentsent->received = 1;
                $shipmentsent->update();
                return response()->json(['success' => 'Added'], 200);
            }
        }
        return response()->json(['error' => 'oops Incorrect Code Entered']);
    }

    public function shipmentreceivedupdate(Request $request, $id)
    {
        $shipment = ShipmentReceive::findOrfail($id);
        $shipment->update($request->all());
        // dd($request->shipment_id);
        $shipmentsent = ShipmentSent::where('shipment_id', $shipment->shipment_id)->first();
        // dd($shipmentsent);
        $shipmentsent->received_logistic_cost = $request->received_logistic_cost;
        $shipmentsent->update();
        return response()->json(['success' => 'Updated'], 200);
    }

    public function getshipmentreceived(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $shipments = ShipmentReceive::orderBy('id', 'DESC')->get();
        } else {

            $shipments = ShipmentReceive::where('user_id', auth()->id())->orderby('id', 'DESC')->get();
        }


        foreach ($shipments as $shipment) {
            // dd(ShipmentSent::where('shipment_id', $shipment->shipment_id)->first()->order_id);
            $shipment->order = ShipmentSent::where('shipment_id', $shipment->shipment_id)->pluck('order_id')->toArray();
            foreach ($shipment->order as $order) {
                $shipment->order = json_decode($order);
            }
            $shipment->shipment_officer_name  = User::where('id', $shipment->user_id)->first()->name;
            $shipment->received_logistic_cost = ShipmentSent::where('shipment_id', $shipment->shipment_id)->first() ?  ShipmentSent::where('shipment_id', $shipment->shipment_id)->first()->received_logistic_cost : '';
        }
        // dd($shipments );

         if ($request->has('paginate')) {
             $p = $request->input('paginate');
         } else {
             $p = 10;
         }
        $shipments = $this->paginateHelper($shipments, $p);
        return response()->json($shipments);
    }

    public function dropshipmentreceived($id)
    {
        $shipment = ShipmentReceive::findOrfail($id);
        $shipment->delete($id);
        return response()->json(['success' => 'Deleted'], 200);
    }
}
