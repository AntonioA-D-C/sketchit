<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{
    public function like(post $post){

        $like = new Like();
        $like->likeable()->associate($post);
        $like->user_id = Auth::id();
        $like->save();
        return response()->json(["message"=>"Post liked successfully"]);
       }
       public function unlike(post $post){
        $post->likes()->detach(Auth::id());
        return response()->json(["message"=>"Post unliked successfully"]);
       }
}
