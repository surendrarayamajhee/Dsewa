<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
class mobileLoginController extends Controller
{
    //
    public function login(Request $request)
    {
                // dd($request->email);
                $request->validate([
                    'email' => 'required|string|email',
                    'password' => 'required|string',

                ]);
                $credentials = request(['email', 'password']);
                $credentials['active'] = 1;
                if(!Auth::attempt($credentials))
                    return response()->json([
                        'message' => 'Incorrect Email or Password'
                    ], 401);
                $user = $request->user();
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;

                $token->save();
                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'name' => Auth::user()->name,
                    'hub' =>User::where('id',Auth::user()->parent_id)->first()->name,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ]);

    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
         }
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
