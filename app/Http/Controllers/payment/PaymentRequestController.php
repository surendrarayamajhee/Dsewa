<?php

namespace App\Http\Controllers\payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\adminPaymentRequest;
use App\RequestPayment;
use App\Order;
use App\Helpers\PaginationHelper;
use Auth;
use App\HubDeliverySent;
use App\Delivered;
use App\Comission;
use Carbon\Carbon;

class PaymentRequestController extends Controller
{
    //
    use PaginationHelper;

    public function image(Request $request)
    {
        $image = $request->file('image');
        $p = public_path('admin/doc/');
        $filename = time() . "." . $image->getClientOriginalExtension();
        $image->move($p, $filename);
        $url = '/admin/doc/';
        return response()->json($url . $filename);
    }
    public function getselectedusers($user)
    {
        if ($user == 'pickup_hub' or $user == 'delivery_hub') {
            $user = 'hub';
        }
        $auth = Auth::user();
        if ($auth->hasRole('admin')) {
            $users = User::where('active', 1)->whereHas('roles', function ($q)  use ($user) {
                $q->where('name', $user);
            })->get();
        } else {
            if ($user == 'admin') {
                $users = User::where('active', 1)->whereHas('roles', function ($q)  use ($user) {
                    $q->where('name', $user);
                })->get();
            } else {
                $users = User::where('active', 1)->where('parent_id', $auth->id)->whereHas('roles', function ($q)  use ($user) {
                    $q->where('name', $user);
                })->get();
            }
        }

        return response()->json($users);
    }
    public function paymany_store(adminPaymentRequest $request)
    {

        $payment = RequestPayment::create([
            'sender_id' => auth()->id(),
            'order_id' => json_encode($request->order_id),
            'amount' => $request->amount,
            'bank_account' => $request->bank_account,
            'receiver_id' => $request->receiver_id,
            'payment_type' => $request->payment_type,
            'bank_branch' => $request->bank_branch,
            'bank_name' => $request->bank_name,
            'date' => $request->date,
            'amount' => $request->amount,
            'image' => $request->image,
            'selected_name' => $request->selected_name,
            'outstanding_payment' => $request->outstanding_payment,
        ]);
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            if ($payment->selected_name == 'admin') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_admin_paid = 1;
                    $comission->update();
                }
            } elseif ($payment->selected_name == 'vendor') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_vendor_paid = 1;
                    $comission->update();
                }
            } elseif ($payment->selected_name == 'pickup_hub') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_pickup_hub_paid = 1;
                    $comission->update();
                }
            } elseif ($payment->selected_name == 'delivery_hub') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_delivery_hub_paid = 1;
                    $comission->update();
                }
            } elseif ($payment->selected_name == 'delivery_officer') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_delivery_paid = 1;
                    $comission->update();
                }
            }

            $payment->is_approved = 1;
            $payment->update();
            $order_id = json_decode($payment->order_id);

            // foreach ($order_id as $orderJson) {
            //     $this->DeliveryStore($orderJson);
            // }
        }
        return response()->json(['success' => 'Sucess'], 200);
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

    public function paymentlist(Request $request)
    {

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $lists = RequestPayment::orderBy('id', 'DESC')->get();
        } elseif ($user->hasRole('hub')) {
            $lists = RequestPayment::orderBy('id', 'DESC')->where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->get();
        } else {
            $lists = RequestPayment::orderBy('id', 'DESC')->where('receiver_id', $user->id)->get();
        }
        if ($request->selected_name != null) {
            $lists =   $lists->where('selected_name',$request->selected_name);
        }
        if ($request->user_id != null) {
            $lists = $lists->filter(function ($list) use ($request) {
                if ($list->sender_id == $request->user_id || $list->receiver_id == $request->user_id) {
                    return $list;
                }
            });
        }

        if ($request->has('paginate')) {
            $p = $request->input('paginate');
        } else {
            $p = 10;
        }
        foreach ($lists as $list) {
            $list->receiver_name = User::where('id', $list->receiver_id)->first()->name;
            $list->sender_name = User::where('id', $list->sender_id)->first()->name;
            $date = Carbon::parse($list->created_at);
            $list->created =   $date->isoFormat('YYYY MMMM Do ');
            $list->order_id = json_decode($list->order_id);
        }


        $lists = $this->paginateHelper($lists, $p);

        return response()->json($lists);
    }
    public function orderlist(Request $request, $userid, $selectedname)
    {
        // $user = User::findOrfail($userid);
        $orderlist = [];
        $orders_id = [];
        if ($selectedname == 'admin') {
            $paymentlist = RequestPayment::where('sender_id', auth()->id())->where('receiver_id', $userid)->pluck('order_id')->toArray();
            foreach ($paymentlist as $list) {

                $lists = json_decode($list);
                foreach ($lists as $list) {
                    $orderlist[] = $list;
                }
            }
            $orders = Order::where('order_status', 6)->where('hub_id', auth()->id())->pluck('order_id')->toArray();
            $orders_id = array_diff($orders, $orderlist);
        } elseif ($selectedname == 'vendor') {

            $paymentlist = RequestPayment::where('receiver_id', $userid)->where('sender_id', auth()->id())->where('receiver_id', $userid)->get();
            foreach ($paymentlist as $list) {

                $lists = json_decode($list->order_id);
                foreach ($lists as $list) {
                    $orderlist[] = $list;
                }
            }
            $orders = Order::where('order_status', 6)->where('sender_id', $userid)->pluck('order_id')->toArray();
            $orders_id = array_diff($orders, $orderlist);
        } elseif ($selectedname == 'pickup_hub') {
            $paymentlist = RequestPayment::where('receiver_id', $userid)->where('sender_id', auth()->id())->where('receiver_id', $userid)->get();
            foreach ($paymentlist as $list) {
                $lists = json_decode($list->order_id);
                foreach ($lists as $list) {
                    $orderlist[] = $list;
                }
            }
            $orders = Order::where('order_status', 6)->where('pickup_hub', $userid)->pluck('order_id')->toArray();
            $orders_id = array_diff($orders, $orderlist);
        } elseif ($selectedname == 'delivery_hub') {
            $paymentlist = RequestPayment::where('receiver_id', $userid)->where('sender_id', auth()->id())->where('receiver_id', $userid)->get();
            foreach ($paymentlist as $list) {
                $lists = json_decode($list->order_id);
                foreach ($lists as $list) {
                    $orderlist[] = $list;
                }
            }
            $orders = Order::where('order_status', 6)->where('hub_id', $userid)->pluck('order_id')->toArray();
            $orders_id = array_diff($orders, $orderlist);
        } elseif ($selectedname == 'delivery_officer') {
            $paymentlist = RequestPayment::where('receiver_id', $userid)->where('sender_id', auth()->id())->where('receiver_id', $userid)->get();
            foreach ($paymentlist as $list) {
                $lists = json_decode($list->order_id);
                foreach ($lists as $list) {
                    $orderlist[] = $list;
                }
            }
            $orders = [];
            $deliverysents = Delivered::where('delivery_boy_id', $userid)->get();
            foreach ($deliverysents as $d) {
                $ids = json_decode($d->order_id);
                foreach ($ids as $id) {
                    $orders[] = $id;
                }
            }
            $orders_id = array_diff($orders, $orderlist);
        }


        return response()->json($orders_id);
    }
    public function isapproved($id)
    {
        $payment = RequestPayment::findOrfail($id);
        if ($payment->is_approved == 0) {
            $payment->is_approved = 1;
            $payment->update();

            if ($payment->selected_name == 'admin') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_admin_paid = 1;
                    $comission->update();
                }
            } else if ($payment->selected_name == 'vendor') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_vendor_paid = 1;
                    $comission->update();
                }
            } else if ($payment->selected_name == 'pickup_hub') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_pickup_hub_paid = 1;
                    $comission->update();
                }
            } else if ($payment->selected_name == 'delivery_hub') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_delivery_hub_paid = 1;
                    $comission->update();
                }
            } else if ($payment->selected_name == 'delivery_officer') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_delivery_paid = 1;
                    $comission->update();
                }
            }
            $payment->receiver_name = User::where('id', $payment->receiver_id)->first()->name;
            $payment->sender_name = User::where('id', $payment->sender_id)->first()->name;
            $payment->order_id = json_decode($payment->order_id);

            return response()->json(['success' => 'Order Payment Approve', 'payment' => $payment], 200);
        } else {
            $payment->is_approved = 0;
            $payment->update();
            if ($payment->selected_name == 'admin') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_admin_paid = 0;
                    $comission->update();
                }
            } else if ($payment->selected_name == 'vendor') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_vendor_paid = 0;
                    $comission->update();
                }
            } else if ($payment->selected_name == 'pickup_hub') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_pickup_hub_paid = 0;
                    $comission->update();
                }
            } else if ($payment->selected_name == 'delivery_hub') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_delivery_hub_paid = 0;
                    $comission->update();
                }
            } else if ($payment->selected_name == 'delivery_officer') {
                $order_id = json_decode($payment->order_id);
                foreach ($order_id as $order) {
                    $comission = Comission::where('order_id', $order)->first();
                    $comission->is_delivery_paid = 0;
                    $comission->update();
                }
            }
            $payment->receiver_name = User::where('id', $payment->receiver_id)->first()->name;
            $payment->sender_name = User::where('id', $payment->sender_id)->first()->name;
            $payment->order_id = json_decode($payment->order_id);
            return response()->json(['success' => 'Order Payment canceled', 'payment' => $payment], 200);
        }
    }
    public function isadmin()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $is_admin = true;
            $is_hub = false;
            $is_vendor = false;
        } elseif ($user->hasRole('hub')) {
            $is_admin = false;
            $is_hub = true;
            $is_vendor = false;
        } else {
            $is_admin = false;
            $is_hub = false;
            $is_vendor = true;
        }
        return response()->json(['is_admin' => $is_admin, 'is_hub' => $is_hub, 'is_vendor' => $is_vendor]);
    }
    public function getdeilveryOfficer()
    {
        $user = Auth::user();
        if ($user->hasRole('hub')) {
            $users = User::where('active', 1)->where('parent_id', $user->id)->whereHas('roles', function ($q) {
                $q->where('name', 'delivery_officer');
            })->get();
            $is_hub = 1;
        } else {
            $users = [];
            $is_hub = 0;
        }
        return response()->json(['users' => $users, 'is_hub' => $is_hub]);
    }
    public function getoutstandingpay(Request $request)
    {
        // dd($request->all());
        $order_id = $request->order_id;
        // dd($request->order_id);
        $user_id = $request->receiver_id;
        $user_name = $request->selected_name;
        $pendingAmt = 0;
        if ($user_name == 'vendor') {
            foreach ($order_id as $order) {
                $comission = Comission::where('order_id', $order)->first();
                // dd($order);
                $order1 = Order::where('order_id', $order)->where('order_status', 6)->first();
                // dd($order1);

                if ($comission->is_vendor_paid == 0) {
                    $pendingAmt = $pendingAmt + ($comission->cod - $order1->shipment_charge);
                }
            }
        } else if ($user_name == 'pickup_hub') {
            foreach ($order_id as $order) {
                $comission = Comission::where('order_id', $order)->first();
                $order1 = Order::where('order_id', $order)->where('order_status', 6)->first();

                if ($comission->is_pickup_hub_paid == 0) {
                    $pendingAmt = $pendingAmt + $comission->pickup_hub;
                }
            }
        } else if ($user_name == 'delivery_hub') {
            foreach ($order_id as $order) {
                $comission = Comission::where('order_id', $order)->first();
                $order1 = Order::where('order_id', $order)->where('order_status', 6)->first();

                if ($comission->is_delivery_hub_paid == 0) {
                    $pendingAmt = $pendingAmt + $comission->delivery_hub;
                }
            }
        } else if ($user_name == 'admin') {
            foreach ($order_id as $order) {
                $comission = Comission::where('order_id', $order)->first();
                $order1 = Order::where('order_id', $order)->where('order_status', 6)->first();

                if ($comission->is_admin_paid == 0) {
                    $pendingAmt = $pendingAmt  + $comission->cod;
                }
            }
        } else if ($user_name == 'delivery_officer') {
            foreach ($order_id as $order) {
                $comission = Comission::where('order_id', $order)->first();
                $order1 = Order::where('order_id', $order)->where('order_status', 6)->first();
                if ($comission->is_delivery_paid == 0) {
                    $pendingAmt = $pendingAmt + $comission->delivery_boy_comission;
                }
            }
        }
        return response()->json($pendingAmt);
    }

    public function payment_del($id)
    {
        // dd($id);
        $payment = RequestPayment::findOrfail($id);
        // dd($payment);
        $payment->delete($id);
        return response()->json(['success' => 'Payment Deleted'], 200);
    }
}
