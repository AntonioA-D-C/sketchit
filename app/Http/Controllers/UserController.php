<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\follow;
use App\Models\post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function user(Request $request)
    {
        try {
            $user = $request->user();
            

            return response()->json($user);
        } catch (Exception $e) {

            //   throw new Error("An error occurred");
            return response()->json(["message" => "An error occurred", "error" => $e]);
        }
    }
    public function index(Request $request)
    {
        try {
            $query = $request->input('query');

            $users = User::query();

            if($request->has("query")){
               
                $users->where('user_name', 'like', '%' . $query . '%')->orWhere('name', 'like', "%$query%");
              
                $users->orderByRaw("CASE WHEN user_name LIKE '%$query%' THEN 1 WHEN name LIKE '%$query%' THEN 2 ELSE 3 END");
            }  
           $users= $users->paginate(100);
            return response()->json(['message' => "Fetching all users", "data" => $users]);
        } catch (Exception $e) {

            //   throw new Error("An error occurred");
            return response()->json(["message" => "An error occurred", "error" => $e]);
        }
    }
    public function show(User $user)
    {
        try {
            $user->loadCount("followers");
            $user->loadCount("followed");
            return response()->json(['message' => "Successfully fetched user", "data" => $user]);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
    public function posts(User $user)
    {
        try {
            $user_posts = post::where('user_id', $user->id)->with('user')->withCount('likes')->withCount("comments")->get();
            return response()->json(['message' => "Successfully fetched user posts", "data" => $user_posts]);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
    public function comments(User $user)
    {
        try {
            $user_comments = comment::where('user_ID', $user->id)->get();

            return response()->json(['message' => "Successfully fetched user comments", "data" => $user_comments]);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
    public function follow(Request $request, User $user)
    {
        try {
            if (!$request->user()->id == $user->id) {
                throw new Exception("An error occurred");
            }
            $follow = new follow();
            $follow->follower_id = $request->user()->id;
            $follow->followed_id = $user->id;
            $follow->save();
            /* $follow->load('follower');
            $follow->load("followed");*/
            return response()->json(['message' => "You're now following " . $user->name . "!", "data" => ["user" => $user]]);
        } catch (Exception $e) {
            throw new Exception("Something went wrong");
        }
    }
    public function unfollow(Request $request, User $user)
    {
        try {
            $this_user = $request->user();
            if (!$this_user->id == $user->id) {
                throw new Exception("An error occurred");
            }

            $follow = follow::where('follower_id', $this_user->id)->where('followed_id', $user->id);
            $follow->delete();

            return response()->json(['message' => "You've unfollowed " . $user->name]);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
    public function followers(User $user, Request $request)
    {
        try {
            $followers = follow::where('followed_id', $user->id)->with("follower")->paginate(10);
           
            /*
            $follower_list = [];
            foreach($followers as $follow_message){
                $follower_list[] = User::find($follow_message->follower_id);
            }
*/


            $is_following = false;
                $is_following = $user->followers()->where('follower_id', Auth::user()->id)->exists();
           
              

            return response()->json(['message' => "User followers", "data" => $followers, "is_following" => $is_following]);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
    public function follows(User $user)
    {
        try {
            $follows = follow::where('follower_id', $user->id)->with("followed")->paginate(10);
        
            /*         foreach($follows as $follow_message){
                $follows_list[] = User::find($follow_message->followed_id);
            }

           
 */ 


            return response()->json(['message' => "Successfully fetched user following", "data" => $follows]);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function common_follows(User $user)
    {
        try {
            $current_user_follows = follow::where('follower_id', auth()->user()->id)->pluck('followed_id');
            $followed_follows = follow::where('followed_id', $user->id)->pluck('follower_id');

            $common_users = User::whereIn('id', $current_user_follows)->whereIn('id', $followed_follows )->paginate(10);
         
          

            return response()->json(['message' => "Successfully fetched user common followers", "data" => $common_users]);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
