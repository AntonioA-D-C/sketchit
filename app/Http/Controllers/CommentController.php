<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use App\Models\comment;
use App\Models\post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $comments = comment::all();
           

            return response()->json($comments);
              } catch(Exception $e){
               
                  //   throw new Error("An error occurred");
                   return response()->json(["message"=>"An error occurred", "error"=>$e]);
                 }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    
        try{

            $request->validate([
               
             'content'=>'string|max:255'  
                
            ]);
            $comment = new comment();
            $post = comment::find(1);
         
            $comment->content = $request->content;
            $comment->user_ID = $request->user()->id;
            $comment->commentable()->associate($post);
            $comment->save();
            $comment->load('user');
            $comment->load('replies');
            
            return response()->json(["message"=>"comment successfully submitted", 'data'=>$comment ]);
        } catch(Exception $e){
        
          throw new Error($e);
          return response()->json(["message"=>"An error occurred", "error"=>$e]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
 

    /**
     * Display the specified resource.
     */
    public function show(comment $comment)
    {
         
        try{
        $comment->load("user");
        $comment->load('replies.user');
        return response()->json(['data'=>$comment ]);
        } catch(Exception $e){
         
            //   throw new Error("An error occurred");
             return response()->json(["message"=>"An error occurred. It's possible this comment was deleted by the person who commented it", "error"=>$e]);
           }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(comment $comment)
    {
        try{
            $comment->delete();
            return response()->json(['message'=>"Successfully deleted comment", 'data'=>$comment ]);
            } catch(Exception $e){
             
                //   throw new Error("An error occurred");
                 return response()->json(["message"=>"An error occurred. It's possible this comment was deleted by the person who commented it", "error"=>$e]);
               }
    
    }
}
