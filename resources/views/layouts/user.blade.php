<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Dashboard - @yield('title', 'Dashboard') | Ollya</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        .user-sidebar { min-height: 100vh; background: linear-gradient(180deg, #2d3561 0%, #1a1d29 100%); }
        .user-sidebar .nav-link { color: rgba(255,255,255,.85); padding: 0.75rem 1rem; }
        .user-sidebar .nav-link:hover, .user-sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.12); }
        .user-header { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid g-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-auto user-sidebar" style="width: 240px;">
                <div class="p-3 border-bottom border-secondary">
                    <a href="{{ route('user.dashboard') }}" class="text-white text-decoration-none fw-bold">Ollya</a>
                    <small class="d-block text-white-50">My Account</small>
                </div>
                <nav class="nav flex-column py-2">
                    <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="{{ route('frontend.index') }}">
                        <i class="fas fa-home me-2"></i> Home
                    </a>
                    <a class="nav-link" href="{{ route('frontend.membership') }}">
                        <i class="fas fa-crown me-2"></i> Membership
                    </a>
                    <a class="nav-link" href="{{ route('frontend.community') }}">
                        <i class="fas fa-users me-2"></i> Community
                    </a>
                    <a class="nav-link" href="{{ route('frontend.contact') }}">
                        <i class="fas fa-envelope me-2"></i> Contact
                    </a>
                    <hr class="border-secondary my-2">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('user-logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                    <form id="user-logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </nav>
            </div>
            <!-- Main content -->
            <div class="col flex-grow-1 bg-light">
                <header class="user-header px-4 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">@yield('page_heading', 'Dashboard')</h5>
                    <span class="text-muted small">{{ auth()->user()->name ?? 'User' }}</span>
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
