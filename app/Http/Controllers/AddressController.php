<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use DB;
use App\Helpers\PaginationHelper;
use App\User;
use App\HubArea;

class AddressController extends Controller
{
    //
    public function getaddressbystate()
    {
        $address = Address::orderBy('address','ASC')->where('parent_id',null)->get();
        return response()->json($address);
    }




    public function getaddressbydistrict()
    {

        $address = Address::orderBy('address','ASC')->where('type', '=', 'DISTRICT')->get();
        return response()->json($address);
    }
    public function changeaddress2($id)
    {

        $address = Address::where('parent_id', $id)->orderBy('address','ASC')->get();
        foreach ($address as $add) {
            $hub = HubArea::where('address_id', $add->id)->first();
            if ($hub) {
                $add->hub_name = User::where('id', $hub->hub_id)->first()->name;
                              $add->name = false;
            } else {
                $add->name = true;
            }
        }
        return response()->json($address);
    }
    public function changeaddress($id)
    {









        $address = Address::where('parent_id', $id)->get();
        foreach ($address as $add) {
            $hub = HubArea::where('address_id', $add->id)->first();
            if ($hub) {
                $add->hub_name = User::where('id', $hub->hub_id)->first()->name;
                              $add->name = false;
            } else {
                $add->name = true;
            }
        }
        return response()->json($address);
    }
}
