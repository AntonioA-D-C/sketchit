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

                'content' => 'string|max:255'

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
    public function all_replies(comment $comment)
    {
        try {


            $comment_replies = comment::where('parent_ID', $comment->id)->paginate(10);
            $comment_replies->load('user');
            $comment_replies->load('likes');
            $comment_replies->loadCount('replies');
            return response()->json($comment_replies);
        } catch (Exception $e) {

            throw new Error($e);
            return response()->json(["message" => "An error occurred", "error" => $e]);
        }
    }
}
