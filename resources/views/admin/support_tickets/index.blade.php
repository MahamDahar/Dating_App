
@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Support Tickets</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Support Tickets</li>
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
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0" style="color:#7c3aed;">{{ $totalTickets }}</h4>
                    <small class="text-muted">Total Tickets</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-danger">{{ $openTickets }}</h4>
                    <small class="text-muted">🔴 Open</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-warning">{{ $inProgressTickets }}</h4>
                    <small class="text-muted">🟡 In Progress</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-success">{{ $resolvedTickets }}</h4>
                    <small class="text-muted">🟢 Resolved</small>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.support_tickets.index') }}"
                      class="d-flex gap-2 flex-wrap align-items-center">
                    <input type="text" name="search" class="form-control form-control-sm"
                           style="max-width:250px;" placeholder="🔍 Search subject or user..."
                           value="{{ request('search') }}">
                    <select name="status" class="form-select form-select-sm" style="max-width:160px;">
                        <option value="">All Status</option>
                        <option value="open"        {{ request('status') == 'open'        ? 'selected' : '' }}>🔴 Open</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>🟡 In Progress</option>
                        <option value="resolved"    {{ request('status') == 'resolved'    ? 'selected' : '' }}>🟢 Resolved</option>
                        <option value="closed"      {{ request('status') == 'closed'      ? 'selected' : '' }}>⚫ Closed</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Filter</button>
                    <a href="{{ route('admin.support_tickets.index') }}" class="btn btn-light btn-sm">Reset</a>
                </form>
            </div>
        </div>

        {{-- Tickets Table --}}
        <div class="card">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold">All Support Tickets</h6>
                <span class="badge bg-primary">{{ $tickets->total() }} Tickets</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Replied</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr class="{{ $ticket->status === 'open' ? 'table-warning' : '' }}">
                                <td>{{ $ticket->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $ticket->user->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                             class="rounded-circle" width="34" height="34" style="object-fit:cover;">
                                        <div>
                                            <div class="fw-semibold" style="font-size:13px;">{{ $ticket->user->name ?? '—' }}</div>
                                            <small class="text-muted">{{ $ticket->user->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size:13px;">{{ Str::limit($ticket->subject, 40) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'open'        => 'danger',
                                            'in_progress' => 'warning',
                                            'resolved'    => 'success',
                                            'closed'      => 'secondary',
                                        ];
                                        $statusIcons = [
                                            'open'        => '🔴',
                                            'in_progress' => '🟡',
                                            'resolved'    => '🟢',
                                            'closed'      => '⚫',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'secondary' }}">
                                        {{ $statusIcons[$ticket->status] ?? '' }}
                                        {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($ticket->admin_reply)
                                        <span class="badge bg-success">✅ Yes</span>
                                    @else
                                        <span class="badge bg-secondary">— No</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $ticket->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.support_tickets.show', $ticket) }}"
                                           class="btn btn-primary btn-sm">
                                            <ion-icon name="eye-outline"></ion-icon> View
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.support_tickets.destroy', $ticket) }}"
                                              onsubmit="return confirm('Delete this ticket?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <ion-icon name="ticket-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                    <p class="mt-2">No support tickets found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($tickets->hasPages())
            <div class="card-footer">
                {{ $tickets->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

    </div>
</div>

@endsection