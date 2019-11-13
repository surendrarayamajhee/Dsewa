<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PicUpAddress;
use App\Helpers\PaginationHelper;

class PicupAddressController extends Controller
{
    //
    public function picupaddress_store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:6|max:16',
        ]);
        PicUpAddress::create($validatedData);
        return response()->json(['success'=>'Added'],200);

    }
    public function picupaddress_update(Request $request,$id)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:6|max:16',
        ]);
        $picupaddress = PicUpAddress::find($id);
       $picupaddress = $picupaddress->update($validatedData);
        return response()->json(['success'=>'Updated'],200);

    }
    public function picupaddress_get(Request $request)
    {
        $picupaddress = PicUpAddress::orderBy('id', 'DESC');
        if($request->has('search'))
        {
            $picupaddress = $picupaddress->where('name','like','%'.$request->input('search').'%');
        }

        $picupaddress= $picupaddress->paginate(10);
        return response()->json($picupaddress);
    }

    public function api_picupaddress(Request $request)
    {
        $picupaddress = PicUpAddress::all('id','name');

        return response()->json($picupaddress);
    }


    public function picupaddress_del(Request $request)
    {
        // $validatedData = $request->validate([
        //     'checkbox' => 'required',
        // ]);
       foreach($request->checkbox as $check)
       {
        $picupaddress = PicUpAddress::find($check);
        $picupaddress= $picupaddress->delete($check);
       }
       return response()->json(['success'=>'Deleted'],200);

    }
}
