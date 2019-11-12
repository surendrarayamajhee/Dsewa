<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comments;
use App\Helpers\PaginationHelper;

class CommentController extends Controller
{
    use PaginationHelper;
     public function getAll()
    {



        



        
     $comments=Comments::all();
     $comments=$this->paginateHelper($comments,10);
     return response()->json($comments);
    }
    public function create(Request $request){
        $comment=Comments::create($request->except('_token'));
        return response()->json(['success'=>'Created'],200);
    }

    public function update(Request $request,$id){
        $comment=Comments::findorfail($id);
        $comment->name=$request->name;
        $comment->update();     
        return response()->json(['success'=>'updated'],200);
    }
    public function delete($id){
        $comment=Comments::findorfail($id);
        $comment->delete();     
        return response()->json(['success'=>'deleted'],200);
    }
}
