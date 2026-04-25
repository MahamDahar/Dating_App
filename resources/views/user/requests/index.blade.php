@extends('layouts.user')
@section('usercontent')

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Requests</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.discover') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Requests</li>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <ion-icon name="alert-circle-outline" class="me-2"></ion-icon>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Main: marriage proposals vs chat requests --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ $mainTab === 'proposals' ? 'active' : '' }}"
                   href="{{ route('user.requests.index', ['main' => 'proposals']) }}">
                    Marriage proposals
                    @if($received->count() > 0)
                        <span class="badge bg-danger ms-1">{{ $received->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $mainTab === 'chat' ? 'active' : '' }}"
                   href="{{ route('user.requests.index', ['main' => 'chat']) }}">
                    Chat requests
                    @if($chatReceived->count() > 0)
                        <span class="badge bg-danger ms-1">{{ $chatReceived->count() }}</span>
                    @endif
                </a>
            </li>
        </ul>

        @if($mainTab === 'proposals')

            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#received">
                        Received
                        @if($received->count() > 0)
                            <span class="badge bg-danger ms-1">{{ $received->count() }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#sent">
                        Sent
                        @if($sent->count() > 0)
                            <span class="badge bg-secondary ms-1">{{ $sent->count() }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="received">
                    <div class="row">
                        @forelse($received as $proposal)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">

                                    <img src="{{ $proposal->sender->mainProfilePhotoUrl() ?? asset('admin/assets/images/avatars/06.png') }}"
                                         alt="{{ $proposal->sender->name }}"
                                         class="rounded-circle mb-3" width="70" height="70"
                                         style="object-fit:cover; border: 3px solid #7c3aed;">

                                    <h6 class="fw-bold mb-1">{{ $proposal->sender->name }}</h6>
                                    <p class="text-muted small mb-1">
                                        @php
                                            $loc = trim(collect([
                                                $proposal->sender->profile?->city,
                                                $proposal->sender->country ?? $proposal->sender->profile?->country,
                                            ])->filter()->implode(', '));
                                        @endphp
                                        {{ $loc !== '' ? $loc : 'Location not set' }}
                                    </p>
                                    <p class="text-muted small mb-3">{{ $proposal->sender->profile?->profession ?? '' }}</p>

                                    <div class="bg-light rounded p-2 mb-3 text-start">
                                        <small class="text-muted fw-semibold">Their message:</small>
                                        <p class="small mb-0 mt-1">{{ Str::limit($proposal->message, 120) }}</p>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-center flex-wrap">

                                        <form method="POST" action="{{ route('user.proposals.accept', $proposal) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm px-3">
                                                <ion-icon name="checkmark-outline"></ion-icon> Accept
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('user.proposals.reject', $proposal) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm px-3">
                                                <ion-icon name="close-outline"></ion-icon> Reject
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('user.proposals.block', $proposal) }}"
                                              onsubmit="return confirm('Are you sure you want to block this user?')">
                                            @csrf
                                            <button type="submit" class="btn btn-dark btn-sm px-3">
                                                <ion-icon name="ban-outline"></ion-icon> Block
                                            </button>
                                        </form>

                                    </div>

                                    <small class="text-muted d-block mt-3">
                                        <ion-icon name="time-outline"></ion-icon>
                                        Received {{ $proposal->created_at->diffForHumans() }}
                                    </small>

                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5 text-muted">
                                    <ion-icon name="mail-unread-outline" style="font-size:52px; color:#d1d5db;"></ion-icon>
                                    <p class="mt-3 mb-0 fw-semibold">No pending proposals received</p>
                                    <p class="small text-muted mt-1">When someone sends you a proposal it will appear here.</p>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="tab-pane fade" id="sent">
                    <div class="row">
                        @forelse($sent as $proposal)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">

                                    <img src="{{ $proposal->receiver->mainProfilePhotoUrl() ?? asset('admin/assets/images/avatars/06.png') }}"
                                         alt="{{ $proposal->receiver->name }}"
                                         class="rounded-circle mb-3" width="70" height="70"
                                         style="object-fit:cover; border: 3px solid #7c3aed;">

                                    <h6 class="fw-bold mb-1">{{ $proposal->receiver->name }}</h6>
                                    <p class="text-muted small mb-1">
                                        @php
                                            $locR = trim(collect([
                                                $proposal->receiver->profile?->city,
                                                $proposal->receiver->country ?? $proposal->receiver->profile?->country,
                                            ])->filter()->implode(', '));
                                        @endphp
                                        {{ $locR !== '' ? $locR : 'Location not set' }}
                                    </p>
                                    <p class="text-muted small mb-3">{{ $proposal->receiver->profile?->profession ?? '' }}</p>

                                    <div class="bg-light rounded p-2 mb-3 text-start">
                                        <small class="text-muted fw-semibold">Your message:</small>
                                        <p class="small mb-0 mt-1">{{ Str::limit($proposal->message, 120) }}</p>
                                    </div>

                                    <button class="btn btn-warning btn-sm w-100" disabled>
                                        <ion-icon name="time-outline"></ion-icon> Awaiting Response
                                    </button>

                                    <small class="text-muted d-block mt-3">
                                        <ion-icon name="time-outline"></ion-icon>
                                        Sent {{ $proposal->created_at->diffForHumans() }}
                                    </small>

                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5 text-muted">
                                    <ion-icon name="paper-plane-outline" style="font-size:52px; color:#d1d5db;"></ion-icon>
                                    <p class="mt-3 mb-0 fw-semibold">No pending proposals sent</p>
                                    <p class="small text-muted mt-1">Proposals you send will appear here until they respond.</p>
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

        @else

            <p class="text-muted small mb-3">After a marriage proposal is accepted, either of you can send a chat request here. Messaging opens only when that chat request is accepted.</p>

            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#chat-received">
                        Received
                        @if($chatReceived->count() > 0)
                            <span class="badge bg-danger ms-1">{{ $chatReceived->count() }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#chat-sent">
                        Sent
                        @if($chatSent->count() > 0)
                            <span class="badge bg-secondary ms-1">{{ $chatSent->count() }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="chat-received">
                    <div class="row">
                        @forelse($chatReceived as $chatRequest)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <img src="{{ $chatRequest->sender->mainProfilePhotoUrl() ?? asset('admin/assets/images/avatars/06.png') }}"
                                         alt="{{ $chatRequest->sender->name }}"
                                         class="rounded-circle mb-3" width="70" height="70"
                                         style="object-fit:cover; border: 3px solid #0d6efd;">

                                    <h6 class="fw-bold mb-1">{{ $chatRequest->sender->name }}</h6>
                                    <p class="text-muted small mb-3">Wants to start chatting with you</p>

                                    @if($chatRequest->message)
                                    <div class="bg-light rounded p-2 mb-3 text-start">
                                        <small class="text-muted fw-semibold">Their note:</small>
                                        <p class="small mb-0 mt-1">{{ Str::limit($chatRequest->message, 200) }}</p>
                                    </div>
                                    @endif

                                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                                        <form method="POST" action="{{ route('user.chat-requests.accept', $chatRequest) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm px-3">
                                                <ion-icon name="checkmark-outline"></ion-icon> Accept
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('user.chat-requests.reject', $chatRequest) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                                                <ion-icon name="close-outline"></ion-icon> Decline
                                            </button>
                                        </form>
                                    </div>

                                    <small class="text-muted d-block mt-3">
                                        {{ $chatRequest->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5 text-muted">
                                    <ion-icon name="chatbubbles-outline" style="font-size:52px; color:#d1d5db;"></ion-icon>
                                    <p class="mt-3 mb-0 fw-semibold">No chat requests received</p>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="tab-pane fade" id="chat-sent">
                    <div class="row">
                        @forelse($chatSent as $chatRequest)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <img src="{{ $chatRequest->receiver->mainProfilePhotoUrl() ?? asset('admin/assets/images/avatars/06.png') }}"
                                         alt="{{ $chatRequest->receiver->name }}"
                                         class="rounded-circle mb-3" width="70" height="70"
                                         style="object-fit:cover; border: 3px solid #6c757d;">

                                    <h6 class="fw-bold mb-1">{{ $chatRequest->receiver->name }}</h6>
                                    <p class="text-muted small mb-3">Waiting for them to accept your chat request</p>

                                    @if($chatRequest->message)
                                    <div class="bg-light rounded p-2 mb-3 text-start">
                                        <small class="text-muted fw-semibold">Your note:</small>
                                        <p class="small mb-0 mt-1">{{ Str::limit($chatRequest->message, 200) }}</p>
                                    </div>
                                    @endif

                                    <button class="btn btn-warning btn-sm w-100" disabled>
                                        <ion-icon name="time-outline"></ion-icon> Pending
                                    </button>

                                    <small class="text-muted d-block mt-3">
                                        Sent {{ $chatRequest->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5 text-muted">
                                    <ion-icon name="paper-plane-outline" style="font-size:52px; color:#d1d5db;"></ion-icon>
                                    <p class="mt-3 mb-0 fw-semibold">No chat requests sent</p>
                                    <p class="small text-muted mt-1">Send one from Discover or Proposals after they accept your marriage proposal.</p>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        @endif

    </div>
</div>

@endsection
