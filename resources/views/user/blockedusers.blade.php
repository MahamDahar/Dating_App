@extends('layouts.user')

@section('usercontent')

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Safety</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item active">Blocked Users</li>
            </ol>
        </nav>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
    <ion-icon name="checkmark-circle-outline" style="font-size:18px;"></ion-icon>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row justify-content-center">
    <div class="col-12 col-lg-7">

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;background:linear-gradient(135deg,#ef4444,#f87171);border-radius:14px;display:flex;align-items:center;justify-content:center;">
                            <ion-icon name="ban-outline" style="font-size:22px;color:white;"></ion-icon>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Blocked Users</h5>
                            <small class="text-muted">People you have blocked on Fobia</small>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-pill fw-semibold" style="background:#fef2f2;color:#ef4444;font-size:13px;">
                        {{ $blockedUsers->count() }} {{ Str::plural('user', $blockedUsers->count()) }}
                    </span>
                </div>

                <hr class="mb-0">

                {{-- Info box --}}
                <div class="d-flex align-items-start gap-2 p-3 rounded-3 my-3" style="background:#fef2f2;border:1px solid #fecaca;">
                    <ion-icon name="information-circle-outline" style="font-size:17px;color:#ef4444;flex-shrink:0;margin-top:1px;"></ion-icon>
                    <small style="color:#991b1b;font-size:12px;">
                        Blocked users cannot see your profile, send you messages, or interact with you in any way.
                    </small>
                </div>

                {{-- Blocked List --}}
                @forelse($blockedUsers as $block)
                <div class="blocked-row">
                    <img src="{{ $block->blocked->avatar ?? asset('admin/assets/images/avatars/06.png') }}"
                        class="blocked-avatar" alt="">

                    <div class="flex-grow-1">
                        <div class="blocked-name">{{ $block->blocked->name ?? 'Unknown User' }}</div>
                        <div class="blocked-meta">
                            <ion-icon name="time-outline" style="font-size:11px;"></ion-icon>
                            Blocked {{ $block->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <form action="{{ route('user.blocked.unblock', $block->blocked_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="unblock-btn"
                            onclick="return confirm('Are you sure you want to unblock {{ $block->blocked->name ?? 'this user' }}?')">
                            <ion-icon name="close-circle-outline"></ion-icon>
                            Unblock
                        </button>
                    </form>
                </div>
                @empty
                <div class="empty-state">
                    <ion-icon name="shield-checkmark-outline"></ion-icon>
                    <p class="fw-semibold mb-1">No blocked users</p>
                    <small>When you block someone, they will appear here.</small>
                </div>
                @endforelse

            </div>
        </div>

    </div>
</div>

<style>
    .blocked-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .blocked-row:last-child { border-bottom: none; }

    .blocked-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #fee2e2;
    }

    .blocked-name {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
    }

    .blocked-meta {
        font-size: 11px;
        color: #aaa;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .unblock-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        background: #fef2f2;
        color: #ef4444;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .unblock-btn:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 50px 0;
        color: #ccc;
    }
    .empty-state ion-icon {
        font-size: 52px;
        display: block;
        margin-bottom: 12px;
        color: #d1d5db;
    }
    .empty-state p { color: #6c757d; margin: 0; }
    .empty-state small { color: #aaa; }
</style>

@endsection