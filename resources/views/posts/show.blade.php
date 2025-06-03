@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="mb-0">{{ $post->title }}</h1>
        <div>
            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted">By {{ $post->author }} • {{ $post->created_at->format('M d, Y') }}</p>
        <div class="mt-3">
            {{ $post->content }}
        </div>
    </div>
</div>
@endsection