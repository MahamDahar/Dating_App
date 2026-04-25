@extends('layouts.user')
@section('usercontent')
<!-- start page content wrapper-->
    <div class="page-content-wrapper">
        <!-- start page content-->
        <div class="page-content">
<div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Proposals Sent</div>
</div>

<div class="row">
    @forelse($proposals as $proposal)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">

                {{-- Receiver Profile Photo --}}
                <img src="{{ $proposal->receiver->mainProfilePhotoUrl() ?? asset('admin/assets/images/avatars/06.png') }}"
                     alt="{{ $proposal->receiver->name }}"
                     class="rounded-circle mb-3" width="80" height="80"
                     style="object-fit:cover; border: 3px solid #7c3aed;">

                <h6 class="fw-bold mb-1">{{ $proposal->receiver->name }}</h6>
                <p class="text-muted small mb-2">
                    @php
                        $loc = trim(collect([
                            $proposal->receiver->profile?->city,
                            $proposal->receiver->country ?? $proposal->receiver->profile?->country,
                        ])->filter()->implode(', '));
                    @endphp
                    {{ $loc !== '' ? $loc : 'Location not set' }}
                </p>

                {{-- Status Badge --}}
                <span class="badge bg-{{
                    $proposal->status === 'accepted' ? 'success' :
                    ($proposal->status === 'rejected' ? 'danger' : 'warning')
                }} mb-3">
                    {{ ucfirst($proposal->status) }}
                </span>

                {{-- Message --}}
                <div class="bg-light rounded p-2 mb-3 text-start">
                    <small class="text-muted">Your message:</small>
                    <p class="small mb-0">{{ Str::limit($proposal->message, 100) }}</p>
                </div>

                {{-- Actions --}}
                @if($proposal->isAccepted())
                    @php
                        $otherId = $proposal->receiver_id;
                        $chatOk = \App\Models\MatchRequest::messagingUnlockedBetween(Auth::id(), $otherId);
                        $pendChat = \App\Models\ChatRequest::pendingBetween(Auth::id(), $otherId);
                    @endphp
                    @if($chatOk)
                    <a href="{{ route('user.chat.with', $otherId) }}" class="btn btn-success btn-sm w-100">
                        <ion-icon name="chatbubbles-outline"></ion-icon> Start Chat
                    </a>
                    @elseif($pendChat && $pendChat->sender_id === Auth::id())
                    <button type="button" class="btn btn-warning btn-sm w-100" disabled>
                        <ion-icon name="time-outline"></ion-icon> Chat request sent — waiting
                    </button>
                    @elseif($pendChat && $pendChat->receiver_id === Auth::id())
                    <a href="{{ route('user.requests.index', ['main' => 'chat']) }}" class="btn btn-primary btn-sm w-100">
                        <ion-icon name="mail-unread-outline"></ion-icon> They sent a chat request — respond
                    </a>
                    @else
                    <form method="POST" action="{{ route('user.chat-requests.store', $proposal->receiver) }}" class="text-start">
                        @csrf
                        <label class="form-label small text-muted mb-1">Optional note</label>
                        <textarea name="message" class="form-control form-control-sm mb-2" rows="2" maxlength="500" placeholder="Salaam…"></textarea>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <ion-icon name="chatbubbles-outline"></ion-icon> Send chat request
                        </button>
                    </form>
                    @endif
                @elseif($proposal->isPending())
                    <button class="btn btn-warning btn-sm w-100" disabled>
                        <ion-icon name="time-outline"></ion-icon> Awaiting Response
                    </button>
                @else
                    <button class="btn btn-secondary btn-sm w-100" disabled>
                        <ion-icon name="close-circle-outline"></ion-icon> Rejected
                    </button>
                @endif

                <small class="text-muted d-block mt-2">
                    Sent {{ $proposal->created_at->diffForHumans() }}
                </small>

            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <ion-icon name="paper-plane-outline" style="font-size:48px;"></ion-icon>
                <p class="mt-3 mb-3">You haven't sent any proposals yet.</p>
                <a href="{{ route('user.discover') }}" class="btn btn-primary btn-sm px-4">
                    Discover Profiles
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>
</div>
</div>

@endsection