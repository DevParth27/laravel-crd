<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            height: 100vh;
            width: 240px;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #0d6efd;
            padding-top: 60px;
            color: white;
        }

        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #0b5ed7;
            border-left: 4px solid #ffc107;
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 240px;
            right: 0;
            z-index: 1000;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column position-fixed">
        <div class="text-center mb-4">
            <h4 class="mt-2"><i class="fa-solid fa-bars"></i> Menu Bar</h4>
        </div>
        <a class="nav-link {{ request()->routeIs('excel.index') ? 'active' : '' }}" href="{{ route('excel.index') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
        @if(Auth::check() && Auth::user()->isAdmin())
        <a class="nav-link {{ request()->routeIs('excel.upload') ? 'active' : '' }}" href="{{ route('excel.upload') }}">
            <i class="fas fa-upload me-2"></i> Upload Excel
        </a>
        @endif
        <a class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}" href="{{ route('posts.index') }}">
            <i class="fas fa-database me-2"></i> Posts CRUD
        </a>
        @if(Auth::check() && Auth::user()->isAdmin())
        <a class="nav-link {{ request()->routeIs('api.index') ? 'active' : '' }}" href="{{ route('api.index') }}">
            <i class="fas fa-cloud-download-alt me-2"></i> Fetch Data
        </a>
        @endif
        <a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">
            <i class="fa-solid fa-user me-2"></i>  Profile
        </a>
        
        <!-- Add this at the bottom of the sidebar -->
        @if(Auth::check())
        <div class="mt-auto mb-3 text-center">
            <div class="bg-light text-primary p-2 rounded">
                <strong>Role: {{ ucfirst(Auth::user()->role) }}</strong>
            </div>
        </div>
        @endif
    </div>

    <!-- Navbar -->
    <!-- <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Laravel Excel DataTables</span>
        </div>
    </nav> -->

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')

</body>
</html>
