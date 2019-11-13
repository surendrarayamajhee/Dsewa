<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { }

    public function vendor()
    {
        return view('Vendors.vendor');
    }
    public function admin()
    {
        return view('home');
    }
    public function hub()
    {
        return view('hubs.hub');
    }
    public function deliveryboy()
    {
        return view('deliveryboy');
    }
    public function pickupofficer()
    {
        return view('deliveryboy');
    }
    public function redirect()
    {
        $user = Auth::user();
        if ($user) {
            if ($user->hasRole('admin')) {
                return view('home');
            } elseif ($user->hasRole('vendor')) {
                return view('Vendors.vendor');
            } elseif ($user->hasRole('hub')) {
                return  view('hubs.hub');
            } elseif ($user->hasRole('delivery_officer')) {
                return view('deliveryboy');
            }
            elseif ($user->hasRole('pickup_officer')) {
                return view('deliveryboy');
            }else {
                return 404;
            }
        } else {
            return 404;
        }
    }
    public function loginasuser($id)
    {
        $user = User::findorfail($id);
        Auth::login($user);
        if ($user) {
            if ($user->hasRole('admin')) {
                return view('home');
            } elseif ($user->hasRole('vendor')) {
                return redirect()->route('vendor');
            } elseif ($user->hasRole('hub')) {
                return  view('hubs.hub');
            } else {
                return 404;
            }
        } else {
            return 404;
        }
    }
}
