<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - @yield('title', 'Dashboard') | Ollya</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        .admin-sidebar { min-height: 100vh; background: #1a1d29; }
        .admin-sidebar .nav-link { color: rgba(255,255,255,.8); padding: 0.75rem 1rem; }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.1); }
        .admin-header { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid g-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-auto admin-sidebar" style="width: 240px;">
                <div class="p-3 border-bottom border-secondary">
                    <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none fw-bold">Ollya Admin</a>
                </div>
                <nav class="nav flex-column py-2">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="{{ route('frontend.index') }}" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i> View Site
                    </a>
                    <hr class="border-secondary my-2">
                    <a class="nav-link" href="{{ route('frontend.login') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </nav>
            </div>
            <!-- Main content -->
            <div class="col flex-grow-1 bg-light">
                <header class="admin-header px-4 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@yield('page_heading', 'Dashboard')</h5>
                    <span class="text-muted small">{{ auth()->user()->name ?? 'Admin' }}</span>
                </header>
                <main class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
