@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>All Posts</h1>
    @can('create', App\Models\Post::class)
        <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New Post</a>
    @endcan
</div>

<div class="row">
    @forelse($posts as $post)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                    <small class="text-muted">By {{ $post->author }}</small>
                </div>
                <div class="card-footer">
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-sm">View</a>

                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endcan

                    @can('delete', $post)
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                No posts found. 
                @can('create', App\Models\Post::class)
                    <a href="{{ route('posts.create') }}">Create your first post!</a>
                @endcan
            </div>
        </div>
    @endforelse
</div>
@endsection
