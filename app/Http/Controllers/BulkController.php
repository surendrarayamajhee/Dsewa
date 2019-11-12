<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\BulkOrder;
use App\HubArea;
use App\BusinessInfo;
use App\Helpers\PaginationHelper;
// use Importer;
use App\UserAddress;
use App\PickUpOrder;
use App\Address;
use App\BulkStore;
use Carbon\Carbon;
use App\HubCharge;
use App\Vendor_Info;
use App\Order;
use App\Helpers\TrackingId;
use App\Helpers\Barcode;
use App\VendorPickup;
use App\Imports\Bulk;
use Exception;
use Illuminate\Support\Facades\File;

use Maatwebsite\Excel\Facades\Excel;

class BulkController extends Controller
{
    use TrackingId, Barcode, PaginationHelper;
    public function all(Request $request)
    {

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $orders = BulkOrder::all();
        } elseif ($user->hasRole('vendor')) {
            $orders = BulkOrder::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        } else {
            $areas = HubArea::where('hub_id', $user->id)->pluck('address_id')->toArray();
            $users = BulkOrder::all('user_id');
            $order = [];
            $orders = [];
            foreach ($users as $user) {
                $ward = BusinessInfo::where('user_id', $user)->first()->ward_id;
                if (in_array($ward, $areas)) {
                    $order[] = $user;
                }
            }
            foreach ($order as $o) {
                $orders[] = BulkOrder::where('user_id', $o)->first();
            }
        }
        $orders = $this->paginateHelper($orders, 10);

        return response()->json($orders);
    }

    public function getbulkorder(Request $request)
    {
        $user = Auth::user();

        $bulks = BulkStore::where('vendor_id', $user->id)->where('packed', 0)->get();
        $paginate = $bulks->count();
        if ($paginate <= 0) {
            $paginate = 1;
        }

        foreach ($bulks as $bulk) {
            $bulk->useraddress = UserAddress::where('id', $bulk->useraddress_id)->first();
            $bulk->product_type = json_decode($bulk->product_type);
            // dd( $bulk->product_type);
            if ($bulk->product_type == null) {
                $bulk->product_type =  ['empty'];
            }
            if ($bulk->cod == null) {
                $bulk->cod =  0;
            }
            if ($bulk->weight == null) {
                $bulk->weight =  0;
            }

            $date = Carbon::parse($bulk->expected_date);
            $bulk->expecteddate =  $date->isoFormat('YYYY MMMM Do');
        }
        $bulks = $this->paginateHelper($bulks, $paginate);
        return response()->json($bulks);
    }
    public function store(Request $request)
    {

        $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw");

        $result = array($request->file('image')->getClientOriginalExtension());

        if (in_array($result[0], $extensions)) {
            $bulk = new BulkOrder();
            $bulk->user_id = auth()->id();

            $image = $request->file('image');
            $p = public_path('bulk/doc/');
            $filename = time() . "." . $image->getClientOriginalExtension();
            $image->move($p, $filename);
            $url = '/bulk/doc/';
            $bulk->file = $url . $filename;
            $bulk->save();
            $bulk->code = 'BULK' . $bulk->id;
            $bulk->update();
            return response()->json(['success' => 'Bulk Uploaded'], 200);
        } else {
            return response()->json(['error' => 'error ']);
        }
    }
    public function create(Request $request)
    {
        try {

            $file = BulkOrder::findOrfail($request->id);
            $bulkstore = BulkStore::where('vendor_id', auth()->id())->get();

            foreach ($bulkstore as $store) {
                $store->delete($store->id);
            }
            $bulk = Excel::import(new Bulk, public_path($file->file));

            if ($bulk) {
                $file->status = 'COMPLETED';
                $file->update();
            }
        } catch (Exception $e) {
            throw new Exception('error !!! There Might Be Some Problem with Your Excel File, Please Check and Reupload');
        }

        return response()->json(['success' => 'Bulk Uploaded'], 200);
    }

    public function updatebulkorder(Request $request, $id)
    {
        $request['product_type'] = json_encode($request->product_type);
        $pickup = BulkStore::findOrfail($id);
        $pickup->update($request->all());
        return response()->json(['success' => 'Updated'], 200);
    }
    public function convert_to_order(Request $request)
    {
        // if ($request->order_pickup_point == null) {
        //     return response()->json(['error' => 'Please Select Pickup Point']);
        // }
        $profile = Vendor_Info::where('vendor_id', auth()->id())->first();
        if (!$profile) {
            return response()->json(['error' => 'Please Fill Your Profile']);
        }
        if (count($request->checkbox) > 0) {
            // try {
                foreach ($request->input('checkbox') as $check) {
                    $bulk = BulkStore::findOrfail($check);

                    if($bulk->product_type==null){
                        return response()->json(['error' => 'Products Not Inserted']);
                    }

                }

            foreach ($request->input('checkbox') as $check) {
                $bulk = BulkStore::findOrfail($check);

                $trackingid = $this->generateid();
                $barcode = $this->generateBarcodeNumber();
                if (isset($bulk)) {

                    $order = PickUpOrder::create([
                        'handling' => $bulk->handling,
                        'description' => $bulk->description,
                        'cod' => $bulk->cod,
                        'expected_date' => $bulk->expected_date,
                        'vendor_id' => $bulk->vendor_id,
                        'useraddress_id' => $bulk->useraddress_id,
                        'product_type' => $bulk->product_type,
                        'weight' => $bulk->weight,
                        'vendor_order_id' => $bulk->vendor_order_id,
                        'order_pickup_point' => null,
                        // 'hub_id' => HubArea::where('address_id', UserAddress::where('id', $bulk->useraddress_id)->first()->ward_no)->first() ? HubArea::where('address_id', UserAddress::where('id', $bulk->useraddress_id)->first()->ward_no)->first()->hub_id : null,

                        // 'pickup_hub' => HubArea::where('address_id', VendorPickup::where('id', $request->order_pickup_point)->first()->ward_id)->first()->hub_id,
                    ]);
                    $useraddress = UserAddress::where('id', $bulk->useraddress_id)->first();
                    $useraddress->is_active = 1;
                    $useraddress->update();

                    if ($useraddress->ward_no) { } else {
                        $order->is_ward_status = 1;
                        $order->update();
                    }
                }
            }
            foreach ($request->input('checkbox') as $check) {
                $bulk = BulkStore::findOrfail($check);
                $bulk->delete($bulk->id);
            }
            // } catch (Exception $e) {
            //     return response()->json(['error' => 'Check You Data']);
            // }
            return response()->json(['success' => 'Bulk Order Added to Order'], 200);
        } else {

            return response()->json(['error' => 'Checkbox is empty']);
        }
    }
    public function drop(Request $request)
    {

        foreach ($request->checkbox as $id) {
            $bulk = BulkStore::findOrfail($id);
            $bulk->delete($id);
        }
        return response()->json(['success' => 'Deleted'], 200);
    }
    public function delete($id)
    {
        $file = BulkOrder::findOrfail($id);
        $ifile = public_path($file->file);
        if (File::exists($ifile)) {
            File::delete($ifile);
        }
        $file->delete($id);
        return response()->json(['success' => 'Deleted'], 200);
    }
}
