<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
class PostController extends Controller
{
    /**
     * Get all posts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::all();
        if($posts)
        {
            return PostResource::collection($posts);
        }
        else
        {
             return response()->json(['message' => "No Records Found"],200);
        }
    }
    public function show(Post $post)
    {
        return new PostResource($post);
    }
    public function update(Request $request, Post $post)
    {
        $post->update($request->all());
        return new PostResource($post);
    }
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    /**
     * Get a specific post
     *
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
}