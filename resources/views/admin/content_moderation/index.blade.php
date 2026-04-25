@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Content Moderation</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Content Moderation</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Stats --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-warning">{{ $totalPending }}</h4>
                    <small class="text-muted">⏳ Pending Review</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-success">{{ $totalApproved }}</h4>
                    <small class="text-muted">✅ Approved</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-danger">{{ $totalRejected }}</h4>
                    <small class="text-muted">❌ Rejected</small>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#photos">
                    🖼️ Photos
                    @if($photoFlags->count() > 0)
                        <span class="badge bg-warning ms-1">{{ $photoFlags->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#bios">
                    📝 Bios
                    @if($bioFlags->count() > 0)
                        <span class="badge bg-warning ms-1">{{ $bioFlags->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#usernames">
                    👤 Usernames
                    @if($usernameFlags->count() > 0)
                        <span class="badge bg-warning ms-1">{{ $usernameFlags->count() }}</span>
                    @endif
                </a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ── PHOTOS TAB ── --}}
            <div class="tab-pane fade show active" id="photos">
                <div class="row">
                    @forelse($photoFlags as $flag)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="{{ asset($flag->photo_path) }}"
                                 class="card-img-top" style="height:180px;object-fit:cover;"
                                 onerror="this.src='{{ asset('admin/assets/images/avatars/06.png') }}'">
                            <div class="card-body text-center p-3">
                                <p class="fw-semibold mb-1" style="font-size:13px;">{{ $flag->user->name ?? '—' }}</p>
                                <small class="text-muted d-block mb-3">{{ $flag->created_at->diffForHumans() }}</small>
                                <div class="d-flex gap-2 justify-content-center">
                                    <form method="POST" action="{{ route('admin.content.approve', $flag) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">✅ Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.content.reject', $flag) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">❌ Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5 text-muted">
                                <ion-icon name="images-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                <p class="mt-2">No photos pending review.</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ── BIOS TAB ── --}}
            <div class="tab-pane fade" id="bios">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Bio Content</th>
                                    <th>Flagged At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bioFlags as $flag)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $flag->user->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                 class="rounded-circle" width="36" height="36" style="object-fit:cover;">
                                            <div>
                                                <div class="fw-semibold">{{ $flag->user->name ?? '—' }}</div>
                                                <small class="text-muted">{{ $flag->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 small" style="max-width:300px;">
                                            {{ Str::limit($flag->content, 100) }}
                                        </p>
                                    </td>
                                    <td><small class="text-muted">{{ $flag->created_at->diffForHumans() }}</small></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form method="POST" action="{{ route('admin.content.approve', $flag) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">✅ Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.content.reject', $flag) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">❌ Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <ion-icon name="document-text-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                        <p class="mt-2">No bios pending review.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── USERNAMES TAB ── --}}
            <div class="tab-pane fade" id="usernames">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Username</th>
                                    <th>Flagged At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usernameFlags as $flag)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $flag->user->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                 class="rounded-circle" width="36" height="36" style="object-fit:cover;">
                                            <div class="fw-semibold">{{ $flag->user->name ?? '—' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $flag->content }}</span>
                                    </td>
                                    <td><small class="text-muted">{{ $flag->created_at->diffForHumans() }}</small></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form method="POST" action="{{ route('admin.content.approve', $flag) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">✅ Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.content.reject', $flag) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">❌ Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <ion-icon name="person-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                        <p class="mt-2">No usernames pending review.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection