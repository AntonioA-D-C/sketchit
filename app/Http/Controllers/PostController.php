<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use App\Models\post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
      $posts = post::withCount('likes')->withCount("comments")->get();
      $posts->load('user');

      return response()->json($posts);
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
                'title' => 'string|max:300|required',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'body_text'=>'string|max:1000',
            ]);
            $post = new post();
            $post->title= $request->title;
            $post->body_text= $request->body_text;
            $post->user_id  = $request->user()->id;
            $post->image = $request->image;
    
            if (!!$request->image) {
    
                //Public storage
                $storage = Storage::disk('public');
                //Borrar vieja imagen
                if ($storage->exists($post->image))
                    $storage->delete($post->image);
                //nombre imagen
                $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
    
                $post->image = $imageName;
                //guardar imagen
                $storage->put($imageName, file_get_contents($request->image));
            }
            
            $post->save();
            $post->load('user');
            return response()->json(["message"=>"Post successfully submitted", 'data'=>$post ]);
        } catch(Exception $e){
        
          throw new Error($e);
          return response()->json(["message"=>"An error occurred", "error"=>$e]);
        }
    
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
    public function show(post $post)
    {
        try{
         $post->loadCount(['likes', 'comments']);
        $post->load("user");
     //   $post->load("comments.replies");
        return response()->json(['data'=>$post ]);
        } catch(Exception $e){
         
            //   throw new Error("An error occurred");
             return response()->json(["message"=>"An error occurred. It's possible this post was deleted by the person who posted it", "error"=>$e]);
           }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, post $post)
    {
        try{
        $request->validate([
            'title' => 'string|max:300|required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'body_text'=>'string|max:1000',
        ]);
     
        $post->title= $request->title;
        $post->body_text= $request->body_text;
        $post->user_id  = $request->user()->id;
        $post->image = $request->image;

        if (!!$request->image) {

            //Public storage
            $storage = Storage::disk('public');
            //Borrar vieja imagen
            if ($storage->exists($post->image))
                $storage->delete($post->image);
            //nombre imagen
            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();

            $post->image = $imageName;
            //guardar imagen
            $storage->put($imageName, file_get_contents($request->image));
        }
        $post->save();
        $post->load('has_user');
        return response()->json(["message"=>"Post successfully updated", 'data'=>$post ]);
    }  catch(Exception $e){
         
        //   throw new Error("An error occurred");
         return response()->json(["message"=>"An error occurred", "error"=>$e]);
       }
  
    }

    /**
     * Update the specified resource in storage.
     */
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(post $post)
    {
        try{
        $exists = post::findOrFail($post);
         $post->load('user');
        $prevData = $post;
        $post->delete();
       
        return response()->json(["message"=>"Post successfully deleted", 'data'=>$prevData ]);
        }  catch(Exception $e){
         
            //   throw new Error("An error occurred");
         return response()->json(["message"=>"An error occurred", "error"=>$e]);
        }

    }
}
