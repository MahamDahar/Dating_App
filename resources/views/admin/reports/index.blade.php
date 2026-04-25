@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reports</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Reports</li>
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

        {{-- Stats Row --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0" style="color:#7c3aed;">{{ $totalReports }}</h4>
                    <small class="text-muted">Total Reports</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-warning">{{ $pendingReports }}</h4>
                    <small class="text-muted">⏳ Pending</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-danger">{{ $bannedUsers }}</h4>
                    <small class="text-muted">🚫 Banned Users</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-warning">{{ $warnedUsers }}</h4>
                    <small class="text-muted">⚠️ Warned Users</small>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- ── LEFT: Most Reported Users ── --}}
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold">🚩 Most Reported Users</h6>
                    </div>
                    <div class="card-body p-0">
                        @forelse($mostReported as $user)
                        <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                            <img src="{{ $user->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                 class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                            <div class="flex-grow-1">
                                <div class="fw-semibold" style="font-size:13px;">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $user->reports_received_count >= 5 ? 'danger' : ($user->reports_received_count >= 3 ? 'warning' : 'secondary') }}">
                                    {{ $user->reports_received_count }} reports
                                </span>
                                @if($user->is_blocked)
                                    <div><span class="badge bg-danger mt-1">🚫 Banned</span></div>
                                @elseif($user->warning_sent)
                                    <div><span class="badge bg-warning mt-1">⚠️ Warned</span></div>
                                @endif
                            </div>
                        </div>

                        {{-- Report progress bar --}}
                        <div class="px-3 pb-2">
                            <div class="d-flex justify-content-between" style="font-size:10px;color:#9ca3af;">
                                <span>0</span>
                                <span>⚠️ Warning at 3</span>
                                <span>🚫 Ban at 5</span>
                            </div>
                            <div class="progress" style="height:5px;border-radius:10px;">
                                <div class="progress-bar bg-{{ $user->reports_received_count >= 5 ? 'danger' : ($user->reports_received_count >= 3 ? 'warning' : 'info') }}"
                                     style="width:{{ min(($user->reports_received_count / 5) * 100, 100) }}%">
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex gap-2 mt-2">
                                @if(!$user->is_blocked)
                                    @if(!$user->warning_sent)
                                    <form method="POST" action="{{ route('admin.problem_reports.warn', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" style="font-size:11px;">
                                            ⚠️ Warn
                                        </button>
                                    </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.problem_reports.block', $user) }}"
                                          onsubmit="return confirm('Ban {{ $user->name }}?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" style="font-size:11px;">
                                            🚫 Ban
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.problem_reports.unblock', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" style="font-size:11px;">
                                            ✅ Unban
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @empty
                        <div class="text-center py-4 text-muted">
                            <small>No reported users yet.</small>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: All Reports Table ── --}}
            <div class="col-lg-8 mb-4">

                {{-- Filters --}}
                <div class="card mb-3">
                    <div class="card-body py-3">
                        <form method="GET" action="{{ route('admin.problem_reports.index') }}"
                              class="d-flex gap-2 flex-wrap align-items-center">
                            <input type="text" name="search" class="form-control form-control-sm"
                                   style="max-width:200px;" placeholder="Search user..."
                                   value="{{ request('search') }}">
                            <select name="status" class="form-select form-select-sm" style="max-width:150px;">
                                <option value="">All Status</option>
                                <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="reviewed"  {{ request('status') == 'reviewed'  ? 'selected' : '' }}>✅ Reviewed</option>
                                <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>❌ Dismissed</option>
                            </select>
                            <select name="reason" class="form-select form-select-sm" style="max-width:180px;">
                                <option value="">All Reasons</option>
                                @foreach(['harassment', 'fake_profile', 'spam', 'inappropriate', 'underage', 'other'] as $reason)
                                    <option value="{{ $reason }}" {{ request('reason') == $reason ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $reason)) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            <a href="{{ route('admin.problem_reports.index') }}" class="btn btn-light btn-sm">Reset</a>
                        </form>
                    </div>
                </div>

                {{-- Reports Table --}}
                <div class="card">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold">All Reports</h6>
                        <span class="badge bg-primary">{{ $reports->total() }} Reports</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Reporter</th>
                                        <th>Reported</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>
                                            <div class="fw-semibold" style="font-size:13px;">
                                                {{ $report->reporter->name ?? '—' }}
                                            </div>
                                            <small class="text-muted">{{ $report->reporter->email ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold" style="font-size:13px;">
                                                {{ $report->reported->name ?? '—' }}
                                                @if($report->reported?->is_blocked)
                                                    <span class="badge bg-danger ms-1" style="font-size:9px;">Banned</span>
                                                @elseif($report->reported?->warning_sent)
                                                    <span class="badge bg-warning ms-1" style="font-size:9px;">Warned</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $report->reported->email ?? '' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ ucwords(str_replace('_', ' ', $report->reason)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{
                                                $report->status === 'pending'   ? 'warning' :
                                                ($report->status === 'reviewed' ? 'success' : 'secondary')
                                            }}">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $report->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.problem_reports.show', $report) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <ion-icon name="eye-outline"></ion-icon>
                                                </a>
                                                @if($report->status === 'pending')
                                                <form method="POST" action="{{ route('admin.problem_reports.dismiss', $report) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-secondary">
                                                        <ion-icon name="close-outline"></ion-icon>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <ion-icon name="flag-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                            <p class="mt-2">No reports found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($reports->hasPages())
                    <div class="card-footer">
                        {{ $reports->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection