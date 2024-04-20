<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\comment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentLikeController extends Controller
{
   public function like(comment $comment){
      $like_exists = Like::where('likeable_type', get_class($comment))
      ->where('likeable_id', $comment->id)
      ->where('user_id', auth()->id())
      ->first();

      if($like_exists){
          throw new Exception("You've already like this comment");
      } else{
      //   return response()->json(['message' => 'Comment not found'], 404);
      }
      try{
      $like = new Like();
      $like->likeable()->associate($comment);
      $like->user_id = Auth::id();
      
  

      $like->save();
      return response()->json(["message"=>"comment liked successfully"]);
      } catch(Exception $e){
      throw new Exception("Something went wrong");
      }
   }
   public function unlike(comment $comment){
      try{
   
         $like = Like::where('likeable_type', get_class($comment))
         ->where('likeable_id', $comment->id)
         ->where('user_id', auth()->id())
         ->first();
         if(!$like){
            return response()->json(['message' => 'Comment not found'], 404);
         }
         $like->delete();
         return response()->json(["message"=>"Comment unliked successfully"]);
         
         }  catch(Exception $e){
           
             throw new Exception("Something went wrong");
             }
   }
}
