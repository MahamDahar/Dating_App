@extends('layouts.user')
@section('usercontent')
 <!-- start page content wrapper-->
    <div class="page-content-wrapper">
        <!-- start page content-->
        <div class="page-content">
<style>
:root {
    --ink:#0f0f14; --soft:#f7f5f2; --accent:#c8975f;
    --pink:#e85d75; --purple:#7c3aed; --muted:#8c8a87;
    --border:#ede9e4; --white:#ffffff;
}
* { box-sizing: border-box; }
body { font-family: 'DM Sans', sans-serif; }

.sp-page {
    background: var(--soft);
    min-height: calc(100vh - 80px);
    padding: 24px 16px 60px;
}
.sp-wrap { max-width: 1000px; margin: 0 auto; }

/* Page header */
.sp-header {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}
.sp-title {
    font-family: 'Playfair Display', serif;
    font-size: 24px; font-weight: 900; color: var(--ink);
}
.sp-count {
    font-size: 13px; color: var(--muted);
    background: var(--white); padding: 5px 14px;
    border-radius: 20px; border: 1px solid var(--border);
}

/* Grid */
.sp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px;
}

/* Profile Card */
.sp-card {
    background: var(--white);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: transform .2s, box-shadow .2s;
    position: relative;
}
.sp-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,.1);
}

/* Photo */
.sp-photo {
    width: 100%; height: 200px;
    object-fit: cover;
    background: linear-gradient(135deg, #0f0f14, #2d1b4e);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 48px; font-weight: 800;
}
.sp-photo img { width: 100%; height: 100%; object-fit: cover; }
.sp-photo img.blurred-photo { filter: blur(8px); transform: scale(1.03); }
.sp-photo .blur-badge {
    position: absolute; top: 10px; left: 10px;
    background: rgba(0,0,0,.68); color: white;
    font-size: 10px; font-weight: 700; padding: 3px 9px;
    border-radius: 20px; z-index: 2;
}

/* Unsave button */
.sp-unsave-btn {
    position: absolute; top: 10px; right: 10px;
    width: 32px; height: 32px; border-radius: 50%;
    background: rgba(0,0,0,.5); backdrop-filter: blur(6px);
    border: none; color: white; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; transition: background .2s;
    z-index: 2;
}
.sp-unsave-btn:hover { background: #e74c3c; }

/* Card body */
.sp-body { padding: 14px 16px 16px; }
.sp-name {
    font-weight: 700; font-size: 15px; color: var(--ink);
    margin-bottom: 4px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.sp-meta {
    font-size: 12px; color: var(--muted);
    display: flex; align-items: center; gap: 6px;
    margin-bottom: 10px;
}
.sp-meta ion-icon { font-size: 13px; color: var(--accent); }

/* Tags */
.sp-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 12px; }
.sp-tag {
    font-size: 11px; padding: 3px 9px;
    border-radius: 20px; font-weight: 500;
    background: var(--soft); color: var(--ink);
    border: 1px solid var(--border);
}

/* View button */
.sp-view-btn {
    display: block; width: 100%; padding: 9px;
    background: var(--ink); color: white;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600;
    text-align: center; text-decoration: none;
    font-family: 'DM Sans', sans-serif;
    transition: background .2s; cursor: pointer;
}
.sp-view-btn:hover { background: #2a2a35; color: white; }

/* Saved at */
.sp-saved-at {
    font-size: 11px; color: var(--muted);
    text-align: center; margin-top: 8px;
}

/* Empty state */
.sp-empty {
    text-align: center; padding: 80px 20px;
    color: var(--muted);
}
.sp-empty-icon { font-size: 64px; opacity: .3; display: block; margin-bottom: 16px; }
.sp-empty h3 { font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 8px; }
.sp-empty p { font-size: 14px; margin-bottom: 24px; }
.sp-empty a {
    display: inline-block; padding: 12px 28px;
    background: var(--purple); color: white;
    border-radius: 12px; font-weight: 600;
    text-decoration: none; font-size: 14px;
    transition: opacity .2s;
}
.sp-empty a:hover { opacity: .9; color: white; }

/* Toast */
.sp-toast {
    position: fixed; bottom: 24px; left: 50%;
    transform: translateX(-50%);
    background: var(--ink); color: white;
    padding: 11px 22px; border-radius: 50px;
    font-size: 13px; font-weight: 600;
    z-index: 9999; opacity: 0;
    transition: opacity .3s; white-space: nowrap;
    pointer-events: none;
}
.sp-toast.show { opacity: 1; }
</style>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="sp-page">
<div class="sp-wrap">

    {{-- Header --}}
    <div class="sp-header">
        <div class="sp-title">🔖 Saved Profiles</div>
        <span class="sp-count">{{ $savedProfiles->total() }} saved</span>
    </div>

    @if($savedProfiles->count())
    <div class="sp-grid">
        @foreach($savedProfiles as $item)
        @php $u = $item->savedUser; $p = $u?->profile; @endphp
        @if(!$u) @continue @endif

        <div class="sp-card" id="card-{{ $u->id }}">

            {{-- Unsave button --}}
            <button class="sp-unsave-btn" onclick="unsaveProfile({{ $u->id }}, this)" title="Remove from saved">
                🔖
            </button>

            {{-- Photo --}}
            <div class="sp-photo">
                @if($p && $p->photos && $p->photos->where('is_main', true)->first())
                    @php $mainPhoto = $p->photos->where('is_main',true)->first(); @endphp
                    <img src="{{ asset('storage/'.$mainPhoto->path) }}" alt="{{ $u->name }}" class="{{ $mainPhoto->is_blurred ? 'blurred-photo' : '' }}">
                    @if($mainPhoto->is_blurred)
                        <span class="blur-badge">Blurred</span>
                    @endif
                @else
                    {{ strtoupper(substr($u->name, 0, 1)) }}
                @endif
            </div>

            {{-- Body --}}
            <div class="sp-body">
                <div class="sp-name">{{ $u->name }}</div>

                <div class="sp-meta">
                    @if($p?->city || $p?->country)
                        <ion-icon name="location-outline"></ion-icon>
                        {{ collect([$p->city, $p->country])->filter()->implode(', ') }}
                    @endif
                </div>

                <div class="sp-tags">
                    @if($p?->date_of_birth)
                        <span class="sp-tag">{{ \Carbon\Carbon::parse($p->date_of_birth)->age }} yrs</span>
                    @endif
                    @if($p?->sect)
                        <span class="sp-tag">{{ $p->sect }}</span>
                    @endif
                    @if($p?->marital_status)
                        <span class="sp-tag">{{ $p->marital_status }}</span>
                    @endif
                </div>

                <a href="{{ route('user.profile.view', $u->id) }}" class="sp-view-btn">
                    👁 View Profile
                </a>

                <div class="sp-saved-at">
                    Saved {{ $item->created_at->diffForHumans() }}
                </div>
            </div>

        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($savedProfiles->hasPages())
    <div style="margin-top:24px">{{ $savedProfiles->links() }}</div>
    @endif

    @else
    {{-- Empty state --}}
    <div class="sp-empty">
        <span class="sp-empty-icon">🔖</span>
        <h3>No saved profiles yet</h3>
        <p>Browse profiles and save the ones you like!</p>
        <a href="{{ route('user.marriage.index') }}">Browse Profiles</a>
    </div>
    @endif

</div>
</div>
</div>
</div>

<div class="sp-toast" id="spToast"></div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

function toast(msg) {
    const t = document.getElementById('spToast');
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(t._t);
    t._t = setTimeout(() => t.classList.remove('show'), 2400);
}

async function unsaveProfile(userId, btn) {
    if (!confirm('Remove this profile from saved?')) return;

    const res = await fetch('{{ route("user.saved.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF
        },
        body: JSON.stringify({ saved_user_id: userId })
    });

    const data = await res.json();

    if (data.success && !data.saved) {
        // Remove card with animation
        const card = document.getElementById('card-' + userId);
        if (card) {
            card.style.transition = 'opacity .3s, transform .3s';
            card.style.opacity = '0';
            card.style.transform = 'scale(.9)';
            setTimeout(() => card.remove(), 300);
        }
        toast('Profile removed from saved.');
    }
}
</script>

@endsection