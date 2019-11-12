<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\hubAreaTemporary;
use App\Address;
use App\User;
use App\HubArea;

class TemporaryAddressController extends Controller
{
    //
    public function get_temp_address($id)
    {
        $temps = hubAreaTemporary::where('hub_id',$id)->get();
        foreach ($temps as $temp) {
            $temp->hub_name = User::where('id', $temp->hub_id)->first()->name;
            $temp->address_name = Address::where('id', $temp->address_id)->first()->address;
            $hubarea = HubArea::where('hub_id', $temp->hub_id)->where('address_id', $temp->address_id)->get();
            if( count($hubarea) > 0)
            {
                $temp->approved = 'approved';
            }
        }
        return response()->json($temps);
    }
    public function store_temp_add_to_hub_area($id)
    {
        $temps = hubAreaTemporary::findOrfail($id);
        $hubarea = HubArea::where('hub_id', $temps->hub_id)->where('address_id', $temps->address_id)->get();
        if (count($hubarea) > 0) {
            return response()->json(['error' => 'Already Added']);
        } else {
            $hub = HubArea::create([
                'hub_id' => $temps->hub_id,
                'address_id' => $temps->address_id
            ]);
            return response()->json(['success' => 'Added Successfully'],200);
        }
    }
}
