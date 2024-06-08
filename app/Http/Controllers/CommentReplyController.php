<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use App\Models\post;
use App\Models\User;
use App\Models\comment;
use Illuminate\Http\Request;
use App\Notifications\NewReply;

class CommentReplyController extends Controller
{
    public function reply(Request $request, comment $comment)
    {
        try {

            $request->validate([

                'content' => 'string|max:500'

            ]);
            $new_comment = new comment();


            $new_comment->content = $request->content;
            $new_comment->user_ID = $request->user()->id;
            $new_comment->post_ID = $comment->post_ID;
            $new_comment->parent_ID = $comment->id;

            $new_comment->depth = $comment->depth+1;
            $new_comment->save();

            $userToNotify = $comment->user;

            $current_post = $new_comment->get_post;

            if ($userToNotify->id !== auth()->id()) {
                $userToNotify->notify(new NewReply($comment->id, $new_comment, $current_post->title));
            }
            $new_comment->load('user');


            return response()->json(["message" => "comment successfully submitted", 'data' => $new_comment]);
        } catch (Exception $e) {

            throw new Error($e);
            return response()->json(["message" => "An error occurred", "error" => $e]);
        }
    }
    public function all_replies(Request $request, comment $comment)
    {
        try {

            $maxDepth =$request->input("depth");
            
            $repliesString = "replies";
            $initialDepth= $comment->depth;
            if(!!$maxDepth && $maxDepth > 0){
                for($i=1; $i<$maxDepth-1; $i++){
                 
                    $repliesString = $repliesString.".replies";
                    }
            
            $comment_replies=  comment::where('parent_ID', $comment->id)->with('likes')->with('user')->with([
                $repliesString => function ($query) use ($maxDepth, $initialDepth) {

                    $query->whereraw("'depth' <  ($maxDepth+$initialDepth)+1")->withCount("replies")->get();
                }
            ]);
            } else{
         $comment_replies =  comment::where('parent_ID', $comment->id)->with('likes')->with('user')->withCount("replies");
            }
            $comment_replies = $comment_replies->paginate(10);
            return response()->json($comment_replies);
        } catch (Exception $e) {

            throw new Error($e);
            return response()->json(["message" => "An error occurred", "error" => $e]);
        }
    }
}
