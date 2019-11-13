<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Requests\VendorinfoRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\Vendor_Info;

class VendorInfoController extends Controller
{
    //
    public function getvendorinfo()
    {
        $vendorinfo = Vendor_Info::where('vendor_id', auth()->id())->first();
        return response()->json($vendorinfo);
    }
    public function getvendorname()
    {
        $vendor = User::find(auth()->id())->name;
        return response()->json(['name'=>$vendor]);
    }
    public function vendorinfo_store(Request $request)
    {
        // dd($request->all());
        $request->merge(['vendor_id' => auth()->id()]);
        Vendor_Info::updateOrCreate(['vendor_id' => auth()->id()],$request->all());
        return response()->json(['success' => 'Successful'], 200);
    }
}
