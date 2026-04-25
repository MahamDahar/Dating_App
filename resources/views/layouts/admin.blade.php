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

  <style>
    .sidebar-wrapper {
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      width: 260px !important;
      height: 100vh !important;
      overflow-y: auto !important;
      overflow-x: hidden !important;
      z-index: 11 !important;
    }
    .sidebar-wrapper::-webkit-scrollbar { width: 4px !important; }
    .sidebar-wrapper::-webkit-scrollbar-track { background: transparent !important; }
    .sidebar-wrapper::-webkit-scrollbar-thumb { background-color: rgba(255,255,255,0.25) !important; border-radius: 4px !important; }
    .sidebar-wrapper { scrollbar-width: thin !important; scrollbar-color: rgba(255,255,255,0.25) transparent !important; }
  </style>

  <title>Fobia - Admin Panel</title>
</head>

<body>
  <div class="wrapper">

    <!--start sidebar -->
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

        {{-- ── MAIN ── --}}
        <li class="menu-label">Main</li>

        <li>
          <a href="{{ route('admin.dashboard') }}">
            <div class="parent-icon"><ion-icon name="home-outline"></ion-icon></div>
            <div class="menu-title">Dashboard</div>
          </a>
        </li>

        {{-- ── USER MANAGEMENT ── --}}
        <li class="menu-label">User Management</li>

        <li>
          <a href="{{ route('admin.index') }}">
            <div class="parent-icon"><ion-icon name="people-outline"></ion-icon></div>
            <div class="menu-title">All Users</div>
          </a>
        </li>

        <li>
          <a href="{{route('admin.verification.index')}}">
            <div class="parent-icon"><ion-icon name="shield-checkmark-outline"></ion-icon></div>
            <div class="menu-title">Verification</div>
          </a>
        </li>

        <li>
          <a href="{{route('admin.matches.index')}}">
            <div class="parent-icon"><ion-icon name="heart-outline"></ion-icon></div>
            <div class="menu-title">Matches</div>
          </a>
        </li>

        <li>
          <a href="{{route('admin.messages.index')}}">
            <div class="parent-icon"><ion-icon name="chatbubbles-outline"></ion-icon></div>
            <div class="menu-title">Messages Monitoring</div>
          </a>
        </li>

        {{-- Proposals sent/received by users --}}
        <li>
          <a href="{{route('admin.proposals.index')}}">
            <div class="parent-icon"><ion-icon name="paper-plane-outline"></ion-icon></div>
            <div class="menu-title">Proposals</div>
          </a>
        </li>

        {{-- ── MODERATION ── --}}
        <li class="menu-label">Moderation</li>


        <li>
          <a href="{{route('admin.blocked_users.index')}}">
            <div class="parent-icon"><ion-icon name="ban-outline"></ion-icon></div>
            <div class="menu-title">Blocked Users</div>
          </a>
        </li>

        <li>
          <a href="{{route('admin.content.index')}}">
            <div class="parent-icon"><ion-icon name="eye-outline"></ion-icon></div>
            <div class="menu-title">Content Moderation</div>
          </a>
        </li>

        <li>
          <a href="{{route('admin.support_tickets.index')}}">
            <div class="parent-icon"><ion-icon name="ticket-outline"></ion-icon></div>
            <div class="menu-title">Support Tickets</div>
          </a>
        </li>

        {{-- User reported problems --}}
        <li>
          <a href="{{route('admin.problem_reports.index')}}">
            <div class="parent-icon"><ion-icon name="flag-outline"></ion-icon></div>
            <div class="menu-title">Reports</div>
          </a>
        </li>

        {{-- ── FINANCE ── --}}
        <li class="menu-label">Finance</li>

        <li>
          <a href="{{ route('admin.subscriptions.index') }}">
            <div class="parent-icon"><ion-icon name="diamond-outline"></ion-icon></div>
            <div class="menu-title">Subscriptions</div>
          </a>
        </li>

        <li>
          <a href="{{ route('admin.payments.index') }}">
            <div class="parent-icon"><ion-icon name="card-outline"></ion-icon></div>
            <div class="menu-title">Payments</div>
          </a>
        </li>

        {{-- ── ENGAGEMENT ── --}}
        <li class="menu-label">Engagement</li>

        <li>
          <a href="{{ route('admin.notifications.index') }}">
            <div class="parent-icon"><ion-icon name="notifications-outline"></ion-icon></div>
            <div class="menu-title">Notifications</div>
          </a>
        </li>

        <li>
          <a href="{{ route('admin.marketing.index') }}">
            <div class="parent-icon"><ion-icon name="megaphone-outline"></ion-icon></div>
            <div class="menu-title">Marketing</div>
          </a>
        </li>

        <li>
          <a href="{{ route('admin.analytics.index') }}">
            <div class="parent-icon"><ion-icon name="bar-chart-outline"></ion-icon></div>
            <div class="menu-title">Analytics</div>
          </a>
        </li>

        {{-- ── CONTENT ── --}}
        <li class="menu-label">Content</li>

        {{-- FAQs manager --}}
        <li>
          <a href="{{ route('admin.faqs.index') }}">
            <div class="parent-icon"><ion-icon name="help-circle-outline"></ion-icon></div>
            <div class="menu-title">FAQs</div>
          </a>
        </li>

        {{-- Privacy Policy & Terms editor --}}
        <li>
          <a href="{{ route('admin.legal.index') }}">
            <div class="parent-icon"><ion-icon name="document-text-outline"></ion-icon></div>
            <div class="menu-title">Privacy & Terms</div>
          </a>
        </li>

        {{-- ── SYSTEM ── --}}
        <li class="menu-label">System</li>

        <li>
          <a href="{{ route('admin.management.index') }}">
            <div class="parent-icon"><ion-icon name="people-circle-outline"></ion-icon></div>
            <div class="menu-title">Admin Management</div>
          </a>
        </li>

        <li>
          <a href="{{ route('admin.settings') }}">
            <div class="parent-icon"><ion-icon name="settings-outline"></ion-icon></div>
            <div class="menu-title">Settings</div>
          </a>
        </li>

        <li>
          <a href="{{ route('logout') }}"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <div class="parent-icon"><ion-icon name="log-out-outline"></ion-icon></div>
            <div class="menu-title">Logout</div>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
          </form>
        </li>

      </ul>
    </aside>
    <!--end sidebar -->

    <!--start top header-->
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
                <div class=""><ion-icon name="search-outline"></ion-icon></div>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link dark-mode-icon" href="javascript:;">
                <div class="mode-icon"><ion-icon name="moon-outline"></ion-icon></div>
              </a>
            </li>

            <li class="nav-item dropdown dropdown-large dropdown-apps">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
                <div class=""><ion-icon name="apps-outline"></ion-icon></div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                <div class="row row-cols-3 g-3 p-3">
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-purple text-white"><ion-icon name="cart-outline"></ion-icon></div>
                    <div class="app-title">Orders</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-info text-white"><ion-icon name="people-outline"></ion-icon></div>
                    <div class="app-title">Teams</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-success text-white"><ion-icon name="shield-checkmark-outline"></ion-icon></div>
                    <div class="app-title">Tasks</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-danger text-white"><ion-icon name="videocam-outline"></ion-icon></div>
                    <div class="app-title">Media</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-warning text-white"><ion-icon name="file-tray-outline"></ion-icon></div>
                    <div class="app-title">Files</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-branding text-white"><ion-icon name="notifications-outline"></ion-icon></div>
                    <div class="app-title">Alerts</div>
                  </div>
                </div>
              </div>
            </li>

            <li class="nav-item dropdown dropdown-large">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
                <div class="position-relative">
                  <span class="notify-badge">8</span>
                  <ion-icon name="notifications-outline"></ion-icon>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a href="javascript:;">
                  <div class="msg-header">
                    <p class="msg-header-title">Notifications</p>
                    <p class="msg-header-clear ms-auto">Marks all as read</p>
                  </div>
                </a>
                <div class="header-notifications-list">
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-primary"><ion-icon name="cart-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Orders <span class="msg-time float-end">2 min ago</span></h6>
                        <p class="msg-info">You have recived new orders</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-danger"><ion-icon name="person-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Customers <span class="msg-time float-end">14 Sec ago</span></h6>
                        <p class="msg-info">5 new user registered</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-warning"><ion-icon name="flag-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Report <span class="msg-time float-end">5 min ago</span></h6>
                        <p class="msg-info">A user has been reported</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-success"><ion-icon name="shield-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">Verification Request <span class="msg-time float-end">1 hr ago</span></h6>
                        <p class="msg-info">New profile verification pending</p>
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
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
                <div class="user-setting">
                  <img src="{{ asset('admin/assets/images/avatars/06.png') }}" class="user-img" alt="">
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex flex-row align-items-center gap-2">
                      <img src="{{ asset('admin/assets/images/avatars/06.png') }}" alt="" class="rounded-circle" width="54" height="54">
                      <div class="">
                        <h6 class="mb-0 dropdown-user-name">Admin</h6>
                        <small class="mb-0 dropdown-user-designation text-secondary">Super Admin</small>
                      </div>
                    </div>
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <ion-icon name="person-outline"></ion-icon>
                      <div class="ms-3"><span>Profile</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('admin.settings') }}">
                    <div class="d-flex align-items-center">
                      <ion-icon name="settings-outline"></ion-icon>
                      <div class="ms-3"><span>Settings</span></div>
                    </div>
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
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
    <!--end top header-->

    @yield('admincontent')

    <footer class="footer">
      <div class="footer-text">Copyright © 2023. All right reserved.</div>
    </footer>

    <a href="javascript:;" class="back-to-top">
      <ion-icon name="arrow-up-outline"></ion-icon>
    </a>

    <div class="switcher-body">
      <button class="btn btn-primary btn-switcher shadow-sm" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
        <ion-icon name="color-palette-outline" class="me-0"></ion-icon>
      </button>
      <div class="offcanvas offcanvas-end shadow border-start-0 p-2" data-bs-scroll="true" data-bs-backdrop="false"
        tabindex="-1" id="offcanvasScrolling">
        <div class="offcanvas-header border-bottom">
          <h5 class="offcanvas-title">Theme Customizer</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
          <h6 class="mb-0">Theme Variation</h6>
          <hr>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="LightTheme" value="option1" checked>
            <label class="form-check-label" for="LightTheme">Light</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="DarkTheme" value="option2">
            <label class="form-check-label" for="DarkTheme">Dark</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="SemiDark" value="option3">
            <label class="form-check-label" for="SemiDark">Semi Dark</label>
          </div>
          <hr />
          <h6 class="mb-0">Header Colors</h6>
          <hr />
          <div class="header-colors-indigators">
            <div class="row row-cols-auto g-3">
              <div class="col"><div class="indigator headercolor1" id="headercolor1"></div></div>
              <div class="col"><div class="indigator headercolor2" id="headercolor2"></div></div>
              <div class="col"><div class="indigator headercolor3" id="headercolor3"></div></div>
              <div class="col"><div class="indigator headercolor4" id="headercolor4"></div></div>
              <div class="col"><div class="indigator headercolor5" id="headercolor5"></div></div>
              <div class="col"><div class="indigator headercolor6" id="headercolor6"></div></div>
              <div class="col"><div class="indigator headercolor7" id="headercolor7"></div></div>
              <div class="col"><div class="indigator headercolor8" id="headercolor8"></div></div>
            </div>
          </div>
        </div>
      </div>
    </div>

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

</body>
</html>