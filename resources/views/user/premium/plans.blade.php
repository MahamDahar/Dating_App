@extends('layouts.user')
@section('usercontent')
  <div class="page-content-wrapper">
    <div class="page-content">
<style>
    .plans-page {
        min-height: 100vh;
        background: #f0f2f8;
        padding: 40px 24px 60px;
        font-family: 'DM Sans', sans-serif;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .plans-hero { text-align: center; margin-bottom: 40px; width: 100%; max-width: 900px; }
    .plans-hero h1 { font-size: 32px; font-weight: 900; color: #0f0f13; }
    .plans-hero h1 span { color: #7c3aed; }
    .plans-hero p { color: #6b7280; font-size: 15px; margin-top: 8px; }

    .plans-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
    }

    @media (max-width: 600px) {
        .plans-grid { gap: 14px; }
    }

    .plan-card {
        background: white;
        border-radius: 20px;
        padding: 30px 24px;
        border: 2px solid #e8eaf0;
        text-align: center;
        transition: transform .2s, box-shadow .2s;
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 320px;
    }

    .plan-card:hover { box-shadow: 0 8px 24px rgba(124,58,237,.12); transform: translateY(-2px); }
    .plan-card.featured { border-color: #7c3aed; }

    .featured-badge {
        position: absolute;
        top: -12px; left: 50%;
        transform: translateX(-50%);
        background: #7c3aed;
        color: white;
        font-size: 11px; font-weight: 700;
        padding: 4px 14px;
        border-radius: 20px;
        white-space: nowrap;
    }

    .plan-icon { font-size: 36px; margin-bottom: 12px; }
    .plan-name { font-size: 18px; font-weight: 700; color: #0f0f13; margin-bottom: 6px; }
    .plan-price { font-size: 38px; font-weight: 900; color: #7c3aed; margin: 12px 0 2px; line-height: 1; }
    .plan-price.free-price { color: #0f0f13; }
    .plan-period { font-size: 13px; color: #6b7280; margin-bottom: 16px; }

    .plan-desc { font-size: 13px; color: #6b7280; margin-bottom: 16px; min-height: 40px; }

    .plan-features {
        list-style: none; padding: 0; margin: 0 0 20px;
        text-align: left; flex: 1;
    }
    .plan-features li {
        font-size: 13px; color: #4b5563;
        padding: 6px 0; border-bottom: 1px solid #f5f5f5;
        display: flex; align-items: center; gap: 8px;
    }
    .plan-features li:last-child { border-bottom: none; }

    .btn-subscribe {
        width: 100%; padding: 12px;
        background: #0f0f13; color: white;
        border: none; border-radius: 12px;
        font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: 'DM Sans', sans-serif;
        transition: background .2s;
        text-decoration: none; display: block; text-align: center;
        margin-top: auto;
    }
    .btn-subscribe:hover { background: #333; color: white; }
    .plan-card.featured .btn-subscribe { background: #7c3aed; }
    .plan-card.featured .btn-subscribe:hover { background: #6d28d9; }

    /* Current plan badge */
    .current-badge {
        display: inline-block;
        background: #d1fae5; color: #065f46;
        font-size: 11px; font-weight: 700;
        padding: 3px 10px; border-radius: 20px;
        margin-bottom: 8px;
    }

    .alert-error {
        max-width: 900px; margin: 0 auto 20px;
        background: #fde8e8; color: #bf1a1a;
        padding: 12px 18px; border-radius: 10px;
        font-size: 13px; font-weight: 600;
        border-left: 4px solid #bf1a1a;
    }
    .alert-success {
        max-width: 900px; margin: 0 auto 20px;
        background: #e8fdf0; color: #1a6c3e;
        padding: 12px 18px; border-radius: 10px;
        font-size: 13px; font-weight: 600;
        border-left: 4px solid #1a6c3e;
    }
</style>

<div class="plans-page">

    <div class="plans-hero">
        <h1>👑 Choose Your <span>Plan</span></h1>
        <p>Select the plan that suits you best and unlock amazing features</p>
    </div>

    @if(session('error'))
        <div class="alert-error">❌ {{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    <div class="plans-grid">

        @forelse($plans as $plan)
        <div class="plan-card {{ $plan->is_featured ? 'featured' : '' }}">

            @if($plan->is_featured)
                <div class="featured-badge">⭐ Most Popular</div>
            @endif

            {{-- Current plan indicator --}}
            @if(Auth::user()->is_premium && $plan->slug !== 'free')
                <div class="current-badge">✅ Current Plan</div>
            @endif

            {{-- Icon based on badge_icon --}}
            <div class="plan-icon">
                {{ $plan->price == 0 ? '🆓' : '👑' }}
            </div>

            <div class="plan-name">{{ $plan->price == 0 ? 'Free' : 'Premium' }}</div>

            {{-- Price --}}
            <div class="plan-price {{ $plan->price == 0 ? 'free-price' : '' }}">
                {{ $plan->price == 0 ? 'Free' : '$'.number_format($plan->price, 2) }}
            </div>
            <div class="plan-period">
                @if($plan->price == 0)
                    forever
                @else
                    per {{ $plan->duration_days }} days
                @endif
            </div>

            {{-- Description --}}
            @if($plan->description)
                <div class="plan-desc">{{ $plan->description }}</div>
            @endif

            {{-- Features --}}
            @php
                $rawFeatures = is_array($plan->features) ? $plan->features : (json_decode($plan->features, true) ?? []);
                $rawFeatures = is_array($rawFeatures) ? $rawFeatures : [];

                // Remove old country-access bullets (e.g. Pakistan/India/Bangladesh text).
                $filteredFeatures = collect($rawFeatures)->filter(function ($feature) {
                    $f = strtolower((string) $feature);
                    return !str_contains($f, 'pakistan')
                        && !str_contains($f, 'india')
                        && !str_contains($f, 'bangladesh')
                        && !str_contains($f, 'country access');
                })->values()->all();

                $countryFeature = $plan->price == 0
                    ? 'Country access: Only your own country profiles.'
                    : 'Country access: Access up to 10 countries in Discover.';

                $displayFeatures = array_merge([$countryFeature], $filteredFeatures);
            @endphp
            @if(count($displayFeatures))
            <ul class="plan-features">
                @foreach($displayFeatures as $feature)
                    <li>✅ {{ $feature }}</li>
                @endforeach
            </ul>
            @endif

            {{-- CTA Button --}}
            @if($plan->price == 0)
                <a href="{{ route('user.discover') }}" class="btn-subscribe">Continue Free</a>
            @else
                <form action="{{ route('user.premium.checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <button type="submit" class="btn-subscribe">
                        Get Premium 👑
                    </button>
                </form>
            @endif

        </div>
        @empty
        <div style="text-align:center;color:#888;padding:40px">
            No plans available at the moment.
        </div>
        @endforelse

    </div>

</div>
</div>
</div>

@endsection

