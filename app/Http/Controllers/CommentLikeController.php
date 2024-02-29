<?php

namespace App\Http\Controllers;

use App\Models\comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentLikeController extends Controller
{
   public function like(comment $comment){
    $comment->likes()->attach(Auth::id());
    return response()->json(["message"=>"Comment liked successfully"]);
   }
   public function unlike(comment $comment){
    $comment->likes()->detach(Auth::id());
    return response()->json(["message"=>"Comment unliked successfully"]);
   }
}
