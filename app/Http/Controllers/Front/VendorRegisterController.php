<?php

namespace App\Http\Controllers\Front;
use App\Address;
use App\BankInfo;
use App\BusinessInfo;
use App\User;
use App\UserDocument;
use App\UserInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VendorRegisterController extends Controller
{
    public function store(Request $request)
    {

        //        dd($request);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->loginemail;
        $user->password = bcrypt($request->password);
        $user_success = $user->save();
        if ($user_success) {

            $user->roles()->attach(array(2));

            //            save user info
            $userInfo = new UserInfo();
            $userInfo->name = $request->name;
            $userInfo->user_id = $user->id;
            $userInfo->address = $request->address;
            $userInfo->email = $request->email;
            $userInfo->job_title = $request->jobtitle;
            $userInfo->citizenship_no = $request->citizenshipno;
            $userInfo->save();

            //            save user business info
            $businessInfo = new BusinessInfo();
            $businessInfo->user_id = $user->id;
            $businessInfo->business_name = $request->businesname;
            $businessInfo->state = $request->state;
            $businessInfo->district = $request->district;
            $businessInfo->municipality_vdc = $request->municipality;
            $businessInfo->ward = $request->ward;
            $businessInfo->tole = $request->tole;
            $businessInfo->pan_vat_no = $request->panvat;
            $businessInfo->phone = $request->phone;
            $businessInfo->mobile = $request->mobile;
            $businessInfo->fax = $request->fax;
            $businessInfo->business_email = $request->businessemail;
            $businessInfo->company_reg_no = $request->companyregno;
            $businessInfo->save();


            //            save bank info

            $bankInfo = new BankInfo();
            $bankInfo->user_id = $user->id;
            $bankInfo->bank_name = $request->bankname;
            $bankInfo->bank_branch = $request->bankbranch;
            $bankInfo->account_no = $request->bankaccountno;
            $bankInfo->account_name = $request->bankaccountname;
            $bankInfo->account_type = $request->accounttype;
            $bankInfo->save();


            //            save user doc

            $userDoct = new UserDocument();
            $userDoct->user_id = $user->id;
            $userDoct->citizenship = $request->citizenship_img;
            $userDoct->pan_vat = $request->pan_vat_img;
            $userDoct->cheque = $request->cheque_img;
            $userDoct->save();
            Auth::login($user);

            redirect()->route('vendor');

        } else {
            return response()->json([
                'errer' => 'error',
                'message' => 'Error while Registration !!'
            ]);
        }
    }
}
