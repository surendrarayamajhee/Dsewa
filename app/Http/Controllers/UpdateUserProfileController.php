<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserInfo;
use App\BusinessInfo;
use App\BankInfo;
use App\UserDocument;
use App\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserProfileController extends Controller
{
    public function update(Request $request, $id)
    {
        // dd($request->personal);
        UserInfo::updateOrCreate(['user_id' => $id], $request->personal);
        BusinessInfo::updateOrCreate(['user_id' => $id], $request->bussiness);
        BankInfo::updateOrCreate(['user_id' => $id], $request->bank);
        UserDocument::updateOrCreate(['user_id' => $id], $request->doc);

        return response()->json(['success' => 'Successful'], 200);
    }
    public function get($id)
    {

        $personal = UserInfo::where('user_id', $id)->first();
        $bussiness = BusinessInfo::where('user_id', $id)->first();
        $bank = BankInfo::where('user_id', $id)->first();
        $doc = UserDocument::where('user_id', $id)->first();
        return response()->json(['personal' => $personal, 'bussiness' => $bussiness, 'bank' => $bank, 'doc' => $doc]);
    }

    public function getprofile()
    {
        $user = User::findOrfail(auth()->id());
        return response()->json($user);
    }
    public function updateprofile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:users,name,' . $id,
            'email' => 'required|string|max:100|email|unique:users,email,'.$id,
            'password' => 'min:6|string|max:11|confirmed',
        ]);
        if ($request->has('password')) {
            $request->validate([
                'password' => 'min:6|string|max:11|confirmed',
            ]);
        }
        $user = User::findOrfail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->update();
    
        return response()->json(['success' => 'User Updated'], 200);
    }
}
