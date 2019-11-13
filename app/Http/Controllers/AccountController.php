<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comission;
use App\Order;
use App\User;
use App\RequestPayment;
use Carbon\Carbon;
use App\UserAddress;
use Illuminate\Support\Facades\DB;
use App\Delivered;
use App\Helpers\PaginationHelper;
use App\OrderComment;

class AccountController extends Controller
{
    use PaginationHelper;

    public function getAll(Request $request)
    {


        // kdmvnds
        $account = Comission::orderBy('id', 'desc')->get();
        // dd($account);

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $accounts = $account;
            if ($request->has('order_id') && $request['order_id'] != null) {
                $accounts = $accounts->where('order_id', $request->order_id);
            }
        } elseif ($user->hasRole('vendor')) {
            //This code can be Better
            $order = Order::where('sender_id', $user->id)->orderBy('id', 'desc')->pluck('order_id')->toArray();
            if ($request->has('order_id') && $request['order_id'] != null) {
                $order = Order::where('hub_id', $user->id)->where('order_id', $request->order_id)->orderBy('id', 'desc')->pluck('order_id')->toArray();
            }
            $accounts = [];
            foreach ($order as $order) {
                if (Comission::where('order_id', $order)->first()) {
                    $accounts[] = Comission::where('order_id', $order)->first();
                }
            }
        } else {

            $order1 = collect(Order::orderBy('id', 'desc')->where('hub_id', $user->id)->pluck('order_id')->toArray());
            $order2 = collect(Order::orderBy('id', 'desc')->where('pickup_hub', $user->id)->pluck('order_id')->toArray());
            $order = $order1->merge($order2)->unique();


            if ($request->has('order_id') && $request['order_id'] != null) {

                $order1 = collect(Order::where('hub_id', $user->id)->where('order_id', $request->order_id)->orderBy('id', 'desc')->pluck('order_id')->toArray());
                $order2 = collect(Order::where('pickup_hub', $user->id)->where('order_id', $request->order_id)->orderBy('id', 'desc')->pluck('order_id')->toArray());
                $order = $order1->merge($order2)->unique();
                // $order = Order::where('order_id', $request->order_id)->where('hub_id', $user->id)->Orwhere('pickup_hub', $user->id)->orderBy('id', 'desc')->pluck('order_id')->toArray();
            }

            $accounts = [];
            foreach ($order as $order) {
                if (Comission::where('order_id', $order)->first()) {

                    $accounts[] = Comission::where('order_id', $order)->first();
                }
            }
        }

        // $order = Order::where('hub_id', $user->id)->pluck('order_id')->toArray();
        $delivered = Delivered::all('order_id', 'delivery_boy_id');
        // dd($order);
        // dd($accounts);
        foreach ($accounts as $account) {
            foreach ($delivered as $deliver) {
                if (in_array($account->order_id, json_decode($deliver->order_id))) {
                    $account->deliveryBoy = User::where('id', $deliver->delivery_boy_id)->first() ?  User::where('id', $deliver->delivery_boy_id)->first()->name : '';
                }
            }
            $comment =  OrderComment::where('order_id',$account->order_id)->orderBy('id','desc')->first();
            // dd($comment->comment);
            $account->comment =$comment ? $comment->comment:'-';
            if($comment)
            {
                $date = Carbon::parse($comment->created_at);
                $account->comment_date =  $date->isoFormat('MMMM Do');
            }
            $account->vendor = User::where('id', Order::where('order_id', $account->order_id)->first()->sender_id)->first() ?  User::where('id', Order::where('order_id', $account->order_id)->first()->sender_id)->first() : '';
            $account->pickup_hub_name = User::where('id', Order::where('order_id', $account->order_id)->first()->pickup_hub)->first() ? User::where('id', Order::where('order_id', $account->order_id)->first()->pickup_hub)->first()->name : '';
            $account->delivery_hub_name = User::where('id', Order::where('order_id', $account->order_id)->first()->hub_id)->first() ?  User::where('id', Order::where('order_id', $account->order_id)->first()->hub_id)->first()->name : '';
            $order = Order::where('order_id', $account->order_id)->first();
            $account->cod = $order->cod;
            $account->delivery_hub = (int) $account->delivery_hub;
            $account->pickup_hub = (int) $account->pickup_hub;
            $account->shipping_cost =  round((int) $account->shipping_cost, 1);

            $account->shipping_charge = round((int) $order->shipment_charge, 1);
            $account->vendor_amt = round($order->cod - $order->shipment_charge, 1);
            $account->dsewa =  round((int) $order->shipment_charge - $account->delivery_hub - $account->pickup_hub - $account->delivery_boy_comission - $account->shipping_cost, 1);
            $account->delivery_boy_paid = $account->is_delivery_paid == 0 ? 'U' : 'P';
            $account->pickup_hub_paid = $account->is_pickup_hub_paid == 0 ? 'U' : 'P';
            $account->dsewa_paid = $account->is_admin_paid == 0 ? 'U' : 'P';
            $account->delivery_hub_paid = $account->is_delivery_hub_paid == 0 ? 'U' : 'P';
            $account->vendor_paid = $account->is_vendor_paid == 0 ? 'U' : 'P';



            // $account->delivery_officer_name=User::where('id',Order::where('order_id',$account->order_id)->first()->sender_id)->first()->name;
        }
        $accounts = $this->paginateHelper($accounts, 15);


        return response()->json($accounts);
    }


    public function getData()
    {
        $user = Auth::User();

        if ($user->hasRole('vendor')) {
            $role = true;
            $r = 'v';
            $order = Order::where('sender_id', auth()->id());
            $totalOrder = $order->where('order_status', '!=', '8')->get()->count();
            $deliveredOrder = Order::where('order_status', 6)->where('order_created_as', '!=', 'RETURN')->where('sender_id', $user->id)->get()->count();
            $returnOrder = Order::where('order_created_as', 'RETURN')->where('order_status', 1)->where('sender_id', $user->id)->get()->count();

            $xx =Order::where('sender_id', $user->id)->where('order_created_as','!=','RETURN')->where('order_status', 1)->get()->count() + Order::where('sender_id', $user->id)->where('order_created_as','!=','RETURN')->where('order_status', 0)->get()->count();
            // dd();

            $pendingOrders = $xx;
            $cancel =  $order->where('order_status', 5)->get()->count();
            $return_delivered_order = Order::where('order_created_as', 'RETURN')->where('order_status', 6)->where('sender_id', $user->id)->get()->count();

            $exchange = Order::where('order_status', 5)->where('sender_id', $user->id)->get()->count();
            $refund = Order::where('order_status', 2)->where('sender_id', $user->id)->get()->count();

            $order_id = Order::where('sender_id', auth()->id())->where('order_status', 6)->pluck('order_id')->toArray();
            $lastCod = RequestPayment::where('receiver_id', $user->id)->orderby('id', 'DESC')->first() ?  RequestPayment::where('receiver_id', $user->id)->orderby('id', 'DESC')->first()->amount : '-';
            $lastCoddate = RequestPayment::where('receiver_id', $user->id)->orderby('id', 'DESC')->first();
            if ($lastCoddate) {
                $date = Carbon::parse(RequestPayment::where('receiver_id', $user->id)->orderby('id', 'DESC')->first()->created_at);
                $lastCoddate =  $date->isoFormat('YYYY MM DD');
            } else {
                $lastCoddate = '-';
            }

            $pendingAmt = 0;
            $totalAmt = 0;
            $receivedAmt = 0;

            foreach ($order_id as $o) {
                $order = Comission::where('order_id', $o)->first();
                $order1 = Order::where('order_id', $o)->first();

                $totalAmt = (int) $totalAmt + (int) $order['vendor_amt'];
                if ($order['is_vendor_paid'] == 0) {
                    $pendingAmt = $pendingAmt + ((int) $order['cod'] - (int) $order1->shipment_charge);
                } else {
                    $receivedAmt = $receivedAmt + ((int) $order['cod'] - (int) $order1->shipment_charge);
                }
            }
        } elseif ($user->hasRole('hub')) {
            $role = true;
            $r = 'h';
            $order = Order::where('hub_id', auth()->id());
            $totalOrder = $order->get()->count();
            $deliveredOrder = $order->where('order_status', 6)->get()->count();
            $returnOrder =  Order::where('pickup_hub', auth()->id())->get()->count();
            $pendingOrders = $order->where('order_status', 1)->orwhere('order_status', 7)->get()->count();
            $orders1 = collect(Order::where('hub_id', auth()->id())->where('order_status', 6)->pluck('order_id')->toArray());
            $orders2 = collect(Order::where('pickup_hub', auth()->id())->where('order_status', 6)->pluck('order_id')->toArray());
            $order_id = $orders1->merge($orders2)->unique();
            // dd($order_id);
            // $order_id = Order::where('hub_id', auth()->id())->orWhere('pickup_hub', auth()->id())->where('order_status', 6)->pluck('order_id')->toArray();
            //prajwol sir le garne request payment
            $lastCod = RequestPayment::where('receiver_id', $user->id)->orderBy('id', 'DESC')->first() ? RequestPayment::where('receiver_id', $user->id)->orderBy('id', 'DESC')->first()->amount : '-';
            $lastCoddate = RequestPayment::where('receiver_id', $user->id)->orderBy('id', 'DESC')->first() ? RequestPayment::where('receiver_id', $user->id)->orderBy('id', 'DESC')->first()->created_at : '';
            if ($lastCoddate) {
                $date = Carbon::parse(RequestPayment::where('receiver_id', $user->id)->orderby('id', 'DESC')->first()->created_at);
                $lastCoddate =  $date->isoFormat('YYYY MMMM dddd');
            } else {
                $lastCoddate = '-';
            }
            $pendingAmt = 0;
            $totalAmt = 0;
            $receivedAmt = 0;
            $exchange = 0;
            $refund =0;


            foreach ($order_id as $id) {

                // echo 'orderid'.$id.'<br>';
                $comission = Comission::where('order_id', $id)->first();
                $order1 = Order::where('order_id', $id)->first();
                // dd((int)$comission['cod'] - (int)$order1->shipment_charge);
                $totalAmt = (int) $totalAmt + ((int) $comission['cod'] - (int) $order1->shipment_charge);
                if ($comission['is_pickup_hub_paid'] == 0) {
                    $pendingAmt = $pendingAmt + ((float) $comission['pickup_hub']);
                } else {
                    $receivedAmt = $receivedAmt + ((float) $comission['pickup_hub']);
                }
                if ($comission['is_delivery_hub_paid'] == 0) {
                    $pendingAmt = $pendingAmt + ((float) $comission['delivery_hub']);
                } else {
                    $receivedAmt = $receivedAmt + ((float) $comission['delivery_hub']);
                }
                $cancel = 0;
                $return_delivered_order = 0;
                // echo $pendingAmt."<br>";
            }
        } else {
            $r = 'A';
            $role = false;
            $pendingAmt = 0;
            $totalAmt = 0;
            $receivedAmt = 0;
            $totalOrder = 0;
            $totalOrder = 0;
            $deliveredOrder = 0;
            $returnOrder = 0;
            $pendingOrders = 0;
            $lastCod = 0;
            $lastCoddate = 0;
            $r = 'a';
            $cancel = 0;
            $return_delivered_order = 0;
            $exchange = 0;
            $refund =0;
        }
        return response()->json([
            'role' => $role, 'r' => $r, 'totalOorder' => $totalOrder, 'deliveredOrder' => $deliveredOrder,
            'returnOrder' => $returnOrder, 'pendingOrders' => $pendingOrders, 'lastCod' => $lastCod, 'lastCoddate' => $lastCoddate, 'pendingAmt' => round($pendingAmt, 1),
            'receivedAmt' => $receivedAmt, 'totalAmt' => $totalAmt, 'cancel' => $cancel, 'return_delivered_order' => $return_delivered_order,'exchange' =>$exchange,'refund' =>$refund
        ]);
    }

    public function vendorgetpayment($id)
    {
        // $order = [];
        $paymentRequests = RequestPayment::where('id', $id)->get();
        // dd($paymentRequests);
        $vendor_id = '';
        foreach ($paymentRequests as $paymentRequest) {
            $orderIds = json_decode($paymentRequest->order_id);


            $orders = Order::whereIn('order_id', $orderIds)->get();
            foreach ($orders as $requestOrderid) {
                $requestOrderid->customer = UserAddress::where('id', $requestOrderid->receiver_id)->first();
            }
            $paymentRequest->orders = $orders;

            $vendor_id = $paymentRequest->receiver_id;

            $vendor_name = User::where('id', $vendor_id)->first()->name;
        }
        // dd($paymentRequests);
        // return response()->json($paymentRequests);

        return view('comission.vendorcom', compact('vendor_name', 'paymentRequests'));
    }

    public function hubgetpayment($id)
    {
        $comission = Comission::pluck('order_id');
        $order = Order::findOrfail($id);

        $vendor = User::where('id', $order->sender_id)->first();
        // dd($sender);
        $receiver = User::where('id', $order->receiver_id)->first();
        $user_pickuphub = User::where('id', $order->pickup_hub)->first();
        $user_deliverhub = User::where('id', $order->hub_id)->first();

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $pickup_hub = true;
            $deliver_hub = true;
        }
        if ($user->id == $order->pickup_hub) {
            $pickup_hub = true;
            if ($order->pickup_hub == $order->hub_id) {
                $deliver_hub = true;
            } else {
                $deliver_hub = false;
            }
        }
        if ($user->id == $order->hub_id) {
            if ($order->pickup_hub == $order->hub_id) {
                $pickup_hub = true;
            } else {
                $pickup_hub = false;
            }
            $deliver_hub = true;
        }

        $vendorno = 'HRN' . $id;
        return view('comission.hubcom', compact('comission', 'order', 'vendorno', 'user_pickuphub', 'deliver_hub', 'pickup_hub', 'user_deliverhub', 'vendor', 'reciver'));
    }
    public function commisiondrop($id)
    {
        // dd($id);
        $comission = Comission::where('order_id', $id)->first();
        // $order = Order::where('order_id', $id)->first();
        // $comission->delete($id);
        // $order->order_status =    $order->previous_order_status;
        // $order->update();
        return response()->json(['success' => 'Deleted']);
    }
    public function comissionUpdate(Request $request, $id)
    {
        $x = $request->shipping_charge - ($request->shipping_cost + $request->delivery_boy_comission);
        $y = $request->pickup_hub + $request->delivery_hub + $request->dsewa;
        if ($x != $y) {
            return response()->json(['error' => 'Calculation Mistake']);
        }
        $comission = Comission::findOrfail($id);
        // dd(comission);
        $comission->pickup_hub = $request->pickup_hub;
        $comission->delivery_hub = $request->delivery_hub;
        $comission->delivery_boy_comission = $request->delivery_boy_comission;
        $comission->dsewa = $request->dsewa;
        $comission->shipping_cost = $request->shipping_cost;
        $comission->update();
        $order = Order::where('order_id', $comission->order_id)->first();
        $order->shipment_charge = $request->shipping_charge;
        $order->update();

        return response()->json(['success' => 'Updated']);
    }
}
