<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Display all posts
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    // Show form for creating new post
    public function create()
    {
        return view('posts.create');
    }

    // Store new post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'author' => 'required|max:255'
        ]);

        Post::create($request->all());
        
        return redirect()->route('posts.index')
                        ->with('success', 'Post created successfully!');
    }

    // Display specific post
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // Show form for editing post
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // Update post
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'author' => 'required|max:255'
        ]);

        $post->update($request->all());

        return redirect()->route('posts.index')
                        ->with('success', 'Post updated successfully!');
    }

    // Delete post
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
                        ->with('success', 'Post deleted successfully!');
    }
}