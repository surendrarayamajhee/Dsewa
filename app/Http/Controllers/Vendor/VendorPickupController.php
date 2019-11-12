<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorpickupRequest;
use App\VendorPickup;
use App\Address;
use Auth;

class VendorPickupController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function vendorpickupaddress_store(VendorpickupRequest $request)
    {

        $request->merge(['vendor_id' => auth()->id()]);
        VendorPickup::create($request->all());
        return response()->json(['success' => 'Pick-up Address Created'], 200);
    }
    public function vendorpickupaddress_update(VendorpickupRequest $request, $id)
    {

        $vaddress = VendorPickup::findOrfail($id);
        $request->merge(['vendor_id' => auth()->id()]);
        $vaddress->update($request->all());
        return response()->json(['success' => 'Pick-up Address Updated'], 200);
    }
    public function getvendoraddress()
    {
        $vpickupaddress = VendorPickup::where('vendor_id', auth()->id())->get();

        $vpickupaddress->transform(function ($item, $key) {
            $item->state = Address::where('id', $item->state_id)->first()->address;
            $item->district = Address::where('id', $item->district_id)->first()->address;
            $item->municipality = Address::where('id', $item->municipality_id)->first()->address;
            $item->ward = Address::where('id', $item->ward_id)->first() ?  Address::where('id', $item->ward_id)->first()->address : '';
            $item->area = Address::where('id', $item->area_id)->first() ?  Address::where('id', $item->area_id)->first()->address : '';
            $item->is_default = $item->is_default ? true : false;

            return $item;
        });
        return response()->json($vpickupaddress);
    }
    public function get_default_vendoraddress()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $vpickupaddress = VendorPickup::all();
        } elseif ($user->hasRole('vendor')) {

            $vpickupaddress = VendorPickup::where('vendor_id', auth()->id())->get();
        } else {
            $vpickupaddress = VendorPickup::all();
        }

        $vpickupaddress->transform(function ($item, $key) {
            $item->state = Address::where('id', $item->state_id)->first()->address;
            $item->district = Address::where('id', $item->district_id)->first()->address;
            $item->municipality = Address::where('id', $item->municipality_id)->first()->address;
            $item->ward = Address::where('id', $item->ward_id)->first() ? Address::where('id', $item->ward_id)->first()->address: '';
            $item->area = Address::where('id', $item->area_id)->first() ?  Address::where('id', $item->area_id)->first()->address:'';
            $item->is_default = $item->is_default == 1 ? true:false;
            $item->ward = Address::where('id', $item->ward_id)->first() ? Address::where('id', $item->ward_id)->first()->address : '';
            $item->area = Address::where('id', $item->area_id)->first() ?  Address::where('id', $item->area_id)->first()->address : '';
            $item->is_default = $item->is_default ? true : false;


            return $item;
        });
        return response()->json($vpickupaddress);
    }
    public function update_default_location(Request $request, $id)
    {
        // dd($id);
        $pickup_point = VendorPickup::where('vendor_id', auth()->id())->get();
        foreach ($pickup_point as $p) {
            $p->is_default = 0;
            $p->update();
        }
        $vaddress = VendorPickup::findOrfail($id);
        $vaddress->is_default = 1;
        $vaddress->update();
        return response()->json(['success' => 'Default Location  Added'], 200);
    }

    public function del_vendoraddress($id)
    {
        $address = VendorPickup::findOrfail($id);
        $address->delete($id);
        return response()->json(['success' => 'Address Deleted'], 200);
    }
}

