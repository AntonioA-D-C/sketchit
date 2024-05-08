<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use App\Models\post;
use App\Models\comment;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
  function post_comments(post $post){
    try{
    
    $post_comments = comment::where('post_ID', $post->id)->whereNull('parent_ID')->with('likes')->with('user')->with("replies")->get();
 
 
    if(count($post_comments)==0){
        return response()->json(["message"=>"There's no one here"]);
    }
    return response()->json($post_comments);  
    } catch(Exception $e){
       return $e;
        throw new Exception("Something went wrong");
    }
}
function leave_comment(Request $request, post $post ){
  try{

      $request->validate([
         
       'content'=>'string|max:255'  
          
      ]);
      $comment = new comment();
    
      $comment->content = $request->content;
      $comment->user_ID = $request->user()->id;
      $comment->post_ID = $post->id;
      $comment->parent_ID = null;
      $comment->save();
      $comment->load('user');
       
      return response()->json(["message"=>"comment successfully submitted", 'data'=>$comment ]);
  } catch(Exception $e){
  
    throw new Error($e);
    return response()->json(["message"=>"An error occurred", "error"=>$e]);
  }
}
}
