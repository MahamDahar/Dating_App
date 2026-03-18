@extends('layouts.user')
@section('usercontent')

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

    .plans-hero { text-align: center; margin-bottom: 40px; width: 100%; max-width: 680px; }
    .plans-hero h1 { font-size: 32px; font-weight: 900; color: #0f0f13; }
    .plans-hero h1 span { color: #7c3aed; }
    .plans-hero p { color: #6b7280; font-size: 15px; margin-top: 8px; }

    .billing-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 36px;
        flex-wrap: wrap;
        width: 100%;
        max-width: 680px;
    }

    .billing-btn {
        padding: 8px 20px;
        border-radius: 30px;
        border: 2px solid #e0e0e0;
        background: white;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: all .2s;
        position: relative;
    }

    .billing-btn.active {
        background: #7c3aed;
        border-color: #7c3aed;
        color: white;
    }

    .billing-btn .save-tag {
        position: absolute;
        top: -10px;
        right: -8px;
        background: #10b981;
        color: white;
        font-size: 9px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 20px;
        white-space: nowrap;
    }

    .plans-grid {
        display: grid;
        grid-template-columns: repeat(2, 320px);
        gap: 20px;
        width: 100%;
        max-width: 680px;
        justify-content: center;
    }

    @media (max-width: 700px) {
        .plans-grid {
            grid-template-columns: 1fr;
        }
    }

    .plan-card {
        background: white;
        border-radius: 20px;
        padding: 30px 24px;
        border: 2px solid #e8eaf0;
        text-align: center;
        transition: transform .2s, box-shadow .2s;
        position: relative;
    }

    .plan-card:hover {
        box-shadow: 0 8px 24px rgba(124, 58, 237, 0.12);
    }

    .plan-card.popular { border-color: #7c3aed; }

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
    .plan-name { font-size: 18px; font-weight: 700; color: #0f0f13; margin-bottom: 6px; }

    .plan-price {
        font-size: 38px;
        font-weight: 900;
        color: #7c3aed;
        margin: 12px 0 2px;
        line-height: 1;
    }

    .plan-price.free-price { color: #0f0f13; }
    .plan-period { font-size: 13px; color: #6b7280; margin-bottom: 6px; }
    .plan-per-month { font-size: 12px; color: #9ca3af; margin-bottom: 4px; min-height: 18px; }

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

    .chat-highlight {
        background: #f5f3ff;
        border-radius: 10px;
        padding: 10px 14px;
        margin: 14px 0;
        font-size: 13px;
        font-weight: 700;
        color: #7c3aed;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chat-highlight.free-chat { background: #f3f4f6; color: #374151; }

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
        text-align: center;
    }

    .btn-subscribe:hover { background: #333; color: white; }

    .plan-card.popular .btn-subscribe { background: #7c3aed; }
    .plan-card.popular .btn-subscribe:hover { background: #6d28d9; }

    .btn-subscribe.loading {
        opacity: .7;
        cursor: not-allowed;
        pointer-events: none;
    }

    .price-panel { display: none; }
    .price-panel.active { display: block; }

    .alert-error {
        max-width: 900px;
        margin: 0 auto 20px;
        background: #fde8e8;
        color: #bf1a1a;
        padding: 12px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        border-left: 4px solid #bf1a1a;
    }
</style>
   <div class="page-content-wrapper">
        <div class="profile-page">
<div class="plans-page">

    <div class="plans-hero">
        <h1>👑 Upgrade to <span>Premium</span></h1>
        <p>Unlock country filters and discover matches from anywhere in the world</p>
    </div>

    @if(session('error'))
        <div class="alert-error">❌ {{ session('error') }}</div>
    @endif

    {{-- Billing Period Toggle --}}
    <div class="billing-toggle">
        <button class="billing-btn active" data-period="monthly">1 Month</button>
        <button class="billing-btn" data-period="quarterly">
            3 Months
            <span class="save-tag">SAVE 33%</span>
        </button>
        <button class="billing-btn" data-period="biannual">
            6 Months
            <span class="save-tag">SAVE 50%</span>
        </button>
    </div>

    <div class="plans-grid">

        {{-- FREE PLAN --}}
        <div class="plan-card">
            <div class="plan-icon">🆓</div>
            <div class="plan-name">Free</div>
            <div class="plan-price free-price">$0</div>
            <div class="plan-period">forever</div>
            <div class="plan-per-month">&nbsp;</div>
            <div class="chat-highlight free-chat">💬 5 free chats included</div>
            <ul class="plan-features">
                <li>✅ Basic filters</li>
                <li>✅ Pakistan, India, Bangladesh</li>
                <li>✅ View match profiles</li>
                <li>❌ Other countries locked</li>
                <li>❌ Advanced filters locked</li>
            </ul>
            <a href="{{ route('user.discover') }}" class="btn-subscribe">Continue Free</a>
        </div>

        {{-- PREMIUM PLAN --}}
        <div class="plan-card popular">
            <div class="popular-badge">⭐ Most Popular</div>
            <div class="plan-icon">👑</div>
            <div class="plan-name">Premium</div>

            <div class="price-panel active" data-panel="monthly">
                <div class="plan-price">$4.00</div>
                <div class="plan-period">per month</div>
                <div class="plan-per-month">billed monthly</div>
            </div>

            <div class="price-panel" data-panel="quarterly">
                <div class="plan-price">$6.00</div>
                <div class="plan-period">for 3 months</div>
                <div class="plan-per-month">$2.00 / month · save $6</div>
            </div>

            <div class="price-panel" data-panel="biannual">
                <div class="plan-price">$8.00</div>
                <div class="plan-period">for 6 months</div>
                <div class="plan-per-month">$1.33 / month · save $16</div>
            </div>

            <div class="chat-highlight">💬 10 free chats included</div>

            <ul class="plan-features">
                <li>✅ Everything in Free</li>
                <li>✅ Filter by ALL countries</li>
                <li>✅ USA, UK, Canada, UAE & more</li>
                <li>✅ Advanced filters</li>
                <li>✅ Priority matches</li>
            </ul>

            {{-- Form submits selected plan to checkout controller --}}
            <form action="{{ route('user.premium.checkout') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="plan" id="selectedPlan" value="monthly">
                <button type="submit" class="btn-subscribe" id="upgradeBtn">
                    Upgrade Now 👑
                </button>
            </form>
        </div>

    </div>
</div>
</div>
</div>
<script>
    const billingBtns  = document.querySelectorAll('.billing-btn');
    const pricePanels  = document.querySelectorAll('.price-panel');
    const selectedPlan = document.getElementById('selectedPlan');
    const upgradeBtn   = document.getElementById('upgradeBtn');
    const checkoutForm = document.getElementById('checkoutForm');

    // Switch billing period + update hidden plan input
    billingBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const period = btn.dataset.period;

            billingBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            pricePanels.forEach(p => {
                p.classList.toggle('active', p.dataset.panel === period);
            });

            selectedPlan.value = period;
        });
    });

    // Show loading state when form submits
    checkoutForm.addEventListener('submit', () => {
        upgradeBtn.textContent = '⏳ Redirecting to payment...';
        upgradeBtn.classList.add('loading');
    });
</script>

@endsection