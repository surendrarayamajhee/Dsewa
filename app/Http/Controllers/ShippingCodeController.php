<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipping_Id_Code;
use App\Helpers\PaginationHelper;

class ShippingCodeController extends Controller
{
    //



    public function shippingcode_store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:6|max:16',
        ]);
        Shipping_Id_Code::create($validatedData);
        return response()->json(['success'=>'Added'],200);

    }
    public function shippingcode_update(Request $request,$id)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:6|max:16',
        ]);
        $Shipping = Shipping_Id_Code::find($id);
       $Shipping = $Shipping->update($validatedData);
        return response()->json(['success'=>'Updated'],200);

    }
    public function shippingcode_get(Request $request)
    {
        $Shipping = Shipping_Id_Code::orderBy('id', 'DESC');
        if($request->has('search'))
        {
            $Shipping = $Shipping->where('name','like','%'.$request->input('search').'%');
        }
        $Shipping= $Shipping->paginate(10);
        return response()->json($Shipping);
    }

    public function shippingcode_del(Request $request)
    {
        // $validatedData = $request->validate([
        //     'checkbox' => 'required',
        // ]);
       foreach($request->checkbox as $check)
       {
        $Shipping = Shipping_Id_Code::find($check);
        $Shipping= $Shipping->delete($check);
       }
       return response()->json(['success'=>'Deleted'],200);

    }
}
