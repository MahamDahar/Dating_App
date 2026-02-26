@extends('layouts.admin')

@section('admincontent')
    <!--start top header-->
    {{-- <header class="top-header">
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

                    <li class="nav-item dropdown dropdown-user-setting">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                            data-bs-toggle="dropdown">
                            <div class="user-setting">
                                <img src="{{ asset('assets/images/avatars/06.png') }}" class="user-img" alt="">
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex flex-row align-items-center gap-2">
                                        <img src="{{ asset('assets/images/avatars/06.png') }}" alt=""
                                            class="rounded-circle" width="54" height="54">
                                        <div class="">
                                            <h6 class="mb-0 dropdown-user-name">{{ $user->name }}</h6>
                                            <small
                                                class="mb-0 dropdown-user-designation text-secondary">{{ ucfirst($user->role) }}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.edit') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <ion-icon name="person-outline"></ion-icon>
                                        </div>
                                        <div class="ms-3"><span>Profile</span></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.settings') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <ion-icon name="settings-outline"></ion-icon>
                                        </div>
                                        <div class="ms-3"><span>Settings</span></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <ion-icon name="speedometer-outline"></ion-icon>
                                        </div>
                                        <div class="ms-3"><span>Dashboard</span></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <ion-icon name="log-out-outline"></ion-icon>
                                            </div>
                                            <div class="ms-3"><span>Logout</span></div>
                                        </div>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header> --}}
    <!--end top header-->

    <!-- start page content wrapper -->
    <div class="page-content-wrapper">
        <div class="page-content">

            <!--start breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Profile</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 align-items-center">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    <ion-icon name="home-outline"></ion-icon>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <ion-icon name="person-circle-outline" class="align-middle"></ion-icon>
                                Edit My Profile
                            </h5>
                        </div>
                        <div class="card-body p-4">

                            <!-- Success Message -->
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <ion-icon name="checkmark-circle-outline" class="align-middle me-2"></ion-icon>
                                    <strong>Success!</strong> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ion-icon name="alert-circle-outline" class="align-middle me-2"></ion-icon>
                                    <strong>Oops!</strong> Please fix the following errors:
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.update') }}" method="POST" class="needs-validation"
                                novalidate>
                                @csrf
                                @method('PUT')

                                <!-- Account Information -->
                                <div class="mb-4">
                                    <h6 class="mb-3 text-primary border-bottom pb-2">
                                        <ion-icon name="shield-checkmark-outline" class="align-middle"></ion-icon>
                                        Account Information
                                    </h6>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                Username <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">@</span>
                                                <input type="text" name="username"
                                                    class="form-control @error('username') is-invalid @enderror"
                                                    value="{{ old('username', $user->username) }}" required>
                                                @error('username')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                Full Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                Email Address <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                Role <span class="text-danger">*</span>
                                            </label>
                                            <select name="role" class="form-select @error('role') is-invalid @enderror"
                                                required>
                                                <option value="user"
                                                    {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User
                                                </option>
                                                <option value="admin"
                                                    {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                                </option>
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Change -->
                                <div class="mb-4">
                                    <h6 class="mb-3 text-primary border-bottom pb-2">
                                        <ion-icon name="lock-closed-outline" class="align-middle"></ion-icon>
                                        Change Password
                                    </h6>
                                    <p class="text-muted small mb-3">
                                        <ion-icon name="information-circle-outline"></ion-icon>
                                        Leave blank if you don't want to change the password
                                    </p>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">New Password</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Enter new password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                placeholder="Confirm new password">
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Information -->
                                <div class="mb-4">
                                    <h6 class="mb-3 text-primary border-bottom pb-2">
                                        <ion-icon name="person-outline" class="align-middle"></ion-icon>
                                        Personal Information
                                    </h6>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Birthday</label>
                                            <input type="date" name="birthday"
                                                class="form-control @error('birthday') is-invalid @enderror"
                                                value="{{ old('birthday', $user->birthday) }}">
                                            @error('birthday')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Gender</label>
                                            <select name="gender"
                                                class="form-select @error('gender') is-invalid @enderror">
                                                <option value="">Select Gender</option>
                                                <option value="male"
                                                    {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male
                                                </option>
                                                <option value="female"
                                                    {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female
                                                </option>
                                                <option value="other"
                                                    {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other
                                                </option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Marital Status</label>
                                            <select name="marital_status"
                                                class="form-select @error('marital_status') is-invalid @enderror">
                                                <option value="">Select Status</option>
                                                <option value="single"
                                                    {{ old('marital_status', $user->marital_status) == 'single' ? 'selected' : '' }}>
                                                    Single</option>
                                                <option value="married"
                                                    {{ old('marital_status', $user->marital_status) == 'married' ? 'selected' : '' }}>
                                                    Married</option>
                                                <option value="divorced"
                                                    {{ old('marital_status', $user->marital_status) == 'divorced' ? 'selected' : '' }}>
                                                    Divorced</option>
                                                <option value="widowed"
                                                    {{ old('marital_status', $user->marital_status) == 'widowed' ? 'selected' : '' }}>
                                                    Widowed</option>
                                                <option value="other"
                                                    {{ old('marital_status', $user->marital_status) == 'other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                            @error('marital_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">City</label>
                                            <input type="text" name="city"
                                                class="form-control @error('city') is-invalid @enderror"
                                                value="{{ old('city', $user->city) }}" placeholder="Enter your city">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Looking For</label>
                                            <input type="text" name="looking_for"
                                                class="form-control @error('looking_for') is-invalid @enderror"
                                                value="{{ old('looking_for', $user->looking_for) }}"
                                                placeholder="What are you looking for?">
                                            @error('looking_for')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 justify-content-end border-top pt-3">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4">
                                        <ion-icon name="arrow-back-outline" class="align-middle"></ion-icon>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <ion-icon name="save-outline" class="align-middle"></ion-icon>
                                        Save Changes
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end page content wrapper -->

    <!--Start Back To Top Button-->
    <a href="javascript:;" class="back-to-top">
        <ion-icon name="arrow-up-outline"></ion-icon>
    </a>
    <!--End Back To Top Button-->

    <!--start overlay-->
    <div class="overlay nav-toggle-icon"></div>
    <!--end overlay-->

@endsection

@push('scripts')
    <script>
        // Form validation
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
@endpush
