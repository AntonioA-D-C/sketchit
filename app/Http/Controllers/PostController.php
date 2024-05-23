<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use App\Models\post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Tags\Tag;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        try {
            $posts = post::query();
            $query = $request->input('query');
            $tags = $request->input('tags');
            if ($request->has("query")) {

                $posts->whereHas('tags', function ($post_tags) use ($query) {
                    $post_tags->where('name', 'like', "%$query%")
                        ->orWhere('slug', 'like', "%$query%"); // Add any additional columns you want to search
                })->orWhere('title', 'like', '%' . $query . '%')->orWhere('body_text', 'like', '%' . $query . '%');

                $posts->orderByRaw("CASE WHEN title LIKE '%$query%' THEN 1 WHEN body_text LIKE '%$query%' THEN 2 ELSE 3 END");
            }
            
            if ($request->has("tags")) {

                $posts->whereHas('tags', function ($post_tags) use ($tags) {
                   $post_tags->whereIn('id', $tags);
                });
             
            }

            $posts = $posts->paginate(100);
            
            $posts->load("tags");

            $tags =  $posts->pluck("tags")->flatten(1);

            $nonEmptyTags = $tags->filter(function ($tag) {
                return !empty($tag['id']); // Assuming id is the key to check if the tag is non-empty
            });

            $tagCounts = $nonEmptyTags->countBy(function ($tag) {
                return $tag["id"];
            });

            $tagDetails = [];
            foreach ($tags as $tag) {
                $tagName = $tag["name"];
                $tagSlug = $tag["slug"];
                $tagId = $tag["id"];
                $tagCount = $tagCounts[$tagId] ?? 0;

                $existingTag = array_filter($tagDetails, function ($tag) use ($tagId) {

                    return $tag["id"] === $tagId;
                });

                if ($existingTag) {
                    continue;
                } else {
                    $tagDetails[] = [
                        'id' => $tagId,
                        'name' => $tagName,
                        'slug' => $tagSlug,
                        'count' => $tagCount
                    ];
                }
            }


            usort($tagDetails, function ($a, $b) {
                return $b["count"] - $a["count"];
            });

            $firstFiveTags = array_slice($tagDetails, 0, 5);

            //   $posts = post::withCount('likes')->withCount("comments")->get();

            $posts->loadCount('likes');
            $posts->loadCount('comments');
            $posts->load('user');

            return response()->json(['data' => $posts, 'tags' => $firstFiveTags]);
        } catch (Exception $e) {
            return $e;
            //   throw new Error("An error occurred");
            return response()->json(["message" => "An error occurred", "error" => $e]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {

            $request->validate([
                'title' => 'string|max:300|required',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'body_text' => 'nullable|string|max:1000',
                'tagList' => 'array'
            ]);
            $post = new post();
            $post->title = $request->title;
            $post->body_text = $request->body_text;
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

            $tags = collect($request['tagList'])->map(function ($tag) {
                return Tag::findOrCreate($tag);
            });
            $post->attachTags($tags);
            return response()->json(["message" => "Post successfully submitted", 'data' => $post]);
        } catch (Exception $e) {

            throw new Error($e);
            return response()->json(["message" => "An error occurred", "error" => $e]);
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
        try {
            $post->loadCount(['likes', 'comments']);
            $post->load("user");
            $post->load("likes");
            //   $post->load("comments.replies");
            return response()->json(['data' => $post]);
        } catch (Exception $e) {

            //   throw new Error("An error occurred");
            return response()->json(["message" => "An error occurred. It's possible this post was deleted by the person who posted it", "error" => $e]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, post $post)
    {
        try {
            $request->validate([
                'title' => 'string|max:300|required',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'body_text' => 'string|max:1000',
            ]);

            $post->title = $request->title;
            $post->body_text = $request->body_text;
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
            return response()->json(["message" => "Post successfully updated", 'data' => $post]);
        } catch (Exception $e) {

            //   throw new Error("An error occurred");
            return response()->json(["message" => "An error occurred", "error" => $e]);
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
        try {
            $exists = post::findOrFail($post);
            $post->load('user');
            $prevData = $post;
            $post->delete();

            return response()->json(["message" => "Post successfully deleted", 'data' => $prevData]);
        } catch (Exception $e) {

            //   throw new Error("An error occurred");
            return response()->json(["message" => "An error occurred", "error" => $e]);
        }
    }
}
