<?php

namespace App\Http\Controllers;

use App\Address;
use App\HubArea;
use App\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\HubCharge;

class HubAddressController extends Controller
{
    //
    public function getaddressbystate()
    {
        $address = Address::orderBy('address', 'ASC')->where('parent_id', null)->get();
        return response()->json($address);
    }




    public function getaddressbydistrict()
    {

        $address = Address::orderBy('address', 'ASC')->where('type', '=', 'DISTRICT')->get();
        return response()->json($address);
    }
    public function changeaddress($id)
    {
        $address = Address::where('parent_id', $id)->orderBy('address', 'ASC')->get();
        foreach ($address as $add) {
            $hub = HubArea::where('address_id', $add->id)->first();
            if ($hub) {

                $add->hub_name = User::where('id', $hub->hub_id)->first()->name;
            } else {
                $add->hub_name = null;
            }
        }
        return response()->json($address);
    }

    public function getWards($id)
    {
        if (Auth::User()->hasRole('admin')) {
            $user = User::where('id', $id)->first();
        } else {
            $user = User::where('id', auth()->id())->first();
        }
        $addresses = $user->hubWards;
        // $charge = [];
        foreach ($addresses as $address) {
            $add = Address::where('id', $address->address_id)->first();
            $address->parent_name = Address::where('id', $add->parent_id)->first()->address;

            $address->address_name = $add->address;
            $address->charge= HubCharge::where('ward_id', $address->address_id)->first() ? HubCharge::where('ward_id', $address->address_id)->first() : '';

            // $add=$this->addRelation($add);
        }
        return response()->json($addresses);
    }
    public function getWards2()
    {
        $user = User::where('id', auth()->id())->first();
        $addresses = $user->hubWards;
        $min_deposit = 0;
        $max_deposit = 0;
        foreach ($addresses as $address) {
            $add = Address::where('id', $address->address_id)->first();
            $p = Address::where('id', $add->parent_id)->first();
            $address->parent_name = $p->address;
            $address->address_name = $add->address;
            $d = Address::where('id', $p->parent_id)->first();
            $address->district_name = $d->address;
            // $add=$this->addRelation($add);
            $min_deposit = $min_deposit + 5000;
            $max_deposit = $max_deposit + 10000;
        }
        return response()->json(['addresses' => $addresses, 'min_deposit' => $min_deposit, 'max_deposit' => $max_deposit]);
    }

    public function hubWards(Request $request)
    {
        $wards = $request->ward;
        $user_id = $request->user_id;

        $user = User::where('id', $user_id)->first();

        foreach ($wards as $ward) {
            if (!$user->hubWards->contains('address_id', $ward)) {
                HubArea::create([
                    'hub_id' => $user_id,
                    'address_id' => $ward,

                ]);
            }
        }



        return response()->json(['success' => 'saved'], 200);
    }
    public function hubWards2(Request $request)
    {
        $wards = $request->ward;
        $user_id = auth()->id();

        $user = User::where('id', $user_id)->first();

        foreach ($wards as $ward) {
            if (!$user->hubWards->contains('address_id', $ward)) {
                HubArea::create([
                    'hub_id' => $user_id,
                    'address_id' => $ward,

                ]);
            }
        }



        return response()->json(['success' => 'saved'], 200);
    }
    public function hubWardDelete(Request $request, $id)
    {
        $hubaddress = HubArea::findorfail($id);
        $hubaddress->delete();
        return response()->json(['success' => 'deleted'], 200);
    }




    public function addRelation($address)
    {

        $address->map(function ($item, $key) {

            $sub = $this->selectChild($item->parent_id);

            return $item = array_add($item, 'parent', $sub);
        });


        return $address;
    }
    public function selectChild($id)
    {

        $address = Address::where('id', $id)->get(); //rooney

        $address = $this->addRelation($address);



        return $address;
    }
}
