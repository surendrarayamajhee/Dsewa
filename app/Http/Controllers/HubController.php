<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HubCharge;
use App\User;


class HubController extends Controller
{
    public function getCharge($id)
    {
        $hub['ward_id'] = $id;

        $hub['fragile_charge'] = HubCharge::where('ward_id', $id)->first() ? HubCharge::where('ward_id', $id)->first()->fragile_charge : '';
        $hub['delivery_charge'] = HubCharge::where('ward_id', $id)->first() ? HubCharge::where('ward_id', $id)->first()->delivery_charge : '';

        return response()->json($hub);
    }
    public function create(Request $request)
    {
        $request->merge(['non_fragile_charge' => $request->fragile_charge]);
        $charge = HubCharge::updateOrCreate(['ward_id' => $request->ward_id], $request->except('_token'));


        return response()->json(['success' => 'created'], 200);
    }
}
