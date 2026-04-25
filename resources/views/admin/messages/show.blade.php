@extends('layouts.admin')
@section('admincontent')

<style>
    .chat-wrapper {
        height: calc(100vh - 280px);
        overflow-y: auto;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .chat-wrapper::-webkit-scrollbar { width: 4px; }
    .chat-wrapper::-webkit-scrollbar-thumb { background: #7c3aed; border-radius: 10px; }

    .msg-bubble {
        max-width: 65%;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 13px;
        line-height: 1.6;
        position: relative;
    }
    .msg-left {
        background: white;
        border: 1px solid #e8eaf0;
        border-bottom-left-radius: 4px;
        align-self: flex-start;
        color: #1e1e2e;
    }
    .msg-right {
        background: #7c3aed;
        color: white;
        border-bottom-right-radius: 4px;
        align-self: flex-end;
    }
    .msg-time {
        font-size: 10px;
        margin-top: 4px;
        opacity: 0.6;
    }
    .msg-sender-name {
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 4px;
        opacity: 0.7;
    }
    .chat-date-divider {
        text-align: center;
        font-size: 11px;
        color: #9ca3af;
        margin: 8px 0;
        position: relative;
    }
    .chat-date-divider::before,
    .chat-date-divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 35%;
        height: 1px;
        background: #e5e7eb;
    }
    .chat-date-divider::before { left: 0; }
    .chat-date-divider::after  { right: 0; }
</style>

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Conversation</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.messages.index') }}">Messages</a>
                        </li>
                        <li class="breadcrumb-item active">Conversation</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.messages.index') }}" class="btn btn-light btn-sm">
                    <ion-icon name="arrow-back-outline"></ion-icon> Back to List
                </a>
            </div>
        </div>

        {{-- Users Header --}}
        <div class="card mb-3">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

                    {{-- User 1 --}}
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $user1->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                             class="rounded-circle" width="48" height="48" style="object-fit:cover;border:3px solid #7c3aed;">
                        <div>
                            <div class="fw-bold">{{ $user1->name }}</div>
                            <small class="text-muted">{{ $user1->email }}</small>
                        </div>
                    </div>

                    {{-- VS --}}
                    <div class="text-center">
                        <span style="font-size:20px;">💬</span>
                        <div style="font-size:11px;color:#9ca3af;">{{ $messages->count() }} messages</div>
                    </div>

                    {{-- User 2 --}}
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $user2->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                             class="rounded-circle" width="48" height="48" style="object-fit:cover;border:3px solid #a855f7;">
                        <div>
                            <div class="fw-bold">{{ $user2->name }}</div>
                            <small class="text-muted">{{ $user2->email }}</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Chat View --}}
        <div class="card">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold">
                    <ion-icon name="chatbubbles-outline" class="me-2" style="color:#7c3aed;"></ion-icon>
                    Chat History
                </h6>
            </div>
            <div class="card-body p-3">

                @if($messages->count() > 0)
                <div class="chat-wrapper" id="chatWrapper">

                    @php $lastDate = null; @endphp

                    @foreach($messages as $message)

                        @php $msgDate = $message->created_at->format('d M Y'); @endphp

                        {{-- Date divider --}}
                        @if($msgDate !== $lastDate)
                            <div class="chat-date-divider">{{ $msgDate }}</div>
                            @php $lastDate = $msgDate; @endphp
                        @endif

                        @php $isUser1 = $message->sender_id == $user1->id; @endphp

                        <div class="d-flex flex-column {{ $isUser1 ? 'align-items-start' : 'align-items-end' }}">
                            <div class="msg-sender-name {{ $isUser1 ? 'text-start' : 'text-end' }}" style="color:{{ $isUser1 ? '#7c3aed' : '#a855f7' }}">
                                {{ $isUser1 ? $user1->name : $user2->name }}
                            </div>
                            <div class="msg-bubble {{ $isUser1 ? 'msg-left' : 'msg-right' }}">
                                {{ $message->message }}
                                <div class="msg-time {{ $isUser1 ? 'text-start' : 'text-end' }}">
                                    {{ $message->created_at->format('h:i A') }}
                                </div>
                            </div>
                        </div>

                    @endforeach

                </div>

                @else
                <div class="text-center py-5 text-muted">
                    <ion-icon name="chatbubble-ellipses-outline" style="font-size:52px;color:#d1d5db;"></ion-icon>
                    <p class="mt-3">No messages in this conversation yet.</p>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>

<script>
    // Auto scroll to bottom of chat
    const chatWrapper = document.getElementById('chatWrapper');
    if (chatWrapper) {
        chatWrapper.scrollTop = chatWrapper.scrollHeight;
    }
</script>

@endsection