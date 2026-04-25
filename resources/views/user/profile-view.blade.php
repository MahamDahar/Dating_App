@extends('layouts.user')

@section('usercontent')
<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Profile</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.saved.index') }}"><ion-icon name="bookmark-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">{{ $user->name ?? 'User' }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1 fw-semibold">{{ $user->name ?? 'User' }}</h5>
                        <div class="text-muted" style="font-size:13px;">
                            {{ $profile?->city ? $profile->city : '' }}
                            @if($profile?->city && $profile?->country) • @endif
                            {{ $profile?->country ? $profile->country : '' }}
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($messagingUnlocked)
                        <a href="{{ route('user.chat.index') }}?user_id={{ $user->id }}" class="btn btn-dark btn-sm px-3">
                            <ion-icon name="chatbubble-outline"></ion-icon> Message
                        </a>
                        @elseif($proposalAccepted && $pendingChatRequest && $pendingChatRequest->sender_id === Auth::id())
                        <button type="button" class="btn btn-warning btn-sm px-3" disabled>Chat request sent</button>
                        @elseif($proposalAccepted && $pendingChatRequest && $pendingChatRequest->receiver_id === Auth::id())
                        <a href="{{ route('user.requests.index', ['main' => 'chat']) }}" class="btn btn-primary btn-sm px-3">Chat request — respond</a>
                        @elseif($proposalAccepted)
                        <form method="POST" action="{{ route('user.chat-requests.store', $user) }}" class="d-flex flex-column align-items-stretch gap-1" style="min-width:180px;">
                            @csrf
                            <textarea name="message" class="form-control form-control-sm" rows="2" maxlength="500" placeholder="Optional note"></textarea>
                            <button type="submit" class="btn btn-primary btn-sm px-3">Send chat request</button>
                        </form>
                        @else
                        <a href="{{ route('user.discover') }}" class="btn btn-outline-secondary btn-sm px-3">Discover</a>
                        @endif
                        <a href="{{ route('user.saved.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                            <ion-icon name="arrow-back-outline"></ion-icon> Back
                        </a>
                    </div>
                </div>

                <hr class="my-3">

                @php
                    $main = $photos->firstWhere('is_main', true) ?? $photos->first();
                @endphp

                <div class="row g-3">
                    <div class="col-12 col-lg-5">
                        <div style="width:100%;aspect-ratio:1/1;border-radius:18px;overflow:hidden;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                            @if($main)
                                <img src="{{ asset('storage/'.$main->path) }}" alt="Photo"
                                     style="width:100%;height:100%;object-fit:cover;{{ $main->is_blurred ? 'filter:blur(8px);transform:scale(1.03);' : '' }}">
                            @else
                                <div style="font-size:48px;font-weight:800;color:#9ca3af;">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        @if($photos->count() > 1)
                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                @foreach($photos as $ph)
                                    <div style="width:62px;height:62px;border-radius:14px;overflow:hidden;background:#f3f4f6;">
                                        <img src="{{ asset('storage/'.$ph->path) }}" alt="Photo"
                                             style="width:100%;height:100%;object-fit:cover;{{ $ph->is_blurred ? 'filter:blur(8px);transform:scale(1.03);' : '' }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="col-12 col-lg-7">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 rounded-4" style="background:#f8fafc;">
                                    <div class="text-muted" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Marital Status</div>
                                    <div class="fw-semibold" style="margin-top:6px;">{{ $profile?->marital_status ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4" style="background:#f8fafc;">
                                    <div class="text-muted" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Education</div>
                                    <div class="fw-semibold" style="margin-top:6px;">{{ $profile?->education ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4" style="background:#f8fafc;">
                                    <div class="text-muted" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Profession</div>
                                    <div class="fw-semibold" style="margin-top:6px;">{{ $profile?->profession ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4" style="background:#f8fafc;">
                                    <div class="text-muted" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Sect</div>
                                    <div class="fw-semibold" style="margin-top:6px;">{{ $profile?->sect ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 rounded-4" style="background:#fff7ed;">
                                    <div class="text-muted" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Bio</div>
                                    <div style="margin-top:8px;color:#374151;line-height:1.7;">
                                        {{ $profile?->bio ?: '—' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

