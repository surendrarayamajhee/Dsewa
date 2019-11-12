<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerRegisterController extends Controller
{
    //
    public function customerregister(Request $request)
    {
        $request->validate([
            'email' =>  ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => 'required|string',
            'phone' =>'required',
            'phone2' =>'',
            'name' =>'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'phone2' => $request->phone2,
            'password' => Hash::make($request->password),
        ]);

        if($user)
        {
            return response()->json(['success' => 'Registered']);
        }

    }
    public function customerlogin(Request $request)
    {
                $request->validate([
                    'phone' => 'required',
                    'password' => 'required|string',

                ]);
                $credentials = request(['phone', 'password']);
                $credentials['active'] = 1;
                if(!Auth::attempt($credentials))
                    return response()->json([
                        'message' => 'Incorrect phone or Password'
                    ], 401);
                $user = $request->user();
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;

                $token->save();
                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'name' => Auth::user()->name,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ]);
    }

}
