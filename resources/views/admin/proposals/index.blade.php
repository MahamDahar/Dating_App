@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Proposals Management</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Proposals</li>
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
                    <h4 class="fw-bold mb-0" style="color:#7c3aed;">{{ $totalProposals }}</h4>
                    <small class="text-muted">Total Proposals</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-warning">{{ $pendingProposals }}</h4>
                    <small class="text-muted">⏳ Pending</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-success">{{ $acceptedProposals }}</h4>
                    <small class="text-muted">✅ Accepted</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-danger">{{ $rejectedProposals }}</h4>
                    <small class="text-muted">❌ Rejected</small>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- ── LEFT: Spam Senders ── --}}
            @if($spamSenders->count() > 0)
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold">🚨 Possible Spam Senders</h6>
                        <small class="text-muted">Users who sent 5+ proposals</small>
                    </div>
                    <div class="card-body p-0">
                        @foreach($spamSenders as $spam)
                        <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                            <img src="{{ $spam->sender->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                 class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                            <div class="flex-grow-1">
                                <div class="fw-semibold" style="font-size:13px;">
                                    {{ $spam->sender->name ?? '—' }}
                                </div>
                                <small class="text-muted">{{ $spam->sender->email ?? '' }}</small>
                            </div>
                            <span class="badge bg-danger">
                                {{ $spam->proposal_count }} sent
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- ── RIGHT: All Proposals ── --}}
            <div class="col-lg-{{ $spamSenders->count() > 0 ? '8' : '12' }} mb-4">

                {{-- Filters --}}
                <div class="card mb-3">
                    <div class="card-body py-3">
                        <form method="GET" action="{{ route('admin.proposals.index') }}"
                              class="d-flex gap-2 flex-wrap align-items-center">
                            <input type="text" name="search" class="form-control form-control-sm"
                                   style="max-width:220px;" placeholder="🔍 Search user..."
                                   value="{{ request('search') }}">
                            <select name="status" class="form-select form-select-sm" style="max-width:160px;">
                                <option value="">All Status</option>
                                <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>✅ Accepted</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm px-4">Filter</button>
                            <a href="{{ route('admin.proposals.index') }}" class="btn btn-light btn-sm">Reset</a>
                        </form>
                    </div>
                </div>

                {{-- Proposals Table --}}
                <div class="card">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold">All Proposals</h6>
                        <span class="badge bg-primary">{{ $proposals->total() }} Total</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Sender</th>
                                        <th>Receiver</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($proposals as $proposal)
                                    <tr>
                                        <td>{{ $proposal->id }}</td>

                                        {{-- Sender --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $proposal->sender->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                     class="rounded-circle" width="34" height="34"
                                                     style="object-fit:cover;">
                                                <div>
                                                    <div class="fw-semibold" style="font-size:13px;">
                                                        {{ $proposal->sender->name ?? '—' }}
                                                    </div>
                                                    <small class="text-muted">{{ $proposal->sender->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Receiver --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $proposal->receiver->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                     class="rounded-circle" width="34" height="34"
                                                     style="object-fit:cover;">
                                                <div>
                                                    <div class="fw-semibold" style="font-size:13px;">
                                                        {{ $proposal->receiver->name ?? '—' }}
                                                    </div>
                                                    <small class="text-muted">{{ $proposal->receiver->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Message --}}
                                        <td>
                                            <small class="text-muted">
                                                {{ Str::limit($proposal->message, 45) }}
                                            </small>
                                        </td>

                                        {{-- Status --}}
                                        <td>
                                            <span class="badge bg-{{
                                                $proposal->status === 'accepted' ? 'success' :
                                                ($proposal->status === 'rejected' ? 'danger' : 'warning')
                                            }}">
                                                {{ ucfirst($proposal->status) }}
                                            </span>
                                        </td>

                                        {{-- Date --}}
                                        <td>
                                            <small class="text-muted">
                                                {{ $proposal->created_at->format('d M Y') }}
                                            </small>
                                        </td>

                                        {{-- Action --}}
                                        <td>
                                            <form method="POST"
                                                  action="{{ route('admin.proposals.destroy', $proposal) }}"
                                                  onsubmit="return confirm('Delete this proposal?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <ion-icon name="trash-outline"></ion-icon>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <ion-icon name="paper-plane-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                            <p class="mt-2">No proposals found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($proposals->hasPages())
                    <div class="card-footer">
                        {{ $proposals->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@endsection