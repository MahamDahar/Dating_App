@extends('layouts.user')
@section('usercontent')

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:wght@700;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bg: #f0f2f8;
            --card: #ffffff;
            --dark: #0f0f13;
            --accent: #7c3aed;
            --accent2: #a855f7;
            --green: #16a34a;
            --orange: #ea580c;
            --red: #dc2626;
            --border: #e8eaf0;
            --text: #1e1e2e;
            --muted: #6b7280;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body,
        .discover-page {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .discover-page {
            min-height: 100vh;
            padding: 0 0 60px;
        }

        /* ── Hero Header ─────────────────────────────── */
        .discover-hero {
            background: var(--dark);
            padding: 32px 28px 28px;
            position: relative;
            overflow: hidden;
        }

        .discover-hero::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.4) 0%, transparent 70%);
            border-radius: 50%;
        }

        .discover-hero::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: 30%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.25) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 16px;
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-family: 'Fraunces', serif;
            font-size: 28px;
            font-weight: 900;
            color: white;
            line-height: 1.2;
        }

        .hero-title span {
            color: var(--accent2);
        }

        .hero-sub {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.55);
            margin-top: 6px;
        }

        .hero-stats {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .hstat {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 10px 16px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .hstat .num {
            font-size: 20px;
            font-weight: 700;
            color: white;
        }

        .hstat .lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 2px;
        }

        /* ── Filter Bar ──────────────────────────────── */
        .filter-wrap {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 14px 24px;
            position: sticky;
            top: 0;
            z-index: 20;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .filter-select,
        .filter-input {
            padding: 8px 12px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--text);
            outline: none;
            transition: border .2s;
            background: white;
            cursor: pointer;
        }

        .filter-select:focus,
        .filter-input:focus {
            border-color: var(--accent);
        }

        .filter-input {
            min-width: 130px;
        }

        .btn-apply {
            padding: 8px 18px;
            background: var(--dark);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .2s;
        }

        .btn-apply:hover {
            background: #333;
        }

        .btn-reset {
            padding: 8px 14px;
            background: #f3f4f6;
            color: var(--muted);
            border: none;
            border-radius: 10px;
            font-size: 13px;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-reset:hover {
            background: #e5e7eb;
        }

        /* ── Country Dropdown ────────────────────────── */
        .country-select-wrap {
            position: relative;
        }

        .country-dropdown-btn {
            padding: 8px 12px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--text);
            background: white;
            cursor: pointer;
            min-width: 190px;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            transition: border .2s;
        }

        .country-dropdown-btn:focus,
        .country-dropdown-btn.open {
            border-color: var(--accent);
            outline: none;
        }

        .country-dropdown-list {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            z-index: 999;
            min-width: 240px;
            max-height: 300px;
            overflow-y: auto;
            padding: 6px;
        }

        .country-dropdown-list.open {
            display: block;
            animation: fadeDown .15s ease;
        }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .country-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            transition: background .15s;
            gap: 10px;
        }

        .country-option:hover {
            background: #f5f3ff;
        }

        .country-option.selected {
            background: #ede9fe;
            font-weight: 600;
        }

        .country-option .c-name {
            flex: 1;
            color: var(--text);
        }

        .country-option.selected .c-name {
            color: var(--accent);
        }

        .badge-free {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            background: #dcfce7;
            color: #16a34a;
            white-space: nowrap;
        }

        .badge-paid {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            background: #fef3c7;
            color: #d97706;
            white-space: nowrap;
        }

        .country-divider {
            height: 1px;
            background: var(--border);
            margin: 4px 6px;
        }

        .country-section-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .6px;
            padding: 6px 12px 2px;
        }

        /* ── Section Label ───────────────────────────── */
        .section-header {
            padding: 24px 24px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            font-family: 'Fraunces', serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
        }

        .section-count {
            font-size: 13px;
            color: var(--muted);
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* ── Cards Grid ──────────────────────────────── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            padding: 0 24px;
        }

        /* ── Match Card ──────────────────────────────── */
        .match-card {
            background: var(--card);
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--border);
            transition: transform .25s, box-shadow .25s;
            animation: cardIn .4s ease both;
        }

        .match-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(124, 58, 237, 0.12);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .match-card:nth-child(1) { animation-delay: .05s; }
        .match-card:nth-child(2) { animation-delay: .10s; }
        .match-card:nth-child(3) { animation-delay: .15s; }
        .match-card:nth-child(4) { animation-delay: .20s; }
        .match-card:nth-child(5) { animation-delay: .25s; }
        .match-card:nth-child(6) { animation-delay: .30s; }

        .card-top {
            padding: 20px 20px 0;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .avatar {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            color: white;
            font-weight: 700;
        }

        .card-info {
            flex: 1;
            min-width: 0;
        }

        .card-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-meta {
            font-size: 12px;
            color: var(--muted);
            margin-top: 3px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .meta-dot { color: #d1d5db; }

        .match-circle {
            position: relative;
            width: 52px;
            height: 52px;
            flex-shrink: 0;
        }

        .match-circle svg { transform: rotate(-90deg); }

        .match-pct-text {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
        }

        .card-body { padding: 14px 20px; }

        .reasons {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 12px;
        }

        .reason-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #4b5563;
        }

        .reason-icon { font-size: 13px; }

        .common-interests {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 14px;
        }

        .interest-tag {
            padding: 3px 9px;
            background: #f3f0ff;
            color: var(--accent);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .card-divider {
            height: 1px;
            background: var(--border);
            margin: 0 20px 14px;
        }

        .card-footer {
            padding: 0 20px 18px;
            display: flex;
            gap: 8px;
        }

        .btn-view {
            flex: 1;
            padding: 10px;
            background: var(--dark);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background .2s;
        }

        .btn-view:hover { background: #333; color: white; }

        .score-badge {
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Empty State ─────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 80px 24px;
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }

        .empty-title {
            font-family: 'Fraunces', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .empty-sub {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
            max-width: 340px;
            margin: 0 auto 24px;
        }

        .btn-complete {
            display: inline-block;
            padding: 12px 24px;
            background: var(--dark);
            color: white;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background .2s;
        }

        .btn-complete:hover { background: #333; color: white; }

        /* ── Profile Modal ───────────────────────────── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
            backdrop-filter: blur(4px);
        }

        .modal-overlay.open { display: flex; }

        .modal-box {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 460px;
            max-height: 85vh;
            overflow-y: auto;
            animation: popIn .25s ease;
        }

        @keyframes popIn {
            from { opacity: 0; transform: scale(.94); }
            to   { opacity: 1; transform: scale(1); }
        }

        .modal-head {
            background: var(--dark);
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            border-radius: 20px 20px 0 0;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-avatar {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .modal-name {
            font-family: 'Fraunces', serif;
            font-size: 20px;
            font-weight: 700;
            color: white;
        }

        .modal-sub {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.55);
            margin-top: 3px;
        }

        .modal-body { padding: 20px 24px 24px; }

        .modal-section { margin-bottom: 18px; }

        .modal-section-title {
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 10px;
        }

        .modal-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: 13px;
        }

        .modal-row:last-child { border-bottom: none; }
        .modal-row .mk { color: var(--muted); }

        .modal-row .mv {
            font-weight: 600;
            color: var(--dark);
            text-align: right;
        }

        .modal-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .modal-tag {
            padding: 4px 10px;
            background: #f3f0ff;
            color: var(--accent);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .match-bar-wrap {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 18px;
        }

        .match-bar-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .match-bar-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--dark);
        }

        .match-bar-pct {
            font-size: 18px;
            font-weight: 700;
        }

        .match-bar-bg {
            height: 8px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .match-bar-fill {
            height: 100%;
            border-radius: 10px;
            transition: width .6s ease;
        }

        /* ── Premium Upgrade Toast ───────────────────── */
        .premium-toast {
            display: none;
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--dark);
            color: white;
            padding: 14px 22px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 8px 30px rgba(0,0,0,0.25);
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
            animation: slideUp .3s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateX(-50%) translateY(16px); }
            to   { opacity: 1; transform: translateX(-50%) translateY(0); }
        }

        .premium-toast a {
            background: #7c3aed;
            color: white;
            padding: 5px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 12px;
        }

        .premium-toast a:hover { background: #6d28d9; }
    </style>

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="discover-page">

                {{-- ── HERO ── --}}
                <div class="discover-hero">
                    <div class="hero-top">
                        <div>
                            <div class="hero-title">Discover <span>Matches</span></div>
                            <div class="hero-sub">Profiles that align with yours — based on deen, city & more</div>
                        </div>
                        <div class="hero-stats">
                            <div class="hstat">
                                <div class="num">{{ $totalFound }}</div>
                                <div class="lbl">Matches</div>
                            </div>
                            <div class="hstat">
                                <div class="num">{{ $highMatches }}</div>
                                <div class="lbl">Strong (70%+)</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── FILTERS ── --}}
                <div class="filter-wrap">
                    <form class="filter-form" method="GET" action="{{ route('user.discover') }}">

                        <select class="filter-select" name="sect">
                            <option value="">All Sects</option>
                            @foreach (['Sunni', 'Shia', 'Ahmadi', 'Nation of Islam', 'Ibadi', 'Just Muslim', 'Prefer not to say'] as $s)
                                <option value="{{ $s }}" @selected(request('sect') == $s)>{{ $s }}</option>
                            @endforeach
                        </select>

                        <select class="filter-select" name="education">
                            <option value="">All Education</option>
                            @foreach (['High School', 'Diploma / Vocational', "Bachelor's Degree", "Master's Degree", 'PhD / Doctorate', 'Islamic Education'] as $e)
                                <option value="{{ $e }}" @selected(request('education') == $e)>{{ $e }}</option>
                            @endforeach
                        </select>

                        <select class="filter-select" name="marital_status">
                            <option value="">Marital Status</option>
                            @foreach (['Never Married', 'Divorced', 'Widowed', 'Separated'] as $m)
                                <option value="{{ $m }}" @selected(request('marital_status') == $m)>{{ $m }}</option>
                            @endforeach
                        </select>

                        <input class="filter-input" type="text" name="city" placeholder="🏙️ City..."
                            value="{{ request('city') }}">

                        {{-- ── COUNTRY DROPDOWN ── --}}
                        <input type="hidden" name="country" id="countryHiddenInput" value="{{ request('country') }}">

                        <div class="country-select-wrap">
                            <button type="button" class="country-dropdown-btn" id="countryDropdownBtn">
                                <span id="countryBtnText">
                                    {{ request('country') ? '🌍 ' . request('country') : '🌍 Country...' }}
                                </span>
                                <span style="color:var(--muted);font-size:11px">▾</span>
                            </button>

                            <div class="country-dropdown-list" id="countryDropdownList">

                                {{-- FREE COUNTRIES --}}
                                <div class="country-section-label">✅ Free</div>

                                @php
                                    $freeCountries = [
                                        ['flag' => '🇵🇰', 'name' => 'Pakistan'],
                                        ['flag' => '🇮🇳', 'name' => 'India'],
                                        ['flag' => '🇧🇩', 'name' => 'Bangladesh'],
                                    ];
                                    $paidCountries = [
                                        ['flag' => '🇺🇸', 'name' => 'USA'],
                                        ['flag' => '🇬🇧', 'name' => 'UK'],
                                        ['flag' => '🇨🇦', 'name' => 'Canada'],
                                        ['flag' => '🇦🇺', 'name' => 'Australia'],
                                        ['flag' => '🇦🇪', 'name' => 'UAE'],
                                        ['flag' => '🇸🇦', 'name' => 'Saudi Arabia'],
                                        ['flag' => '🇩🇪', 'name' => 'Germany'],
                                        ['flag' => '🇫🇷', 'name' => 'France'],
                                        ['flag' => '🇹🇷', 'name' => 'Turkey'],
                                        ['flag' => '🇲🇾', 'name' => 'Malaysia'],
                                        ['flag' => '🇮🇩', 'name' => 'Indonesia'],
                                        ['flag' => '🇳🇱', 'name' => 'Netherlands'],
                                        ['flag' => '🇮🇹', 'name' => 'Italy'],
                                        ['flag' => '🇸🇪', 'name' => 'Sweden'],
                                        ['flag' => '🇳🇿', 'name' => 'New Zealand'],
                                    ];
                                @endphp

                                @foreach ($freeCountries as $country)
                                    <div class="country-option {{ request('country') == $country['name'] ? 'selected' : '' }}"
                                         data-value="{{ $country['name'] }}"
                                         data-type="free"
                                         onclick="selectCountry('{{ $country['name'] }}', '{{ $country['flag'] }} {{ $country['name'] }}', 'free')">
                                        <span class="c-name">{{ $country['flag'] }} {{ $country['name'] }}</span>
                                        <span class="badge-free">✅ Free</span>
                                    </div>
                                @endforeach

                                <div class="country-divider"></div>

                                {{-- PAID COUNTRIES --}}
                                <div class="country-section-label">👑 Premium</div>

                                @foreach ($paidCountries as $country)
                                    <div class="country-option {{ request('country') == $country['name'] ? 'selected' : '' }}"
                                         data-value="{{ $country['name'] }}"
                                         data-type="paid"
                                         onclick="selectCountry('{{ $country['name'] }}', '{{ $country['flag'] }} {{ $country['name'] }}', 'paid')">
                                        <span class="c-name">{{ $country['flag'] }} {{ $country['name'] }}</span>
                                        <span class="badge-paid">👑 Premium</span>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        {{-- ── END COUNTRY DROPDOWN ── --}}

                        <input class="filter-input" type="text" name="nationality" placeholder="🌍 Nationality..."
                            value="{{ request('nationality') }}">

                        <button type="submit" class="btn-apply">Apply</button>
                        <a href="{{ route('user.discover') }}" class="btn-reset">✕ Reset</a>
                    </form>
                </div>

                {{-- ── CARDS ── --}}
                @if ($matches->count())
                    <div class="section-header">
                        <div class="section-title">Your Matches</div>
                        <div class="section-count">{{ $matches->count() }} found</div>
                    </div>

                    <div class="cards-grid">
                        @foreach ($matches as $match)
                            @php
                                $pct = $match->match_pct;
                                $color = $pct >= 70 ? '#16a34a' : ($pct >= 45 ? '#ea580c' : '#dc2626');
                                $circ = round(2 * 3.14159 * 20);
                                $offset = round($circ * (1 - $pct / 100));
                                $initial = strtoupper(substr($match->user->name ?? 'U', 0, 1));
                                $interests = $match->common_interests ?? [];
                            @endphp

                            <div class="match-card" onclick="openModal({{ $loop->index }})">

                                <div class="card-top">
                                    <div class="avatar">{{ $initial }}</div>
                                    <div class="card-info">
                                        <div class="card-name">{{ $match->user->name ?? 'Anonymous' }}</div>
                                        <div class="card-meta">
                                            @if ($match->grew_up)
                                                <span>🏙️ {{ $match->grew_up }}</span>
                                            @endif
                                            @if ($match->profession)
                                                <span class="meta-dot">•</span>
                                                <span>{{ $match->profession }}</span>
                                            @endif
                                        </div>
                                        <div class="card-meta" style="margin-top:2px">
                                            @if ($match->sect)
                                                <span>{{ $match->sect }}</span>
                                            @endif
                                            @if ($match->marital_status)
                                                <span class="meta-dot">•</span>
                                                <span>{{ $match->marital_status }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="match-circle">
                                        <svg width="52" height="52" viewBox="0 0 52 52">
                                            <circle cx="26" cy="26" r="20" fill="none" stroke="#f0f0f0" stroke-width="5" />
                                            <circle cx="26" cy="26" r="20" fill="none"
                                                stroke="{{ $color }}" stroke-width="5"
                                                stroke-dasharray="{{ $circ }}"
                                                stroke-dashoffset="{{ $offset }}"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="match-pct-text" style="color:{{ $color }}">{{ $pct }}%</div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    @if (!empty($match->match_reasons))
                                        <div class="reasons">
                                            @foreach ($match->match_reasons as $reason)
                                                <div class="reason-item">
                                                    <span class="reason-icon">{{ $reason['icon'] }}</span>
                                                    <span>{{ $reason['text'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if (count($interests) > 0)
                                        <div class="common-interests">
                                            @foreach (array_slice($interests, 0, 4) as $int)
                                                <span class="interest-tag">{{ $int }}</span>
                                            @endforeach
                                            @if (count($interests) > 4)
                                                <span class="interest-tag">+{{ count($interests) - 4 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="card-divider"></div>

                                <div class="card-footer" onclick="event.stopPropagation()">
                                    <button class="btn-view" onclick="openModal({{ $loop->index }})">
                                        👤 View Profile
                                    </button>
                                    <div class="score-badge">⚡ {{ $match->match_score }}pts</div>
                                </div>
                            </div>

                            @php
                                $dash = '—';
                                $heightFormatted = $match->height_cm
                                    ? floor($match->height_cm / 2.54 / 12) . "'" . round(fmod($match->height_cm / 2.54, 12)) . '"'
                                    : $dash;
                                $interestsFormatted = $match->interests
                                    ? implode(', ', array_slice(explode(',', $match->interests), 0, 10))
                                    : $dash;
                            @endphp

                            <script>
                                window.__matchData = window.__matchData || [];
                                window.__matchData[{{ $loop->index }}] = {
                                    name: @json($match->user->name ?? 'Anonymous'),
                                    initial: @json(strtoupper(substr($match->user->name ?? 'U', 0, 1))),
                                    pct: {{ $pct }},
                                    color: @json($color),
                                    score: {{ $match->match_score }},
                                    sect: @json($match->sect ?? $dash),
                                    profession: @json($match->profession ?? $dash),
                                    education: @json($match->education ?? $dash),
                                    nationality: @json($match->nationality ?? $dash),
                                    grew_up: @json($match->grew_up ?? $dash),
                                    height: @json($heightFormatted),
                                    marital: @json($match->marital_status ?? $dash),
                                    intentions: @json($match->marriage_intentions ?? $dash),
                                    religion: @json($match->religion_practice ?? $dash),
                                    born_muslim: @json($match->born_muslim ?? $dash),
                                    interests: @json($interestsFormatted),
                                    personality: @json($match->personality ?? ''),
                                    bio: @json($match->bio ?? ''),
                                    reasons: @json($match->match_reasons ?? []),
                                    common: @json($match->common_interests ?? []),
                                };
                            </script>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">🔍</div>
                        <div class="empty-title">No matches found</div>
                        <div class="empty-sub">
                            @if (request()->hasAny(['sect', 'education', 'marital_status', 'city', 'nationality', 'country']))
                                Filters se koi result nahi mila. Filters reset karo ya apni profile update karo.
                            @else
                                Abhi koi matching profile nahi mili. Apni profile complete karo taake better matches milein.
                            @endif
                        </div>
                        @if (request()->hasAny(['sect', 'education', 'marital_status', 'city', 'nationality', 'country']))
                            <a href="{{ route('user.discover') }}" class="btn-complete">Reset Filters</a>
                        @else
                            <a href="{{ route('user.userprofile') }}" class="btn-complete">Complete Profile →</a>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ── PREMIUM TOAST ── --}}
    <div class="premium-toast" id="premiumToast">
        👑 This country requires Premium
        <a href="{{ route('premium.plans') }}">Upgrade Now</a>
    </div>

    {{-- ── PROFILE MODAL ── --}}
    <div class="modal-overlay" id="profileModal">
        <div class="modal-box">
            <div class="modal-head">
                <div class="modal-avatar" id="modalAvatar">U</div>
                <div>
                    <div class="modal-name" id="modalName">—</div>
                    <div class="modal-sub" id="modalSub">—</div>
                </div>
                <button class="modal-close" onclick="closeModal()">✕</button>
            </div>
            <div class="modal-body">

                <div class="match-bar-wrap">
                    <div class="match-bar-top">
                        <span class="match-bar-label">Match Score</span>
                        <span class="match-bar-pct" id="modalPct">—</span>
                    </div>
                    <div class="match-bar-bg">
                        <div class="match-bar-fill" id="modalBar" style="width:0%"></div>
                    </div>
                </div>

                <div class="modal-section" id="modalReasonsWrap">
                    <div class="modal-section-title">Why you matched</div>
                    <div id="modalReasons"></div>
                </div>

                <div class="modal-section" id="modalCommonWrap">
                    <div class="modal-section-title">Common Interests</div>
                    <div class="modal-tags" id="modalCommon"></div>
                </div>

                <div class="modal-section">
                    <div class="modal-section-title">Profile</div>
                    <div id="modalRows"></div>
                </div>

                <div class="modal-section" id="modalBioWrap" style="display:none">
                    <div class="modal-section-title">Bio</div>
                    <p id="modalBio" style="font-size:13px;color:#4b5563;line-height:1.7"></p>
                </div>

                <div class="modal-section">
                    <div class="modal-section-title">All Interests</div>
                    <div class="modal-tags" id="modalInterests"></div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // ── Pass premium status from PHP to JS ──────────
        const isPremiumUser = {{ auth()->user()->isPremium() ? 'true' : 'false' }};

        // ── Country Dropdown Logic ───────────────────────
        const countryBtn   = document.getElementById('countryDropdownBtn');
        const countryList  = document.getElementById('countryDropdownList');
        const countryInput = document.getElementById('countryHiddenInput');
        const countryText  = document.getElementById('countryBtnText');

        countryBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            countryList.classList.toggle('open');
            countryBtn.classList.toggle('open');
        });

        document.addEventListener('click', function () {
            countryList.classList.remove('open');
            countryBtn.classList.remove('open');
        });

        countryList.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        function selectCountry(value, label, type) {
            if (type === 'paid' && !isPremiumUser) {
                // Show toast instead of hard redirect
                showPremiumToast();
                countryList.classList.remove('open');
                countryBtn.classList.remove('open');
                return;
            }

            countryInput.value = value;
            countryText.textContent = '🌍 ' + label;

            document.querySelectorAll('.country-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            document.querySelector(`.country-option[data-value="${value}"]`)
                    ?.classList.add('selected');

            countryList.classList.remove('open');
            countryBtn.classList.remove('open');
        }

        // ── Premium Toast ────────────────────────────────
        let toastTimeout;
        function showPremiumToast() {
            const toast = document.getElementById('premiumToast');
            toast.style.display = 'flex';
            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.style.display = 'none';
            }, 4000);
        }

        // ── Profile Modal ────────────────────────────────
        function openModal(idx) {
            const d = window.__matchData[idx];
            if (!d) return;

            document.getElementById('modalAvatar').textContent = d.initial;
            document.getElementById('modalName').textContent = d.name;
            document.getElementById('modalSub').textContent = d.sect + ' • ' + d.grew_up;
            document.getElementById('modalPct').textContent = d.pct + '%';
            document.getElementById('modalPct').style.color = d.color;
            document.getElementById('modalBar').style.width = d.pct + '%';
            document.getElementById('modalBar').style.background = d.color;

            const reasonsEl = document.getElementById('modalReasons');
            if (d.reasons && d.reasons.length) {
                reasonsEl.innerHTML = d.reasons.map(r =>
                    `<div style="display:flex;align-items:center;gap:6px;padding:5px 0;font-size:13px;color:#374151;border-bottom:1px solid #f5f5f5">
                        <span>${r.icon}</span><span>${r.text}</span>
                    </div>`
                ).join('');
                document.getElementById('modalReasonsWrap').style.display = 'block';
            } else {
                document.getElementById('modalReasonsWrap').style.display = 'none';
            }

            const commonEl = document.getElementById('modalCommon');
            if (d.common && d.common.length) {
                commonEl.innerHTML = d.common.map(i =>
                    `<span class="modal-tag">${i}</span>`).join('');
                document.getElementById('modalCommonWrap').style.display = 'block';
            } else {
                document.getElementById('modalCommonWrap').style.display = 'none';
            }

            const fields = [
                ['Profession',  d.profession],
                ['Education',   d.education],
                ['Nationality', d.nationality],
                ['Grew Up',     d.grew_up],
                ['Height',      d.height],
                ['Marital',     d.marital],
                ['Intentions',  d.intentions],
                ['Religion',    d.religion],
                ['Born Muslim', d.born_muslim],
                ['Personality', d.personality],
                ['Match Score', d.score + ' pts'],
            ];
            document.getElementById('modalRows').innerHTML = fields.map(([k, v]) =>
                `<div class="modal-row">
                    <span class="mk">${k}</span>
                    <span class="mv">${v || '—'}</span>
                </div>`
            ).join('');

            if (d.bio) {
                document.getElementById('modalBio').textContent = d.bio;
                document.getElementById('modalBioWrap').style.display = 'block';
            } else {
                document.getElementById('modalBioWrap').style.display = 'none';
            }

            const intEl = document.getElementById('modalInterests');
            if (d.interests && d.interests !== '—') {
                intEl.innerHTML = d.interests.split(',').map(i =>
                    `<span class="modal-tag">${i.trim()}</span>`).join('');
            } else {
                intEl.innerHTML = '<span style="color:#aaa;font-size:13px">No interests listed</span>';
            }

            document.getElementById('profileModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('profileModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        document.getElementById('profileModal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    </script>

@endsection