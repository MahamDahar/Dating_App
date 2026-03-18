@extends('layouts.user')
@section('usercontent')

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
:root { --pink:#e85d75;--purple:#a855f7;--dark:#1a1a2e;--muted:#7c8494;--bg:#f8f5ff;--card-radius:24px; }
*{ box-sizing:border-box; }
.marriage-page{ min-height:calc(100vh - 120px);background:var(--bg);font-family:'DM Sans',sans-serif; }

/* GPS SCREEN */
.gps-screen{ min-height:calc(100vh - 120px);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;padding:40px 20px; }
.gps-screen::before{ content:'';position:absolute;top:-100px;right:-100px;width:420px;height:420px;background:radial-gradient(circle,rgba(244,194,194,.35) 0%,transparent 70%);border-radius:50%;pointer-events:none; }
.gps-screen::after { content:'';position:absolute;bottom:-80px;left:-80px;width:350px;height:350px;background:radial-gradient(circle,rgba(196,181,253,.25) 0%,transparent 70%);border-radius:50%;pointer-events:none; }
.fheart{ position:absolute;opacity:.1;animation:floatUp linear infinite;pointer-events:none;font-size:18px; }
.fh1{left:8%;animation-duration:8s;} .fh2{left:20%;animation-duration:11s;animation-delay:2s;font-size:22px;}
.fh3{left:78%;animation-duration:9s;animation-delay:1s;} .fh4{left:90%;animation-duration:13s;animation-delay:3s;font-size:13px;}
.fh5{left:50%;animation-duration:10s;animation-delay:4s;font-size:24px;}
@keyframes floatUp{ 0%{transform:translateY(100vh);opacity:0;} 10%{opacity:.1;} 90%{opacity:.06;} 100%{transform:translateY(-120px);opacity:0;} }
.gps-card{ position:relative;z-index:2;background:white;border-radius:28px;padding:56px 48px 48px;max-width:460px;width:100%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.07),0 0 0 1px rgba(0,0,0,.04);animation:cardReveal .7s cubic-bezier(.16,1,.3,1) both; }
@keyframes cardReveal{ from{opacity:0;transform:translateY(28px) scale(.97);} to{opacity:1;transform:translateY(0) scale(1);} }
.icon-ring{ width:88px;height:88px;margin:0 auto 28px;position:relative; }
.icon-ring-outer{ width:88px;height:88px;border-radius:50%;background:linear-gradient(135deg,#fde8e8,#f5e6ff);display:flex;align-items:center;justify-content:center;position:relative; }
.icon-ring-outer::before,.icon-ring-outer::after{ content:'';position:absolute;border-radius:50%;animation:pulseRing 2.4s ease-out infinite; }
.icon-ring-outer::before{ width:110px;height:110px;border:1.5px solid rgba(232,93,117,.25); }
.icon-ring-outer::after { width:132px;height:132px;border:1px solid rgba(232,93,117,.12);animation-delay:.6s; }
@keyframes pulseRing{ 0%{transform:scale(.85);opacity:0;} 30%{opacity:1;} 100%{transform:scale(1.15);opacity:0;} }
.icon-emoji{ font-size:36px;animation:tickTock 3s ease-in-out infinite; }
@keyframes tickTock{ 0%,100%{transform:rotate(-6deg);} 50%{transform:rotate(6deg);} }
.deco-dots{ display:flex;justify-content:center;gap:6px;margin-bottom:28px; }
.deco-dot{ width:6px;height:6px;border-radius:50%;background:#fde8e8; }
.deco-dot.mid{ width:8px;height:8px;background:#f5e6ff; }
.gps-title{ font-family:'Playfair Display',serif;font-size:26px;font-weight:900;color:var(--dark);line-height:1.25;margin-bottom:14px; }
.gps-title span{ background:linear-gradient(135deg,#e85d75,#a855f7);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text; }
.gps-sub{ font-size:14.5px;font-weight:300;color:var(--muted);line-height:1.7;max-width:320px;margin:0 auto 36px; }
.btn-gps{ display:inline-flex;align-items:center;justify-content:center;gap:10px;background:linear-gradient(135deg,#e85d75,#a855f7);color:white;border:none;border-radius:50px;padding:16px 40px;font-family:'DM Sans',sans-serif;font-size:15px;font-weight:500;cursor:pointer;width:100%;max-width:280px;box-shadow:0 8px 24px rgba(232,93,117,.3);transition:transform .2s,box-shadow .2s; }
.btn-gps:hover{ transform:translateY(-2px);box-shadow:0 14px 32px rgba(232,93,117,.4); }
.btn-gps .gps-icon{ font-size:19px;animation:gpsPulse 1.8s ease-in-out infinite; }
@keyframes gpsPulse{ 0%,100%{transform:scale(1);} 50%{transform:scale(1.2);} }
.privacy-note{ margin-top:20px;font-size:11.5px;color:#b0b8c8;display:flex;align-items:center;justify-content:center;gap:5px; }

/* PROFILES SCREEN */
.profiles-screen{ display:none; }
.profiles-header{ background:white;padding:16px 24px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #f0f0f0;position:sticky;top:0;z-index:10; }
.profiles-header h5{ font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--dark);margin:0; }
.gps-active-badge{ display:flex;align-items:center;gap:6px;background:#f0fdf4;color:#16a34a;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600; }
.gps-dot{ width:7px;height:7px;border-radius:50%;background:#16a34a;animation:blink 1.5s ease-in-out infinite; }
@keyframes blink{ 0%,100%{opacity:1;} 50%{opacity:.3;} }

.swipe-container{ display:flex;flex-direction:column;align-items:center;padding:28px 16px 120px; }
.swipe-stack{ position:relative;width:100%;max-width:360px;height:560px;margin:0 auto; }

/* Loading spinner */
.stack-loading{ display:flex;align-items:center;justify-content:center;height:200px;color:var(--muted);font-size:14px;gap:10px; }
.spinner{ width:22px;height:22px;border:2px solid #e9d5ff;border-top-color:var(--purple);border-radius:50%;animation:spin .7s linear infinite; }

/* Profile Card */
.profile-card{ position:absolute;width:100%;height:100%;border-radius:var(--card-radius);overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.18);cursor:grab;user-select:none;background:#1a1a2e; }
.profile-card:nth-last-child(2){ transform:scale(.95) translateY(14px);filter:brightness(.7);z-index:1; }
.profile-card:nth-last-child(3){ transform:scale(.90) translateY(28px);filter:brightness(.5);z-index:0; }
.profile-card:last-child{ z-index:3; }
.profile-img{ width:100%;height:100%;object-fit:cover;display:block; }
.profile-img-placeholder{ width:100%;height:100%;display:flex;align-items:center;justify-content:center; }
.card-overlay{ position:absolute;inset:0;background:linear-gradient(to bottom,transparent 30%,rgba(0,0,0,.05) 50%,rgba(0,0,0,.7) 78%,rgba(0,0,0,.92) 100%); }
.card-top-badges{ position:absolute;top:14px;left:14px;right:14px;display:flex;align-items:flex-start;justify-content:space-between;z-index:4; }
.active-badge{ display:flex;align-items:center;gap:5px;background:rgba(0,0,0,.45);backdrop-filter:blur(8px);color:white;font-size:11px;font-weight:500;padding:5px 10px;border-radius:20px; }
.active-dot{ width:6px;height:6px;border-radius:50%; }
.active-dot.online{ background:#22c55e;box-shadow:0 0 6px #22c55e; }
.active-dot.today { background:#f59e0b;box-shadow:0 0 6px #f59e0b; }
.active-dot.offline{ background:#9ca3af; }
.premium-badge{ display:flex;align-items:center;gap:4px;background:linear-gradient(135deg,#f59e0b,#ef4444);color:white;font-size:10px;font-weight:700;padding:5px 10px;border-radius:20px;box-shadow:0 2px 10px rgba(245,158,11,.4); }
.like-indicator,.nope-indicator{ position:absolute;top:30px;font-family:'Playfair Display',serif;font-size:26px;font-weight:900;padding:5px 14px;border-radius:10px;border-width:3px;border-style:solid;z-index:10;opacity:0;pointer-events:none;transition:opacity .1s; }
.like-indicator{ left:20px;color:#16a34a;border-color:#16a34a;transform:rotate(-15deg); }
.nope-indicator{ right:20px;color:#ef4444;border-color:#ef4444;transform:rotate(15deg); }
.card-bottom{ position:absolute;bottom:0;left:0;right:0;padding:16px 18px 20px;z-index:4; }
.card-name-row{ display:flex;align-items:baseline;gap:8px;margin-bottom:6px; }
.card-name{ font-family:'Playfair Display',serif;font-size:24px;font-weight:700;color:white; }
.card-age{ font-size:18px;font-weight:300;color:rgba(255,255,255,.85); }
.card-distance{ display:flex;align-items:center;gap:6px;margin-bottom:12px;font-size:13px;color:rgba(255,255,255,.75); }
.card-distance ion-icon{ font-size:14px;color:#e85d75; }
.card-info-blocks{ display:flex;flex-wrap:wrap;gap:6px; }
.info-block{ background:rgba(255,255,255,.13);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.18);color:white;padding:5px 10px;border-radius:8px;font-size:11.5px;display:flex;align-items:center;gap:4px; }
.info-block ion-icon{ font-size:12px;opacity:.8; }

/* Match popup */
.match-popup{ position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:2000;display:none;align-items:center;justify-content:center;padding:20px; }
.match-popup.show{ display:flex;animation:fadeIn .3s ease; }
.match-box{ background:linear-gradient(135deg,#1a1a2e,#2d1b4e);border-radius:28px;padding:40px 32px;text-align:center;max-width:340px;width:100%; }
.match-emoji{ font-size:60px;display:block;margin-bottom:16px;animation:bounceIn .5s ease; }
.match-title{ font-family:'Playfair Display',serif;font-size:28px;font-weight:900;color:white;margin-bottom:8px; }
.match-sub{ color:rgba(255,255,255,.6);font-size:14px;margin-bottom:24px; }
.btn-match-chat{ width:100%;background:linear-gradient(135deg,#e85d75,#a855f7);color:white;border:none;border-radius:14px;padding:14px;font-size:15px;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;margin-bottom:10px; }
.btn-match-close{ width:100%;background:rgba(255,255,255,.1);color:white;border:none;border-radius:14px;padding:12px;font-size:14px;cursor:pointer;font-family:'DM Sans',sans-serif; }
@keyframes bounceIn{ 0%{transform:scale(.5);} 70%{transform:scale(1.1);} 100%{transform:scale(1);} }
@keyframes fadeIn{ from{opacity:0;} to{opacity:1;} }

/* Action buttons */
.swipe-actions{ display:flex;align-items:center;justify-content:center;gap:16px;margin-top:28px; }
.action-btn{ border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(0,0,0,.12);transition:transform .2s,box-shadow .2s;font-size:20px; }
.action-btn:hover{ transform:scale(1.1); }
.action-btn:active{ transform:scale(.95); }
.btn-dislike{ width:56px;height:56px;background:white;color:#ef4444; }
.btn-fav    { width:50px;height:50px;background:white;color:#f59e0b;font-size:18px; }
.btn-view   { width:50px;height:50px;background:linear-gradient(135deg,#3b82f6,#06b6d4);color:white;font-size:18px; }
.btn-like   { width:56px;height:56px;background:linear-gradient(135deg,#e85d75,#a855f7);color:white; }

.no-more-cards{ display:none;text-align:center;padding:60px 20px; }
.no-more-cards .emoji{ font-size:56px;margin-bottom:16px;display:block; }
.no-more-cards h4{ font-family:'Playfair Display',serif;font-size:22px;color:var(--dark);margin-bottom:8px; }
.no-more-cards p{ color:var(--muted);font-size:14px; }
.btn-refresh{ margin-top:20px;display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#e85d75,#a855f7);color:white;border:none;border-radius:50px;padding:12px 28px;font-size:14px;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif; }

/* DRAWER */
.drawer-overlay{ position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;opacity:0;pointer-events:none;transition:opacity .35s ease;backdrop-filter:blur(3px); }
.drawer-overlay.open{ opacity:1;pointer-events:all; }
.profile-drawer{ position:fixed;bottom:0;left:0;right:0;background:white;border-radius:28px 28px 0 0;z-index:1001;max-height:92vh;overflow-y:auto;transform:translateY(100%);transition:transform .4s cubic-bezier(.16,1,.3,1);padding-bottom:40px; }
.profile-drawer.open{ transform:translateY(0); }
.drawer-handle{ display:flex;justify-content:center;padding:14px 0 6px; }
.drawer-handle-bar{ width:44px;height:4px;background:#e5e7eb;border-radius:10px; }
.drawer-hero{ height:220px;display:flex;align-items:center;justify-content:center;position:relative;margin:0 16px;border-radius:20px;overflow:hidden; }
.drawer-hero-overlay{ position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,rgba(0,0,0,.8) 100%); }
.drawer-hero-info{ position:absolute;bottom:16px;left:18px;right:18px;z-index:2; }
.drawer-hero-name{ font-family:'Playfair Display',serif;font-size:26px;font-weight:900;color:white; }
.drawer-hero-meta{ font-size:13px;color:rgba(255,255,255,.75);display:flex;align-items:center;gap:8px;margin-top:4px; }
.drawer-hero-badges{ position:absolute;top:14px;left:18px;right:18px;display:flex;justify-content:space-between;z-index:2; }
.drawer-body{ padding:20px 20px 0; }
.d-section{ margin-bottom:22px; }
.d-section-title{ font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#9ca3af;margin-bottom:12px;display:flex;align-items:center;gap:6px; }
.d-section-title::after{ content:'';flex:1;height:1px;background:#f3f4f6; }
.d-row{ display:flex;align-items:center;gap:12px;padding:9px 0;border-bottom:1px solid #f9f9f9; }
.d-row:last-child{ border-bottom:none; }
.d-row-icon{ width:32px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:15px; }
.d-row-key{ font-size:12px;color:#9ca3af;margin-bottom:1px; }
.d-row-val{ font-size:13px;font-weight:600;color:var(--dark); }
.bio-box{ background:#f8f5ff;border-radius:14px;padding:16px;font-size:13.5px;color:#4b5563;line-height:1.75;font-style:italic; }
.verif-grid{ display:grid;grid-template-columns:1fr 1fr;gap:10px; }
.verif-item{ background:#fafafa;border:1px solid #f0f0f0;border-radius:14px;padding:14px;display:flex;align-items:center;gap:10px; }
.verif-item.verified{ background:#f0fdf4;border-color:#bbf7d0; }
.verif-item.unverified{ background:#fff7f7;border-color:#fecaca; }
.verif-icon{ font-size:20px; }
.verif-label{ font-size:12px;font-weight:600;color:var(--dark); }
.verif-status{ font-size:11px;margin-top:1px; }
.verif-item.verified .verif-status{ color:#16a34a; }
.verif-item.unverified .verif-status{ color:#ef4444; }
.compliment-box{ background:linear-gradient(135deg,#fdf4ff,#fce7f3);border:1px solid #f0abfc;border-radius:18px;padding:20px; }
.compliment-title{ font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--dark);margin-bottom:4px; }
.compliment-sub{ font-size:12.5px;color:var(--muted);margin-bottom:14px; }
.chat-nudge{ background:white;border:1px solid #e9d5ff;border-radius:12px;padding:12px 14px;margin-bottom:12px;display:flex;align-items:center;gap:10px;font-size:13px;color:var(--dark); }
.chat-nudge ion-icon{ font-size:18px;color:var(--purple); }
.compliment-textarea{ width:100%;border:1.5px solid #e9d5ff;border-radius:12px;padding:12px 14px;font-family:'DM Sans',sans-serif;font-size:13px;color:var(--dark);resize:none;outline:none;background:white;transition:border .2s;min-height:80px; }
.compliment-textarea:focus{ border-color:var(--purple); }
.btn-send-compliment{ width:100%;background:linear-gradient(135deg,#e85d75,#a855f7);color:white;border:none;border-radius:12px;padding:14px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:opacity .2s; }
.btn-send-compliment:hover{ opacity:.9; }
.drawer-actions-row{ display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:16px;padding:16px 20px;background:#fafafa;border-radius:18px;border:1px solid #f0f0f0; }
.da-btn{ display:flex;flex-direction:column;align-items:center;gap:4px;background:none;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:11px;color:var(--muted);padding:8px 12px;border-radius:12px;transition:background .2s,color .2s; }
.da-btn:hover{ background:#f0f0f0; }
.da-btn ion-icon{ font-size:22px; }
.da-btn.share ion-icon{ color:#3b82f6; }
.da-btn.fav ion-icon{ color:#f59e0b; }
.da-btn.report ion-icon{ color:#ef4444; }
.da-btn.fav.active{ background:#fffbeb; }
.drawer-close{ position:absolute;top:60px;right:28px;z-index:10;width:34px;height:34px;border-radius:50%;background:rgba(0,0,0,.45);backdrop-filter:blur(8px);border:none;color:white;font-size:16px;display:flex;align-items:center;justify-content:center;cursor:pointer; }

@keyframes spin{ from{transform:rotate(0deg);} to{transform:rotate(360deg);} }
</style>

<div class="marriage-page">

    {{-- GPS Screen --}}
    <div class="gps-screen" id="gpsScreen">
        <div class="fheart fh1">❤️</div>
        <div class="fheart fh2">💜</div>
        <div class="fheart fh3">🤍</div>
        <div class="fheart fh4">💕</div>
        <div class="fheart fh5">❤️</div>

        <div class="gps-card">
            <div class="icon-ring">
                <div class="icon-ring-outer">
                    <span class="icon-emoji">⏳</span>
                </div>
            </div>
            <div class="deco-dots">
                <div class="deco-dot"></div>
                <div class="deco-dot mid"></div>
                <div class="deco-dot"></div>
            </div>
            <h1 class="gps-title">Ready to meet your<br><span>future partner?</span></h1>
            <p class="gps-sub">Enable GPS on your device to start seeing your dreamy partner nearby</p>
            <button class="btn-gps" id="enableGpsBtn" onclick="enableGPS()">
                <ion-icon name="location-outline" class="gps-icon"></ion-icon>
                Enable GPS
            </button>
            <div class="privacy-note">
                <ion-icon name="lock-closed-outline" style="font-size:12px;"></ion-icon>
                Your location is never shared with others
            </div>
        </div>
    </div>

    {{-- Profiles Screen --}}
    <div class="profiles-screen" id="profilesScreen">
        <div class="profiles-header">
            <h5>💑 Nearby Partners</h5>
            <div class="gps-active-badge">
                <div class="gps-dot"></div> GPS Active
            </div>
        </div>

        <div class="swipe-container">
            <div class="swipe-stack" id="swipeStack">
                <div class="stack-loading">
                    <div class="spinner"></div> Finding nearby profiles...
                </div>
            </div>

            <div class="no-more-cards" id="noMoreCards">
                <span class="emoji">💫</span>
                <h4>You've seen everyone nearby!</h4>
                <p>Check back later for new profiles in your area</p>
                <button class="btn-refresh" onclick="reloadProfiles()">
                    <ion-icon name="refresh-outline"></ion-icon> Refresh
                </button>
            </div>

            <div class="swipe-actions" id="swipeActions">
                <button class="action-btn btn-dislike" onclick="doPass()"    title="Pass">✕</button>
                <button class="action-btn btn-fav"     onclick="doFav()"     title="Favourite">⭐</button>
                <button class="action-btn btn-view"    onclick="openDrawer()" title="View Profile">👁</button>
                <button class="action-btn btn-like"    onclick="doLike()"    title="Like">♥</button>
            </div>
        </div>
    </div>

    {{-- Match Popup --}}
    <div class="match-popup" id="matchPopup">
        <div class="match-box">
            <span class="match-emoji">🎉</span>
            <div class="match-title">It's a Match!</div>
            <div class="match-sub" id="matchSubText">You and someone both liked each other!</div>
            <button class="btn-match-chat" onclick="closeMatch()">
                <ion-icon name="chatbubble-outline"></ion-icon> Send a Message
            </button>
            <button class="btn-match-close" onclick="closeMatch()">Keep Swiping</button>
        </div>
    </div>

    {{-- Drawer Overlay --}}
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>

    {{-- Slide-up Drawer --}}
    <div class="profile-drawer" id="profileDrawer">
        <button class="drawer-close" onclick="closeDrawer()">✕</button>
        <div class="drawer-handle"><div class="drawer-handle-bar"></div></div>

        <div class="drawer-hero" id="drawerHero">
            <div class="drawer-hero-overlay"></div>
            <div class="drawer-hero-badges">
                <div class="active-badge">
                    <div class="active-dot online" id="drawerActiveDot"></div>
                    <span id="drawerActiveText">Active now</span>
                </div>
                <div id="drawerPremiumBadge"></div>
            </div>
            <div class="drawer-hero-info">
                <div class="drawer-hero-name" id="drawerName">—</div>
                <div class="drawer-hero-meta">
                    <span id="drawerAge"></span>
                    <span>·</span>
                    <span id="drawerDistance"></span>
                    <span>·</span>
                    <span id="drawerFlag"></span>
                    <span id="drawerCountry"></span>
                </div>
            </div>
        </div>

        <div class="drawer-body">
            <div class="d-section">
                <div class="d-section-title"><ion-icon name="person-outline"></ion-icon> Basic Info</div>
                <div id="drawerBasicRows"></div>
            </div>
            <div class="d-section">
                <div class="d-section-title"><ion-icon name="document-text-outline"></ion-icon> Bio</div>
                <div class="bio-box" id="drawerBio">—</div>
            </div>
            <div class="d-section">
                <div class="d-section-title"><ion-icon name="shield-checkmark-outline"></ion-icon> Profile Verification</div>
                <div class="verif-grid" id="drawerVerif"></div>
            </div>
            <div class="d-section">
                <div class="d-section-title"><ion-icon name="heart-outline"></ion-icon> Send a Compliment</div>
                <div class="compliment-box">
                    <div class="compliment-title">Don't wait — chat with me! 💬</div>
                    <div class="compliment-sub">Let them know what you like about their profile</div>
                    <div class="chat-nudge">
                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                        <span>Start a conversation that stands out ✨</span>
                    </div>
                    <textarea class="compliment-textarea" id="complimentText"
                        placeholder="Hey! I really liked your profile because..."></textarea>
                    <button class="btn-send-compliment" onclick="sendCompliment()" style="margin-top:12px;">
                        <ion-icon name="paper-plane-outline"></ion-icon> Send Compliment
                    </button>
                </div>
            </div>

            <div class="drawer-actions-row">
                <button class="da-btn share" onclick="shareProfile()">
                    <ion-icon name="share-social-outline"></ion-icon> Share
                </button>
                <button class="da-btn fav" id="drawerFavBtn" onclick="toggleDrawerFav()">
                    <ion-icon name="star-outline" id="drawerFavIcon"></ion-icon> Favourite
                </button>
                <button class="da-btn report" onclick="reportProfile()">
                    <ion-icon name="flag-outline"></ion-icon> Report
                </button>
            </div>
        </div>
    </div>

</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const bgGradients = [
    'linear-gradient(160deg,#3b1a6b,#7c3aed)',
    'linear-gradient(160deg,#6b1a3a,#e85d75)',
    'linear-gradient(160deg,#0a3d62,#0ea5e9)',
    'linear-gradient(160deg,#1a4a2e,#16a34a)',
    'linear-gradient(160deg,#4a2a00,#f59e0b)',
];

let profiles    = [];
let currentIdx  = 0;
let isDragging  = false;
let startX      = 0;
let currentX    = 0;
let activeCard  = null;
let userLat     = null;
let userLng     = null;

// ── GPS Enable ──
function enableGPS() {
    const btn = document.getElementById('enableGpsBtn');
    btn.innerHTML = `<ion-icon name="reload-outline" style="font-size:19px;animation:spin .8s linear infinite;"></ion-icon> Enabling...`;
    btn.style.opacity = '.8';
    btn.style.pointerEvents = 'none';

    const onSuccess = (pos) => {
        userLat = pos.coords.latitude;
        userLng = pos.coords.longitude;

        // Save location to backend
        fetch('{{ route("user.marriage.location") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ latitude: userLat, longitude: userLng })
        });

        btn.innerHTML = `<ion-icon name="checkmark-circle-outline" style="font-size:19px;"></ion-icon> GPS Enabled!`;
        btn.style.background = 'linear-gradient(135deg,#16a34a,#22c55e)';
        btn.style.opacity = '1';
        setTimeout(showProfilesScreen, 600);
    };

    const onError = () => {
        // Allow without GPS (will show profiles without distance)
        btn.innerHTML = `<ion-icon name="checkmark-circle-outline" style="font-size:19px;"></ion-icon> Continue`;
        btn.style.opacity = '1';
        setTimeout(showProfilesScreen, 400);
    };

    if (!navigator.geolocation) { onError(); return; }
    navigator.geolocation.getCurrentPosition(onSuccess, onError, { timeout: 8000 });
}

function showProfilesScreen() {
    document.getElementById('gpsScreen').style.display        = 'none';
    document.getElementById('profilesScreen').style.display   = 'block';
    fetchProfiles();
}

// ── Fetch Profiles ──
function fetchProfiles() {
    const lat = userLat || 0;
    const lng = userLng || 0;

    fetch(`{{ route('user.marriage.nearby') }}?latitude=${lat}&longitude=${lng}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        profiles   = data.profiles || [];
        currentIdx = 0;
        renderStack();
    })
    .catch(() => showToast('Could not load profiles. Try again.'));
}

// ── Render Stack ──
function renderStack() {
    const stack = document.getElementById('swipeStack');
    const remaining = profiles.slice(currentIdx);
    stack.innerHTML = '';

    if (remaining.length === 0) {
        stack.style.display = 'none';
        document.getElementById('noMoreCards').style.display  = 'block';
        document.getElementById('swipeActions').style.display = 'none';
        return;
    }

    remaining.slice(0, 3).reverse().forEach(p => {
        stack.appendChild(buildCard(p));
    });

    initDrag();
}

function buildCard(p) {
    const card = document.createElement('div');
    card.className = 'profile-card';
    card.dataset.id = p.id;
    const bg = bgGradients[p.id % bgGradients.length];

    card.innerHTML = `
        <div class="profile-img-placeholder" style="background:${bg};">
            <span style="font-family:'Playfair Display',serif;font-size:100px;font-weight:900;color:rgba(255,255,255,.18);">${p.name.charAt(0)}</span>
        </div>
        <div class="card-overlay"></div>
        <div class="card-top-badges">
            <div class="active-badge">
                <div class="active-dot ${p.status}"></div> ${p.statusText}
            </div>
            ${p.isPremium ? `<div class="premium-badge"><ion-icon name="star" style="font-size:11px;"></ion-icon>&nbsp;Premium</div>` : ''}
        </div>
        <div class="like-indicator">LIKE 💚</div>
        <div class="nope-indicator">NOPE ❌</div>
        <div class="card-bottom">
            <div class="card-name-row">
                <span class="card-name">${p.name}</span>
                <span class="card-age">${p.age ?? ''}</span>
            </div>
            <div class="card-distance">
                <ion-icon name="location-outline"></ion-icon>
                ${p.distance} away &nbsp;·&nbsp; ${p.flag} ${p.country}
            </div>
            <div class="card-info-blocks">
                <div class="info-block"><ion-icon name="flag-outline"></ion-icon>${p.country}</div>
                <div class="info-block"><ion-icon name="briefcase-outline"></ion-icon>${p.designation}</div>
                <div class="info-block"><ion-icon name="school-outline"></ion-icon>${p.education}</div>
                <div class="info-block"><ion-icon name="moon-outline"></ion-icon>${p.sect}</div>
            </div>
        </div>
    `;
    return card;
}

// ── Drag ──
function initDrag() {
    const stack   = document.getElementById('swipeStack');
    const topCard = stack.querySelector('.profile-card:last-child');
    if (!topCard) return;
    activeCard = topCard;
    topCard.addEventListener('mousedown', onDragStart);
    topCard.addEventListener('touchstart', onDragStart, { passive: true });
}

function onDragStart(e) {
    isDragging = true; currentX = 0;
    startX = e.touches ? e.touches[0].clientX : e.clientX;
    activeCard.style.transition = 'none';
    document.addEventListener('mousemove', onDragMove);
    document.addEventListener('mouseup',   onDragEnd);
    document.addEventListener('touchmove', onDragMove, { passive: false });
    document.addEventListener('touchend',  onDragEnd);
}

function onDragMove(e) {
    if (!isDragging || !activeCard) return;
    if (e.cancelable) e.preventDefault();
    currentX = (e.touches ? e.touches[0].clientX : e.clientX) - startX;
    activeCard.style.transform = `translate(${currentX}px,${currentX * .05}px) rotate(${currentX * .07}deg)`;
    const like = activeCard.querySelector('.like-indicator');
    const nope = activeCard.querySelector('.nope-indicator');
    if (currentX > 40)       { like.style.opacity = Math.min((currentX-40)/80,1); nope.style.opacity = 0; }
    else if (currentX < -40) { nope.style.opacity = Math.min((-currentX-40)/80,1); like.style.opacity = 0; }
    else                     { like.style.opacity = nope.style.opacity = 0; }
}

function onDragEnd() {
    if (!isDragging) return;
    isDragging = false;
    document.removeEventListener('mousemove', onDragMove);
    document.removeEventListener('mouseup',   onDragEnd);
    document.removeEventListener('touchmove', onDragMove);
    document.removeEventListener('touchend',  onDragEnd);

    if      (currentX >  110) doLike();
    else if (currentX < -110) doPass();
    else {
        activeCard.style.transition = 'transform .4s cubic-bezier(.175,.885,.32,1.275)';
        activeCard.style.transform  = 'translate(0,0) rotate(0deg)';
        activeCard.querySelector('.like-indicator').style.opacity = 0;
        activeCard.querySelector('.nope-indicator').style.opacity = 0;
    }
    currentX = 0;
}

function flyOut(dir, cb) {
    if (!activeCard) return;
    const x = dir === 'right' ? window.innerWidth+300 : -(window.innerWidth+300);
    activeCard.style.transition = 'transform .42s ease';
    activeCard.style.transform  = `translate(${x}px,-50px) rotate(${dir==='right'?28:-28}deg)`;
    setTimeout(() => { currentIdx++; renderStack(); if(cb) cb(); }, 380);
}

// ── Actions ──
function doLike() {
    const p = profiles[currentIdx];
    if (!p) return;

    fetch('{{ route("user.marriage.like") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ liked_id: p.id })
    })
    .then(r => r.json())
    .then(data => {
        if (data.match) {
            flyOut('right', () => showMatchPopup(p));
        } else {
            flyOut('right');
        }
    })
    .catch(() => flyOut('right'));
}

function doPass() {
    const p = profiles[currentIdx];
    if (!p) return;

    fetch('{{ route("user.marriage.pass") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ passed_id: p.id })
    }).catch(() => {});

    flyOut('left');
}

function doFav() {
    const p = profiles[currentIdx];
    if (!p) return;

    fetch('{{ route("user.marriage.favourite") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ favourite_id: p.id })
    })
    .then(r => r.json())
    .then(data => {
        showToast(data.favourited ? '⭐ Added to favourites!' : 'Removed from favourites');
        profiles[currentIdx].isFavourite = data.favourited;
    });
}

function reloadProfiles() {
    document.getElementById('swipeStack').style.display   = '';
    document.getElementById('noMoreCards').style.display  = 'none';
    document.getElementById('swipeActions').style.display = '';
    document.getElementById('swipeStack').innerHTML = `<div class="stack-loading"><div class="spinner"></div> Loading...</div>`;
    fetchProfiles();
}

// ── Match popup ──
function showMatchPopup(p) {
    document.getElementById('matchSubText').textContent = `You and ${p.name} both liked each other!`;
    document.getElementById('matchPopup').classList.add('show');
}
function closeMatch() {
    document.getElementById('matchPopup').classList.remove('show');
}

// ── Drawer ──
function openDrawer() {
    const p = profiles[currentIdx];
    if (!p) return;
    populateDrawer(p);
    document.getElementById('drawerOverlay').classList.add('open');
    document.getElementById('profileDrawer').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDrawer() {
    document.getElementById('drawerOverlay').classList.remove('open');
    document.getElementById('profileDrawer').classList.remove('open');
    document.body.style.overflow = '';
}

function populateDrawer(p) {
    const bg = bgGradients[p.id % bgGradients.length];
    document.getElementById('drawerHero').style.background = bg;
    document.getElementById('drawerName').textContent     = p.name;
    document.getElementById('drawerAge').textContent      = p.age ? p.age + ' yrs' : '';
    document.getElementById('drawerDistance').textContent = p.distance + ' away';
    document.getElementById('drawerFlag').textContent     = p.flag;
    document.getElementById('drawerCountry').textContent  = p.country;
    document.getElementById('drawerActiveDot').className  = 'active-dot ' + p.status;
    document.getElementById('drawerActiveText').textContent = p.statusText;
    document.getElementById('drawerPremiumBadge').innerHTML = p.isPremium
        ? `<div class="premium-badge"><ion-icon name="star" style="font-size:11px;"></ion-icon>&nbsp;Premium</div>` : '';

    const infos = [
        { icon:'briefcase-outline', color:'#7c3aed', bg:'#f3f0ff', key:'Designation',   val: p.designation },
        { icon:'school-outline',    color:'#0ea5e9', bg:'#f0f9ff', key:'Education',      val: p.education },
        { icon:'moon-outline',      color:'#8b5cf6', bg:'#f5f3ff', key:'Sect',           val: p.sect },
        { icon:'heart-outline',     color:'#e85d75', bg:'#fdf2f8', key:'Marital Status', val: p.marital },
        { icon:'flag-outline',      color:'#16a34a', bg:'#f0fdf4', key:'Nationality',    val: p.nationality },
        { icon:'location-outline',  color:'#f59e0b', bg:'#fffbeb', key:'City',           val: p.city },
    ];

    document.getElementById('drawerBasicRows').innerHTML = infos.map(r => `
        <div class="d-row">
            <div class="d-row-icon" style="background:${r.bg};">
                <ion-icon name="${r.icon}" style="color:${r.color};font-size:16px;"></ion-icon>
            </div>
            <div>
                <div class="d-row-key">${r.key}</div>
                <div class="d-row-val">${r.val || '—'}</div>
            </div>
        </div>
    `).join('');

    document.getElementById('drawerBio').textContent = p.bio || 'No bio added yet.';

    const verifs = [
        { key:'phone', label:'Phone',   icon:'call-outline'   },
        { key:'email', label:'Email',   icon:'mail-outline'   },
        { key:'id',    label:'ID Card', icon:'card-outline'   },
        { key:'photo', label:'Photo',   icon:'camera-outline' },
    ];
    document.getElementById('drawerVerif').innerHTML = verifs.map(v => {
        const ok = p.verified?.[v.key];
        return `<div class="verif-item ${ok ? 'verified' : 'unverified'}">
            <div class="verif-icon">${ok ? '✅' : '❌'}</div>
            <div>
                <div class="verif-label">${v.label}</div>
                <div class="verif-status">${ok ? 'Verified' : 'Not Verified'}</div>
            </div>
        </div>`;
    }).join('');

    // Fav state
    const favBtn  = document.getElementById('drawerFavBtn');
    const favIcon = document.getElementById('drawerFavIcon');
    if (p.isFavourite) {
        favBtn.classList.add('active');
        favIcon.setAttribute('name','star');
    } else {
        favBtn.classList.remove('active');
        favIcon.setAttribute('name','star-outline');
    }

    document.getElementById('complimentText').value = '';
}

function toggleDrawerFav() {
    const p = profiles[currentIdx];
    if (!p) return;

    fetch('{{ route("user.marriage.favourite") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ favourite_id: p.id })
    })
    .then(r => r.json())
    .then(data => {
        profiles[currentIdx].isFavourite = data.favourited;
        const btn  = document.getElementById('drawerFavBtn');
        const icon = document.getElementById('drawerFavIcon');
        if (data.favourited) {
            btn.classList.add('active');
            icon.setAttribute('name','star');
            showToast('⭐ Added to favourites!');
        } else {
            btn.classList.remove('active');
            icon.setAttribute('name','star-outline');
            showToast('Removed from favourites');
        }
    });
}

function shareProfile() {
    const p = profiles[currentIdx];
    if (!p) return;
    if (navigator.share) {
        navigator.share({ title: p.name + "'s Profile", text: 'Check out this profile on Fobia!', url: window.location.href });
    } else {
        navigator.clipboard.writeText(window.location.href);
        showToast('🔗 Profile link copied!');
    }
}

function reportProfile() {
    const p = profiles[currentIdx];
    if (!p) return;
    if (confirm('Report ' + p.name + "'s profile?")) {
        showToast('🚩 Profile reported. Thank you.');
        closeDrawer();
    }
}

function sendCompliment() {
    const msg = document.getElementById('complimentText').value.trim();
    if (!msg) { showToast('Please write a compliment first 😊'); return; }
    // TODO: send to backend compliments route
    showToast('💌 Compliment sent!');
    document.getElementById('complimentText').value = '';
    setTimeout(closeDrawer, 800);
}

// ── Toast ──
function showToast(msg) {
    let t = document.getElementById('_toast');
    if (!t) {
        t = document.createElement('div');
        t.id = '_toast';
        t.style.cssText = 'position:fixed;bottom:30px;left:50%;transform:translateX(-50%);background:#1a1a2e;color:white;padding:11px 22px;border-radius:50px;font-size:13px;font-family:"DM Sans",sans-serif;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,.2);transition:opacity .3s;white-space:nowrap;';
        document.body.appendChild(t);
    }
    t.textContent = msg;
    t.style.opacity = '1';
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.style.opacity = '0', 2200);
}
</script>

@endsection