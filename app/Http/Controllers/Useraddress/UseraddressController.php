<?php

namespace App\Http\Controllers\Useraddress;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserAddress;
use App\Http\Requests\UserAddressRequest;
use App\Address;
use Auth;
use App\Order;
use App\Helpers\PaginationHelper;
use App\HubCharge;
use App\PickUpOrder;
use App\HubArea;

class UseraddressController extends Controller
{
    use PaginationHelper;
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function useraddressStore(UserAddressRequest $request)
    {

        $address = UserAddress::create($request->all());
        $state = Address::find($request->district);
        $address->state_no = $state->parent_id;
        $address->vendor_id = Auth::user()->id;
        // $address->district = $request->district;
        $address->update();
        $address = UserAddress::where('id', $address->id)->select('id', 'phone1', 'first_name', 'last_name')->first();
        return response()->json(['success' => 'User is Added', 'id' => $address], 200);
    }
    public function useraddressupdate(UserAddressRequest $request, $id)
    {
        $address = UserAddress::findOrfail($id);
        $user = Auth::user();
        if ($user->hasRole(['admin', 'hub'])) {
            $address->update($request->all());
            // dd($request->all());
            if ($request->district) {
                $state = Address::find($request->district);
                $address->state_no = $state->parent_id;
            }
            $address->update();

            $pickups = PickUpOrder::where('useraddress_id', $address->id)->get();
            foreach ($pickups as $pickup) {
                if ($pickup->is_ward_status) {
                    $pickup->is_ward_status = 0;
                    $pickup->update();
                }
            }

            return response()->json(['success' => 'User is Updated', 'add' => $address], 200);
        }
        $orders = Order::where('receiver_id', $address->id)->where('order_status', 1)->get();
        // dd($orders);
        if (count($orders) > 0) {
            return response()->json(['error' => 'Error! Order is in Pending ']);
        } else {
            $address->update($request->all());
            if ($request->district) {
                $state = Address::find($request->district);
                $address->state_no = $state->parent_id;
            }
            $address->update();

            $pickups = PickUpOrder::where('useraddress_id', $address->id)->get();
            foreach ($pickups as $pickup) {
                if ($address->ward_no) {
                    $pickup->is_ward_status = 0;
                    $pickup->update();
                } else {
                    $pickup->is_ward_status = 1;
                    $pickup->update();
                }
            }

            return response()->json(['success' => 'User is Updated', 'add' => $address], 200);
        }
    }

    public function useraddress_update(UserAddressRequest $request, $id, $orderid)
    {
        $address = UserAddress::findOrfail($id);
        $address->update($request->all());

        if ($request->district) {
            $state = Address::find($request->district);
            $address->state_no = $state->parent_id;
        }
        if ($request->ward_no) {
            $address->ward_no = $request->ward_no;
        }
        $address->update();
        $order = Order::where('id', $orderid)->first();
        $shipment_charge = 0;
        if ($request->ward_no) {
            $hubcharge = HubCharge::where('ward_id',  $address->ward_no)->first();

            $hubarea = HubArea::where('address_id', UserAddress::where('id', $address->id)->first()->ward_no)->first();
            if ($hubarea) {
                if ($order->order_status == 1 || $order->order_status == 0) {
                    $order->hub_id = $hubarea->hub_id;
                    $order->order_status = 1;
                }
            } else {
                if ($order->order_status == 1 || $order->order_status == 0) {

                    $order->order_status = 0;
                }
            }
            if ($hubcharge) {

                if ($order->handling == 'FRAGILE') {
                    $shipment_charge = $hubcharge->fragile_charge;
                } else {
                    $shipment_charge = $hubcharge->non_fragile_charge;
                }
            }
        } else {
            if ($order->order_status == 1 || $order->order_status == 0) {

                $order->order_status = 0;
            }

            $shipment_charge = 0;
        }
        if ($order->order_status == 1 || $order->order_status == 0) {

            $order->shipment_charge = $shipment_charge * $order->weight;
        }
        $order->update();
        $pickups = PickUpOrder::where('useraddress_id', $address->vendor_id)->get();
        foreach ($pickups as $pickup) {
            if ($pickup->is_ward_status) {
                $pickup->is_ward_status = 0;
                $pickup->update();
            }
        }

        return response()->json(['success' => 'User is Updated'], 200);
    }
    public function getuseraddress(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {


            $address = UserAddress::orderBy('id', 'DESC');
        } elseif ($user->hasRole('vendor')) {

            $address = UserAddress::orderBy('id', 'DESC')->where('vendor_id', auth()->id())->where('is_active', 1);
        }


        if ($request->has('district')) {
            $address = $address->where('district', $request->district);
        }
        if ($request->has('municipality')) {
            $address = $address->where('municipality', $request->municipality);
        }
        if ($request->has('ward')) {
            $address = $address->where('ward_no', $request->ward);
        }
        if ($request->has('area')) {
            $address = $address->where('area', $request->area);
        }
        if ($request->has('phone')) {
            $address = $address->where('phone1', $request->phone)->orWhere('phone2', $request->phone);
        }
        $address = $address->get();

        $address->transform(function ($item, $key) {
            $item->state_name = Address::where('id', $item->state_no)->first() ? Address::where('id', $item->state_no)->first()->address : '-';
            $item->district_name = Address::where('id', $item->district)->first() ?  Address::where('id', $item->district)->first()->address : '-';
            $item->municipality_name = Address::where('id', $item->municipality)->first() ?  Address::where('id', $item->municipality)->first()->address : '-';
            $item->ward_name = Address::where('id', $item->ward_no)->first() ? Address::where('id', $item->ward_no)->first()->address : "-";
            $item->area_name = Address::where('id', $item->area)->first() ? Address::where('id', $item->area)->first()->address : "-";

            return $item;
        });
        if ($request->has('paginate')) {
            $p = $request->input('paginate');
        } else {
            $p = 10;
        }
        $address = $this->paginateHelper($address, $p);
        return response()->json($address);
    }

    public function getusersphone()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $address = UserAddress::all('id', 'phone1', 'first_name', 'last_name');
        } elseif ($user->hasRole('vendor')) {
            $address = UserAddress::where('vendor_id', auth()->id())->select('id', 'phone1', 'first_name', 'last_name')->get();
        }
        elseif ($user->hasRole('hub')) {
            $address = UserAddress::where('vendor_id', auth()->id())->select('id', 'phone1', 'first_name', 'last_name')->get();
        }

        return response()->json($address);
    }
    public function useraddress(Request $request)
    {
        if ($request->has('id')) {
            $users = UserAddress::find($request->id);
            return response()->json($users);
        }
    }
    public function getuseraddress_name_id()
    {
        $users = UserAddress::all('id', 'first_name', 'last_name');
        return response()->json($users);
    }

    public function delete_userAddress($id)
    {
        $users = UserAddress::findOrfail($id);

        if (Auth::user()->hasRole('admin')) {
            $order = Order::where('receiver_id', $id)->first();

            if ($order) {
                return response()->json(['error' => 'Cannot Delete Customer']);
            } else {
                $users->delete();
                $users->update();
            }
        } else {
            $users->is_active = 0;
            $users->update();
        }
        return response()->json(['success' => 'User Deleted']);
    }
}
