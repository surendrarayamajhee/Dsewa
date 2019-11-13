<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\BulkAssignedOrder;
use App\HubDeliverySent;
class deliveryboybulkassignedorderController extends Controller
{
    public function index()
    {
        $deliveries=BulkAssignedOrder::where('user_id',auth()->id())->get();
        // dd($deliveries);
        foreach($deliveries as $delivery)
        {
            $delivery->order_id=json_decode($delivery->order_id);
        }
        return response()->json($deliveries);
    }
    public function store(Request $request)
    {
        $delivery=new BulkAssignedOrder();
        $delivery->destination=$request->destination;
        $delivery->order_id=json_encode($request->order_id) ;
        $delivery->user_id = auth()->id();
        $delivery->save();
        return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => "Added the Bulk Assigned Order"
            ]);
    }
    // bulk assigned to officer
    public function officerIndex()
    {
        $destinations=BulkAssignedOrder::where('user_id',auth()->id())->select('id','destination')->get();
        return response()->json($destinations);
    }
     public function officerStore(Request $request)
    {
        $deliveries=BulkAssignedOrder::findOrFail($request->dest_id);
        $delivery=new HubDeliverySent();
        $delivery->order_id=$deliveries->order_id;
        $delivery->delivery_boy_id=$request->delivery_boy_id;
        $delivery->user_id=Auth::user()->id;
        $delivery->date_time=$request->date_time;
        $delivery->comments=$request->comments;
        $delivery->save();
        $deliveries->delete();
        return response()->json([
                'status' => 'success',
                'title' => 'Success',
                'message' => "Added the Bulk Assigned to Officer"
            ]);
    }


}
