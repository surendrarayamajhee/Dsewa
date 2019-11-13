<?php

namespace App\Http\Controllers\mobile;

use App\Address;
use App\BusinessInfo;
use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\UserAddress;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CustomerResource;
use App\OrderComment;
use App\User;
use App\UserInfo;
use Illuminate\Support\Carbon;

class CustomerController extends Controller
{
    //
    public function getcustomerOrder()
    {
        $phone = Auth::user()->phone;
        $phone2 = Auth::user()->phone2;
        $user2 = [];
        $user1 = UserAddress::where('phone1', 'like', '%' . $phone . '%')->pluck('id')->toArray();
        if ($phone2) {
            $user2 = UserAddress::where('phone1', 'like', '%' . $phone2 . '%')->pluck('id')->toArray();
        }
        $user = array_unique(array_merge($user1, $user2));
        $orders = [];
        foreach ($user as $u) {
            $orders[] = Order::where('receiver_id', $u)->select('order_id', 'cod', 'product_type', 'sender_id', 'tracking_id','pickup_hub','hub_id','created_at')->first();
        }
        $orders = array_filter($orders);
        // dd($orders);
        foreach ($orders as $order) {
            // dd( $order->sender_id);
            $order->vendor = User::where('id', $order->sender_id)->first() ? User::where('id', $order->sender_id)->first()->name : '';
            $order->vender_pic = UserInfo::where('user_id', $order->sender_id)->first() ?  UserInfo::where('user_id', $order->sender_id)->first()->image : '';
            $order->vendor_contact =$this->contactno($order->sender_id);
            $order->pickup_hub =$this->contactno($order->pickup_hub);
            $order->delivery_hub =$this->contactno($order->hub_id);

            $order->product_type = json_decode($order->product_type);
        }

        // test


        return new CustomerResource($orders);
    }
    public function contactno($id)
    {
        // dd($id);
        $contact =  array(
            'phone1' =>  BusinessInfo::where('user_id',$id)->first() ? BusinessInfo::where('user_id',$id)->first()->phone:'',
            'phone2' =>  BusinessInfo::where('user_id',$id)->first() ? BusinessInfo::where('user_id',$id)->first()->mobile:'',

        );
        return $contact;
    }
    public function getcustomerinfo()
    {
        $user = Auth::user();

        return new CustomerResource($user);
    }
    public function updatecustomerinfo(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'email' =>  'required',
            'phone' =>'required',
            'phone2' =>'',
            'name' =>'required'
        ]);

        $user = Auth::user();
        if ($request->has('image')) {
            $image = $request->file('image');
            $p = public_path('customer/doc/');
            $filename = time() . "." . $image->getClientOriginalExtension();
            $image->move($p, $filename);
            $url = '/customer/doc/';
            $URLImage = $url . $filename;
            $user->image = $URLImage;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->phone2 = $request->phone2;
        $user->update();
        return new CustomerResource($user);
    }
    public function getcomments()
    {
        $Comment = Comment::all('id', 'name');
        return new CustomerResource($Comment);
    }
    public function get_Order_comment($id)
    {
        // dd($id);
        $Comment = OrderComment::where('order_id', $id)->get();
        $Comment->transform(function ($item, $key) {
            $item->user_name = User::where('id', $item->user_id)->first() ? User::where('id', $item->user_id)->first()->name:'';
            $date = Carbon::parse($item->updated_at);
            $item->expecteddate =  $date->isoFormat('YYYY-MM-DD hh:mm:ss');
            $item->date = $item->created_at->diffForHumans();
            return $item;
        });
        return new CustomerResource($Comment);

    }
    public function Order_comment_store(Request $request, $id)
    {
        $request->merge(['user_id' => auth()->id(), 'order_id' => $id]);
        // dd($request->all());
        OrderComment::create($request->all());
        return response()->json(['success' => 'Comment Added']);
    }

}
