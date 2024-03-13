<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\follow;
use App\Models\post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        try{
      $users = User::all();
  
      return response()->json(['message'=>"Fetching all users", "data"=>$users]);
        } catch(Exception $e){
         
            //   throw new Error("An error occurred");
             return response()->json(["message"=>"An error occurred", "error"=>$e]);
           }
    }
    public function show(User $user){
        try{
            return response()->json(['message'=>"Successfully fetched user", "data"=>$user]);
        } catch(Exception $e){
            throw new Exception($e);
        }
    }
    public function posts(User $user){
        try{
            $user_posts = post::where('user_id', $user->id)->get();
            return response()->json(['message'=>"Successfully fetched user posts", "data"=>$user_posts]);
        } catch(Exception $e){
            throw new Exception($e);
        }
    }
    public function comments(User $user){
        try{
            $user_comments = comment::where('user_ID', $user->id)->get();
           
            return response()->json(['message'=>"Successfully fetched user comments", "data"=>$user_comments]);
        } catch(Exception $e){
            throw new Exception($e);
        }

    }
    public function follow(Request $request, User $user){
        try{
            if(!$request->user()->id === $user->id){
                throw new Exception("An error occurred");
            }
            $follow = new follow();
            $follow->follower_id = $request->user()->id;
            $follow->followed_id = $user->id;
            $follow->save();
           /* $follow->load('follower');
            $follow->load("followed");*/
            return response()->json(['message'=>"You're now following ".$user->name."!", "data"=>["user"=>$user]]);
        } catch(Exception $e){
            throw new Exception("Something went wrong");
        }
    }
    public function unfollow(Request $request, User $user){
        try{
            $this_user = $request->user();
            if(!$this_user->id === $user->id){
                throw new Exception("An error occurred");
            }
      
            $follow = follow::where('follower_id', $this_user->id)->where('followed_id', $user->id);
            $follow->delete();
            
            return response()->json(['message'=>"You've unfollowed ".$user->name]);
        } catch(Exception $e){
            throw new Exception($e);
        }
    }
    public function followers(User $user){
        try{
            $followers = follow::where('followed_id', $user->id)->get();
         
            $follower_list = [];
            foreach($followers as $follow_message){
                $follower_list[] = User::find($follow_message->follower_id);
            }
            return response()->json(['message'=>"User followers", "data"=>$follower_list]);
        } catch(Exception $e){
            throw new Exception($e);
        }
    }
    public function follows(User $user){
        try{
            $follows = follow::where('follower_id', $user->id)->get();
           
            foreach($follows as $follow_message){
                $follows_list[] = User::find($follow_message->followed_id);
            }
            if(count($follows)==0){
                return response()->json(['message'=>"This user doesn't follow anyone"]);  
            }
            return response()->json(['message'=>"Successfully fetched user comments", "data"=>$follows_list]);
        } catch(Exception $e){
            throw new Exception($e);
        }
    }
}
