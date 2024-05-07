<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\post;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{

    function post_likes(post $post){
        try{
        
        $post_likes = like::where('likeable_type', get_class($post))->where('likeable_id', $post->id)->get();
 
        return response()->json(['message'=>"Successfully fetched likes", 'data'=>$post_likes]);  
        } catch(Exception $e){
           return $e;
            throw new Exception("Something went wrong");
        }
    }
    public function like(post $post){
        $like_exists = Like::where('likeable_type', get_class($post))
        ->where('likeable_id', $post->id)
        ->where('user_id', auth()->id())
        ->first();

        if($like_exists){
            throw new Exception("You've already like this post");
        }
        try{
        $like = new Like();
        $like->likeable()->associate($post);
        $like->user_id = Auth::id();
        
    

        $like->save();
        return response()->json(["message"=>"Post liked successfully"]);
        } catch(Exception $e){
        throw new Exception("Something went wrong");
        }
       }
       public function unlike(post $post){
        try{
   
        $like = Like::where('likeable_type', get_class($post))
        ->where('likeable_id', $post->id)
        ->where('user_id', auth()->id())
        ->first();

        $like->delete();
        return response()->json(["message"=>"Post unliked successfully"]);
        
        }  catch(Exception $e){
          
            throw new Exception("Something went wrong");
            }
       }
}
