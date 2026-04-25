@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Messages Monitoring</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Messages Monitoring</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Stats --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0" style="color:#7c3aed;">{{ $totalConversations }}</h4>
                    <small class="text-muted">Total Conversations</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-success">{{ $totalMessages }}</h4>
                    <small class="text-muted">Total Messages</small>
                </div>
            </div>
        </div>

        {{-- Search --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.messages.index') }}" class="d-flex gap-3 align-items-center">
                    <input type="text" name="search" class="form-control form-control-sm"
                           style="max-width:300px;" placeholder="🔍 Search by user name or email..."
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm px-4">Search</button>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-light btn-sm">Reset</a>
                </form>
            </div>
        </div>

        {{-- Conversations Table --}}
        <div class="card">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold">All Conversations</h6>
                <span class="badge bg-primary">{{ $conversations->total() }} Conversations</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User 1</th>
                                <th>User 2</th>
                                <th>Messages</th>
                                <th>Last Message</th>
                                <th>Last Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($conversations as $index => $conv)
                            <tr>
                                <td>{{ $conversations->firstItem() + $index }}</td>

                                {{-- User 1 --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $conv->senderUser->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                             class="rounded-circle" width="36" height="36"
                                             style="object-fit:cover;">
                                        <div>
                                            <div class="fw-semibold">{{ $conv->senderUser->name ?? '—' }}</div>
                                            <small class="text-muted">{{ $conv->senderUser->email ?? '—' }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- User 2 --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $conv->receiverUser->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                             class="rounded-circle" width="36" height="36"
                                             style="object-fit:cover;">
                                        <div>
                                            <div class="fw-semibold">{{ $conv->receiverUser->name ?? '—' }}</div>
                                            <small class="text-muted">{{ $conv->receiverUser->email ?? '—' }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Message Count --}}
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $conv->message_count }} msgs
                                    </span>
                                </td>

                                {{-- Last Message Preview --}}
                                <td>
                                    <small class="text-muted">
                                        {{ $conv->last_message ? Str::limit($conv->last_message->message, 40) : '—' }}
                                    </small>
                                </td>

                                {{-- Last Active --}}
                                <td>
                                    <small class="text-muted">
                                        {{ $conv->last_message_at ? \Carbon\Carbon::parse($conv->last_message_at)->diffForHumans() : '—' }}
                                    </small>
                                </td>

                                {{-- Action --}}
                                <td>
                                    <a href="{{ route('admin.messages.show', [$conv->sender_id, $conv->receiver_id]) }}"
                                       class="btn btn-sm btn-primary">
                                        <ion-icon name="eye-outline"></ion-icon> View Chat
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <ion-icon name="chatbubbles-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                    <p class="mt-2">No conversations found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($conversations->hasPages())
            <div class="card-footer">
                {{ $conversations->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

    </div>
</div>

@endsection