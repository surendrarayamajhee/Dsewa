<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserAddress;

class NewUserController extends Controller
{
    //order
    public function getvendor()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('id',2);
        })->select('id', 'name')->orderBy('id', 'desc')->get();

        return response()->json($users);
    }

    public function getCustomer()
    {
        $customer = UserAddress::where('is_active',1)->select('id','first_name','last_name')->orderBy('id', 'desc')->get();

        return response()->json($customer);
    }
}
