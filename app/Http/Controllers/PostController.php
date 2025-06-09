<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
    public function getData()
    {
        $posts = Post::all();
    
        return DataTables::of($posts)
            ->addColumn('actions', function ($post) {
                $buttons = '<a href="' . route('posts.show', $post) . '" class="btn btn-sm btn-secondary">View</a>';
    
                if (auth()->user()->can('update', $post)) {
                    $buttons .= ' <a href="' . route('posts.edit', $post) . '" class="btn btn-sm btn-primary">Edit</a>';
                }
    
                if (auth()->user()->can('delete', $post)) {
                    $buttons .= '
                        <form action="' . route('posts.destroy', $post) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Delete this post?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>';
                }
    
                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
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