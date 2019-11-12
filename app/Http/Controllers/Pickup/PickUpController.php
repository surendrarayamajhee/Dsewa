<?php

namespace App\Http\Controllers\Pickup;

use Illuminate\Http\Request;
use App\SendPickUp;
use App\User;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderLog;
use App\ReceivePickup;
use App\UserAddress;
use App\Address;

class PickUpController extends Controller
{
    use PaginationHelper;
    public function store(Request $request)
    {
        $request->validate([

            'pickup_officer_id' => 'required',
            'vendor_id' => 'required',
            'order_id' => 'required',

        ]);

        if (SendPickUp::where('orders', json_encode($request->order_id))->first()) {
            return response()->json(['error' => 'Order Already Added']);
        }
        $a = $request->vendor_id;

        $pickUp = new SendPickUp();
        $pickUp->vendor_id = $request->vendor_id;
        //here stays  auth user
        $pickUp->user_id = auth()->id();
        $pickUp->orders = json_encode($request->order_id);
        $pickUp->pickup_logistic_officer = $request->pickup_officer_id;
        $pickUp->save();
        //    foreach($orders as $order){
        //        $o=Order::where('order_id',$order)->id;
        //        $log=OrderLog::create([
        //            'user_id'=>$request->pickup_officer_id,
        //            'order_id'=>$o,
        //            'log'=>'Assigned Pickup'


        //        ]);
        //    }
        return response()->json(['success' => 'Saved', 200]);
    }
    public function update(Request $request, $id)
    {
        $pickUp = SendPickUp::findorfail($id);
        $pickUp->vendor_id = $request->vendor_id;
        //here stays  auth user
        $pickUp->user_id = 1;
        $pickUp->orders = json_encode($request->order_id);
        $pickUp->pickup_logistic_officer = $request->pickup_officer_id;
        $pickUp->update();
        //     foreach($orders as $order){
        //         $o=Order::where('order_id',$order)->id;
        // $log=OrderLog::where('order_id',$o)->where('log','Assigned Pickup')->first();
        //       $update=  $log->update([
        //             'user_id'=>$request->pickup_officer_id,
        //             'order_id'=>$o,
        //             'log'=>'Assigned Pickup'


        //         ]);
        //       }
        return response()->json(['success' => 'Updated', 200]);
    }
    public function sentPickUpList()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $pickups = SendPickUp::orderBy('id', 'DESC')->get();
        } else {
            $pickups = SendPickUp::orderBy('id', 'DESC')->where('user_id', auth()->id())->get();
        }
        // dd($pickups);

        foreach ($pickups as $pickup) {

            $pickup->order_id = json_decode($pickup->orders);
            $pickup->order_count = count($pickup->order_id);
            if ($pickup->vendor_id != null) {
                $pickup->vendor_name = User::findorfail($pickup->vendor_id) ? User::findorfail($pickup->vendor_id)->name : '';
            }
            $pickup->logistic_officer_name = User::findorfail($pickup->pickup_logistic_officer) ?  User::findorfail($pickup->pickup_logistic_officer)->name : "";
            $pickup->created_by = User::findorfail($pickup->user_id) ? User::findorfail($pickup->user_id)->name : '';
            $pickup->pickup_officer_id = $pickup->pickup_logistic_officer;
        }

        $pickups = $this->paginateHelper($pickups, 10);
        return response()->json($pickups);
    }
    public function received(Request $request)
    {
        if ($request->barcode) {
            $order = Order::where('bar_code', $request->barcode)->orWhere('order_id', $request->barcode)->first();
            if ($order) {
                $pickups = SendPickUp::all()->pluck('orders')->toArray();
                $pickupOrder = [];
                foreach ($pickups as $pickup) {
                    $pick = json_decode($pickup);
                    foreach ($pick as $p) {
                        $pickupOrder[] = $p;
                    }
                }
                if (!in_array($order->order_id, $pickupOrder)) {
                    return response()->json(['error' => 'Order Is Not Send For Pickup']);
                }

                if (ReceivePickup::where('order_id', json_encode($order->order_id))->first()) {
                    return response()->json(['error' => 'Already Pickup Received']);
                } else {
                    $received = ReceivePickup::create([
                        'order_id' => $order->order_id,
                        'user_id' => auth()->id(),
                    ]);
                    return response()->json(['success' => 'Package Received'], 200);
                }
            } else {
                return response()->json(['error' => 'Bar Code Doesnot Exist !!!']);
            }
        }
        return response()->json(['error' => 'Bar code is Blank !!!']);
    }
    public function getVendorList()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'vendor');
        })->get();
        return response()->json($users);
    }
    public function getPickupEmployees()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $users = User::where('active', 1)->whereHas('roles', function ($q) {
                $q->where('name', 'pickup_officer');
            })->get();
        } else {
            $users = User::where('active', 1)->where('parent_id', $user->id)->whereHas('roles', function ($q) {
                $q->where('name', 'pickup_officer');
            })->get();
        }
        return response()->json($users);
    }
    public function orders($id)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $orders = Order::where('order_status', 1, 0);
            $orders = $orders->where('sender_id', $id)->select('order_id', 'sender_id', 'order_created_as', 'receiver_id')->get();
        } else {

            // $send = SendPickUp::where('user_id', auth()->id())->get();
            // $aa = [];
            // foreach ($send as $s) {
            //     $o = json_decode($s->orders);
            //     foreach ($o as $n) {
            //         $aa[] = $n;
            //     }
            // }
            // $orders = [];
            // $a = Order::where('order_status', 1)->orWhere('order_status', 0)->where('sender_id', $id)->pluck('order_id')->toArray();
            // $p = array_diff($a, $aa);
        }
        $orders = [];
        $send = SendPickUp::where('user_id', auth()->id())->get();
        $aa = [];
        foreach ($send as $s) {
            $o = json_decode($s->orders);
            foreach ($o as $n) {
                $aa[] = $n;
            }
        }
        $z = Order::where('sender_id', $id)->where('pickup_hub', auth()->id())->get();

        $orders1 = collect($z->where('order_status', 1));
        $orders2 = collect($z->where('order_status', 0));
        $a = $orders1->merge($orders2)->unique()->pluck('order_id')->toArray();

        $p = array_diff($a, $aa);

        foreach ($p as $q) {
            $orders[] = Order::where('order_id', $q)->select('order_id', 'sender_id', 'order_created_as', 'receiver_id')->first();
        }
        //   $sent_orders=ReceivePickup::pluck('order_id')->toArray();
        // $orders=array_diff($orders1->pluck('order_id')->toArray(),$sent_orders);


        foreach ($orders as $order) {
            // $order->vendor_name = User::where('id', $order->sender_id)->first()->name;
            if ($order->order_created_as != "NEW") {
                if ($order->order_created_as == 'RETURN') {

                    $order->vendor_name = Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->ward_no)->first() ? Address::where('id', UserAddress::where('id', $order->receiver_id)->first()->ward_no)->first()->address : '';
                    $order->tag_created_as = substr($order->order_created_as, 0, 1);
                } else {

                    $order->vendor_name = User::where('id', $order->sender_id)->first()->name;

                    $order->tag_created_as = substr($order->order_created_as, 0, 1);
                }
                //    $order->tag_created_as = substr($order->order_created_as, 0, 1);
            }
        }
        return response()->json($orders);
    }
    public function receiveLists(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $lists = ReceivePickup::orderBy('id', 'DESC')->get();
        } else {
            $lists = ReceivePickup::orderBy('id', 'DESC')->where('user_id', auth()->id())->get();
        }
        if ($request->has('orderId') && $request['orderId']) {
            $lists = $lists->where('order_id', $request->orderId);
        }
        foreach ($lists as $list) {
            $list->hub_name = User::findorfail($list->user_id)->name;
        }
        $lists = $this->paginateHelper($lists, 10);
        return response()->json($lists);
    }
    public function delete($id)
    {
        $pickUp = SendPickUp::findOrfail($id);
        $pickUp->delete($id);
        return response()->json(['success' => 'Deleted']);
    }
    public function receive_delete($id)
    {
        $r = ReceivePickup::findorfail($id);
        $r->delete();
        return response()->json(['success' => 'Deleted']);
    }
}
