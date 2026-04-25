@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Ticket #{{ $ticket->id }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.support_tickets.index') }}">Support Tickets</a>
                        </li>
                        <li class="breadcrumb-item active">Ticket #{{ $ticket->id }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.support_tickets.index') }}" class="btn btn-light btn-sm">
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

            {{-- ── Ticket Detail ── --}}
            <div class="col-lg-7 mb-4">

                {{-- User Info --}}
                <div class="card mb-4">
                    <div class="card-body d-flex align-items-center gap-3">
                        <img src="{{ $ticket->user->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                             class="rounded-circle" width="52" height="52" style="object-fit:cover;">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0">{{ $ticket->user->name ?? '—' }}</h6>
                            <small class="text-muted">{{ $ticket->user->email ?? '' }}</small>
                        </div>
                        @php
                            $statusColors = ['open' => 'danger', 'in_progress' => 'warning', 'resolved' => 'success', 'closed' => 'secondary'];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'secondary' }} px-3 py-2">
                            {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>
                </div>

                {{-- Ticket Message --}}
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold">{{ $ticket->subject }}</h6>
                        <small class="text-muted">Submitted {{ $ticket->created_at->format('d M Y h:i A') }}</small>
                    </div>
                    <div class="card-body">
                        <p style="font-size:14px;line-height:1.8;color:#374151;">
                            {{ $ticket->message }}
                        </p>
                    </div>
                </div>

                {{-- Admin Reply (if exists) --}}
                @if($ticket->admin_reply)
                <div class="card mb-4" style="border-left:4px solid #7c3aed;">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold" style="color:#7c3aed;">
                            <ion-icon name="chatbubble-outline" class="me-1"></ion-icon>
                            Admin Reply
                        </h6>
                        <small class="text-muted">
                            Replied {{ $ticket->replied_at?->format('d M Y h:i A') }}
                        </small>
                    </div>
                    <div class="card-body">
                        <p style="font-size:14px;line-height:1.8;color:#374151;">
                            {{ $ticket->admin_reply }}
                        </p>
                    </div>
                </div>
                @endif

            </div>

            {{-- ── Reply Form ── --}}
            <div class="col-lg-5 mb-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold">
                            <ion-icon name="send-outline" class="me-1" style="color:#7c3aed;"></ion-icon>
                            {{ $ticket->admin_reply ? 'Update Reply' : 'Reply to Ticket' }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.support_tickets.reply', $ticket) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Your Reply</label>
                                <textarea name="admin_reply" class="form-control" rows="6"
                                          placeholder="Type your reply here..." required
                                          maxlength="1000">{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
                                <small class="text-muted">Max 1000 characters</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Update Status</label>
                                <select name="status" class="form-select">
                                    <option value="open"        {{ $ticket->status == 'open'        ? 'selected' : '' }}>🔴 Open</option>
                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>🟡 In Progress</option>
                                    <option value="resolved"    {{ $ticket->status == 'resolved'    ? 'selected' : '' }}>🟢 Resolved</option>
                                    <option value="closed"      {{ $ticket->status == 'closed'      ? 'selected' : '' }}>⚫ Closed</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <ion-icon name="send-outline" class="me-1"></ion-icon>
                                {{ $ticket->admin_reply ? 'Update Reply' : 'Send Reply' }}
                            </button>

                        </form>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="card mt-3">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold">Quick Actions</h6>
                    </div>
                    <div class="card-body d-flex gap-2 flex-wrap">
                        <form method="POST" action="{{ route('admin.support_tickets.destroy', $ticket) }}"
                              onsubmit="return confirm('Delete this ticket?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <ion-icon name="trash-outline"></ion-icon> Delete Ticket
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection