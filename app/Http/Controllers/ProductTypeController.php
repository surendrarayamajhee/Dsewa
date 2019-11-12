<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationHelper;
use App\Http\Requests\ProductTypeRequest;

use Illuminate\Http\Request;
use App\Product_type;
use Auth;

class ProductTypeController extends Controller
{
    //
    use PaginationHelper;
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function product_type_store(ProductTypeRequest $request)
    {
        $request->merge(['vendor_id' => Auth::user()->id]);
        Product_type::create($request->all());
        return response()->json(['success' => 'Product Type Added'], 200);
    }
    public function product_type_update(ProductTypeRequest $request, $id)
    {

        $product = Product_type::findOrfail($id);
        $product = $product->update($request->all());
        return response()->json(['success' => 'Product Type Updated'], 200);
    }
    public function product_type_get(Request $request)
    {

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $product = Product_type::orderBy('id', 'DESC')->get();
        } elseif ($user->hasRole('vendor')) {

            $product = Product_type::where('vendor_id', auth()->id())->get();
        }

        if ($request->has('search')) {
            $product = $product->where('name', 'like', "%{$request->search}%");
        }
        $product =  $this->paginateHelper($product, 10);
        return response()->json($product);
    }
    public function product_type_del(Request $request)
    {
        // $validatedData = $request->validate([
        //     'checkbox' => 'required',
        // ]);
        foreach ($request->checkbox as $check) {
            $product = Product_type::find($check);
            $product = $product->delete($check);
        }
        return response()->json(['success' => 'Product Type Deleted'], 200);
    }
    public function producttypebyvendor()
    {
        // dd('cdjvsd');
        $user = Auth::user();
        if ($user->hasRole('vendor')) {
            $product = Product_type::where('vendor_id', auth()->id())->get();

        } else {
            $product = Product_type::orderBy('id', 'DESC')->get();

        }

        // $product = Product_type::orderBy('id', 'DESC')->get();
        // $product = $product->where('vendor_id', auth()->id());
        return response()->json($product);
    }
}
