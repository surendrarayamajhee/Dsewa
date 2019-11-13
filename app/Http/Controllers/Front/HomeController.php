<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    
    public function index(){

        return view('front.index');
    }
    public function admin()
    {

        return view('admin');
    }
    public function register(){

        return view('auth.register');
    }
    
    
  
    
    
    public function faq(){

        return view('front.faq');
    }
    
    
    public function careeers(){

        return view('front.careeers');
    }
    
    
    public function aboutus(){

        return view('front.aboutus');
    }
    
    
    public function contactus(){

        return view('front.contactus');
    }
    
    
    public function logistics(){

        return view('front.logistics');
    }
    
    
    public function officialpartners(){

        return view('front.officialpartners');
    }
    
    
    public function ourservice(){

        return view('front.ourservice');
    }
    
    
    public function protfolio(){

        return view('front.protfolio');
    }

    
    
    public function ecommerce(){

        return view('front.ecommerce');
    }
}
