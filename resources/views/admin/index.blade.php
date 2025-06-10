@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-users"></i> User Management</h5>
                                    <p class="card-text">Manage users and their permissions</p>
                                    <a href="{{ route('admin.users') }}" class="btn btn-light">Manage Users</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-file-excel"></i> Excel Files</h5>
                                    <p class="card-text">Manage Excel files and data</p>
                                    <a href="{{ route('excel.index') }}" class="btn btn-light">View Files</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-database"></i> Posts</h5>
                                    <p class="card-text">Manage posts and content</p>
                                    <a href="{{ route('posts.index') }}" class="btn btn-light">View Posts</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection