@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Email Verification</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Verification</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <ion-icon name="checkmark-circle-outline" class="me-2"></ion-icon>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <ion-icon name="alert-circle-outline" class="me-2"></ion-icon>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Stats Row --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0" style="color:#7c3aed;">{{ $totalUsers }}</h4>
                    <small class="text-muted">Total Users</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-success">{{ $verifiedUsers }}</h4>
                    <small class="text-muted">✅ Verified</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-danger">{{ $unverifiedUsers }}</h4>
                    <small class="text-muted">❌ Unverified</small>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.verification.index') }}"
                      class="d-flex gap-3 flex-wrap align-items-center">
                    <input type="text" name="search" class="form-control form-control-sm"
                           style="max-width:250px;" placeholder="Search name or email..."
                           value="{{ request('search') }}">
                    <select name="status" class="form-select form-select-sm" style="max-width:180px;">
                        <option value="">All Users</option>
                        <option value="verified"   {{ request('status') == 'verified'   ? 'selected' : '' }}>
                            ✅ Verified
                        </option>
                        <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>
                            ❌ Unverified
                        </option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Filter</button>
                    <a href="{{ route('admin.verification.index') }}" class="btn btn-light btn-sm">Reset</a>
                </form>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="card">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold">Users Verification Status</h6>
                <span class="badge bg-primary">{{ $users->total() }} Users</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Status</th>
                                <th>Verified At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $user->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                             class="rounded-circle" width="36" height="36"
                                             style="object-fit:cover;">
                                        <span class="fw-semibold">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">✅ Verified</span>
                                    @else
                                        <span class="badge bg-danger">❌ Unverified</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->email_verified_at
                                        ? \Carbon\Carbon::parse($user->email_verified_at)->format('d M Y')
                                        : '—' }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @if($user->email_verified_at)
                                            {{-- Unverify --}}
                                            <form method="POST"
                                                  action="{{ route('admin.verification.unverify', $user) }}"
                                                  onsubmit="return confirm('Remove verification from {{ $user->name }}?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <ion-icon name="close-circle-outline"></ion-icon> Unverify
                                                </button>
                                            </form>
                                        @else
                                            {{-- Manually Verify --}}
                                            <form method="POST"
                                                  action="{{ route('admin.verification.verify', $user) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <ion-icon name="checkmark-circle-outline"></ion-icon> Verify
                                                </button>
                                            </form>

                                            {{-- Send Reminder Email --}}
                                            <form method="POST"
                                                  action="{{ route('admin.verification.reminder') }}">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <button type="submit" class="btn btn-sm btn-info text-white">
                                                    <ion-icon name="mail-outline"></ion-icon> Send Email
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <ion-icon name="shield-checkmark-outline"
                                              style="font-size:48px;color:#d1d5db;"></ion-icon>
                                    <p class="mt-2">No users found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
            @endif
        </div>

    </div>
</div>

@endsection