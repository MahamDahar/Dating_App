<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('admin/assets/css/pace.min.css') }}" rel="stylesheet">
    <script src="{{ asset('admin/assets/js/pace.min.js') }}"></script>

    <link href="{{ asset('admin/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet">

    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/icons.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <link href="{{ asset('admin/assets/css/dark-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/semi-dark.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/header-colors.css') }}" rel="stylesheet">

    <title>Fobia</title>

    <style>
        /* Settings submenu styling */
        .has-arrow.settings-parent>a {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .settings-submenu {
            list-style: none;
            padding: 0;
        }

        .settings-submenu li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px 9px 52px;
            font-size: 13px;
            color: #6c757d;
            text-decoration: none;
            transition: all .2s;
            border-left: 3px solid transparent;
        }

        .settings-submenu li a:hover {
            color: #0f0f13;
            background: #f5f5f5;
            border-left-color: #7c3aed;
        }

        .settings-submenu li a ion-icon {
            font-size: 15px;
            flex-shrink: 0;
        }

        .settings-submenu .divider {
            height: 1px;
            background: #f0f0f0;
            margin: 6px 16px;
        }

        .settings-submenu .danger-item a {
            color: #e74c3c;
        }

        .settings-submenu .danger-item a:hover {
            color: #c0392b;
            background: #fdf0ef;
            border-left-color: #e74c3c;
        }

        .settings-submenu .section-label {
            padding: 8px 16px 4px 52px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #aaa;
        }

        /* Nested notification submenu indentation */
        .notification-submenu li a {
            padding-left: 70px;
        }

        .notification-submenu .section-label {
            padding-left: 70px;
        }

        /* ── Animated arrow ── */
        .has-arrow::after {
            content: '' !important;
            display: inline-block !important;
            width: 8px !important;
            height: 8px !important;
            border-right: 2px solid #888 !important;
            border-bottom: 2px solid #888 !important;
            transform: rotate(45deg) !important;
            transition: transform 0.3s ease, border-color 0.3s ease !important;
            float: right !important;
            margin-top: 5px !important;
            margin-left: auto !important;
            border-top: none !important;
            border-left: none !important;
        }

        .has-arrow[aria-expanded="true"]::after {
            transform: rotate(-135deg) !important;
            margin-top: 9px !important;
            border-color: #7c3aed !important;
        }

        /* metismenu active state */
        .metismenu .mm-active>a.has-arrow::after {
            transform: rotate(-135deg) !important;
            margin-top: 9px !important;
            border-color: #7c3aed !important;
        }

        /* ── Sidebar Scrollable ── */
        .sidebar-wrapper {
            overflow-y: auto !important;
            overflow-x: hidden !important;
            max-height: 100vh !important;
            position: fixed !important;
        }

        .sidebar-wrapper::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-wrapper::-webkit-scrollbar-thumb {
            background: #7c3aed;
            border-radius: 10px;
        }

        .sidebar-wrapper::-webkit-scrollbar-thumb:hover {
            background: #5b21b6;
        }

        /* Fix notification toggle arrow */
        .notification-toggle.has-arrow::after {
            float: none !important;
            display: inline-block !important;
            margin-top: 0 !important;
            margin-left: 8px !important;
            vertical-align: middle !important;
            position: relative !important;
            top: -1px !important;
        }
    </style>
</head>

<body>

    <div class="wrapper">

        {{-- ── Sidebar ── --}}
        <aside class="sidebar-wrapper">
            <div class="sidebar-header">
                <div>
                    <img src="{{ asset('admin/assets/images/logo-icon-2.png') }}" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">Fobia</h4>
                </div>
            </div>

            <ul class="metismenu" id="menu">

                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('user.dashboard') }}">
                        <div class="parent-icon"><ion-icon name="home-outline"></ion-icon></div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>

                {{-- My Profile --}}
                <li>
                    <a href="{{ route('user.profile') }}">
                        <div class="parent-icon"><ion-icon name="person-circle-outline"></ion-icon></div>
                        <div class="menu-title">My Profile</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.marriage.index') }}">
                        <div class="parent-icon"><ion-icon name="heart-circle-outline"></ion-icon></div>
                        <div class="menu-title">Marriage</div>
                    </a>
                </li>
                {{-- Discover --}}
                <li>
                    <a href="{{ route('user.discover') }}">
                        <div class="parent-icon"><ion-icon name="compass-outline"></ion-icon></div>
                        <div class="menu-title">Discover</div>
                    </a>
                </li>

                {{-- Who Viewed Me --}}
                <li>
                    <a href="{{ route('user.who-viewed-me') }}">
                        <div class="parent-icon" style="position:relative">
                            <ion-icon name="eye-outline"></ion-icon>
                            @php
                                $viewCount = 0;
                                try {
                                    $viewCount = \App\Models\ProfileView::where('viewed_id', Auth::id())
                                        ->where('seen', false)
                                        ->count();
                                } catch (\Exception $e) {
                                }
                            @endphp
                            @if ($viewCount > 0)
                                <span
                                    style="position:absolute;top:-6px;right:-6px;background:#7c3aed;color:white;font-size:9px;font-weight:800;min-width:16px;height:16px;border-radius:20px;display:flex;align-items:center;justify-content:center;padding:0 4px;line-height:1;">
                                    {{ $viewCount > 99 ? '99+' : $viewCount }}
                                </span>
                            @endif
                        </div>
                        <div class="menu-title">
                            Who Viewed Me
                            @if ($viewCount > 0)
                                <span
                                    style="background:#7c3aed;color:white;font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;margin-left:6px;">
                                    {{ $viewCount > 99 ? '99+' : $viewCount }}
                                </span>
                            @endif
                        </div>
                    </a>
                </li>

                {{-- Chat --}}
                <li>
                    <a href="{{ route('user.chat.index') }}">
                        <div class="parent-icon"><ion-icon name="chatbubbles-outline"></ion-icon></div>
                        <div class="menu-title">Chat</div>
                    </a>
                </li>

                {{-- Activity --}}
                <li>
                    <a href="{{ route('user.activity') }}">
                        <div class="parent-icon"><ion-icon name="pulse-outline"></ion-icon></div>
                        <div class="menu-title">Activity</div>
                    </a>
                </li>

                {{-- Blocked Users --}}
                <li>
                    <a href="{{ route('user.blocked.index') }}">
                        <div class="parent-icon"><ion-icon name="ban-outline"></ion-icon></div>
                        <div class="menu-title">Blocked Users</div>
                    </a>
                </li>

                {{-- Settings with submenu --}}
                <li class="settings-parent">
                    <a href="javascript:;" class="has-arrow settings-toggle" id="settingsToggle" aria-expanded="false">
                        <div class="parent-icon"><ion-icon name="settings-outline"></ion-icon></div>
                        <div class="menu-title">Settings</div>
                    </a>

                    <ul class="settings-submenu" id="settingsSubmenu"
                        style="display:none; list-style:none; padding-left:1rem;">

                        <li>
                            <a href="{{ route('user.settings.profile') }}">
                                <ion-icon name="person-outline"></ion-icon>
                                Profile Settings
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('user.settings.privacy') }}">
                                <ion-icon name="lock-closed-outline"></ion-icon>
                                Privacy Settings
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('user.settings.visibility') }}">
                                <ion-icon name="eye-off-outline"></ion-icon>
                                Profile Visibility
                            </a>
                        </li>

                        {{-- Notification Settings with nested submenu --}}
                        <li class="notifications-parent">
                            <a href="javascript:;" class="has-arrow notification-toggle" id="notificationToggle"
                                aria-expanded="false">
                                <ion-icon name="notifications-outline"></ion-icon>
                                Notification Settings
                            </a>

                            <ul class="settings-submenu notification-submenu" id="notificationSubmenu"
                                style="display:none;">
                                <li>
                                    <div class="section-label">Push Notifications</div>
                                </li>
                                <li>
                                    <a href="{{ route('user.settings.notifications.push') }}">
                                        <ion-icon name="phone-portrait-outline"></ion-icon>
                                        Push Notifications
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <div class="section-label">In-App Notifications</div>
                                </li>
                                <li>
                                    <a href="{{ route('user.settings.notifications.inapp') }}">
                                        <ion-icon name="apps-outline"></ion-icon>
                                        In-App Notifications
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <div class="section-label">Email Notifications</div>
                                </li>
                                <li>
                                    <a href="{{ route('user.settings.notifications.email') }}">
                                        <ion-icon name="mail-outline"></ion-icon>
                                        Email Notifications
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <div class="section-label">Security Alerts</div>
                                </li>
                                <li>
                                    <a href="{{ route('user.settings.notifications.security') }}">
                                        <ion-icon name="shield-checkmark-outline"></ion-icon>
                                        Security Alerts
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('user.settings.password') }}">
                                <ion-icon name="key-outline"></ion-icon>
                                Change Password
                            </a>
                        </li>

                        <li class="divider"></li>
                        <li>
                            <div class="section-label">Account</div>
                        </li>

                        <li>
                            <a href="{{ route('user.settings.deactivate') }}">
                                <ion-icon name="pause-circle-outline"></ion-icon>
                                Deactivate for 15 Days
                            </a>
                        </li>

                        <li class="danger-item">
                            <a href="{{ route('user.settings.delete') }}">
                                <ion-icon name="trash-outline"></ion-icon>
                                Delete Account
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li class="danger-item">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <ion-icon name="log-out-outline"></ion-icon>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display:none;">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </li>

            </ul>
        </aside>
        {{-- ── End Sidebar ── --}}

        {{-- ── Top Header ── --}}
        <header class="top-header">
            <nav class="navbar navbar-expand gap-3">
                <div class="toggle-icon">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <form class="searchbar">
                    <div class="position-absolute top-50 translate-middle-y search-icon ms-3">
                        <ion-icon name="search-outline"></ion-icon>
                    </div>
                    <input class="form-control" type="text" placeholder="Search for anything">
                    <div class="position-absolute top-50 translate-middle-y search-close-icon">
                        <ion-icon name="close-outline"></ion-icon>
                    </div>
                </form>

                <div class="top-navbar-right ms-auto">
                    <ul class="navbar-nav align-items-center">

                        <li class="nav-item">
                            <a class="nav-link mobile-search-button" href="javascript:;">
                                <ion-icon name="search-outline"></ion-icon>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link dark-mode-icon" href="javascript:;">
                                <div class="mode-icon">
                                    <ion-icon name="moon-outline"></ion-icon>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item dropdown dropdown-large">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                data-bs-toggle="dropdown">
                                <div class="position-relative">
                                    <span class="notify-badge">8</span>
                                    <ion-icon name="notifications-outline"></ion-icon>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:;">
                                    <div class="msg-header">
                                        <p class="msg-header-title">Notifications</p>
                                        <p class="msg-header-clear ms-auto">Mark all as read</p>
                                    </div>
                                </a>
                                <div class="header-notifications-list">
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex align-items-center">
                                            <div class="notify text-primary"><ion-icon
                                                    name="heart-outline"></ion-icon></div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name">New Match! <span class="msg-time float-end">2 min
                                                        ago</span></h6>
                                                <p class="msg-info">Someone liked your profile</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex align-items-center">
                                            <div class="notify text-success"><ion-icon
                                                    name="chatbubble-outline"></ion-icon></div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name">New Message <span class="msg-time float-end">5
                                                        min ago</span></h6>
                                                <p class="msg-info">You have a new message</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <a href="javascript:;">
                                    <div class="text-center msg-footer">View All Notifications</div>
                                </a>
                            </div>
                        </li>

                        <li class="nav-item dropdown dropdown-user-setting">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                data-bs-toggle="dropdown">
                                <div class="user-setting">
                                    <img src="{{ asset('admin/assets/images/avatars/06.png') }}" class="user-img"
                                        alt="">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex flex-row align-items-center gap-2">
                                            <img src="{{ asset('admin/assets/images/avatars/06.png') }}"
                                                alt="" class="rounded-circle" width="54" height="54">
                                            <div>
                                                <h6 class="mb-0 dropdown-user-name">{{ Auth::user()->name ?? 'User' }}
                                                </h6>
                                                <small
                                                    class="mb-0 dropdown-user-designation text-secondary">{{ Auth::user()->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                                        <div class="d-flex align-items-center">
                                            <ion-icon name="person-outline"></ion-icon>
                                            <div class="ms-3"><span>My Profile</span></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.settings.profile') }}">
                                        <div class="d-flex align-items-center">
                                            <ion-icon name="settings-outline"></ion-icon>
                                            <div class="ms-3"><span>Settings</span></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                        <div class="d-flex align-items-center">
                                            <ion-icon name="speedometer-outline"></ion-icon>
                                            <div class="ms-3"><span>Dashboard</span></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <div class="d-flex align-items-center">
                                            <ion-icon name="log-out-outline"></ion-icon>
                                            <div class="ms-3"><span>Logout</span></div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>
        {{-- ── End Top Header ── --}}

        {{-- ── Page Content ── --}}
        <div class="page-wrapper">
            <div class="page-content">
                @yield('usercontent')
            </div>
        </div>
        {{-- ── End Page Content ── --}}

        <footer class="footer">
            <div class="footer-text">
                Copyright © 2023. All right reserved.
            </div>
        </footer>

        <a href="javascript:;" class="back-to-top">
            <ion-icon name="arrow-up-outline"></ion-icon>
        </a>

        <div class="overlay nav-toggle-icon"></div>

    </div>

    <script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script src="{{ asset('admin/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/easyPieChart/jquery.easypiechart.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/index.js') }}"></script>
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>

    <script>
        // Settings dropdown
        document.getElementById('settingsToggle').addEventListener('click', function() {
            const submenu = document.getElementById('settingsSubmenu');
            const isOpen = submenu.style.display === 'block';
            submenu.style.display = isOpen ? 'none' : 'block';
            this.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        });

        // Notification dropdown inside Settings
        const notificationToggle = document.getElementById('notificationToggle');
        if (notificationToggle) {
            notificationToggle.addEventListener('click', function() {
                const submenu = document.getElementById('notificationSubmenu');
                const isOpen = submenu.style.display === 'block';
                submenu.style.display = isOpen ? 'none' : 'block';
                this.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            });
        }
    </script>
</body>

</html>
