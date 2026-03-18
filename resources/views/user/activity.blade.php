@extends('layouts.user')

@section('usercontent')
<div class="page-content-wrapper">
      <div class="page-content">
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Activity</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item active">My Activity</li>
            </ol>
        </nav>
    </div>
</div>

{{-- ── Page Title ── --}}
<div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:48px;height:48px;background:linear-gradient(135deg,#7c3aed,#a855f7);border-radius:14px;display:flex;align-items:center;justify-content:center;">
        <ion-icon name="pulse-outline" style="font-size:22px;color:white;"></ion-icon>
    </div>
    <div>
        <h5 class="mb-0 fw-semibold">My Activity</h5>
        <small class="text-muted">Overview of your activity on Fobia</small>
    </div>
</div>

{{-- ── Stats Overview Cards ── --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff;">
                <ion-icon name="heart-outline" style="color:#7c3aed;"></ion-icon>
            </div>
            <div class="stat-number">{{ $totalLikesReceived }}</div>
            <div class="stat-label">Likes Received</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <ion-icon name="thumbs-up-outline" style="color:#16a34a;"></ion-icon>
            </div>
            <div class="stat-number">{{ $totalLikesGiven }}</div>
            <div class="stat-label">Likes Given</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <ion-icon name="people-outline" style="color:#2563eb;"></ion-icon>
            </div>
            <div class="stat-number">{{ $totalMatches }}</div>
            <div class="stat-label">Total Matches</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed;">
                <ion-icon name="eye-outline" style="color:#ea580c;"></ion-icon>
            </div>
            <div class="stat-number">{{ $totalProfileViews }}</div>
            <div class="stat-label">Profile Views</div>
        </div>
    </div>

</div>

{{-- ── Detailed Sections Row ── --}}
<div class="row g-4">

    {{-- Likes Given & Received --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="section-header mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <ion-icon name="heart-outline" style="font-size:18px;color:#7c3aed;"></ion-icon>
                        <h6 class="mb-0 fw-semibold">Likes</h6>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="tab-btn active" data-tab="likes-received">Received</button>
                        <button class="tab-btn" data-tab="likes-given">Given</button>
                    </div>
                </div>

                {{-- Likes Received --}}
                <div id="likes-received" class="tab-content-area">
                    @forelse($likesReceived as $like)
                    <div class="activity-row">
                        <img src="{{ $like->sender->avatar ?? asset('admin/assets/images/avatars/06.png') }}"
                            class="activity-avatar" alt="">
                        <div class="flex-grow-1">
                            <div class="activity-name">{{ $like->sender->name ?? 'Unknown' }}</div>
                            <div class="activity-time">{{ $like->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="badge-pill" style="background:#fdf4ff;color:#7c3aed;">
                            <ion-icon name="heart" style="font-size:11px;"></ion-icon> Liked you
                        </span>
                    </div>
                    @empty
                    <div class="empty-state">
                        <ion-icon name="heart-dislike-outline"></ion-icon>
                        <p>No likes received yet</p>
                    </div>
                    @endforelse
                </div>

                {{-- Likes Given --}}
                <div id="likes-given" class="tab-content-area" style="display:none;">
                    @forelse($likesGiven as $like)
                    <div class="activity-row">
                        <img src="{{ $like->receiver->avatar ?? asset('admin/assets/images/avatars/06.png') }}"
                            class="activity-avatar" alt="">
                        <div class="flex-grow-1">
                            <div class="activity-name">{{ $like->receiver->name ?? 'Unknown' }}</div>
                            <div class="activity-time">{{ $like->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="badge-pill" style="background:#f0fdf4;color:#16a34a;">
                            <ion-icon name="thumbs-up" style="font-size:11px;"></ion-icon> You liked
                        </span>
                    </div>
                    @empty
                    <div class="empty-state">
                        <ion-icon name="thumbs-up-outline"></ion-icon>
                        <p>You haven't liked anyone yet</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    {{-- Match History --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="section-header mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <ion-icon name="people-outline" style="font-size:18px;color:#2563eb;"></ion-icon>
                        <h6 class="mb-0 fw-semibold">Match History</h6>
                    </div>
                    <span class="badge rounded-pill" style="background:#eff6ff;color:#2563eb;font-size:11px;">
                        {{ $totalMatches }} Matches
                    </span>
                </div>

                @forelse($matches as $match)
                @php
                    $other = $match->sender_id === Auth::id() ? $match->receiver : $match->sender;
                @endphp
                <div class="activity-row">
                    <img src="{{ $other->avatar ?? asset('admin/assets/images/avatars/06.png') }}"
                        class="activity-avatar" alt="">
                    <div class="flex-grow-1">
                        <div class="activity-name">{{ $other->name ?? 'Unknown' }}</div>
                        <div class="activity-time">Matched {{ $match->updated_at->diffForHumans() }}</div>
                    </div>
                    <a href="{{ route('user.chat.index') }}" class="badge-pill" style="background:#eff6ff;color:#2563eb;text-decoration:none;">
                        <ion-icon name="chatbubble-outline" style="font-size:11px;"></ion-icon> Chat
                    </a>
                </div>
                @empty
                <div class="empty-state">
                    <ion-icon name="people-outline"></ion-icon>
                    <p>No matches yet — keep exploring!</p>
                </div>
                @endforelse

            </div>
        </div>
    </div>

    {{-- Profile Views History --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="section-header mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <ion-icon name="eye-outline" style="font-size:18px;color:#ea580c;"></ion-icon>
                        <h6 class="mb-0 fw-semibold">Profile Views</h6>
                    </div>
                    <span class="badge rounded-pill" style="background:#fff7ed;color:#ea580c;font-size:11px;">
                        {{ $totalProfileViews }} Views
                    </span>
                </div>

                @forelse($profileViews as $view)
                <div class="activity-row">
                    <img src="{{ $view->viewer->avatar ?? asset('admin/assets/images/avatars/06.png') }}"
                        class="activity-avatar" alt="">
                    <div class="flex-grow-1">
                        <div class="activity-name">{{ $view->viewer->name ?? 'Someone' }}</div>
                        <div class="activity-time">{{ $view->created_at->diffForHumans() }}</div>
                    </div>
                    <span class="badge-pill" style="background:#fff7ed;color:#ea580c;">
                        <ion-icon name="eye" style="font-size:11px;"></ion-icon> Viewed
                    </span>
                </div>
                @empty
                <div class="empty-state">
                    <ion-icon name="eye-off-outline"></ion-icon>
                    <p>No profile views yet</p>
                </div>
                @endforelse

            </div>
        </div>
    </div>

    {{-- Messages Summary --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="section-header mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <ion-icon name="chatbubbles-outline" style="font-size:18px;color:#0ea5e9;"></ion-icon>
                        <h6 class="mb-0 fw-semibold">Messages Summary</h6>
                    </div>
                    <span class="badge rounded-pill" style="background:#f0f9ff;color:#0ea5e9;font-size:11px;">
                        {{ $totalMessages }} Total
                    </span>
                </div>

                {{-- Summary Stats --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="mini-stat" style="background:#f0f9ff;">
                            <div class="mini-stat-num" style="color:#0ea5e9;">{{ $messagesSent }}</div>
                            <div class="mini-stat-label">Sent</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mini-stat" style="background:#f0fdf4;">
                            <div class="mini-stat-num" style="color:#16a34a;">{{ $messagesReceived }}</div>
                            <div class="mini-stat-label">Received</div>
                        </div>
                    </div>
                </div>

                {{-- Recent Conversations --}}
                @forelse($recentConversations as $convo)
                <div class="activity-row">
                    <img src="{{ $convo->avatar ?? asset('admin/assets/images/avatars/06.png') }}"
                        class="activity-avatar" alt="">
                    <div class="flex-grow-1">
                        <div class="activity-name">{{ $convo->name ?? 'Unknown' }}</div>
                        <div class="activity-time" style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $convo->last_message ?? 'No messages yet' }}
                        </div>
                    </div>
                    <a href="{{ route('user.chat.index') }}" class="badge-pill" style="background:#f0f9ff;color:#0ea5e9;text-decoration:none;">
                        <ion-icon name="arrow-forward-outline" style="font-size:11px;"></ion-icon> Open
                    </a>
                </div>
                @empty
                <div class="empty-state">
                    <ion-icon name="chatbubbles-outline"></ion-icon>
                    <p>No conversations yet</p>
                </div>
                @endforelse

            </div>
        </div>
    </div>

</div>
 </div>
 </div>
<style>
    /* Stat Cards */
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 16px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 12px;
    }
    .stat-icon ion-icon { font-size: 22px; }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
    }

    /* Section Header */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Tab Buttons */
    .tab-btn {
        background: #f5f5f5;
        border: none;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 12px;
        font-weight: 500;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s;
    }
    .tab-btn.active {
        background: #7c3aed;
        color: white;
    }

    /* Activity Row */
    .activity-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .activity-row:last-child { border-bottom: none; }

    .activity-avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .activity-name {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a2e;
    }
    .activity-time {
        font-size: 11px;
        color: #aaa;
        margin-top: 1px;
    }

    .badge-pill {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        flex-shrink: 0;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 30px 0;
        color: #ccc;
    }
    .empty-state ion-icon { font-size: 36px; display: block; margin-bottom: 8px; }
    .empty-state p { font-size: 13px; color: #aaa; margin: 0; }

    /* Mini Stats */
    .mini-stat {
        border-radius: 12px;
        padding: 12px;
        text-align: center;
    }
    .mini-stat-num { font-size: 22px; font-weight: 700; line-height: 1; }
    .mini-stat-label { font-size: 11px; color: #6c757d; margin-top: 2px; }
</style>

<script>
    // Likes tab toggle
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const tab = this.dataset.tab;

            // Update buttons
            this.closest('.card-body').querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Show/hide content
            document.querySelectorAll('.tab-content-area').forEach(area => area.style.display = 'none');
            document.getElementById(tab).style.display = 'block';
        });
    });
</script>

@endsection