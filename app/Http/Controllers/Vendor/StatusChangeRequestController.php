<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrderStatusChangeRequest as OrderStatusChange;
use App\OrderStatus;
use Auth;
use App\OrderComment;
use App\Comment;

use App\Order;
use App\Helpers\PaginationHelper;

class StatusChangeRequestController extends Controller
{
    use PaginationHelper;

    public function post_status_request(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'status_id' => 'required',
            'cod' => '',

            'comment_id'=>'required'
        ]);
        if($request->status_id !=5 ){
            $request->validate([

            'product_type' => 'required',
            ]);
            }
        if (OrderStatusChange::where('order_id', $request->order_id)->first()) {
            return response()->json(['error' => "Duplicate Order"]);
        }
        $productType = Order::where('order_id', $request->order_id)->first()->product_type;
        $productType = count(json_decode($productType));

        if ($request->status_id == 4) {
            $count = count($request->product_type);
            if ($count == $productType) {
                return response()->json(['error' => 'Cannot Partial All Products Type']);
            }
        } else  if ($request->status_id == 8) {
            $count = count($request->product_type);
            if ($count != $productType) {
                return response()->json(['error' => 'Please Select All Products To Return']);
            }
        }

        $comment = new OrderComment();
        $comment->user_id = auth()->id();
        $comment->order_id = $request->order_id;
        $comment->comment = $request->comment_id;
        $comment->save();
        if ($request->order_id  && $request->status_id) {

            $request['product_type'] = json_encode($request->product_type);
            $request->merge(['vendor_id' => auth()->id()]);
            // $request['request_status'] = 1;
            OrderStatusChange::create($request->all());

            return response()->json(['success' => 'Order Status Updated'], 200);
        }
        return response()->json(['error' => 'Order or Status  Is Empty']);
    }
    public function vendor_change_status_order(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $orderrequest = OrderStatusChange::orderBy('id', 'DESC')->get();
        } elseif ($user->hasRole('vendor')) {
            $orderrrequest=[];
            $or=OrderStatusChange::orderBy('id','desc')->pluck('order_id')->toArray();
            foreach($or as $o)
        {
            $o=Order::where('order_id',$o)->first();
            if($o->sender_id== auth()->id()){
                $orderrequest[]=OrderStatusChange::where('order_id',$o->order_id)->first();

            }


            }
            // dd($orderrequest);
        } elseif ($user->hasRole('hub')) {
            // $orders = OrderStatusChange::where('request_status', '0')->pluck('order_id')->toArray();
            $orders = OrderStatusChange::all()->pluck('order_id')->toArray();

            $orderrequest = [];
            $orderrequest1 = [];
            foreach ($orders as $order) {
                $order = Order::where('order_id', $order)->first();

                if ($order->hub_id == auth()->id()) {
                    $orderrequest1[] = OrderStatusChange::where('order_id', $order->order_id)->first()->order_id;
                }
            }
            $orderRequest2 = OrderStatusChange::where('vendor_id', auth()->id())->pluck('order_id')->toArray();
            foreach ($orderRequest2 as $order) {

                $orderrequest1[] = OrderStatusChange::where('order_id', $order)->first()->order_id;
            }
            $orderrequest3 = array_unique($orderrequest1);
            $orderrequest = OrderStatusChange::orderBy('id', 'DESC')->whereIn('order_id', $orderrequest3)->get();
            // foreach ($orderrequest3 as $o) {
            //     $orderrequest[] = OrderStatusChange::where('order_id', $o)->first();
            // }
        }

        foreach ($orderrequest as $req) {

            $req->order_id = json_decode($req->order_id, true);
            $req->comment_id = json_decode($req->comment_id, true);
            $req->product_type = json_decode($req->product_type);
            $req->status = OrderStatus::where('id', $req->status_id)->first()->name;
            $req->comment =  $req->comment_id;
            $req->request_status = $req->request_status == 1 ? false : true;
        }

        $orderrequest =  $this->paginateHelper($orderrequest, 10);


        return response()->json(['orderrequest' => $orderrequest]);
    }
    public function drop_request($id)
    {
        $order = OrderStatusChange::findOrfail($id);
        $order->delete($id);
        return response()->json(['success' => 'Payment Deleted.']);
    }
}
