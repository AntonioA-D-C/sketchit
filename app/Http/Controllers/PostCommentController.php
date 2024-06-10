<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use App\Models\post;
use App\Models\comment;
use App\Notifications\PostReply;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
  function post_comments(post $post, Request $request){
    try{
    
  //  $post_comments = comment::where('post_ID', $post->id)->whereNull('parent_ID')->with('likes')->with('user')->with("replies")->get();

  $maxDepth = $request->input("depth");

$repliesString = "replies";



if(!!$maxDepth && $maxDepth > 0){
  for($i=1; $i<$maxDepth; $i++){

    $repliesString = $repliesString.".replies";
    }
  $post_comments = comment::withTrashed()->where('post_ID', $post->id)->whereNull('parent_ID')->with('likes')->with('user')->with([$repliesString => function($query) use ($maxDepth){
   
    $query->where("depth", '<', $maxDepth+1)->withCount("replies")->get();
  }
  ])->get();
}  else{
  $post_comments = comment::withTrashed()->where('post_ID', $post->id)->whereNull('parent_ID')->with('likes')->with('user')->get();
}
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
         
       'content'=>'string|max:500'  
          
      ]);
      $comment = new comment();
    
      $comment->content = $request->content;
      $comment->user_ID = $request->user()->id;
      $comment->post_ID = $post->id;
      $comment->parent_ID = null;
      $comment->save();
      $comment->load('user');
       
      $userToNotify = $post->user;

      if ($userToNotify->id !== auth()->id()) {
        $userToNotify->notify(new PostReply($comment->id, $post));
    }
      return response()->json(["message"=>"comment successfully submitted", 'data'=>$comment ]);
  } catch(Exception $e){
  
    throw new Error($e);
    return response()->json(["message"=>"An error occurred", "error"=>$e]);
  }
}
}
