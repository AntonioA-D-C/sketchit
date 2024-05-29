<?php

namespace App\Http\Controllers;

use App\Models\comment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        /*
        $notifications = $user->notifications()->select('notifications.*')
        ->leftJoin('comments', function ($join) {
            $join->on(DB::raw("CAST(JSON_VALUE(data, '$.comment_id') AS UNSIGNED)"), '=', 'comments.id');
        })
        ->paginate(10);*/

        $notifications = $user->notifications()->paginate(10);
   
        $notificationCollection = [];
        foreach($notifications as $notification){
          
            $comment = comment::find($notification["data"]["reply_id"]);
            $comment->load('user');
            $comment->load('get_post');
            $notification->comment = $comment;
            $notificationCollection[] =  $notification;
        }
        
        $total_unread = $user->unreadNotifications->count();
        
        return response()->json(['data'=>$notifications, 'total_unread'=>$total_unread]);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function mark_as_read($notification){
        try{
            $user = Auth::user();

        
            $new_notification = $user->notifications()->findOrFail($notification);
    
            $new_notification->markAsRead();
    
            return response()->json(['message'=>"Notification marked as read"]);
        } catch(Exception $e){
      
       return response()->json(['message'=>"Something went wrong"]);
        }
    

    }
    
    public function mark_all_as_read(Request $request){
        try{
        $user = Auth::user();

        $notification = $user->notifications->markAsRead();

        return response()->json(['message'=>"All notifications marked as read"]);
        } catch(Exception $e){
          //return $e;
            return response()->json(['message'=>"Something went wrong"]);

        }
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(notification $notification)
    {
        //
    }
}
