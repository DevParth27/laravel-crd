<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

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
        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get a specific post
     *
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
}