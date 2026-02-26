@extends('layouts.user')
@section('usercontent')

<style>
    .plans-page {
        min-height: 100vh;
        background: #f0f2f8;
        padding: 40px 24px;
        font-family: 'DM Sans', sans-serif;
    }

    .plans-hero {
        text-align: center;
        margin-bottom: 40px;
    }

    .plans-hero h1 {
        font-size: 32px;
        font-weight: 900;
        color: #0f0f13;
    }

    .plans-hero h1 span { color: #7c3aed; }

    .plans-hero p {
        color: #6b7280;
        font-size: 15px;
        margin-top: 8px;
    }

    .plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        max-width: 700px;
        margin: 0 auto;
    }

    .plan-card {
        background: white;
        border-radius: 20px;
        padding: 30px 24px;
        border: 2px solid #e8eaf0;
        text-align: center;
        transition: transform .2s, box-shadow .2s;
    }

    .plan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(124, 58, 237, 0.12);
    }

    .plan-card.popular {
        border-color: #7c3aed;
        position: relative;
    }

    .popular-badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: #7c3aed;
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 14px;
        border-radius: 20px;
        white-space: nowrap;
    }

    .plan-icon { font-size: 36px; margin-bottom: 12px; }

    .plan-name {
        font-size: 18px;
        font-weight: 700;
        color: #0f0f13;
        margin-bottom: 6px;
    }

    .plan-price {
        font-size: 36px;
        font-weight: 900;
        color: #7c3aed;
        margin: 12px 0 4px;
    }

    .plan-price span {
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
    }

    .plan-features {
        list-style: none;
        padding: 0;
        margin: 20px 0;
        text-align: left;
    }

    .plan-features li {
        font-size: 13px;
        color: #4b5563;
        padding: 6px 0;
        border-bottom: 1px solid #f5f5f5;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .plan-features li:last-child { border-bottom: none; }

    .btn-subscribe {
        width: 100%;
        padding: 12px;
        background: #0f0f13;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: background .2s;
        text-decoration: none;
        display: block;
    }

    .btn-subscribe:hover { background: #333; color: white; }

    .plan-card.popular .btn-subscribe {
        background: #7c3aed;
    }

    .plan-card.popular .btn-subscribe:hover {
        background: #6d28d9;
    }
</style>

<div class="plans-page">

    <div class="plans-hero">
        <h1>👑 Upgrade to <span>Premium</span></h1>
        <p>Unlock country filters and discover matches from anywhere in the world</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger text-center mb-4">{{ session('error') }}</div>
    @endif

    <div class="plans-grid">

        {{-- FREE PLAN --}}
        <div class="plan-card">
            <div class="plan-icon">🆓</div>
            <div class="plan-name">Free</div>
            <div class="plan-price">$0 <span>/ forever</span></div>
            <ul class="plan-features">
                <li>✅ Basic filters</li>
                <li>✅ Pakistan, India, Bangladesh</li>
                <li>✅ View match profiles</li>
                <li>❌ Other countries locked</li>
                <li>❌ Advanced filters locked</li>
            </ul>
            <a href="{{ route('user.discover') }}" class="btn-subscribe">
                Continue Free
            </a>
        </div>

        {{-- PREMIUM PLAN --}}
        <div class="plan-card popular">
            <div class="popular-badge">⭐ Most Popular</div>
            <div class="plan-icon">👑</div>
            <div class="plan-name">Premium</div>
            <div class="plan-price">$9.99 <span>/ month</span></div>
            <ul class="plan-features">
                <li>✅ Everything in Free</li>
                <li>✅ Filter by ALL countries</li>
                <li>✅ USA, UK, Canada, UAE & more</li>
                <li>✅ Advanced filters</li>
                <li>✅ Priority matches</li>
            </ul>
            {{-- Stripe checkout will go here later --}}
            <a href="#" class="btn-subscribe">
                Upgrade Now 👑
            </a>
        </div>

    </div>
</div>

@endsection