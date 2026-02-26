@extends('layouts.user')
@section('usercontent')

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        .profile-page {
            font-family: 'Poppins', sans-serif;
            background: #f4f6fb;
            min-height: 100vh;
            padding: 28px 16px;
        }

        .profile-container {
            max-width: 700px;
            margin: 0 auto;
        }

        /* ---- Top card ---- */
        .top-card {
            background: #111;
            color: white;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 16px;
        }

        .top-card h2 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .top-card p {
            color: #aaa;
            font-size: 13px;
        }

        .completion-circle {
            position: relative;
            width: 70px;
            height: 70px;
            flex-shrink: 0;
        }

        .completion-circle svg {
            transform: rotate(-90deg);
        }

        .completion-circle .pct-inside {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: white;
        }

        /* ---- Section card ---- */
        .info-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
            margin-bottom: 16px;
            overflow: hidden;
        }

        .info-card .card-head {
            padding: 14px 20px;
            background: #f9f9f9;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
            font-weight: 700;
            color: #444;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 13px 20px;
            border-bottom: 1px solid #f5f5f5;
            gap: 16px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .lbl {
            color: #888;
            font-size: 13px;
            min-width: 140px;
            font-weight: 500;
        }

        .info-row .val {
            color: #111;
            font-size: 13px;
            font-weight: 600;
            text-align: right;
            word-break: break-word;
            max-width: 360px;
        }

        /* Tags inline */
        .tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin: 2px;
        }

        .tag-dark {
            background: #111;
            color: white;
        }

        .tag-light {
            background: #f0f0f0;
            color: #444;
        }

        .tag-green {
            background: #e8fdf0;
            color: #1a8c4e;
        }

        /* Action buttons */
        .action-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .btn-edit {
            padding: 11px 22px;
            background: #111;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            display: inline-block;
            transition: background .2s;
        }

        .btn-edit:hover {
            background: #333;
        }

        .btn-delete {
            padding: 11px 22px;
            background: #fff;
            color: #e74c3c;
            border: 2px solid #e74c3c;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all .2s;
        }

        .btn-delete:hover {
            background: #e74c3c;
            color: white;
        }

        /* Empty state */
        .empty-profile {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .empty-profile .icon {
            font-size: 60px;
            margin-bottom: 16px;
        }

        .empty-profile h3 {
            font-size: 18px;
            font-weight: 700;
            color: #111;
            margin-bottom: 8px;
        }

        .empty-profile p {
            color: #888;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
<div class="page-content-wrapper">
    <div class="profile-page">
        <div class="profile-container">

            @if (session('success'))
                <div
                    style="background:#e8fdf0;color:#1a8c4e;padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:13px;font-weight:600;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($profile)
                {{-- Top Card --}}
                <div class="top-card">
                    <div>
                        <h2>{{ Auth::user()->name }}</h2>
                        <p>{{ Auth::user()->email }}</p>
                        <p style="margin-top:6px;color:#ccc">
                            Profile updated {{ $profile->updated_at->diffForHumans() }}
                        </p>
                    </div>
                    {{-- Circular progress --}}
                    <div class="completion-circle">
                        <svg width="70" height="70" viewBox="0 0 70 70">
                            <circle cx="35" cy="35" r="28" fill="none" stroke="#333" stroke-width="6" />
                            <circle cx="35" cy="35" r="28" fill="none" stroke="#4CAF50" stroke-width="6"
                                stroke-dasharray="{{ round(2 * 3.14159 * 28) }}"
                                stroke-dashoffset="{{ round(2 * 3.14159 * 28 * (1 - $profile->profile_completion / 100)) }}"
                                stroke-linecap="round" />
                        </svg>
                        <div class="pct-inside">{{ $profile->profile_completion }}%</div>
                    </div>
                </div>

                {{-- Religion & Identity --}}
                <div class="info-card">
                    <div class="card-head">☪️ Religion & Identity</div>
                    <div class="info-row">
                        <span class="lbl">Sect</span>
                        <span class="val">{{ $profile->sect ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Religion Practice</span>
                        <span class="val">{{ $profile->religion_practice ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Born Muslim</span>
                        <span class="val">{{ $profile->born_muslim ?? '—' }}</span>
                    </div>
                </div>

                {{-- Personal Info --}}
                <div class="info-card">
                    <div class="card-head">👤 Personal Info</div>
                    <div class="info-row">
                        <span class="lbl">Nationality</span>
                        <span class="val">{{ $profile->nationality ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Grew Up</span>
                        <span class="val">{{ $profile->grew_up ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Ethnicity</span>
                        <span class="val">
                            @forelse($profile->ethnicity_array as $e)
                                <span class="tag tag-light">{{ $e }}</span>
                            @empty —
                            @endforelse
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Height</span>
                        <span class="val">
                            {{ $profile->height_formatted ?? '—' }}
                            @if ($profile->height_cm)
                                <small style="color:#aaa">({{ $profile->height_cm }} cm)</small>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Marital Status</span>
                        <span class="val">{{ $profile->marital_status ?? '—' }}</span>
                    </div>
                </div>

                {{-- Career --}}
                <div class="info-card">
                    <div class="card-head">💼 Career & Education</div>
                    <div class="info-row">
                        <span class="lbl">Profession</span>
                        <span class="val">{{ $profile->profession ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Education</span>
                        <span class="val">{{ $profile->education ?? '—' }}</span>
                    </div>
                </div>

                {{-- Marriage --}}
                <div class="info-card">
                    <div class="card-head">💍 Marriage</div>
                    <div class="info-row">
                        <span class="lbl">Intentions</span>
                        <span class="val">{{ $profile->marriage_intentions ?? '—' }}</span>
                    </div>
                </div>

                {{-- Interests --}}
                <div class="info-card">
                    <div class="card-head">⭐ Interests</div>
                    <div class="info-row">
                        <span class="lbl">Interests</span>
                        <span class="val">
                            @forelse($profile->interests_array as $i)
                                <span class="tag tag-dark">{{ $i }}</span>
                            @empty —
                            @endforelse
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Personality</span>
                        <span class="val">
                            @forelse($profile->personality_array as $p)
                                <span class="tag tag-green">{{ $p }}</span>
                            @empty —
                            @endforelse
                        </span>
                    </div>
                </div>

                {{-- Bio --}}
                @if ($profile->bio)
                    <div class="info-card">
                        <div class="card-head">📝 Bio</div>
                        <div style="padding:16px 20px;color:#444;font-size:14px;line-height:1.7">
                            {{ $profile->bio }}
                        </div>
                    </div>
                @endif

                {{-- Settings --}}
                <div class="info-card">
                    <div class="card-head">⚙️ Settings</div>
                    <div class="info-row">
                        <span class="lbl">Notifications</span>
                        <span class="val" style="color:{{ $profile->notifications_enabled ? '#1a8c4e' : '#aaa' }}">
                            {{ $profile->notifications_enabled ? '✓ Enabled' : 'Disabled' }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Hide from Contacts</span>
                        <span class="val" style="color:{{ $profile->hide_from_contacts ? '#1a8c4e' : '#aaa' }}">
                            {{ $profile->hide_from_contacts ? '✓ Hidden' : 'Visible' }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="action-row">
                    <a href="{{ route('profile.edit') }}" class="btn-edit">✏️ Edit Profile</a>
                    <form method="POST" action="{{ route('profile.destroy') }}"
                        onsubmit="return confirm('Delete your profile? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete">🗑 Delete Profile</button>
                    </form>
                </div>
            @else
                {{-- No profile yet --}}
                <div class="empty-profile">
                    <div class="icon">👤</div>
                    <h3>Profile not set up yet</h3>
                    <p>Complete your profile to start matching with others.</p>
                    <a href="{{ route('profile.show') }}" class="btn-edit">Set Up Profile →</a>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
