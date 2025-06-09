<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel App') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --secondary-color: #f72585;
            --dark-color: #212529;
            --light-color: #f8f9fa;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #ef233c;
            --sidebar-width: 250px;
            --header-height: 60px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            padding-top: var(--header-height);
            color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.2s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link.active {
            border-left: 4px solid var(--secondary-color);
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 15px;
        }
        
        .user-role-badge {
            background-color: rgba(255, 255, 255, 0.9);
            color: var(--primary-color);
            border-radius: 30px;
            padding: 8px 15px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: calc(var(--header-height) + 20px) 30px 30px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        /* Header Styles */
        .app-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 30px;
            transition: all 0.3s ease;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Alert Styles */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        
        /* Button Styles */
        .btn {
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .app-header {
                left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="btn btn-primary d-lg-none position-fixed" 
            style="top: 10px; left: 10px; z-index: 1050;" 
            id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <div class="sidebar-header text-center mb-4">
            <h4 class="mt-2 fw-bold">
                <i class="fa-solid fa-chart-line me-2"></i> 
                {{ config('app.name', 'Data App') }}
            </h4>
        </div>
        
        <div class="px-3 py-2">
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('excel.index') ? 'active' : '' }}" 
               href="{{ route('excel.index') }}">
                <i class="fas fa-tachometer-alt me-3"></i> 
                <span>Dashboard</span>
            </a>
            
            @if(Auth::check() && Auth::user()->isAdmin())
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('excel.upload') ? 'active' : '' }}" 
               href="{{ route('excel.upload') }}">
                <i class="fas fa-upload me-3"></i> 
                <span>Upload Excel</span>
            </a>
            @endif
            
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('posts.index') ? 'active' : '' }}" 
               href="{{ route('posts.index') }}">
                <i class="fas fa-database me-3"></i> 
                <span>Posts CRUD</span>
            </a>
            
            @if(Auth::check() && Auth::user()->isAdmin())
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('api.index') ? 'active' : '' }}" 
               href="{{ route('api.index') }}">
                <i class="fas fa-cloud-download-alt me-3"></i> 
                <span>Fetch Data</span>
            </a>
            @endif
            
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('profile.show') ? 'active' : '' }}" 
               href="{{ route('profile.show') }}">
                <i class="fa-solid fa-user me-3"></i> 
                <span>Profile</span>
            </a>
        </div>
        
        <!-- User Role Badge -->
        @if(Auth::check())
        <div class="mt-auto sidebar-footer text-center pb-4">
            <div class="user-role-badge d-inline-block">
                <i class="fas fa-user-tag me-2"></i>
                {{ ucfirst(Auth::user()->role) }}
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-sm btn-light">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Header
    <header class="app-header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h5 class="mb-0 fw-bold text-primary">@yield('page-title', 'Dashboard')</h5>
        </div>
    </header> -->

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const appHeader = document.querySelector('.app-header');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = sidebarToggle.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    }
                });
            }
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>