@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                
                {{-- Page Header --}}
                <div class="text-center mb-5">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-lg mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user-circle text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h2 class="text-white fw-bold mb-2">Profile Settings</h2>
                    <p class="text-white-50 mb-0">Manage your account information and security settings</p>
                </div>

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row g-4">
                    {{-- Profile Information Card --}}
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-lg h-100" style="backdrop-filter: blur(10px);">
                            <div class="card-header bg-gradient text-white border-0 py-3" 
                                 style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-edit me-2"></i>
                                    <h5 class="mb-0 fw-semibold">Personal Information</h5>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                {{-- Update Name Section --}}
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3 fw-semibold">
                                        <i class="fas fa-signature me-2"></i>Update Name
                                    </h6>
                                    <form method="POST" action="{{ route('profile.updateName') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-medium text-dark">Full Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-user text-muted"></i>
                                                </span>
                                                <input type="text" name="name" 
                                                       class="form-control border-start-0 @error('name') is-invalid @enderror"
                                                       value="{{ old('name', $user->name) }}" 
                                                       placeholder="Enter your full name" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-outline-primary btn-sm px-4">
                                            <i class="fas fa-save me-1"></i>Update Name
                                        </button>
                                    </form>
                                </div>

                                <hr class="my-4">

                                {{-- Update Email Section --}}
                                <div>
                                    <h6 class="text-muted mb-3 fw-semibold">
                                        <i class="fas fa-envelope me-2"></i>Update Email
                                    </h6>
                                    <form method="POST" action="{{ route('profile.updateEmail') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-medium text-dark">Email Address</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-at text-muted"></i>
                                                </span>
                                                <input type="email" name="email" 
                                                       class="form-control border-start-0 @error('email') is-invalid @enderror"
                                                       value="{{ old('email', $user->email) }}" 
                                                       placeholder="Enter your email address" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-outline-primary btn-sm px-4">
                                            <i class="fas fa-save me-1"></i>Update Email
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Security Settings Card --}}
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-lg h-100">
                            <div class="card-header bg-gradient text-white border-0 py-3" 
                                 style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <h5 class="mb-0 fw-semibold">Security Settings</h5>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                {{-- Change Password Section --}}
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3 fw-semibold">
                                        <i class="fas fa-key me-2"></i>Change Password
                                    </h6>
                                    <form method="POST" action="{{ route('profile.resetPassword') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-medium text-dark">Current Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-lock text-muted"></i>
                                                </span>
                                                <input type="password" name="current_password"
                                                       class="form-control border-start-0 @error('current_password') is-invalid @enderror" 
                                                       placeholder="Enter current password" required>
                                                @error('current_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-medium text-dark">New Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-key text-muted"></i>
                                                </span>
                                                <input type="password" name="new_password"
                                                       class="form-control border-start-0 @error('new_password') is-invalid @enderror" 
                                                       placeholder="Enter new password" required>
                                                @error('new_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-medium text-dark">Confirm New Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-check text-muted"></i>
                                                </span>
                                                <input type="password" name="new_password_confirmation" 
                                                       class="form-control border-start-0" 
                                                       placeholder="Confirm new password" required>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-outline-warning btn-sm px-4">
                                            <i class="fas fa-save me-1"></i>Update Password
                                        </button>
                                    </form>
                                </div>

                                <hr class="my-4">

                                {{-- Account Actions Section --}}
                                <div>
                                    <h6 class="text-muted mb-3 fw-semibold">
                                        <i class="fas fa-cog me-2"></i>Account Actions
                                    </h6>
                                    <div class="d-grid">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i>Sign Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Current User Info Display --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm bg-white bg-opacity-90">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="text-muted mb-2">Currently signed in as:</h6>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1 fw-semibold">{{ $user->name }}</h5>
                                                <p class="text-muted mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Member since {{ $user->created_at->format('M Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Custom CSS --}}
<style>
    .form-control:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
    }
    
    .input-group-text {
        transition: all 0.3s ease;
    }
    
    .form-control:focus + .input-group-text,
    .input-group-text:has(+ .form-control:focus) {
        border-color: #4facfe;
        background-color: #f8f9ff;
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .btn {
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .alert {
        border-radius: 12px;
    }
    
    .card {
        border-radius: 16px;
        overflow: hidden;
    }
    
    .card-header {
        border-radius: 0 !important;
    }
    
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
</style>
@endsection