@extends('layouts.user')
@section('usercontent')
<div class="page-content-wrapper">
    <div class="page-content">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
:root{
    --ink:#0f0f14; --soft:#f7f5f2; --accent:#c8975f;
    --pink:#e85d75; --purple:#7c3aed; --muted:#8c8a87;
    --border:#ede9e4; --white:#ffffff;
    --card-r:20px; --r:14px;
}
*{ box-sizing:border-box; margin:0; padding:0; }
body{ font-family:'DM Sans',sans-serif; background:var(--soft); }

/* ══ ALERTS ══ */
.alert-success{ background:#e8fdf0;color:#1a6c3e;padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:13px;font-weight:600;border-left:4px solid #1a6c3e; }
.alert-error  { background:#fde8e8;color:#bf1a1a;padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:13px;font-weight:600;border-left:4px solid #bf1a1a; }

/* ════════════════════════════════════════
   MULTI-STEP FORM
════════════════════════════════════════ */
.form-shell{
    min-height:calc(100vh - 80px);
    display:flex; align-items:flex-start; justify-content:center;
    padding:32px 16px 60px; background:var(--soft);
}
.form-card{
    width:100%; max-width:540px;
    background:var(--white); border-radius:24px;
    box-shadow:0 4px 32px rgba(0,0,0,.08);
    overflow:hidden;
}
.form-progress-bar{ height:3px; background:#ede9e4; position:relative; }
.form-progress-fill{
    height:100%;
    background:linear-gradient(90deg,var(--pink),var(--purple));
    border-radius:3px;
    transition:width .4s cubic-bezier(.4,0,.2,1);
}
.form-header2{
    display:flex; align-items:center; gap:12px;
    padding:18px 24px 14px;
    border-bottom:1px solid var(--border);
}
.back-btn{
    width:34px; height:34px; border-radius:50%;
    background:var(--soft); border:1px solid var(--border);
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; color:var(--ink); font-size:14px;
    transition:background .2s;
}
.back-btn:hover{ background:var(--border); }
.step-label{ font-size:12px; font-weight:600; color:var(--muted); letter-spacing:.6px; text-transform:uppercase; }

.form-body2{ padding:28px 28px 10px; }

.form-slide{ display:none; animation:slideIn .3s ease; }
.form-slide.active{ display:block; }
@keyframes slideIn{ from{opacity:0;transform:translateX(16px);} to{opacity:1;transform:translateX(0);} }

.slide-title2{
    font-family:'Playfair Display',serif;
    font-size:22px; font-weight:700; color:var(--ink);
    line-height:1.3; margin-bottom:8px;
}
.slide-sub2{ font-size:13.5px; color:var(--muted); line-height:1.65; margin-bottom:24px; }

/* Pills */
.pill-group2{ display:flex; flex-wrap:wrap; gap:8px; }
.pill2{
    padding:9px 16px;
    border:1.5px solid var(--border);
    border-radius:50px;
    font-size:13px; font-weight:500; color:var(--ink);
    cursor:pointer; transition:all .2s; user-select:none;
    background:var(--white);
}
.pill2:hover{ border-color:var(--ink); }
.pill2.sel{ background:var(--ink); color:var(--white); border-color:var(--ink); }

/* Text inputs */
.finput{
    width:100%; padding:13px 16px;
    border:1.5px solid var(--border); border-radius:12px;
    font-size:14px; font-family:'DM Sans',sans-serif; color:var(--ink);
    outline:none; background:#fafaf9; transition:border .2s, box-shadow .2s;
}
.finput:focus{ border-color:var(--ink); background:var(--white); box-shadow:0 0 0 3px rgba(15,15,20,.06); }
textarea.finput{ resize:vertical; min-height:100px; }

/* Chips */
.chips{ display:flex; flex-wrap:wrap; gap:7px; margin-top:12px; }
.fchip{
    padding:6px 12px; background:var(--soft);
    border:1px solid var(--border); border-radius:20px;
    font-size:12px; font-weight:500; color:var(--muted);
    cursor:pointer; transition:all .2s;
}
.fchip:hover{ background:var(--border); color:var(--ink); }

/* Toggle */
.sw{ position:relative; display:inline-block; width:46px; height:25px; }
.sw input{ opacity:0; width:0; height:0; }
.sw-slider{ position:absolute; inset:0; background:#d1d5db; border-radius:25px; cursor:pointer; transition:.3s; }
.sw-slider::before{ content:''; position:absolute; height:19px; width:19px; left:3px; bottom:3px; background:white; border-radius:50%; transition:.3s; box-shadow:0 1px 4px rgba(0,0,0,.2); }
.sw input:checked + .sw-slider{ background:var(--ink); }
.sw input:checked + .sw-slider::before{ transform:translateX(21px); }

/* Height slider */
.height-num{ font-family:'Playfair Display',serif; font-size:48px; font-weight:900; color:var(--ink); text-align:center; line-height:1; }
.height-unit{ font-size:14px; color:var(--muted); text-align:center; margin-top:6px; margin-bottom:20px; }
.hslider{ width:100%; -webkit-appearance:none; height:5px; background:var(--border); border-radius:10px; outline:none; }
.hslider::-webkit-slider-thumb{ -webkit-appearance:none; width:26px; height:26px; border-radius:50%; background:var(--ink); cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,.2); }

/* Photo upload (step) */
.photo-grid-form{ display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
.photo-slot{
    aspect-ratio:3/4; border-radius:14px; border:2px dashed var(--border);
    background:var(--soft); display:flex; flex-direction:column;
    align-items:center; justify-content:center; cursor:pointer;
    position:relative; overflow:hidden; transition:border .2s, background .2s;
}
.photo-slot:hover{ border-color:var(--ink); background:#f0ede9; }
.photo-slot.has-photo{ border-style:solid; border-color:transparent; }
.photo-slot img{ width:100%; height:100%; object-fit:cover; position:absolute; inset:0; }
.photo-slot .main-badge{ position:absolute; top:8px; left:8px; z-index:2; background:var(--accent); color:white; font-size:10px; font-weight:700; padding:3px 8px; border-radius:20px; }
.photo-slot .del-btn{ position:absolute; top:6px; right:6px; z-index:2; width:24px; height:24px; border-radius:50%; background:rgba(0,0,0,.55); color:white; border:none; font-size:11px; cursor:pointer; display:none; align-items:center; justify-content:center; }
.photo-slot:hover .del-btn{ display:flex; }
.photo-slot .plus-icon{ font-size:26px; color:var(--muted); margin-bottom:4px; }
.photo-slot .slot-label{ font-size:11px; color:var(--muted); font-weight:500; }
.photo-note{ font-size:12px; color:var(--muted); text-align:center; margin-top:10px; }

/* Interest sections */
.interest-cat{ margin-bottom:18px; }
.interest-cat-label{ font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:var(--muted); margin-bottom:8px; }
.sel-count{ font-size:12px; color:var(--muted); margin-top:10px; }

/* Form nav */
.form-nav{ display:flex; gap:10px; padding:16px 28px 24px; border-top:1px solid var(--border); }
.btn-prev2{ padding:12px 18px; background:var(--soft); border:1px solid var(--border); border-radius:12px; font-size:13.5px; font-weight:600; color:var(--muted); cursor:pointer; font-family:'DM Sans',sans-serif; transition:background .2s; }
.btn-prev2:hover{ background:var(--border); }
.btn-next2{ flex:1; padding:13px; background:var(--ink); color:var(--white); border:none; border-radius:12px; font-size:14px; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; transition:background .2s; }
.btn-next2:hover{ background:#2a2a35; }
.skip-link{ text-align:center; padding:0 28px 16px; font-size:13px; color:var(--muted); cursor:pointer; text-decoration:underline; text-underline-offset:3px; background:none; border:none; font-family:'DM Sans',sans-serif; width:100%; display:block; }
.done-card{ text-align:center; padding:32px 24px; }
.done-emoji{ font-size:64px; display:block; margin-bottom:16px; animation:bob 1.2s ease infinite alternate; }
@keyframes bob{ from{transform:translateY(0);} to{transform:translateY(-10px);} }
.done-title{ font-family:'Playfair Display',serif; font-size:26px; font-weight:900; color:var(--ink); margin-bottom:8px; }
.done-sub{ font-size:14px; color:var(--muted); margin-bottom:24px; line-height:1.6; }
.btn-submit2{ width:100%; padding:15px; background:linear-gradient(135deg,var(--pink),var(--purple)); color:white; border:none; border-radius:14px; font-size:15px; font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif; transition:opacity .2s; }
.btn-submit2:hover{ opacity:.9; }

/* Toast */
.ftoast{ position:fixed; top:24px; left:50%; transform:translateX(-50%); background:var(--ink); color:white; padding:12px 22px; border-radius:50px; font-size:13px; font-weight:600; font-family:'DM Sans',sans-serif; z-index:9999; pointer-events:none; opacity:0; transition:opacity .3s; white-space:nowrap; }
.ftoast.show{ opacity:1; }

/* DOB row */
.dob-row{ display:flex; gap:8px; }
.dob-row select{ flex:1; }

/* ════════════════════════════════════════
   PROFILE VIEW
════════════════════════════════════════ */
.pv-page{ background:var(--soft); min-height:calc(100vh - 80px); padding:24px 16px 60px; }
.pv-wrap{ max-width:680px; margin:0 auto; }

.pv-hero{ background:var(--white); border-radius:var(--card-r); overflow:hidden; margin-bottom:16px; box-shadow:0 2px 16px rgba(0,0,0,.06); }
.pv-cover{ height:200px; background:linear-gradient(135deg,#0f0f14 0%,#2d1b4e 50%,#4a1942 100%); position:relative; overflow:hidden; }
.pv-cover-pattern{ position:absolute; inset:0; opacity:.15; background-image:radial-gradient(circle at 20% 50%, white 1px, transparent 1px),radial-gradient(circle at 80% 20%, white 1px, transparent 1px),radial-gradient(circle at 60% 80%, white 1px, transparent 1px); background-size:40px 40px, 60px 60px, 50px 50px; }
.pv-cover-btns{ position:absolute; top:14px; right:14px; display:flex; gap:8px; }
.pv-cover-btn{ background:rgba(0,0,0,.4); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.15); color:white; padding:7px 14px; border-radius:20px; font-size:12px; font-weight:600; cursor:pointer; text-decoration:none; font-family:'DM Sans',sans-serif; transition:background .2s; }
.pv-cover-btn:hover{ background:rgba(0,0,0,.6); color:white; }
.pv-cover-btn.danger{ color:#ff6b6b; }

.pv-photo-strip{ display:flex; gap:6px; padding:0 20px; overflow-x:auto; scrollbar-width:none; margin-top:-40px; position:relative; z-index:2; }
.pv-photo-strip::-webkit-scrollbar{ display:none; }
.pv-photo-item{ flex-shrink:0; width:90px; height:120px; border-radius:12px; overflow:hidden; border:3px solid var(--white); box-shadow:0 4px 12px rgba(0,0,0,.15); background:var(--border); cursor:pointer; position:relative; }
.pv-photo-item.main-photo{ width:100px; height:133px; margin-top:-6px; }
.pv-photo-item img{ width:100%; height:100%; object-fit:cover; }
.pv-photo-item img.blurred-photo{ filter:blur(8px); transform:scale(1.03); }
.pv-photo-item .main-pip{ position:absolute; bottom:6px; left:50%; transform:translateX(-50%); background:var(--accent); color:white; font-size:9px; font-weight:700; padding:2px 7px; border-radius:10px; white-space:nowrap; }
.pv-photo-placeholder{ width:90px; height:120px; border-radius:12px; border:2px dashed var(--border); background:var(--soft); display:flex; flex-direction:column; align-items:center; justify-content:center; flex-shrink:0; cursor:pointer; color:var(--muted); font-size:11px; gap:4px; text-decoration:none; }

.pv-hero-info{ padding:16px 20px 20px; }
.pv-name{ font-family:'Playfair Display',serif; font-size:26px; font-weight:900; color:var(--ink); margin-bottom:4px; display:flex; align-items:center; gap:10px; }
.pv-age-badge{ font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500; color:var(--muted); background:var(--soft); padding:3px 10px; border-radius:20px; border:1px solid var(--border); }
.pv-tagline{ font-size:13.5px; color:var(--muted); margin-bottom:14px; line-height:1.6; }
.pv-quick-tags{ display:flex; flex-wrap:wrap; gap:7px; margin-bottom:14px; }
.pv-qtag{ display:flex; align-items:center; gap:5px; background:var(--soft); border:1px solid var(--border); padding:5px 12px; border-radius:20px; font-size:12.5px; font-weight:500; color:var(--ink); }
.pv-qtag ion-icon{ font-size:13px; color:var(--accent); }

.pv-completion{ background:var(--soft); border-radius:12px; padding:12px 16px; display:flex; align-items:center; gap:12px; }
.pv-comp-bar{ flex:1; height:6px; background:var(--border); border-radius:10px; overflow:hidden; }
.pv-comp-fill{ height:100%; background:linear-gradient(90deg,var(--pink),var(--purple)); border-radius:10px; transition:width .6s; }
.pv-comp-pct{ font-size:13px; font-weight:700; color:var(--ink); white-space:nowrap; }
.pv-comp-label{ font-size:12px; color:var(--muted); }

.pv-section{ background:var(--white); border-radius:var(--card-r); overflow:hidden; margin-bottom:14px; box-shadow:0 2px 12px rgba(0,0,0,.04); }
.pv-sec-head{ padding:14px 20px; border-bottom:1px solid var(--border); font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.7px; color:var(--muted); display:flex; align-items:center; gap:7px; }
.pv-sec-head ion-icon{ font-size:14px; color:var(--accent); }

.pv-row{ display:flex; align-items:center; gap:14px; padding:12px 20px; border-bottom:1px solid #faf9f8; }
.pv-row:last-child{ border-bottom:none; }
.pv-row-icon{ width:34px; height:34px; border-radius:10px; background:var(--soft); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:15px; }
.pv-row-key{ font-size:11.5px; color:var(--muted); margin-bottom:2px; }
.pv-row-val{ font-size:13.5px; font-weight:600; color:var(--ink); }

.tag2{ display:inline-flex; align-items:center; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:500; margin:2px; }
.tag-ink  { background:var(--ink); color:white; }
.tag-soft { background:var(--soft); color:var(--ink); border:1px solid var(--border); }
.tag-green{ background:#e8fdf0; color:#1a6c3e; border:1px solid #b3f0cc; }
.tag-gold { background:#fef3e2; color:#96600a; border:1px solid #fcd29a; }

.pv-bio{ padding:16px 20px; font-size:14px; color:#444; line-height:1.8; font-style:italic; border-left:3px solid var(--accent); margin:0 20px 20px; background:#fefcf9; border-radius:0 10px 10px 0; }
.pv-interests{ padding:14px 20px; display:flex; flex-wrap:wrap; gap:7px; }

/* ── Action Buttons (bottom of view) ── */
.pv-bottom-actions{
    display:flex; gap:10px; margin-top:6px; padding-bottom:10px;
}
.btn-pv-edit{
    flex:1; padding:14px; background:var(--ink); color:white;
    border:none; border-radius:12px; font-size:14px; font-weight:600;
    cursor:pointer; font-family:'DM Sans',sans-serif; text-decoration:none;
    display:flex; align-items:center; justify-content:center; gap:8px;
    transition:background .2s;
}
.btn-pv-edit:hover{ background:#2a2a35; color:white; }
.btn-pv-del{
    padding:14px 20px; background:white; color:#e74c3c;
    border:1.5px solid #e74c3c; border-radius:12px; font-size:14px; font-weight:600;
    cursor:pointer; font-family:'DM Sans',sans-serif; transition:all .2s;
}
.btn-pv-del:hover{ background:#e74c3c; color:white; }

/* ════════════════════════════════════════
   EDIT MODE
════════════════════════════════════════ */
.edit-page{ background:var(--soft); min-height:calc(100vh - 80px); padding:24px 16px 60px; }
.edit-wrap{ max-width:640px; margin:0 auto; }
.edit-page-title{ font-family:'Playfair Display',serif; font-size:24px; font-weight:900; color:var(--ink); margin-bottom:4px; }
.edit-page-sub{ font-size:13.5px; color:var(--muted); margin-bottom:24px; }

.edit-card2{ background:var(--white); border-radius:var(--card-r); overflow:hidden; margin-bottom:14px; box-shadow:0 2px 12px rgba(0,0,0,.04); }
.edit-card2 .ec-head{ padding:14px 20px; border-bottom:1px solid var(--border); font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.7px; color:var(--muted); }
.edit-card2 .ec-row{ display:flex; align-items:center; gap:14px; padding:12px 20px; border-bottom:1px solid #faf9f8; }
.edit-card2 .ec-row:last-child{ border-bottom:none; }
.edit-card2 .ec-lbl{ font-size:13px; font-weight:500; color:var(--muted); min-width:140px; flex-shrink:0; }
.edit-card2 .ec-row input,
.edit-card2 .ec-row select{ flex:1; padding:9px 13px; border:1.5px solid var(--border); border-radius:10px; font-size:13px; font-family:'DM Sans',sans-serif; color:var(--ink); background:#fafaf9; outline:none; transition:border .2s; appearance:none; }
.edit-card2 .ec-row input:focus,
.edit-card2 .ec-row select:focus{ border-color:var(--ink); background:white; }
.edit-card2 .ec-textarea{ padding:14px 20px; }
.edit-card2 textarea{ width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:10px; font-size:13px; font-family:'DM Sans',sans-serif; color:var(--ink); background:#fafaf9; outline:none; resize:vertical; min-height:90px; transition:border .2s; }
.edit-card2 textarea:focus{ border-color:var(--ink); background:white; }
.edit-card2 .ec-toggle{ display:flex; align-items:center; justify-content:space-between; padding:13px 20px; border-bottom:1px solid #faf9f8; }
.edit-card2 .ec-toggle:last-child{ border-bottom:none; }
.edit-card2 .ec-toggle-lbl{ font-size:13px; font-weight:500; color:var(--ink); }
.edit-card2 .ec-toggle-sub{ font-size:11.5px; color:var(--muted); margin-top:2px; }

.photo-edit-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:8px; padding:16px 20px; }
.peg-slot{ aspect-ratio:3/4; border-radius:12px; overflow:hidden; border:2px dashed var(--border); background:var(--soft); display:flex; flex-direction:column; align-items:center; justify-content:center; position:relative; cursor:pointer; transition:border .2s; }
.peg-slot:hover{ border-color:var(--ink); }
.peg-slot.filled{ border-style:solid; border-color:transparent; }
.peg-slot img{ width:100%; height:100%; object-fit:cover; position:absolute; inset:0; }
.peg-slot img.blurred-photo{ filter:blur(8px); transform:scale(1.03); }
.peg-slot .peg-main{ position:absolute; top:6px; left:6px; background:var(--accent); color:white; font-size:9px; font-weight:700; padding:2px 7px; border-radius:10px; }
.peg-slot .peg-blur-badge{ position:absolute; top:6px; left:58px; background:rgba(0,0,0,.65); color:white; font-size:9px; font-weight:700; padding:2px 7px; border-radius:10px; }
.peg-slot .peg-del{ position:absolute; top:6px; right:6px; width:22px; height:22px; border-radius:50%; background:rgba(0,0,0,.55); color:white; border:none; font-size:11px; cursor:pointer; display:none; align-items:center; justify-content:center; }
.peg-slot:hover .peg-del{ display:flex; }
.peg-slot .peg-set-main{ position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,.55); color:white; border:none; font-size:11px; font-weight:600; padding:5px; cursor:pointer; display:none; font-family:'DM Sans',sans-serif; }
.peg-slot:hover .peg-set-main{ display:block; }
.peg-slot .peg-toggle-blur{ position:absolute; bottom:28px; left:0; right:0; background:rgba(0,0,0,.58); color:white; border:none; font-size:11px; font-weight:600; padding:5px; cursor:pointer; display:none; font-family:'DM Sans',sans-serif; }
.peg-slot:hover .peg-toggle-blur{ display:block; }
.peg-slot .peg-plus{ font-size:24px; color:var(--muted); margin-bottom:4px; }
.peg-slot .peg-lbl{ font-size:11px; color:var(--muted); }

.edit-action-bar{ display:flex; gap:10px; margin-top:6px; }
.btn-save2{ flex:1; padding:14px; background:var(--ink); color:white; border:none; border-radius:12px; font-size:14px; font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif; transition:background .2s; }
.btn-save2:hover{ background:#2a2a35; }
.btn-cancel2{ padding:14px 20px; background:var(--soft); color:var(--muted); border:1px solid var(--border); border-radius:12px; font-size:14px; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; text-decoration:none; display:inline-flex; align-items:center; transition:background .2s; }
.btn-cancel2:hover{ background:var(--border); color:var(--ink); }
</style>

<div style="max-width:680px;margin:0 auto;padding:16px 16px 0">
    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">❌ {{ $errors->first() }}</div>
    @endif
</div>

<div class="ftoast" id="fToast"></div>

{{-- ════════════════════════════════
     MODE A — NO PROFILE → MULTI-STEP FORM
════════════════════════════════ --}}
@if(!$profile && !request('edit'))

<div class="form-shell">
<div style="width:100%;max-width:540px">
<div class="form-card">

    <div class="form-progress-bar">
        <div class="form-progress-fill" id="pFill" style="width:0%"></div>
    </div>
    <div class="form-header2">
        <button type="button" class="back-btn" id="backBtn">←</button>
        <div style="flex:1">
            <div class="step-label" id="stepLbl">Step 1 of 17</div>
        </div>
    </div>

    <form id="mForm" method="POST" action="{{ route('user.profile.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-body2">

        {{-- STEP 1: Photos --}}
        <div class="form-slide active" data-step="1">
            <h2 class="slide-title2">Add your photos 📸</h2>
            <p class="slide-sub2">Upload up to 6 photos. Your first photo will be your main photo.</p>
            <div class="photo-grid-form" id="photoGrid"></div>
            <p class="photo-note">Drag to reorder · First photo = Main</p>
        </div>

        {{-- STEP 2: DOB --}}
        <div class="form-slide" data-step="2">
            <h2 class="slide-title2">When's your birthday? 🎂</h2>
            <p class="slide-sub2">Your age will be shown on your profile. You must be 18+.</p>
            <div class="dob-row">
                <select class="finput" name="dob_day" id="dobDay">
                    <option value="">Day</option>
                    @for($d=1;$d<=31;$d++)<option value="{{ $d }}">{{ $d }}</option>@endfor
                </select>
                <select class="finput" name="dob_month" id="dobMonth">
                    <option value="">Month</option>
                    @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $i=>$m)
                        <option value="{{ $i+1 }}">{{ $m }}</option>
                    @endforeach
                </select>
                <select class="finput" name="dob_year" id="dobYear">
                    <option value="">Year</option>
                    @for($y=date('Y')-18;$y>=1950;$y--)<option value="{{ $y }}">{{ $y }}</option>@endfor
                </select>
            </div>
            <input type="hidden" name="date_of_birth" id="dobFinal">
            <div id="agePreview" style="margin-top:14px;font-size:14px;color:var(--muted);"></div>
        </div>

        {{-- STEP 3: City (country fixed at registration) --}}
        @php
            $regCountry = (string) (auth()->user()->country ?? '');
            $cityListSignup = \App\Support\CountryCities::citiesFor($regCountry);
        @endphp
        <div class="form-slide" data-step="3">
            <h2 class="slide-title2">Where are you based? 🌍</h2>
            <p class="slide-sub2">Your country is the one you chose at sign up. Pick your city from the list.</p>
            <div style="margin-bottom:14px">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:8px">Country</div>
                <div class="finput" style="background:#f0efec;color:var(--muted);cursor:not-allowed;border-style:dashed;margin-bottom:0" aria-readonly="true">{{ $regCountry !== '' ? $regCountry : '—' }}</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:8px">City</div>
                <select name="city" id="profileCitySelect" class="finput" required>
                    <option value="" disabled {{ old('city') ? '' : 'selected' }}>Select your city</option>
                    @foreach($cityListSignup as $cityName)
                        <option value="{{ $cityName }}" {{ old('city') === $cityName ? 'selected' : '' }}>{{ $cityName }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- STEP 4: Sect --}}
        <div class="form-slide" data-step="4">
            <h2 class="slide-title2">What sect do you belong to?</h2>
            <p class="slide-sub2">This helps us find compatible matches for you.</p>
            <div class="pill-group2" id="sectGrp">
                @foreach(['Sunni','Shia','Ahmadi','Nation of Islam','Ibadi','Just Muslim','Prefer not to say'] as $s)
                    <span class="pill2" data-val="{{ $s }}">{{ $s }}</span>
                @endforeach
            </div>
            <input type="hidden" name="sect" id="sectH">
        </div>

        {{-- STEP 5: Profession --}}
        <div class="form-slide" data-step="5">
            <h2 class="slide-title2">What's your profession?</h2>
            <p class="slide-sub2">Select one or type your own.</p>
            <input type="text" class="finput" name="profession" id="profInp" placeholder="e.g. Doctor, Engineer...">
            <div class="chips">
                @foreach(['Doctor','Software Engineer','Teacher','Lawyer','Engineer','Accountant','Designer','Business Owner','Nurse','Architect','Student','Homemaker'] as $p)
                    <span class="fchip" data-target="profInp">{{ $p }}</span>
                @endforeach
            </div>
        </div>

        {{-- STEP 6: Education --}}
        <div class="form-slide" data-step="6">
            <h2 class="slide-title2">Highest education level?</h2>
            <p class="slide-sub2">Select your highest qualification.</p>
            <div class="pill-group2" id="eduGrp">
                @foreach(['High School','Diploma / Vocational',"Bachelor's Degree","Master's Degree",'PhD / Doctorate','Islamic Education','Other'] as $e)
                    <span class="pill2" data-val="{{ $e }}">{{ $e }}</span>
                @endforeach
            </div>
            <input type="hidden" name="education" id="eduH">
        </div>

        {{-- STEP 7: Nationality --}}
        <div class="form-slide" data-step="7">
            <h2 class="slide-title2">What's your nationality?</h2>
            <p class="slide-sub2">Select or type your own.</p>
            <input type="text" class="finput" name="nationality" id="natInp" placeholder="e.g. Pakistani, British...">
            <div class="chips">
                @foreach(['Pakistani','British','American','Indian','Bangladeshi','Saudi Arabian','Emirati','Turkish','Egyptian','Malaysian','Canadian'] as $n)
                    <span class="fchip" data-target="natInp">{{ $n }}</span>
                @endforeach
            </div>
        </div>

        {{-- STEP 8: Ethnicity --}}
        <div class="form-slide" data-step="8">
            <h2 class="slide-title2">What's your ethnicity?</h2>
            <p class="slide-sub2">Select all that apply.</p>
            <div class="pill-group2" id="ethGrp">
                @foreach(['Arab','South Asian','African','Black British','White / Caucasian','East Asian','Southeast Asian','Mixed','Other','Prefer not to say'] as $e)
                    <span class="pill2 multi" data-val="{{ $e }}">{{ $e }}</span>
                @endforeach
            </div>
            <input type="hidden" name="ethnicity" id="ethH">
        </div>

        {{-- STEP 9: Height --}}
        <div class="form-slide" data-step="9">
            <h2 class="slide-title2">How tall are you?</h2>
            <p class="slide-sub2">Drag the slider or pick a quick option.</p>
            <div class="height-num" id="hNum">5'6"</div>
            <div class="height-unit" id="hCm">168 cm</div>
            <input type="range" class="hslider" id="hSlider" min="140" max="220" value="168">
            <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--muted);margin-top:4px"><span>4'7"</span><span>7'3"</span></div>
            <div class="chips" style="margin-top:14px">
                @foreach([160=>"5'3",163=>"5'4",165=>"5'5",168=>"5'6",170=>"5'7",173=>"5'8",175=>"5'9",178=>"5'10",180=>"5'11",183=>"6'0"] as $cm=>$ft)
                    <span class="fchip hchip" data-cm="{{ $cm }}">{{ $ft }}"</span>
                @endforeach
            </div>
            <input type="hidden" name="height_cm" id="hInp" value="168">
        </div>

        {{-- STEP 10: Marital Status --}}
        <div class="form-slide" data-step="10">
            <h2 class="slide-title2">Marital status?</h2>
            <p class="slide-sub2">Be honest for the best matches.</p>
            <div class="pill-group2" id="marGrp">
                @foreach(['Never Married','Divorced','Widowed','Separated','Married (polygamy)'] as $m)
                    <span class="pill2" data-val="{{ $m }}">{{ $m }}</span>
                @endforeach
            </div>
            <input type="hidden" name="marital_status" id="marH">
        </div>

        {{-- STEP 11: Marriage Intentions --}}
        <div class="form-slide" data-step="11">
            <h2 class="slide-title2">Intentions for marriage?</h2>
            <p class="slide-sub2">This helps set expectations with matches.</p>
            <div class="pill-group2" id="intentGrp">
                @foreach(['Seriously looking','Open to options','Not sure yet','Within 1 year','Within 2-3 years'] as $i)
                    <span class="pill2" data-val="{{ $i }}">{{ $i }}</span>
                @endforeach
            </div>
            <input type="hidden" name="marriage_intentions" id="intentH">
        </div>

        {{-- STEP 12: Want children --}}
        <div class="form-slide" data-step="12">
            <h2 class="slide-title2">Do you want children? 👶</h2>
            <p class="slide-sub2">Let potential matches know your family plans.</p>
            <div class="pill-group2" id="childGrp" style="margin-bottom:20px">
                @foreach(['Yes','No','Open to it','Have children already'] as $c)
                    <span class="pill2" data-val="{{ $c }}">{{ $c }}</span>
                @endforeach
            </div>
            <input type="hidden" name="want_children" id="childH">
            <p class="slide-sub2" style="margin-bottom:10px">How many? (optional)</p>
            <div class="pill-group2" id="numChildGrp">
                @foreach(['1','2','3','4+','Prefer not to say'] as $n)
                    <span class="pill2" data-val="{{ $n }}">{{ $n }}</span>
                @endforeach
            </div>
            <input type="hidden" name="num_children" id="numChildH">
        </div>

        {{-- STEP 13: Smoking --}}
        <div class="form-slide" data-step="13">
            <h2 class="slide-title2">Lifestyle habits 🚬</h2>
            <p class="slide-sub2">Help matches understand your lifestyle.</p>
            <p style="font-size:13px;font-weight:600;color:var(--ink);margin-bottom:10px">Smoking</p>
            <div class="pill-group2" id="smokeGrp" style="margin-bottom:20px">
                @foreach(['Never','Occasionally','Yes, regularly','Prefer not to say'] as $s)
                    <span class="pill2" data-val="{{ $s }}">{{ $s }}</span>
                @endforeach
            </div>
            <input type="hidden" name="smoking" id="smokeH">
            <p style="font-size:13px;font-weight:600;color:var(--ink);margin-bottom:10px">Drinking</p>
            <div class="pill-group2" id="drinkGrp" style="margin-bottom:8px">
                @foreach(['Never','Occasionally','Yes, regularly','Prefer not to say'] as $d)
                    <span class="pill2" data-val="{{ $d }}">{{ $d }}</span>
                @endforeach
            </div>
            <input type="hidden" name="drinking" id="drinkH">
        </div>

        {{-- STEP 14: Languages --}}
        <div class="form-slide" data-step="14">
            <h2 class="slide-title2">Languages spoken? 🗣️</h2>
            <p class="slide-sub2">Select all languages you speak.</p>
            <div class="pill-group2" id="langGrp">
                @foreach(['English','Urdu','Arabic','Hindi','Punjabi','Bengali','Turkish','Malay','French','German','Persian','Pashto','Somali'] as $l)
                    <span class="pill2 multi" data-val="{{ $l }}">{{ $l }}</span>
                @endforeach
            </div>
            <input type="hidden" name="languages" id="langH">
        </div>

        {{-- STEP 15: Religion practice (skippable) --}}
        <div class="form-slide" data-step="15" data-skippable="1">
            <h2 class="slide-title2">How do you practise Islam?</h2>
            <p class="slide-sub2">Select the option that best describes you.</p>
            <div class="pill-group2" id="relGrp">
                @foreach(['Practising','Moderately Practising','Not Practising','Revert / New Muslim'] as $r)
                    <span class="pill2" data-val="{{ $r }}">{{ $r }}</span>
                @endforeach
            </div>
            <input type="hidden" name="religion_practice" id="relH" value="Moderately Practising">
        </div>

        {{-- STEP 16: Interests --}}
        <div class="form-slide" data-step="16">
            <h2 class="slide-title2">Your interests ⭐</h2>
            <p class="slide-sub2">Select up to <strong>15</strong> that represent you.</p>
            @foreach([
                ['🎨 Arts & Culture',['Acting','Anime','Art galleries','Creative writing','Design','DIY','Fashion','Film & Cinema','Photography','Music','Painting']],
                ['⚽ Sports & Fitness',['Football','Cricket','Basketball','Gym','Running','Swimming','Cycling','Hiking','Martial Arts','Tennis']],
                ['🍽️ Food & Lifestyle',['Cooking','Baking','Trying restaurants','Coffee','Travelling','Reading','Gardening','Volunteering']],
                ['💻 Tech & Learning',['Technology','Gaming','Podcasts','Languages','Science','History','Islamic Studies']],
            ] as [$cat,$items])
                <div class="interest-cat">
                    <div class="interest-cat-label">{{ $cat }}</div>
                    <div class="pill-group2">
                        @foreach($items as $item)
                            <span class="pill2 interest-p" data-val="{{ $item }}">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <p class="sel-count">Selected: <span id="intCount">0</span>/15</p>
            <input type="hidden" name="interests" id="intH">
        </div>

        {{-- STEP 17: Bio + Personality --}}
        <div class="form-slide" data-step="17">
            <h2 class="slide-title2">Tell us about yourself 📝</h2>
            <p class="slide-sub2">Your bio is what makes you stand out. Max 300 characters.</p>
            <textarea class="finput" name="bio" id="bioInp" rows="4" placeholder="e.g. I'm a passionate engineer from Karachi who loves cricket and cooking..."></textarea>
            <div style="text-align:right;font-size:12px;color:var(--muted);margin-top:4px"><span id="bioCount">0</span>/300</div>
            <div class="chips" style="margin-top:10px">
                <span class="fchip bio-s" data-text="I work as a ">✍️ Career</span>
                <span class="fchip bio-s" data-text="In my free time, I enjoy ">🎯 Hobbies</span>
                <span class="fchip bio-s" data-text="Deen is very important to me. ">🕌 Deen</span>
                <span class="fchip bio-s" data-text="Family means everything to me. ">👨‍👩‍👧 Family</span>
            </div>
            <p style="font-size:13px;font-weight:600;color:var(--ink);margin:20px 0 10px">Personality traits (max 5)</p>
            <div class="pill-group2" id="perGrp">
                @foreach(['Introvert','Extrovert','Ambivert','Ambitious','Caring','Funny','Calm','Creative','Adventurous','Family-oriented','Intellectual','Spiritual','Hardworking','Romantic','Empathetic'] as $t)
                    <span class="pill2 per-p" data-val="{{ $t }}">{{ $t }}</span>
                @endforeach
            </div>
            <p class="sel-count">Selected: <span id="perCount">0</span>/5</p>
            <input type="hidden" name="personality" id="perH">
        </div>

        {{-- DONE --}}
        <div class="form-slide done-card" data-step="done">
            <span class="done-emoji">🎉</span>
            <div class="done-title">You're all set!</div>
            <p class="done-sub">Your profile looks amazing. Let's find your perfect match!</p>
            <button type="submit" class="btn-submit2">✅ Save My Profile</button>
        </div>

    </div>{{-- /form-body2 --}}

    <div class="form-nav" id="fNav">
        <button type="button" class="btn-prev2" id="prevBtn" style="display:none">← Back</button>
        <button type="button" class="btn-next2" id="nextBtn">Continue →</button>
    </div>
    <button type="button" class="skip-link" id="skipBtn" style="display:none">Skip this step</button>

    </form>
</div>
</div>
</div>

{{-- ════════════════════════════════
     MODE B — VIEW PROFILE
════════════════════════════════ --}}
@elseif($profile && !request('edit'))

<div class="pv-page">
<div class="pv-wrap">

    {{-- Hero --}}
    <div class="pv-hero">
        <div class="pv-cover">
            <div class="pv-cover-pattern"></div>
        </div>

        {{-- Photo strip --}}
        <div class="pv-photo-strip">
            @forelse($photos as $photo)
                <div class="pv-photo-item {{ $photo->is_main ? 'main-photo' : '' }}">
                    <img src="{{ asset('storage/'.$photo->path) }}" alt="Photo" class="{{ $photo->is_blurred ? 'blurred-photo' : '' }}">
                    @if($photo->is_main)<div class="main-pip">⭐ Main</div>@endif
                    @if($photo->is_blurred)<div class="peg-blur-badge">Blurred</div>@endif
                </div>
            @empty
                <a href="{{ route('user.profile') }}?edit=1" class="pv-photo-placeholder">
                    <span style="font-size:22px">📸</span>
                    <span>Add photos</span>
                </a>
            @endforelse
        </div>

        {{-- Name / info --}}
        <div class="pv-hero-info">
            <div class="pv-name">
                {{ Auth::user()->name }}
                @if($profile->date_of_birth)
                    <span class="pv-age-badge">{{ \Carbon\Carbon::parse($profile->date_of_birth)->age }} yrs</span>
                @endif
            </div>
            @if($profile->bio)
                <p class="pv-tagline">"{{ Str::limit($profile->bio, 80) }}"</p>
            @endif

            <div class="pv-quick-tags">
                @if($profile->city || $profile->country)
                    <div class="pv-qtag"><ion-icon name="location-outline"></ion-icon> {{ collect([$profile->city,$profile->country])->filter()->implode(', ') }}</div>
                @endif
                @if($profile->profession)
                    <div class="pv-qtag"><ion-icon name="briefcase-outline"></ion-icon> {{ $profile->profession }}</div>
                @endif
                @if($profile->marital_status)
                    <div class="pv-qtag"><ion-icon name="heart-outline"></ion-icon> {{ $profile->marital_status }}</div>
                @endif
                @if($profile->sect)
                    <div class="pv-qtag"><ion-icon name="moon-outline"></ion-icon> {{ $profile->sect }}</div>
                @endif
            </div>

            {{-- Completion --}}
            <div class="pv-completion">
                <div>
                    <div class="pv-comp-pct">{{ $profile->profile_completion }}%</div>
                    <div class="pv-comp-label">Complete</div>
                </div>
                <div class="pv-comp-bar">
                    <div class="pv-comp-fill" style="width:{{ $profile->profile_completion }}%"></div>
                </div>
            </div>

            @if((int) $profile->profile_completion >= 100)
                <div style="margin-top:12px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <a href="{{ route('user.verification.index') }}" class="btn btn-dark btn-sm px-3">
                        Verify Photo
                    </a>
                    @if(Auth::user()->photo_verified)
                        <span class="tag2 tag-green">Photo Verified</span>
                    @else
                        <span class="tag2 tag-soft">Not Verified Yet</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Religion --}}
    <div class="pv-section">
        <div class="pv-sec-head"><ion-icon name="moon-outline"></ion-icon> Religion & Identity</div>
        @foreach([
            ['moon-outline','Sect',$profile->sect],
            ['book-outline','Practice',$profile->religion_practice],
            ['person-outline','Born Muslim',$profile->born_muslim],
        ] as [$icon,$key,$val])
            @if($val)
            <div class="pv-row">
                <div class="pv-row-icon"><ion-icon name="{{ $icon }}" style="color:var(--accent);font-size:16px;"></ion-icon></div>
                <div><div class="pv-row-key">{{ $key }}</div><div class="pv-row-val">{{ $val }}</div></div>
            </div>
            @endif
        @endforeach
    </div>

    {{-- Personal --}}
    <div class="pv-section">
        <div class="pv-sec-head"><ion-icon name="person-outline"></ion-icon> Personal Info</div>
        @if($profile->date_of_birth)
        <div class="pv-row">
            <div class="pv-row-icon"><ion-icon name="calendar-outline" style="color:var(--pink);font-size:16px;"></ion-icon></div>
            <div><div class="pv-row-key">Date of Birth</div><div class="pv-row-val">{{ \Carbon\Carbon::parse($profile->date_of_birth)->format('d M Y') }} · Age {{ \Carbon\Carbon::parse($profile->date_of_birth)->age }}</div></div>
        </div>
        @endif
        @foreach([
            ['location-outline','Location',collect([$profile->city,$profile->country])->filter()->implode(', '),'var(--purple)'],
            ['flag-outline','Nationality',$profile->nationality,'var(--accent)'],
            ['home-outline','Grew Up',$profile->grew_up,'#16a34a'],
            ['resize-outline','Height',$profile->height_cm ? ($profile->height_formatted ?? $profile->height_cm.' cm') : null,'#0ea5e9'],
            ['heart-outline','Marital Status',$profile->marital_status,'var(--pink)'],
        ] as [$icon,$key,$val,$color])
            @if($val)
            <div class="pv-row">
                <div class="pv-row-icon"><ion-icon name="{{ $icon }}" style="color:{{ $color }};font-size:16px;"></ion-icon></div>
                <div><div class="pv-row-key">{{ $key }}</div><div class="pv-row-val">{{ $val }}</div></div>
            </div>
            @endif
        @endforeach
        @if($profile->ethnicity)
        <div class="pv-row">
            <div class="pv-row-icon"><ion-icon name="people-outline" style="color:#8b5cf6;font-size:16px;"></ion-icon></div>
            <div>
                <div class="pv-row-key">Ethnicity</div>
                <div class="pv-row-val">
                    @foreach(explode(',',$profile->ethnicity) as $e)
                        <span class="tag2 tag-soft">{{ trim($e) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Lifestyle --}}
    @if($profile->smoking || $profile->drinking || $profile->want_children || $profile->languages)
    <div class="pv-section">
        <div class="pv-sec-head"><ion-icon name="leaf-outline"></ion-icon> Lifestyle</div>
        @if($profile->smoking)
        <div class="pv-row">
            <div class="pv-row-icon">🚬</div>
            <div><div class="pv-row-key">Smoking</div><div class="pv-row-val">{{ $profile->smoking }}</div></div>
        </div>
        @endif
        @if($profile->drinking)
        <div class="pv-row">
            <div class="pv-row-icon">🍷</div>
            <div><div class="pv-row-key">Drinking</div><div class="pv-row-val">{{ $profile->drinking }}</div></div>
        </div>
        @endif
        @if($profile->want_children)
        <div class="pv-row">
            <div class="pv-row-icon">👶</div>
            <div>
                <div class="pv-row-key">Want Children</div>
                <div class="pv-row-val">{{ $profile->want_children }}{{ $profile->num_children ? ' · '.$profile->num_children : '' }}</div>
            </div>
        </div>
        @endif
        @if($profile->languages)
        <div class="pv-row">
            <div class="pv-row-icon">🗣️</div>
            <div>
                <div class="pv-row-key">Languages</div>
                <div class="pv-row-val">
                    @foreach(explode(',',$profile->languages) as $l)
                        <span class="tag2 tag-gold">{{ trim($l) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Career --}}
    <div class="pv-section">
        <div class="pv-sec-head"><ion-icon name="briefcase-outline"></ion-icon> Career & Education</div>
        @foreach([
            ['briefcase-outline','Profession',$profile->profession,'var(--purple)'],
            ['school-outline','Education',$profile->education,'#0ea5e9'],
        ] as [$icon,$key,$val,$color])
            @if($val)
            <div class="pv-row">
                <div class="pv-row-icon"><ion-icon name="{{ $icon }}" style="color:{{ $color }};font-size:16px;"></ion-icon></div>
                <div><div class="pv-row-key">{{ $key }}</div><div class="pv-row-val">{{ $val }}</div></div>
            </div>
            @endif
        @endforeach
    </div>

    {{-- Marriage --}}
    <div class="pv-section">
        <div class="pv-sec-head"><ion-icon name="diamond-outline"></ion-icon> Marriage</div>
        @if($profile->marriage_intentions)
        <div class="pv-row">
            <div class="pv-row-icon"><ion-icon name="diamond-outline" style="color:var(--pink);font-size:16px;"></ion-icon></div>
            <div><div class="pv-row-key">Intentions</div><div class="pv-row-val">{{ $profile->marriage_intentions }}</div></div>
        </div>
        @endif
    </div>

    {{-- Bio --}}
    @if($profile->bio)
    <div class="pv-section">
        <div class="pv-sec-head"><ion-icon name="document-text-outline"></ion-icon> Bio</div>
        <div class="pv-bio">{{ $profile->bio }}</div>
    </div>
    @endif

    {{-- Interests --}}
    @if($profile->interests)
    <div class="pv-section">
        <div class="pv-sec-head"><ion-icon name="star-outline"></ion-icon> Interests & Personality</div>
        <div class="pv-interests">
            @foreach(explode(',',$profile->interests) as $i)
                <span class="tag2 tag-ink">{{ trim($i) }}</span>
            @endforeach
        </div>
        @if($profile->personality)
        <div class="pv-interests" style="padding-top:0;border-top:1px solid var(--border)">
            @foreach(explode(',',$profile->personality) as $p)
                <span class="tag2 tag-green">{{ trim($p) }}</span>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    {{-- ══ EDIT & DELETE BUTTONS ══ --}}
    <div class="pv-bottom-actions">
        <a href="{{ route('user.profile') }}?edit=1" class="btn-pv-edit">
            <ion-icon name="create-outline"></ion-icon> Edit Profile
        </a>
        <form method="POST" action="{{ route('user.profile.destroy') }}"
            onsubmit="return confirm('Are you sure you want to delete your profile? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-pv-del">🗑 Delete</button>
        </form>
    </div>

</div>
</div>

{{-- ════════════════════════════════
     MODE C — EDIT MODE
════════════════════════════════ --}}
@elseif($profile && request('edit'))

<div class="edit-page">
<div class="edit-wrap">
    @php
        $f = fn ($key, $fallback = null) => old($key, data_get($profile, $key, $fallback));
        $sv = function ($key, $fallback = null) use ($f) {
            $value = $f($key, $fallback);
            return is_string($value) ? trim($value) : $value;
        };
        $eq = function ($a, $b) {
            if ($a === null || $b === null) return false;
            return strcasecmp(trim((string) $a), trim((string) $b)) === 0;
        };
        $maritalForEdit = $sv('marital_status');
        if ($eq($maritalForEdit, 'Single')) {
            $maritalForEdit = 'Never Married';
        } elseif ($eq($maritalForEdit, 'Married')) {
            $maritalForEdit = 'Married (polygamy)';
        }
        $dobForInput = $f('date_of_birth');
        if ($dobForInput instanceof \Carbon\CarbonInterface) {
            $dobForInput = $dobForInput->format('Y-m-d');
        } elseif (!empty($dobForInput)) {
            try {
                $dobForInput = \Carbon\Carbon::parse($dobForInput)->format('Y-m-d');
            } catch (\Throwable $e) {
                $dobForInput = '';
            }
        }
        $regCountryEdit = (string) (auth()->user()->country ?? '');
        $cityListEdit = \App\Support\CountryCities::citiesFor($regCountryEdit);
    @endphp

    <div class="edit-page-title">✏️ Edit Profile</div>
    <div class="edit-page-sub">Update your info to get better matches.</div>

    <form method="POST" action="{{ route('user.profile.update') }}">
    @csrf @method('PATCH')

    {{-- Photos --}}
    <div class="edit-card2">
        <div class="ec-head">📸 Photos</div>
        @if(!Auth::user()->isPremium())
            <div style="padding:10px 20px;border-bottom:1px solid #faf9f8;font-size:12px;color:#6b7280">
                Blur photos is a premium feature. Upgrade to unlock.
            </div>
        @endif
        <div class="photo-edit-grid" id="pegGrid">
            @foreach($photos as $photo)
            <div class="peg-slot filled" data-id="{{ $photo->id }}">
                <img src="{{ asset('storage/'.$photo->path) }}" class="{{ $photo->is_blurred ? 'blurred-photo' : '' }}">
                @if($photo->is_main)<div class="peg-main">⭐ Main</div>@endif
                @if($photo->is_blurred)<div class="peg-blur-badge">Blurred</div>@endif
                <button type="button" class="peg-del" onclick="delPhoto({{ $photo->id }},this)">✕</button>
                <button type="button" class="peg-toggle-blur" onclick="toggleBlur({{ $photo->id }}, {{ $photo->is_blurred ? 'true' : 'false' }})">
                    {{ $photo->is_blurred ? 'Unblur photo' : 'Blur photo' }}
                </button>
                @if(!$photo->is_main)
                <button type="button" class="peg-set-main" onclick="setMain({{ $photo->id }},this)">Set as main</button>
                @endif
            </div>
            @endforeach
            @for($i=count($photos);$i<6;$i++)
            <div class="peg-slot" onclick="document.getElementById('pegUpload').click()">
                <div class="peg-plus">+</div>
                <div class="peg-lbl">Add photo</div>
            </div>
            @endfor
        </div>
        <input type="file" id="pegUpload" accept="image/*" style="display:none" onchange="uploadPhoto(this)">
        <p style="font-size:12px;color:var(--muted);padding:0 20px 14px">Max 6 photos · JPG, PNG, WebP · 5MB each</p>
    </div>

    {{-- Religion --}}
    <div class="edit-card2">
        <div class="ec-head">☪️ Religion & Identity</div>
        <div class="ec-row"><span class="ec-lbl">Sect</span>
            <select name="sect">
                <option value="">-- Select --</option>
                @foreach(['Sunni','Shia','Ahmadi','Nation of Islam','Ibadi','Just Muslim','Prefer not to say'] as $s)
                    <option value="{{ $s }}" @selected($eq($sv('sect'), $s))>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="ec-row"><span class="ec-lbl">Religion Practice</span>
            <select name="religion_practice">
                <option value="">-- Select --</option>
                @foreach(['Practising','Moderately Practising','Not Practising','Revert / New Muslim'] as $r)
                    <option value="{{ $r }}" @selected($eq($sv('religion_practice'), $r))>{{ $r }}</option>
                @endforeach
            </select>
        </div>
        <div class="ec-row"><span class="ec-lbl">Born Muslim</span>
            <select name="born_muslim">
                <option value="">-- Select --</option>
                @foreach(['Yes'=>'Yes, born Muslim','No'=>'No, I reverted','Prefer not to say'=>'Prefer not to say'] as $v=>$l)
                    <option value="{{ $v }}" @selected($eq($sv('born_muslim'), $v))>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Personal --}}
    <div class="edit-card2">
        <div class="ec-head">👤 Personal Info</div>
        <div class="ec-row"><span class="ec-lbl">Date of Birth</span>
            <input type="date" name="date_of_birth" value="{{ $dobForInput }}">
        </div>
        <div class="ec-row"><span class="ec-lbl">Country</span>
            <input type="text" class="finput" value="{{ $regCountryEdit !== '' ? $regCountryEdit : '—' }}" readonly style="background:#f0efec;color:var(--muted);cursor:not-allowed;border-style:dashed">
        </div>
        <div class="ec-row"><span class="ec-lbl">City</span>
            <select name="city" class="finput" required>
                <option value="">-- Select city --</option>
                @foreach($cityListEdit as $cityName)
                    <option value="{{ $cityName }}" @selected($eq($sv('city'), $cityName))>{{ $cityName }}</option>
                @endforeach
            </select>
        </div>
        <div class="ec-row"><span class="ec-lbl">Nationality</span>
            <input type="text" name="nationality" value="{{ $f('nationality') }}">
        </div>
        <div class="ec-row"><span class="ec-lbl">Grew Up</span>
            <input type="text" name="grew_up" value="{{ $f('grew_up') }}">
        </div>
        <div class="ec-row"><span class="ec-lbl">Ethnicity</span>
            <input type="text" name="ethnicity" value="{{ $f('ethnicity') }}" placeholder="Comma separated">
        </div>
        <div class="ec-row"><span class="ec-lbl">Height (cm)</span>
            <input type="number" name="height_cm" value="{{ $f('height_cm') }}" min="100" max="250">
        </div>
        <div class="ec-row"><span class="ec-lbl">Marital Status</span>
            <select name="marital_status">
                <option value="">-- Select --</option>
                @foreach(['Never Married','Divorced','Widowed','Separated','Married (polygamy)'] as $m)
                    <option value="{{ $m }}" @selected($eq($maritalForEdit, $m))>{{ $m }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Lifestyle --}}
    <div class="edit-card2">
        <div class="ec-head">🌿 Lifestyle</div>
        <div class="ec-row"><span class="ec-lbl">Smoking</span>
            <select name="smoking">
                <option value="">-- Select --</option>
                @foreach(['Never','Occasionally','Yes, regularly','Prefer not to say'] as $s)
                    <option value="{{ $s }}" @selected($eq($sv('smoking'), $s))>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="ec-row"><span class="ec-lbl">Drinking</span>
            <select name="drinking">
                <option value="">-- Select --</option>
                @foreach(['Never','Occasionally','Yes, regularly','Prefer not to say'] as $d)
                    <option value="{{ $d }}" @selected($eq($sv('drinking'), $d))>{{ $d }}</option>
                @endforeach
            </select>
        </div>
        <div class="ec-row"><span class="ec-lbl">Want Children</span>
            <select name="want_children">
                <option value="">-- Select --</option>
                @foreach(['Yes','No','Open to it','Have children already'] as $c)
                    <option value="{{ $c }}" @selected($eq($sv('want_children'), $c))>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div class="ec-row"><span class="ec-lbl">How many</span>
            <select name="num_children">
                <option value="">-- Select --</option>
                @foreach(['1','2','3','4+','Prefer not to say'] as $n)
                    <option value="{{ $n }}" @selected($eq($sv('num_children'), $n))>{{ $n }}</option>
                @endforeach
            </select>
        </div>
        <div class="ec-row"><span class="ec-lbl">Languages</span>
            <input type="text" name="languages" value="{{ $f('languages') }}" placeholder="e.g. English, Urdu, Arabic">
        </div>
    </div>

    {{-- Career --}}
    <div class="edit-card2">
        <div class="ec-head">💼 Career & Education</div>
        <div class="ec-row"><span class="ec-lbl">Profession</span>
            <input type="text" name="profession" value="{{ $f('profession') }}">
        </div>
        <div class="ec-row"><span class="ec-lbl">Education</span>
            <select name="education">
                <option value="">-- Select --</option>
                @foreach(['High School','Diploma / Vocational',"Bachelor's Degree","Master's Degree",'PhD / Doctorate','Islamic Education','Other'] as $e)
                    <option value="{{ $e }}" @selected($eq($sv('education'), $e))>{{ $e }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Marriage --}}
    <div class="edit-card2">
        <div class="ec-head">💍 Marriage</div>
        <div class="ec-row"><span class="ec-lbl">Intentions</span>
            <select name="marriage_intentions">
                <option value="">-- Select --</option>
                @foreach(['Seriously looking','Open to options','Not sure yet','Within 1 year','Within 2-3 years'] as $i)
                    <option value="{{ $i }}" @selected($eq($sv('marriage_intentions'), $i))>{{ $i }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Bio --}}
    <div class="edit-card2">
        <div class="ec-head">📝 Bio</div>
        <div class="ec-textarea">
            <textarea name="bio" id="bioEd" maxlength="300" placeholder="Tell potential matches about yourself...">{{ $f('bio') }}</textarea>
            <div style="text-align:right;font-size:12px;color:var(--muted);margin-top:4px"><span id="bioEdCnt">{{ strlen($profile->bio??'') }}</span>/300</div>
        </div>
    </div>

    {{-- Interests + Personality --}}
    <div class="edit-card2">
        <div class="ec-head">⭐ Interests & Personality</div>
        <div class="ec-row"><span class="ec-lbl">Interests</span>
            <input type="text" name="interests" value="{{ $f('interests') }}" placeholder="Comma separated">
        </div>
        <div class="ec-row"><span class="ec-lbl">Personality</span>
            <input type="text" name="personality" value="{{ $f('personality') }}" placeholder="e.g. Caring, Ambitious">
        </div>
    </div>

    {{-- Settings --}}
    <div class="edit-card2">
        <div class="ec-head">⚙️ Settings</div>
        <div class="ec-toggle">
            <div><div class="ec-toggle-lbl">🔔 Notifications</div><div class="ec-toggle-sub">Get notified about new matches</div></div>
            <label class="sw"><input type="checkbox" name="notifications_enabled" value="1" {{ old('notifications_enabled', $profile->notifications_enabled) ? 'checked' : '' }}><span class="sw-slider"></span></label>
        </div>
        <div class="ec-toggle">
            <div><div class="ec-toggle-lbl">🔒 Hide from Contacts</div><div class="ec-toggle-sub">Your profile won't appear to contacts</div></div>
            <label class="sw"><input type="checkbox" name="hide_from_contacts" value="1" {{ old('hide_from_contacts', $profile->hide_from_contacts) ? 'checked' : '' }}><span class="sw-slider"></span></label>
        </div>
    </div>

    <div class="edit-action-bar">
        <a href="{{ route('user.profile') }}" class="btn-cancel2">← Cancel</a>
        <button type="submit" class="btn-save2">💾 Save Changes</button>
    </div>

    </form>
</div>
</div>
</div>
</div>

@endif

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;
function toast(msg){ const t=document.getElementById('fToast'); t.textContent=msg; t.classList.add('show'); clearTimeout(t._t); t._t=setTimeout(()=>t.classList.remove('show'),2400); }

const slides = [...document.querySelectorAll('.form-slide')];
if(slides.length){
    const TOTAL=slides.length-1, SKIP=[14];
    let cur=0, selEth=[], selLang=[], selInt=[], selPer=[];
    const pFill=document.getElementById('pFill'),stepLb=document.getElementById('stepLbl'),
          nextB=document.getElementById('nextBtn'),prevB=document.getElementById('prevBtn'),
          skipB=document.getElementById('skipBtn'),fNav=document.getElementById('fNav');

    function showSlide(i){
        slides.forEach((s,idx)=>s.classList.toggle('active',idx===i));
        const isDone=i===TOTAL;
        pFill.style.width=((i/TOTAL)*100)+'%';
        fNav.style.display=isDone?'none':'flex';
        skipB.style.display=(SKIP.includes(i)&&!isDone)?'block':'none';
        prevB.style.display=i===0?'none':'inline-block';
        stepLb.textContent=isDone?'Complete 🎉':`Step ${i+1} of ${TOTAL}`;
        cur=i;
    }

    function validate(i){
        if(SKIP.includes(i)) return true;
        const checks={
            1:()=>{ const d=document.getElementById('dobDay')?.value,m=document.getElementById('dobMonth')?.value,y=document.getElementById('dobYear')?.value; if(!d||!m||!y){toast('⚠️ Please select your date of birth');return false;} const age=new Date().getFullYear()-parseInt(y); if(age<18){toast('⚠️ You must be 18+');return false;} document.getElementById('dobFinal').value=`${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`; return true; },
            2:()=>{ const c=document.getElementById('profileCitySelect'); if(!c?.value.trim()){toast('⚠️ Please select your city');return false;} return true; },
            3:()=>{ if(!document.getElementById('sectH').value){toast('⚠️ Please select your sect');return false;} return true; },
            4:()=>{ if(!document.querySelector('[name="profession"]')?.value.trim()){toast('⚠️ Please enter your profession');return false;} return true; },
            5:()=>{ if(!document.getElementById('eduH').value){toast('⚠️ Please select your education');return false;} return true; },
            6:()=>{ if(!document.querySelector('[name="nationality"]')?.value.trim()){toast('⚠️ Please enter nationality');return false;} return true; },
            7:()=>{ if(!selEth.length){toast('⚠️ Select at least one ethnicity');return false;} return true; },
            9:()=>{ if(!document.getElementById('marH').value){toast('⚠️ Please select marital status');return false;} return true; },
            10:()=>{ if(!document.getElementById('intentH').value){toast('⚠️ Please select marriage intentions');return false;} return true; },
            11:()=>{ if(!document.getElementById('childH').value){toast('⚠️ Please select children preference');return false;} return true; },
            12:()=>{
                if(!document.getElementById('smokeH').value){toast('⚠️ Please select smoking preference');return false;}
                if(!document.getElementById('drinkH').value){toast('⚠️ Please select drinking preference');return false;}
                return true;
            },
            13:()=>{ if(!selLang.length){toast('⚠️ Select at least one language');return false;} return true; },
            15:()=>{ if(!selInt.length){toast('⚠️ Select at least one interest');return false;} return true; },
        };
        return checks[i]?checks[i]():true;
    }

    nextB.onclick=()=>{ if(validate(cur)&&cur<TOTAL) showSlide(cur+1); };
    prevB.onclick=()=>{ if(cur>0) showSlide(cur-1); };
    skipB.onclick=()=>{ if(cur<TOTAL) showSlide(cur+1); };
    document.getElementById('backBtn').onclick=()=>{ if(cur>0) showSlide(cur-1); };
    showSlide(0);

    ['dobDay','dobMonth','dobYear'].forEach(id=>{ document.getElementById(id)?.addEventListener('change',()=>{ const d=document.getElementById('dobDay').value,m=document.getElementById('dobMonth').value,y=document.getElementById('dobYear').value; if(d&&m&&y){ const age=new Date().getFullYear()-parseInt(y); document.getElementById('agePreview').textContent=`Age: ${age} years`; } }); });

    function bindSingle(gId,hId){ document.querySelectorAll('#'+gId+' .pill2').forEach(p=>{ p.addEventListener('click',()=>{ document.querySelectorAll('#'+gId+' .pill2').forEach(x=>x.classList.remove('sel')); p.classList.add('sel'); document.getElementById(hId).value=p.dataset.val; }); }); }
    bindSingle('sectGrp','sectH'); bindSingle('eduGrp','eduH'); bindSingle('marGrp','marH');
    bindSingle('intentGrp','intentH'); bindSingle('childGrp','childH'); bindSingle('numChildGrp','numChildH');
    bindSingle('smokeGrp','smokeH'); bindSingle('drinkGrp','drinkH'); bindSingle('relGrp','relH');

    document.querySelectorAll('#ethGrp .pill2').forEach(p=>{ p.addEventListener('click',()=>{ const v=p.dataset.val; if(p.classList.contains('sel')){p.classList.remove('sel');selEth=selEth.filter(x=>x!==v);}else{p.classList.add('sel');selEth.push(v);} document.getElementById('ethH').value=selEth.join(','); }); });
    document.querySelectorAll('#langGrp .pill2').forEach(p=>{ p.addEventListener('click',()=>{ const v=p.dataset.val; if(p.classList.contains('sel')){p.classList.remove('sel');selLang=selLang.filter(x=>x!==v);}else{p.classList.add('sel');selLang.push(v);} document.getElementById('langH').value=selLang.join(','); }); });
    document.querySelectorAll('.interest-p').forEach(p=>{ p.addEventListener('click',()=>{ const v=p.dataset.val; if(p.classList.contains('sel')){p.classList.remove('sel');selInt=selInt.filter(x=>x!==v);}else{if(selInt.length>=15){toast('⚠️ Max 15 interests');return;}p.classList.add('sel');selInt.push(v);} document.getElementById('intH').value=selInt.join(','); document.getElementById('intCount').textContent=selInt.length; }); });
    document.querySelectorAll('.per-p').forEach(p=>{ p.addEventListener('click',()=>{ const v=p.dataset.val; if(p.classList.contains('sel')){p.classList.remove('sel');selPer=selPer.filter(x=>x!==v);}else{if(selPer.length>=5){toast('⚠️ Max 5 traits');return;}p.classList.add('sel');selPer.push(v);} document.getElementById('perH').value=selPer.join(','); document.getElementById('perCount').textContent=selPer.length; }); });
    document.querySelectorAll('.fchip[data-target]').forEach(c=>{ c.addEventListener('click',()=>{ const inp=document.getElementById(c.dataset.target); if(inp) inp.value=c.textContent.trim(); }); });

    const hSlider=document.getElementById('hSlider');
    function cmToFt(cm){ const t=cm/2.54; return Math.floor(t/12)+"'"+Math.round(t%12)+'"'; }
    function setHeight(cm){ document.getElementById('hNum').textContent=cmToFt(cm); document.getElementById('hCm').textContent=cm+' cm'; document.getElementById('hInp').value=cm; hSlider.value=cm; }
    hSlider?.addEventListener('input',()=>setHeight(parseInt(hSlider.value)));
    document.querySelectorAll('.hchip').forEach(c=>c.addEventListener('click',()=>setHeight(parseInt(c.dataset.cm))));
    setHeight(168);

    const bioInp=document.getElementById('bioInp');
    bioInp?.addEventListener('input',()=>{ if(bioInp.value.length>300)bioInp.value=bioInp.value.slice(0,300); document.getElementById('bioCount').textContent=bioInp.value.length; });
    document.querySelectorAll('.bio-s').forEach(c=>c.addEventListener('click',()=>{ if(bioInp&&!bioInp.value.includes(c.dataset.text)){bioInp.value+=(bioInp.value?' ':'')+c.dataset.text; document.getElementById('bioCount').textContent=bioInp.value.length; bioInp.focus();} }));

    const photoData=[];
    function buildPhotoGrid(){ const grid=document.getElementById('photoGrid'); if(!grid) return; grid.innerHTML=''; for(let i=0;i<6;i++){ const slot=document.createElement('div'); slot.className='photo-slot'+(photoData[i]?' has-photo':''); if(photoData[i]){ slot.innerHTML=`<img src="${photoData[i].url}">${i===0?'<div class="main-badge">⭐ Main</div>':''}<button type="button" class="del-btn" onclick="removeFormPhoto(${i})">✕</button>`; }else{ slot.innerHTML=`<div class="plus-icon">+</div><div class="slot-label">${i===0?'Main photo':'Photo '+(i+1)}</div>`; slot.onclick=()=>document.getElementById('photoFileInp').click(); } grid.appendChild(slot); } }
    const fi=document.createElement('input'); fi.type='file'; fi.id='photoFileInp'; fi.accept='image/*'; fi.style.display='none';
    fi.onchange=()=>{ if(!fi.files[0]) return; const url=URL.createObjectURL(fi.files[0]); photoData.push({url,file:fi.files[0]}); buildPhotoGrid(); fi.value=''; };
    document.body.appendChild(fi);
    window.removeFormPhoto=(i)=>{ photoData.splice(i,1); buildPhotoGrid(); };
    buildPhotoGrid();
}

const bioEd=document.getElementById('bioEd'),bioEdCnt=document.getElementById('bioEdCnt');
if(bioEd){ bioEd.addEventListener('input',()=>{ bioEdCnt.textContent=bioEd.value.length; }); }

function parseJsonResponse(text){
    try { return JSON.parse(text); } catch (e) { return null; }
}
function apiHeaders(json){
    const h={'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':CSRF||''};
    if(json) h['Content-Type']='application/json';
    return h;
}
async function uploadPhoto(input){
    const file=input.files[0];
    if(!file) return;
    const fd=new FormData();
    fd.append('photo',file);
    if(CSRF) fd.append('_token', CSRF);
    try{
        const r=await fetch('/user/profile/photo/upload',{
            method:'POST',
            headers:apiHeaders(false),
            body:fd,
            credentials:'same-origin',
        });
        const text=await r.text();
        const d=parseJsonResponse(text);
        if(!d){
            toast('❌ Upload failed — check console or try refreshing the page.');
            console.error('Photo upload non-JSON response:', text.slice(0,400));
            return;
        }
        if(d.errors){
            const msg=Object.values(d.errors).flat().join(' ')||d.message||'Invalid file';
            toast('❌ '+msg);
            return;
        }
        if(!r.ok||d.error){ toast('❌ '+(d.error||d.message||'Upload failed')); return; }
        toast('✅ Photo uploaded!');
        location.reload();
    }catch(e){
        toast('❌ Network error');
        console.error(e);
    }finally{
        input.value='';
    }
}
async function delPhoto(id,btn){
    if(!confirm('Delete this photo?')) return;
    const r=await fetch('/user/profile/photo',{
        method:'DELETE',
        headers:apiHeaders(true),
        body:JSON.stringify({photo_id:id}),
        credentials:'same-origin',
    });
    const d=parseJsonResponse(await r.text())||{};
    if(d.success){ toast('Photo deleted'); location.reload(); }
    else{ toast('❌ '+(d.error||d.message||'Could not delete')); }
}
async function setMain(id,btn){
    const r=await fetch('/user/profile/photo/main',{
        method:'POST',
        headers:apiHeaders(true),
        body:JSON.stringify({photo_id:id}),
        credentials:'same-origin',
    });
    const d=parseJsonResponse(await r.text())||{};
    if(d.success){ toast('⭐ Main photo set!'); location.reload(); }
    else{ toast('❌ '+(d.error||d.message||'Could not update')); }
}
async function toggleBlur(id,isBlurred){
    const isPremium = {{ Auth::user()->isPremium() ? 'true' : 'false' }};
    if(!isPremium){
        if(confirm('Blur option is only for premium users.\nDo you want to upgrade now?')){
            window.location.href='/user/premium/plans';
        }
        return;
    }
    const r=await fetch('/user/profile/photo/blur',{
        method:'POST',
        headers:apiHeaders(true),
        body:JSON.stringify({photo_id:id}),
        credentials:'same-origin',
    });
    const d=parseJsonResponse(await r.text())||{};
    if(!r.ok){
        toast('❌ '+(d.error||'Unable to update blur setting'));
        if(d.upgrade_url){ window.location.href=d.upgrade_url.charAt(0)==='/'?d.upgrade_url:'/user/premium/plans'; }
        return;
    }
    toast(d.is_blurred ? 'Photo blurred' : 'Photo unblurred');
    location.reload();
}
</script>

@endsection