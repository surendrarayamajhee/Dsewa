<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ShipmentType;
use App\UserAddress;
use App\PickUpOrder;
use App\Address;
use App\Http\Requests\StoreAddress;
use App\Helpers\PaginationHelper;
use App\User;

class Vendorcontroller extends Controller
{
    use PaginationHelper;
    public function index()
    {
        return view('Vendors.vendor');
    }




    public function getaddress()
    {
        $address = Address::all('id', 'address');
        return response()->json($address);
    }

    public function storeaddress(StoreAddress $request)
    {
        Address::create($request->validated());
        return response()->json(['success' => 'Address Added'], 200);
    }
    public function updateaddress(StoreAddress $request, $id)
    {
        $address = Address::find($id);
        $address = $address->update($request->validated());

        return response()->json(['success' => 'Address Updated'], 200);
    }

    public function addressbypaginate(Request $request)
    {
        $address = Address::orderBy('id', 'DESC');

        if ($request->has('state')) {
            $address = $address->where('parent_id', '=', $request->input('address'))->where('type', 'State_No');
        }

        if ($request->has('district')) {
            $address = $address->orWhere('address', 'like', '%' . $request->input('address') . '%')->where('type', 'District');
        }
        if ($request->has('municipality')) {

            $address = $address->where('address', 'like', '%' . $request->input('address') . '%')->where('type', 'Area');
            dd($address->get());
        }

        // if($request->has('municipality'))
        // {
        //     $address = $address->where('address','like','%'.$request->input('address').'%')->where('type','Area');

        // }


        if ($request->has('paginate')) {
            $p = $request->input('paginate');
        } else {
            $p = 4;
        }
        $address = $address->get();
        $address = $this->addrelation($address);

        $address = $this->paginateHelper($address, $p);
        return response()->json($address);
    }


    public function getaddressmodel($id)
    {
        $address = Address::all();
        foreach ($address as $addressKey => $addressValue) {
            $parent_id = address::where('id', $addressValue->parent_id)->first();
            $address[$addressKey]['parent_name'] = isset($parent_id->address) ? $parent_id->address : '-';
        }
        return $address;
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
