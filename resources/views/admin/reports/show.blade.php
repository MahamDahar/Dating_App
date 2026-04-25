@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Report Detail</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.problem_reports.index') }}">Reports</a>
                        </li>
                        <li class="breadcrumb-item active">Report #{{ $report->id }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.problem_reports.index') }}" class="btn btn-light btn-sm">
                    <ion-icon name="arrow-back-outline"></ion-icon> Back
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">

            {{-- ── Report Detail ── --}}
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold">Report #{{ $report->id }} Detail</h6>
                    </div>
                    <div class="card-body">

                        {{-- Reporter --}}
                        <div class="mb-4">
                            <small class="text-muted fw-bold text-uppercase">Reporter</small>
                            <div class="d-flex align-items-center gap-3 mt-2">
                                <img src="{{ $report->reporter->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                     class="rounded-circle" width="44" height="44" style="object-fit:cover;">
                                <div>
                                    <div class="fw-bold">{{ $report->reporter->name }}</div>
                                    <small class="text-muted">{{ $report->reporter->email }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- Reported --}}
                        <div class="mb-4">
                            <small class="text-muted fw-bold text-uppercase">Reported User</small>
                            <div class="d-flex align-items-center gap-3 mt-2">
                                <img src="{{ $report->reported->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                     class="rounded-circle" width="44" height="44"
                                     style="object-fit:cover; border:2px solid #dc2626;">
                                <div>
                                    <div class="fw-bold">
                                        {{ $report->reported->name }}
                                        @if($report->reported->is_blocked)
                                            <span class="badge bg-danger ms-1">🚫 Banned</span>
                                        @elseif($report->reported->warning_sent)
                                            <span class="badge bg-warning ms-1">⚠️ Warned</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $report->reported->email }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- Report Info --}}
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted">Reason</td>
                                <td><span class="badge bg-light text-dark border">{{ ucwords(str_replace('_', ' ', $report->reason)) }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td>
                                    <span class="badge bg-{{ $report->status === 'pending' ? 'warning' : ($report->status === 'reviewed' ? 'success' : 'secondary') }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date</td>
                                <td>{{ $report->created_at->format('d M Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Reports Against</td>
                                <td>
                                    <span class="badge bg-{{ $uniqueReporters >= 5 ? 'danger' : ($uniqueReporters >= 3 ? 'warning' : 'secondary') }}">
                                        {{ $uniqueReporters }} unique reporters
                                    </span>
                                </td>
                            </tr>
                        </table>

                        {{-- Description --}}
                        @if($report->description)
                        <div class="bg-light rounded p-3 mt-3">
                            <small class="text-muted fw-bold">Description:</small>
                            <p class="mb-0 mt-1 small">{{ $report->description }}</p>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- Actions Card --}}
                <div class="card mt-3">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold">Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">

                            @if($report->status === 'pending')
                            <form method="POST" action="{{ route('admin.problem_reports.dismiss', $report) }}">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    ❌ Dismiss Report
                                </button>
                            </form>
                            @endif

                            @if(!$report->reported->is_blocked)
                                @if(!$report->reported->warning_sent)
                                <form method="POST" action="{{ route('admin.problem_reports.warn', $report->reported) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        ⚠️ Send Warning
                                    </button>
                                </form>
                                @endif

                                <form method="POST" action="{{ route('admin.problem_reports.block', $report->reported) }}"
                                      onsubmit="return confirm('Ban {{ $report->reported->name }}?')">
                                    @csrf
                                    <input type="hidden" name="reason" value="Banned after admin review of reports.">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        🚫 Ban User
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.problem_reports.unblock', $report->reported) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        ✅ Unban User
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            {{-- ── All Reports Against This User ── --}}
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold">All Reports Against {{ $report->reported->name }}</h6>
                        <span class="badge bg-danger">{{ $allReportsAgainst->count() }}</span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="card-body border-bottom pb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size:12px;">
                            <span class="text-muted">Report Progress</span>
                            <span class="fw-bold">{{ $uniqueReporters }} / 5</span>
                        </div>
                        <div class="progress" style="height:8px;border-radius:10px;">
                            <div class="progress-bar bg-{{ $uniqueReporters >= 5 ? 'danger' : ($uniqueReporters >= 3 ? 'warning' : 'info') }}"
                                 style="width:{{ min(($uniqueReporters / 5) * 100, 100) }}%;border-radius:10px;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1" style="font-size:10px;color:#9ca3af;">
                            <span>0</span>
                            <span>⚠️ Warning (3)</span>
                            <span>🚫 Ban (5)</span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @foreach($allReportsAgainst as $r)
                        <div class="d-flex align-items-start gap-3 p-3 border-bottom">
                            <img src="{{ $r->reporter->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                 class="rounded-circle mt-1" width="36" height="36" style="object-fit:cover;flex-shrink:0;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold" style="font-size:13px;">{{ $r->reporter->name }}</span>
                                    <small class="text-muted">{{ $r->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="badge bg-light text-dark border mt-1" style="font-size:10px;">
                                    {{ ucwords(str_replace('_', ' ', $r->reason)) }}
                                </span>
                                @if($r->description)
                                <p class="small text-muted mb-0 mt-1">{{ Str::limit($r->description, 80) }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection