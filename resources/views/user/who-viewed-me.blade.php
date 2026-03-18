@extends('layouts.user')
@section('usercontent')

    <style>
        /* .wvm-page {
                min-height: 100vh;
                background: #f0f2f8;
                padding: 32px 24px 60px;
                font-family: 'DM Sans', sans-serif;
                box-sizing: border-box;
            } */

        /* ── Header ── */
        .wvm-header {
            max-width: 900px;
            margin: 0 auto 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .wvm-title {
            font-size: 24px;
            font-weight: 900;
            color: #0f0f13;
        }

        .wvm-title span {
            color: #7c3aed;
        }

        .wvm-weekly {
            background: white;
            border-radius: 12px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .wvm-weekly strong {
            color: #7c3aed;
            font-size: 18px;
        }

        /* ── Premium banner ── */
        .premium-banner {
            max-width: 900px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border-radius: 16px;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .premium-banner-text {
            color: white;
        }

        .premium-banner-text h4 {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .premium-banner-text p {
            font-size: 13px;
            opacity: .85;
            margin: 0;
        }

        .btn-upgrade-banner {
            background: white;
            color: #7c3aed;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: all .2s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-upgrade-banner:hover {
            background: #f5f3ff;
            color: #6d28d9;
        }

        /* ── Grid ── */
        .wvm-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            max-width: 900px;
            margin: 0 auto;
        }

        /* ── Viewer card ── */
        .viewer-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            transition: transform .2s, box-shadow .2s;
            position: relative;
        }

        .viewer-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .10);
        }

        /* Avatar area */
        .viewer-avatar {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
            background: #e5e7eb;
        }

        .viewer-avatar-placeholder {
            width: 100%;
            height: 160px;
            background: linear-gradient(135deg, #e0e7ff, #ede9fe);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 52px;
        }

        /* Blurred card (free users see this) */
        .viewer-card.blurred .viewer-avatar-placeholder,
        .viewer-card.blurred .viewer-avatar {
            filter: blur(12px);
            transform: scale(1.05);
        }

        .viewer-card.blurred .viewer-info {
            filter: blur(6px);
            pointer-events: none;
            user-select: none;
        }

        .lock-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            z-index: 2;
        }

        .lock-icon-wrap {
            width: 44px;
            height: 44px;
            background: rgba(124, 58, 237, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .lock-label {
            background: rgba(0, 0, 0, .65);
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            text-align: center;
        }

        /* Card info */
        .viewer-info {
            padding: 12px 14px;
        }

        .viewer-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f0f13;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .viewer-meta {
            font-size: 12px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .viewer-time {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }

        /* New badge */
        .new-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #7c3aed;
            color: white;
            font-size: 10px;
            font-weight: 800;
            padding: 3px 9px;
            border-radius: 20px;
            z-index: 3;
        }

        /* Empty state */
        .wvm-empty {
            max-width: 900px;
            margin: 40px auto;
            text-align: center;
            padding: 60px 24px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
        }

        .wvm-empty-icon {
            font-size: 60px;
            margin-bottom: 16px;
        }

        .wvm-empty h3 {
            font-size: 20px;
            font-weight: 800;
            color: #0f0f13;
            margin-bottom: 8px;
        }

        .wvm-empty p {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
        }
    </style>
    <div class="page-content-wrapper">
        <div class="profile-page">


            {{-- Header --}}
            <div class="wvm-header">
                <div class="wvm-title">👁 Who Viewed <span>Me</span></div>
                <div class="wvm-weekly">
                    <span>This week:</span>
                    <strong>{{ $weeklyCount }}</strong>
                    <span>{{ Str::plural('view', $weeklyCount) }}</span>
                </div>
            </div>

            {{-- Premium banner for free users --}}
            @if (!$isPremium && $viewers->count() > 0)
                <div class="premium-banner">
                    <div class="premium-banner-text">
                        <h4>🔓 Unlock Who Viewed You</h4>
                        <p>Upgrade to Premium to see exactly who visited your profile</p>
                    </div>
                    <a href="{{ route('user.premium.plans') }}" class="btn-upgrade-banner">
                        👑 Upgrade Now
                    </a>
                </div>
            @endif

            {{-- Viewer cards --}}
            @if ($viewers->count() > 0)
                <div class="wvm-grid">
                    @foreach ($viewers as $view)
                        @php
                            $viewer = $view->viewer;
                            $profile = $viewer?->profile;
                            $isBlurred = !$isPremium;
                            $isNew = !$view->seen;
                            $name = $isBlurred ? 'Someone' : $viewer->name ?? 'Unknown';
                            $city = $isBlurred ? '••••••' : $profile->nationality ?? ($viewer->city ?? 'Unknown');
                        @endphp

                        <div class="viewer-card {{ $isBlurred ? 'blurred' : '' }}">

                            {{-- New badge --}}
                            @if ($isNew)
                                <span class="new-badge">NEW</span>
                            @endif

                            {{-- Avatar --}}
                            <div class="viewer-avatar-placeholder">
                                {{ $isBlurred ? '👤' : '👤' }}
                            </div>

                            {{-- Lock overlay for free users --}}
                            @if ($isBlurred)
                                <div class="lock-overlay">
                                    <div class="lock-icon-wrap">🔒</div>
                                    <div class="lock-label">Premium Only</div>
                                </div>
                            @endif

                            {{-- Info --}}
                            <div class="viewer-info">
                                <div class="viewer-name">{{ $name }}</div>
                                <div class="viewer-meta">
                                    📍 {{ $city }}
                                </div>
                                <div class="viewer-time">
                                    {{ $view->viewed_at->diffForHumans() }}
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty state --}}
                <div class="wvm-empty">
                    <div class="wvm-empty-icon">👁</div>
                    <h3>No views yet</h3>
                    <p>When someone visits your profile, they'll appear here.<br>
                        Complete your profile to attract more visitors!</p>
                    <a href="{{ route('user.profile') }}"
                        style="display:inline-block;margin-top:20px;padding:12px 24px;background:#7c3aed;color:white;border-radius:12px;font-weight:700;text-decoration:none;font-size:14px;">
                        ✏️ Complete My Profile
                    </a>
                </div>
            @endif



        </div>
    </div>

@endsection
