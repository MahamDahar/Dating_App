@extends('layouts.user')
@section('usercontent')

<style>
    .success-page {
        min-height: 100vh;
        background: #f0f2f8;
        padding: 60px 24px;
        font-family: 'DM Sans', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .success-card {
        background: white;
        border-radius: 24px;
        padding: 50px 40px;
        max-width: 480px;
        width: 100%;
        text-align: center;
        box-shadow: 0 16px 50px rgba(124, 58, 237, 0.12);
        border: 2px solid #ede9fe;
        animation: popIn .4s ease;
    }

    @keyframes popIn {
        from { opacity: 0; transform: scale(.94); }
        to   { opacity: 1; transform: scale(1); }
    }

    .success-icon {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        margin: 0 auto 24px;
        animation: bounceIn .6s ease;
    }

    @keyframes bounceIn {
        0%   { transform: scale(0); }
        60%  { transform: scale(1.15); }
        100% { transform: scale(1); }
    }

    .success-title {
        font-size: 26px;
        font-weight: 900;
        color: #0f0f13;
        margin-bottom: 10px;
    }

    .success-title span { color: #7c3aed; }

    .success-sub {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.7;
        margin-bottom: 30px;
    }

    .success-perks {
        background: #f5f3ff;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 28px;
        text-align: left;
    }

    .success-perks-title {
        font-size: 12px;
        font-weight: 700;
        color: #7c3aed;
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 12px;
    }

    .perk-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #374151;
        padding: 6px 0;
        border-bottom: 1px solid #ede9fe;
    }

    .perk-item:last-child { border-bottom: none; }

    .premium-badge-big {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 24px;
    }

    .expires-info {
        font-size: 12px;
        color: #9ca3af;
        margin-bottom: 28px;
    }

    .btn-discover {
        display: block;
        width: 100%;
        padding: 14px;
        background: #0f0f13;
        color: white;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: background .2s;
        margin-bottom: 10px;
    }

    .btn-discover:hover { background: #333; color: white; }

    .btn-back {
        display: block;
        width: 100%;
        padding: 12px;
        background: #f3f4f6;
        color: #6b7280;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s;
    }

    .btn-back:hover { background: #e5e7eb; color: #374151; }

    .confetti {
        font-size: 28px;
        letter-spacing: 6px;
        margin-bottom: 16px;
    }
</style>

<div class="success-page">
    <div class="success-card">

        <div class="confetti">🎉 🎊 🎉</div>

        <div class="success-icon">👑</div>

        <div class="success-title">Welcome to <span>Premium!</span></div>
        <div class="success-sub">
            Your account has been upgraded successfully. You now have access to all premium features!
        </div>

        <div class="premium-badge-big">
            👑 Premium Member
        </div>

        <div class="expires-info">
            ✅ Active until {{ now()->addMonth()->format('F d, Y') }}
        </div>

        <div class="success-perks">
            <div class="success-perks-title">🔓 What's now unlocked</div>
            <div class="perk-item">🌍 Filter matches by ALL countries</div>
            <div class="perk-item">🇺🇸 USA, 🇬🇧 UK, 🇨🇦 Canada, 🇦🇪 UAE & more</div>
            <div class="perk-item">🔍 Advanced filters</div>
            <div class="perk-item">⭐ Priority matches</div>
            <div class="perk-item">💬 Unlimited messaging</div>
        </div>

        <a href="{{ route('user.discover') }}" class="btn-discover">
            🔍 Start Discovering Matches
        </a>
        <a href="{{ route('user.dashboard') }}" class="btn-back">
            Go to Dashboard
        </a>

    </div>
</div>

@endsection