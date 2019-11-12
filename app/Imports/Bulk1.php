<?php

namespace App\Imports;

use App\Address;
use App\BulkStore;
use App\Http\Controllers\ProductTypeController;
use App\Product_type;
use App\UserAddress;
use Exception;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class Bulk1 implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function collection(Collection $rows)
    {

        foreach ($rows as  $row) {
            $d = null;
            $m = null;
            $w = null;

            if (isset($row['district'])) {
                if ($row['district'] != null) {

                    $district = Address::where('type', 'DISTRICT')->where('address', 'LIKE', '%' . $row['district'] . '%')->first();
                    if ($district) {
                        $d = $district->id;
                        $municipilaty = Address::where('parent_id', $district->id)->where('address', 'LIKE', '%' . $row['municipality'] . '%')->first();
                        if ($municipilaty) {
                            $m = $municipilaty->id;
                            $ward = Address::where('parent_id', $municipilaty->id)->where('address', 'LIKE', '%' . (int) $row['ward_number'] . '%')->first();
                            if ($ward) {
                                $w = $ward->id;
                            }
                        }
                    }
                }
            }
            if (isset($row['address'])) {
                $address_description = $row['address'];
            } elseif (isset($row['address_description'])) {
                $address_description = $row['address_description'];
            } else {
                $address_description = null;
            }
            if (isset($row['first_name'])) {
                $first_name = $row['first_name'];
                if (isset($row['last_name'])) {
                    $last_name = $row['last_name'];
                }
            } elseif (isset($row['name'])) {
                $parts = explode(" ", $row['name']);
                $first_name = array_shift($parts);
                $last_name = implode(" ", $parts);
            } else {
                $first_name = null;
                $last_name = null;
            }
            if (isset($row['phone'])) {
                $phone1 = $row['phone'];
            } elseif (isset($row['contact1'])) {
                $phone1 = $row['contact1'];
            } else {
                $phone1 = null;
            }
            if (isset($row['phone2'])) {
                $phone2 = $row['phone2'];
            } elseif (isset($row['contact2'])) {
                $phone2 = $row['contact2'];
            } else {
                $phone2 = null;
            }

            $user =  UserAddress::create([
                'vendor_id' => auth()->id(),
                'first_name' => $first_name,
                'last_name' => $last_name,
                'district' => $d,
                'municipality' => $m,
                'ward_no' => $w,
                'description' => $address_description,
                'phone1' => $phone1,
                'phone2' => $phone2,
                'is_active' => 0,
            ]);
            if (isset($row['product_type'])) {
                if ($row['product_type'] != null) {
                    $pd = explode(",", $row['product_type']);
                    foreach ($pd as $p) {
                        $pt = Product_type::where('name', $p)->where('vendor_id', auth()->id())->first();
                        if ($pt) { } else {
                            $product = new Product_type();
                            $product->vendor_id = auth()->id();
                            $product->name = $p;
                            $product->save();
                        }
                        $product_description = json_encode($pd);
                    }
                } else {
                    $product_description = null;
                }
            } elseif (isset($row['product'])) {
                if ($row['product'] != null) {
                    $pd = explode(",", $row['product']);
                    foreach ($pd as $p) {
                        $pt = Product_type::where('name', $p)->where('vendor_id', auth()->id())->first();
                        if ($pt) { } else {
                            $product = new Product_type();
                            $product->vendor_id = auth()->id();
                            $product->name = $p;
                            $product->save();
                        }
                    }
                    $product_description = json_encode($pd);
                } else {
                    $product_description = null;
                }
            } else {
                $product_description = null;
            }
            if (isset($row['weight'])) {
                $weight = $row['weight'];
            } else {
                $weight = 1;
            }
            if (isset($row['product_details'])) {
                $product_details = $row['product_details'];
            } elseif (isset($row['quantity'])) {
                $product_details = $row['quantity'];
            } else {
                $product_details = null;
            }
            if (isset($row['cod'])) {
                $cod = $row['cod'];
            } else {
                $cod = 0;
            }
            if (isset($row['order'])) {
                $vendor_order_id = $row['order'];
            } else {
                $vendor_order_id = null;
            }
            $bulk = BulkStore::create([
                'weight' => $weight,
                'vendor_id' => auth()->id(),
                'description' =>  $product_details,
                'handling' => "NON_FRAGILE",
                'product_type' => $product_description,
                'expected_date' => Carbon::now()->addDays(2),
                'cod' => (int) $cod,
                'useraddress_id' => $user->id,
                'order_pickup_point' => null,
                'vendor_order_id' => $vendor_order_id,
            ]);
            if ($w == null) {
                $bulk->is_ward_status = 1;
                $bulk->update();
            }
        }


        // return response()->json(['success'=>'saved'],200);
    }


    public function headingRow(): int
    {
        return 1;
    }
}
