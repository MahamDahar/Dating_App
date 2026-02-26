@extends('layouts.admin')
@section('admincontent')
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
                            <div class="">
                                <ion-icon name="search-outline"></ion-icon>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dark-mode-icon" href="javascript:;">
                            <div class="mode-icon">
                                <ion-icon name="moon-outline"></ion-icon>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item dropdown dropdown-large dropdown-apps">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                            data-bs-toggle="dropdown">
                            <div class="">
                                <ion-icon name="apps-outline"></ion-icon>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                            <div class="row row-cols-3 g-3 p-3">
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-purple text-white">
                                        <ion-icon name="cart-outline"></ion-icon>
                                    </div>
                                    <div class="app-title">Orders</div>
                                </div>
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-info text-white">
                                        <ion-icon name="people-outline"></ion-icon>
                                    </div>
                                    <div class="app-title">Teams</div>
                                </div>
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-success text-white">
                                        <ion-icon name="shield-checkmark-outline"></ion-icon>
                                    </div>
                                    <div class="app-title">Tasks</div>
                                </div>
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-danger text-white">
                                        <ion-icon name="videocam-outline"></ion-icon>
                                    </div>
                                    <div class="app-title">Media</div>
                                </div>
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-warning text-white">
                                        <ion-icon name="file-tray-outline"></ion-icon>
                                    </div>
                                    <div class="app-title">Files</div>
                                </div>
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-branding text-white">
                                        <ion-icon name="notifications-outline"></ion-icon>
                                    </div>
                                    <div class="app-title">Alerts</div>
                                </div>
                            </div>
                        </div>
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
                                    <p class="msg-header-clear ms-auto">Marks all as read</p>
                                </div>
                            </a>
                            <div class="header-notifications-list">
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="notify text-primary">
                                            <ion-icon name="cart-outline"></ion-icon>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
                                                    ago</span></h6>
                                            <p class="msg-info">You have recived new orders</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="notify text-danger">
                                            <ion-icon name="person-outline"></ion-icon>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
                                                    ago</span></h6>
                                            <p class="msg-info">5 new user registered</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="notify text-success">
                                            <ion-icon name="document-outline"></ion-icon>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min
                                                    ago</span></h6>
                                            <p class="msg-info">The pdf files generated</p>
                                        </div>
                                    </div>
                                </a>

                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="notify text-info">
                                            <ion-icon name="checkmark-done-outline"></ion-icon>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">New Product Approved <span class="msg-time float-end">2
                                                    hrs ago</span></h6>
                                            <p class="msg-info">Your new product has approved</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="notify text-warning">
                                            <ion-icon name="send-outline"></ion-icon>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
                                                    ago</span></h6>
                                            <p class="msg-info">5.1 min avarage time response</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="notify text-danger">
                                            <ion-icon name="chatbox-ellipses-outline"></ion-icon>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs
                                                    ago</span></h6>
                                            <p class="msg-info">New customer comments recived</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="notify text-primary">
                                            <ion-icon name="albums-outline"></ion-icon>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
                                                    ago</span></h6>
                                            <p class="msg-info">24 new authors joined last week</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="notify text-success">
                                            <ion-icon name="shield-outline"></ion-icon>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5
                                                    hrs
                                                    ago</span></h6>
                                            <p class="msg-info">Successfully shipped your item</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="notify text-warning">
                                            <ion-icon name="cafe-outline"></ion-icon>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
                                                    ago</span></h6>
                                            <p class="msg-info">45% less alerts last 4 weeks</p>
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
                                <img src="assets/images/avatars/06.png" class="user-img" alt="">
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex flex-row align-items-center gap-2">
                                        <img src="assets/images/avatars/06.png" alt="" class="rounded-circle"
                                            width="54" height="54">
                                        <div class="">
                                            <h6 class="mb-0 dropdown-user-name">Jhon Deo</h6>
                                            <small class="mb-0 dropdown-user-designation text-secondary">UI
                                                Developer</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <ion-icon name="person-outline"></ion-icon>
                                        </div>
                                        <div class="ms-3"><span>Profile</span></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <ion-icon name="settings-outline"></ion-icon>
                                        </div>
                                        <div class="ms-3"><span>Setting</span></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <ion-icon name="speedometer-outline"></ion-icon>
                                        </div>
                                        <div class="ms-3"><span>Dashboard</span></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <ion-icon name="wallet-outline"></ion-icon>
                                        </div>
                                        <div class="ms-3"><span>Earnings</span></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <ion-icon name="cloud-download-outline"></ion-icon>
                                        </div>
                                        <div class="ms-3"><span>Downloads</span></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <ion-icon name="log-out-outline"></ion-icon>
                                        </div>
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


    <!-- start page content wrapper-->
    <div class="page-content-wrapper">
        <!-- start page content-->
        <div class="page-content">

            <!--start breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Tables</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 align-items-center">
                            <li class="breadcrumb-item"><a href="javascript:;"><ion-icon
                                        name="home-outline"></ion-icon></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Advance Tables</li>
                        </ol>
                    </nav>
                </div>
            
            </div>
            <!--end breadcrumb-->
            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">User Details</h5>
                        <form method="GET" action="{{ route('admin.index') }}"
                            class="ms-auto position-relative d-flex">

                            <input class="form-control me-2" type="text" name="search"
                                value="{{ request('search') }}" placeholder="Search user...">

                            <button class="btn btn-primary me-2" type="submit">
                                Search
                            </button>

                            @if (request('search'))
                                <a href="{{ route('admin.index') }}" class="btn btn-secondary">
                                    Clear
                                </a>
                            @endif

                        </form>


                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table align-middle">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Birthday</th>
                                    <th>Gender</th>
                                    <th>Looking for</th>
                                    <th>Martial Status</th>
                                    <th>City</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->birthday }}</td>
                                        <td>{{ $user->gender }}</td>
                                        <td>{{ $user->looking_for }}</td>
                                        <td>{{ $user->marital_status }}</td>
                                        <td>{{ $user->city }}</td>
                                        <td>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No Users Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>

                    </div>
                </div>
            </div>






        </div>
        <!-- end page content-->
    </div>



    <!-- JS Files-->
    <script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

    <!--plugins-->
    <script src="{{ asset('admin/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>

    <!-- Main JS-->
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>

    
@endsection
