<?php

namespace App\Http\Controllers\Comment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrderComment;
use App\User;
use App\Comment;
use Carbon\Carbon;

class OrderCommentController extends Controller
{


    public function Order_comment_store(Request $request, $id)
    {
       
        $request->merge(['user_id' => auth()->id(), 'order_id' => $id]);
        OrderComment::create($request->all());
        return response()->json(['success' => 'Comment Added']);        
    }
    public function get_Order_comment(Request $request)
    {
        
        $Comment = OrderComment::where('order_id', $request->id)->get();
        $Comment->transform(function ($item, $key) {
            $item->user_name = User::where('id', $item->user_id)->first() ? User::where('id', $item->user_id)->first()->name:'';
            // $date = Carbon::parse($item->expected_date);
            // $item->expecteddate =  $date->isoFormat('YYYY MM Do HH:MM:SS');
            $date = Carbon::parse($item->created_at);
            $item->expecteddate =  $date->isoFormat('YYYY MM Do hh:mm:ss');
            $item->date =  $item->expecteddate .' '. '('.$item->created_at->diffForHumans().')';
            return $item;
        });
        return response()->json($Comment);
    }
    public function getcomment()
    {
        $Comment = Comment::all('id', 'name');
        return response()->json($Comment);
    }
}
