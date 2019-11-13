<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposit;

class DepositController extends Controller
{

   public function store(Request $request){
       $id=auth()->id();
    //    dd($request->all());
       $deposit=Deposit::create([
           'outstanding_payment'=>$request->outstanding_payment,
           'date'=>$request->date,
           'bank_branch'=>$request->bank_branch,
           'bank_name'=>$request->bank_name,
           'amount'=>$request->amount,
           'payment_type'=>$request->payment_type,
           'is_verified'=>'0',
           'image'=>'fdsfds',
           'user_id'=>auth()->id(),
       ]);
       return response()->json(['success'=>'saved'],200);
   }
   public function get(){
  $deposits=Deposit::where('user_id',auth()->id())->get();
foreach($deposits as $deposit)
{
  $deposit->date=\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $deposit->date)->format('Y-m-d');;
   $deposit->verified=$deposit->verified==0?'Unverified':'Verified';
}
return response()->json($deposits);
} 

}
