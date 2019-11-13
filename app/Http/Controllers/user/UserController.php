<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Helpers\PaginationHelper;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    //
    use PaginationHelper;

    public function getuser()
    {
        $user = User::all('id', 'name');

        return response()->json($user);
    }
    public function getRoles()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {

            $roles = DB::table('roles')->select(['id', 'display_name'])->get();
        } else {
            $roles = DB::table('roles')->where('name', 'delivery_officer')->orWhere('name', 'pickup_officer')->orWhere('name', 'shipment_officer')->select(['id', 'display_name'])->get();
        }
        return response()->json($roles);
    }
    public function store(UserRequest $request){
$user=User::create([
    'name'=>$request->name,

    'email'=>$request->email,
    'password'=>Hash::make($request['password']),
    'active'=>$request->active,
    'parent_id'=>auth()->id(),
]);
if($request->roles){
$user->roles()->attach( $request['roles'] );
}
return response()->json(['success'=>'User Saved'],200);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:users,name,' . $id,
            'email' => 'required|string|max:100|email|unique:users,email,'.$id,
            'roles' => 'required',
            'active' => 'required'
        ]);
        if ($request->has('password')) {
            $request->validate([
                'password' => 'min:6|string|max:11|confirmed',
            ]);
        }

        $user = User::findOrfail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->active = $request->active;
        if ($request->has('password')) {
            $user->password = Hash::make($request['password']);
        }
        $user->update();

        $user->roles()->sync($request['roles']);
        return response()->json(['success' => 'User Updated'], 200);
    }


    public function getAll(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $users = User::orderBy('id', 'desc');
        } else {
            $users = User::orderBy('id', 'desc')->where('parent_id', $user->id);
        }
        if ($request->has('filterRole')) {
            $role = $request->filterRole;
            $users = User::whereHas('roles', function ($q) use ($role) {
                $q->where('id', $role);
            });
        } elseif ($request->has('name')) {
            $users = $users->where('name', 'LIKE', '%' . $request->name . '%');
        } else { }
        $users = $users->get();
        $hub_vendor = false;
        foreach ($users as $user) {
            $user->names = $user->roles()->pluck('name')->toArray();
            if (in_array('hub', $user->names) || in_array('vendor', $user->names)) {
                $hub_vendor = true;
            }
            $user->hub_vendor = $hub_vendor;
            $user->roles = $user->roles()->pluck('id')->toArray();
            if ($user->active == 1) {
                $user->active_name = "ACTIVE";
            } else {
                $user->active_name = "IN-ACTIVE";
            }
        }

        $users = $this->paginateHelper($users, 10);


        return response()->json($users);
    }
}
