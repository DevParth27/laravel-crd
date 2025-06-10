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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection