@extends('layouts.user')
@section('usercontent')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Likes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.discover') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Likes</li>
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
            <div class="col-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0" style="color:#7c3aed;">{{ $likesSent->count() }}</h4>
                    <small class="text-muted">Likes Given</small>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0" style="color:#dc2626;">{{ $likesReceived->count() }}</h4>
                    <small class="text-muted">Likes Received</small>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0" style="color:#16a34a;">{{ $mutualLikes->count() }}</h4>
                    <small class="text-muted">Mutual Likes</small>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#received">
                    ❤️ Likes Received
                    @if($likesReceived->count() > 0)
                        <span class="badge bg-danger ms-1">{{ $likesReceived->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#sent">
                    🤍 Likes Given
                    @if($likesSent->count() > 0)
                        <span class="badge bg-secondary ms-1">{{ $likesSent->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#mutual">
                    💚 Mutual Likes
                    @if($mutualLikes->count() > 0)
                        <span class="badge bg-success ms-1">{{ $mutualLikes->count() }}</span>
                    @endif
                </a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ── LIKES RECEIVED ── --}}
            <div class="tab-pane fade show active" id="received">
                <div class="row">
                    @forelse($likesReceived as $like)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">

                                <div class="position-relative d-inline-block mb-3">
                                    <img src="{{ $like->liker->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                         class="rounded-circle" width="75" height="75"
                                         style="object-fit:cover; border: 3px solid #dc2626;">
                                    <span style="position:absolute;bottom:0;right:0;background:#dc2626;color:white;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;">❤️</span>
                                </div>

                                <h6 class="fw-bold mb-1">{{ $like->liker->name }}</h6>
                                <p class="text-muted small mb-1">{{ $like->liker->city ?? 'Location not set' }}</p>
                                <p class="text-muted small mb-3">{{ $like->liker->profession ?? '' }}</p>

                                {{-- Like back button --}}
                                <form method="POST" action="{{ route('user.likes.toggle', $like->liker) }}">
                                    @csrf
                                    @php
                                        $iLikedBack = $likesSent->where('liked_id', $like->liker_id)->count() > 0;
                                    @endphp
                                    <button type="submit" class="btn btn-sm w-100 {{ $iLikedBack ? 'btn-danger' : 'btn-outline-danger' }}">
                                        {{ $iLikedBack ? '❤️ Liked Back' : '🤍 Like Back' }}
                                    </button>
                                </form>

                                <small class="text-muted d-block mt-2">
                                    <ion-icon name="time-outline"></ion-icon>
                                    {{ $like->created_at->diffForHumans() }}
                                </small>

                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5 text-muted">
                                <ion-icon name="heart-outline" style="font-size:52px;color:#d1d5db;"></ion-icon>
                                <p class="mt-3 mb-0 fw-semibold">No likes received yet</p>
                                <p class="small text-muted mt-1">When someone likes your profile it will appear here.</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ── LIKES SENT ── --}}
            <div class="tab-pane fade" id="sent">
                <div class="row">
                    @forelse($likesSent as $like)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">

                                <div class="position-relative d-inline-block mb-3">
                                    <img src="{{ $like->liked->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                         class="rounded-circle" width="75" height="75"
                                         style="object-fit:cover; border: 3px solid #7c3aed;">
                                    <span style="position:absolute;bottom:0;right:0;background:#7c3aed;color:white;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;">🤍</span>
                                </div>

                                <h6 class="fw-bold mb-1">{{ $like->liked->name }}</h6>
                                <p class="text-muted small mb-1">{{ $like->liked->city ?? 'Location not set' }}</p>
                                <p class="text-muted small mb-3">{{ $like->liked->profession ?? '' }}</p>

                                {{-- Unlike button --}}
                                <form method="POST" action="{{ route('user.likes.toggle', $like->liked) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm w-100"
                                        onclick="return confirm('Remove like from this profile?')">
                                        💔 Unlike
                                    </button>
                                </form>

                                <small class="text-muted d-block mt-2">
                                    <ion-icon name="time-outline"></ion-icon>
                                    Liked {{ $like->created_at->diffForHumans() }}
                                </small>

                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5 text-muted">
                                <ion-icon name="heart-dislike-outline" style="font-size:52px;color:#d1d5db;"></ion-icon>
                                <p class="mt-3 mb-0 fw-semibold">You haven't liked any profiles yet</p>
                                <p class="small text-muted mt-1">Start discovering profiles and like the ones you're interested in.</p>
                                <a href="{{ route('user.discover') }}" class="btn btn-primary btn-sm px-4 mt-2">
                                    Discover Profiles
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ── MUTUAL LIKES ── --}}
            <div class="tab-pane fade" id="mutual">
                <div class="row">
                    @forelse($mutualLikes as $like)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100" style="border: 2px solid #16a34a;">
                            <div class="card-body text-center">

                                <div class="position-relative d-inline-block mb-3">
                                    <img src="{{ $like->liked->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                         class="rounded-circle" width="75" height="75"
                                         style="object-fit:cover; border: 3px solid #16a34a;">
                                    <span style="position:absolute;bottom:0;right:0;background:#16a34a;color:white;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;">💚</span>
                                </div>

                                <span class="badge bg-success mb-2">💚 Mutual Like</span>
                                <h6 class="fw-bold mb-1">{{ $like->liked->name }}</h6>
                                <p class="text-muted small mb-1">{{ $like->liked->city ?? 'Location not set' }}</p>
                                <p class="text-muted small mb-3">{{ $like->liked->profession ?? '' }}</p>

                                {{-- Send Proposal --}}
                                @php
                                    $existingProposal = \App\Models\Proposal::where('sender_id', Auth::id())
                                        ->where('receiver_id', $like->liked_id)
                                        ->first();
                                @endphp

                                @if($existingProposal?->status === 'accepted')
                                    @php
                                        $oid = $like->liked_id;
                                        $cOk = \App\Models\MatchRequest::messagingUnlockedBetween(Auth::id(), $oid);
                                        $pChat = \App\Models\ChatRequest::pendingBetween(Auth::id(), $oid);
                                    @endphp
                                    @if($cOk)
                                    <a href="{{ route('user.chat.with', $oid) }}" class="btn btn-success btn-sm w-100">
                                        💬 Chat Now
                                    </a>
                                    @elseif($pChat && $pChat->sender_id === Auth::id())
                                    <button type="button" class="btn btn-warning btn-sm w-100" disabled>⏳ Chat request pending</button>
                                    @elseif($pChat && $pChat->receiver_id === Auth::id())
                                    <a href="{{ route('user.requests.index', ['main' => 'chat']) }}" class="btn btn-primary btn-sm w-100">📥 Respond to chat request</a>
                                    @else
                                    <form method="POST" action="{{ route('user.chat-requests.store', $like->liked) }}" class="text-start">
                                        @csrf
                                        <textarea name="message" class="form-control form-control-sm mb-1" rows="2" maxlength="500" placeholder="Optional note"></textarea>
                                        <button type="submit" class="btn btn-primary btn-sm w-100">💬 Send chat request</button>
                                    </form>
                                    @endif
                                @elseif($existingProposal?->status === 'pending')
                                    <button class="btn btn-warning btn-sm w-100" disabled>
                                        ⏳ Proposal Sent
                                    </button>
                                @else
                                    <a href="{{ route('user.discover') }}" class="btn btn-outline-success btn-sm w-100">
                                        🕊️ Send Proposal
                                    </a>
                                @endif

                                <small class="text-muted d-block mt-2">
                                    <ion-icon name="time-outline"></ion-icon>
                                    Liked {{ $like->created_at->diffForHumans() }}
                                </small>

                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5 text-muted">
                                <ion-icon name="heart-circle-outline" style="font-size:52px;color:#d1d5db;"></ion-icon>
                                <p class="mt-3 mb-0 fw-semibold">No mutual likes yet</p>
                                <p class="small text-muted mt-1">When you and someone else both like each other they'll appear here.</p>
                                <a href="{{ route('user.discover') }}" class="btn btn-primary btn-sm px-4 mt-2">
                                    Discover Profiles
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

@endsection