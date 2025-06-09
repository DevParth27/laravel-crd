@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="profile-page">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                
                {{-- Profile Header --}}
                <div class="profile-header mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                            <div class="profile-avatar-container">
                                <div class="profile-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="profile-name mb-1">{{ $user->name }}</h2>
                            <p class="profile-email mb-2">{{ $user->email }}</p>
                            <div class="profile-meta">
                                <span><i class="fas fa-calendar-alt"></i> Member since {{ $user->created_at->format('M Y') }}</span>
                                <span><i class="fas fa-user-tag"></i> {{ ucfirst($user->role ?? 'User') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <div class="alert-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="alert-content">
                                {{ session('success') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Profile Navigation --}}
                <ul class="nav nav-tabs profile-tabs mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" 
                                type="button" role="tab" aria-controls="personal" aria-selected="true">
                            <i class="fas fa-user-edit me-2"></i>Personal Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" 
                                type="button" role="tab" aria-controls="security" aria-selected="false">
                            <i class="fas fa-shield-alt me-2"></i>Security
                        </button>
                    </li>
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content profile-tab-content" id="profileTabsContent">
                    {{-- Personal Info Tab --}}
                    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <div class="card">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Personal Information</h5>
                                
                                {{-- Update Name Form --}}
                                <form method="POST" action="{{ route('profile.updateName') }}" class="mb-5">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="name" class="form-label">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <input type="text" id="name" name="name" 
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $user->name) }}" 
                                                placeholder="Enter your full name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-text">This name will be displayed on your profile</div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            @if(auth()->user()->role === 'admin')
                                                <input type="email" id="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    value="{{ old('email', $user->email) }}"
                                                    placeholder="Enter email address">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @else
                                                <input type="email" class="form-control" 
                                                    value="{{ $user->email }}" 
                                                    readonly disabled>
                                            @endif
                                        </div>         
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Security Tab --}}
                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                        <div class="card">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Security Settings</h5>
                                
                                {{-- Change Password Form --}}
                                <form method="POST" action="{{ route('profile.resetPassword') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" id="current_password" name="current_password"
                                                class="form-control @error('current_password') is-invalid @enderror" 
                                                placeholder="Enter current password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-key"></i>
                                            </span>
                                            <input type="password" id="new_password" name="new_password"
                                                class="form-control @error('new_password') is-invalid @enderror" 
                                                placeholder="Enter new password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                                                class="form-control" 
                                                placeholder="Confirm new password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-shield-alt me-2"></i>Update Password
                                        </button>
                                    </div>
                                </form>
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
    /* Profile Page Styles */
    .profile-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }
    
    /* Profile Header */
    .profile-header {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    
    .profile-avatar-container {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .profile-avatar-upload {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #fff;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .profile-avatar-upload:hover {
        background: #f8f9fa;
        transform: scale(1.1);
    }
    
    .profile-name {
        font-weight: 700;
        color: #333;
    }
    
    .profile-email {
        color: #6c757d;
        font-size: 0.95rem;
    }
    
    .profile-meta {
        display: flex;
        gap: 16px;
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .profile-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    /* Alert Styling */
    .alert {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
        margin-right: 12px;
    }
    
    /* Tabs Styling */
    .profile-tabs {
        border-bottom: none;
        gap: 8px;
    }
    
    .profile-tabs .nav-link {
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-weight: 500;
        color: #495057;
        transition: all 0.2s ease;
    }
    
    .profile-tabs .nav-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }
    
    .profile-tabs .nav-link.active {
        background-color: #0d6efd;
        color: white;
    }
    
    /* Card Styling */
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .card-title {
        font-weight: 600;
        color: #333;
    }
    
    /* Form Styling */
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 16px;
        border-color: #dee2e6;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }
    
    .input-group .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    .input-group .input-group-text {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    /* Button Styling */
    .btn {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-primary:hover {
        background: #0b5ed7;
        border-color: #0a58ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
    }
    
    .btn-outline-primary {
        color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .profile-header {
            padding: 16px;
        }
        
        .profile-tabs .nav-link {
            padding: 8px 12px;
            font-size: 0.9rem;
        }
        
        .card-body {
            padding: 16px;
        }
    }
</style>

{{-- Custom JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>
@endsection