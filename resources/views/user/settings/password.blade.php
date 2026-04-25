@extends('layouts.user')
@section('usercontent')
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

.pw-page {
    background: var(--soft);
    min-height: calc(100vh - 80px);
    padding: 24px 16px 60px;
}
.pw-wrap { max-width: 560px; margin: 0 auto; }

.pw-title {
    font-family: 'Playfair Display', serif;
    font-size: 24px; font-weight: 900;
    color: var(--ink); margin-bottom: 4px;
}
.pw-sub { font-size: 13.5px; color: var(--muted); margin-bottom: 24px; }

.pw-card {
    background: var(--white);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
}
.pw-card-head {
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px;
    color: var(--muted);
    display: flex; align-items: center; gap: 7px;
}
.pw-card-body { padding: 24px; }

.pw-field { margin-bottom: 18px; }
.pw-label {
    display: block;
    font-size: 13px; font-weight: 600;
    color: var(--ink); margin-bottom: 7px;
}
.pw-input-wrap { position: relative; }
.pw-input {
    width: 100%; padding: 12px 44px 12px 14px;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    font-size: 14px; font-family: 'DM Sans', sans-serif;
    color: var(--ink); background: #fafaf9;
    outline: none; transition: border .2s, box-shadow .2s;
}
.pw-input:focus {
    border-color: var(--purple);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
.pw-eye {
    position: absolute; right: 14px; top: 50%;
    transform: translateY(-50%);
    cursor: pointer; color: var(--muted);
    font-size: 18px; display: flex;
}

/* Strength bar */
.pw-strength { margin-top: 8px; }
.pw-strength-bar {
    height: 4px; border-radius: 10px;
    background: var(--border); overflow: hidden;
    margin-bottom: 4px;
}
.pw-strength-fill {
    height: 100%; border-radius: 10px;
    width: 0%; transition: width .3s, background .3s;
}
.pw-strength-text { font-size: 11px; color: var(--muted); }

.pw-hint {
    font-size: 12px; color: var(--muted);
    margin-top: 5px; display: flex;
    align-items: center; gap: 5px;
}

/* Submit button */
.pw-btn {
    width: 100%; padding: 14px;
    background: var(--ink); color: white;
    border: none; border-radius: 12px;
    font-size: 14px; font-weight: 700;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    transition: background .2s; margin-top: 6px;
}
.pw-btn:hover { background: #2a2a35; }

/* Alerts */
.alert-success {
    background: #e8fdf0; color: #1a6c3e;
    padding: 12px 16px; border-radius: 10px;
    font-size: 13px; font-weight: 600;
    border-left: 4px solid #1a6c3e;
    margin-bottom: 20px;
}
.alert-error {
    background: #fde8e8; color: #bf1a1a;
    padding: 12px 16px; border-radius: 10px;
    font-size: 13px; font-weight: 600;
    border-left: 4px solid #bf1a1a;
    margin-bottom: 20px;
}

/* Tips card */
.pw-tips {
    background: #f5f3ff;
    border-radius: 14px;
    padding: 16px 20px;
    margin-top: 16px;
}
.pw-tips-title {
    font-size: 12px; font-weight: 700;
    color: var(--purple); text-transform: uppercase;
    letter-spacing: .6px; margin-bottom: 10px;
}
.pw-tip {
    font-size: 13px; color: #555;
    padding: 4px 0;
    display: flex; align-items: center; gap: 8px;
}
</style>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="pw-page">
<div class="pw-wrap">

    <div class="pw-title">🔐 Change Password</div>
    <div class="pw-sub">Keep your account secure with a strong password.</div>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">❌ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">❌ {{ $errors->first() }}</div>
    @endif

    <div class="pw-card">
        <div class="pw-card-head">
            <ion-icon name="key-outline"></ion-icon>
            Update Password
        </div>
        <div class="pw-card-body">
            <form method="POST" action="{{ route('user.settings.password.update') }}">
                @csrf @method('PUT')

                {{-- Current Password --}}
                <div class="pw-field">
                    <label class="pw-label">Current Password</label>
                    <div class="pw-input-wrap">
                        <input type="password" name="current_password"
                            class="pw-input" placeholder="Enter current password"
                            id="currentPw" required>
                        <span class="pw-eye" onclick="togglePw('currentPw', this)">
                            <ion-icon name="eye-outline"></ion-icon>
                        </span>
                    </div>
                </div>

                {{-- New Password --}}
                <div class="pw-field">
                    <label class="pw-label">New Password</label>
                    <div class="pw-input-wrap">
                        <input type="password" name="password"
                            class="pw-input" placeholder="Enter new password"
                            id="newPw" oninput="checkStrength(this.value)" required>
                        <span class="pw-eye" onclick="togglePw('newPw', this)">
                            <ion-icon name="eye-outline"></ion-icon>
                        </span>
                    </div>
                    <div class="pw-strength">
                        <div class="pw-strength-bar">
                            <div class="pw-strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="pw-strength-text" id="strengthText"></div>
                    </div>
                    <div class="pw-hint">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        Minimum 6 characters
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="pw-field">
                    <label class="pw-label">Confirm New Password</label>
                    <div class="pw-input-wrap">
                        <input type="password" name="password_confirmation"
                            class="pw-input" placeholder="Confirm new password"
                            id="confirmPw" required>
                        <span class="pw-eye" onclick="togglePw('confirmPw', this)">
                            <ion-icon name="eye-outline"></ion-icon>
                        </span>
                    </div>
                </div>

                <button type="submit" class="pw-btn">🔐 Update Password</button>

            </form>

            {{-- Tips --}}
            <div class="pw-tips">
                <div class="pw-tips-title">💡 Password Tips</div>
                <div class="pw-tip">✅ Use at least 8 characters</div>
                <div class="pw-tip">✅ Mix uppercase & lowercase letters</div>
                <div class="pw-tip">✅ Include numbers and symbols</div>
                <div class="pw-tip">❌ Don't use your name or birthdate</div>
                <div class="pw-tip">❌ Don't reuse old passwords</div>
            </div>

        </div>
    </div>

</div>
</div>
</div>
</div>

<script>
// Toggle password visibility
function togglePw(id, btn) {
    const inp = document.getElementById(id);
    const icon = btn.querySelector('ion-icon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.setAttribute('name', 'eye-off-outline');
    } else {
        inp.type = 'password';
        icon.setAttribute('name', 'eye-outline');
    }
}

// Password strength checker
function checkStrength(val) {
    const fill = document.getElementById('strengthFill');
    const text = document.getElementById('strengthText');
    let score = 0;

    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { w: '0%',   color: '#e0e0e0', label: '' },
        { w: '25%',  color: '#e74c3c', label: '😟 Weak' },
        { w: '50%',  color: '#f39c12', label: '😐 Fair' },
        { w: '75%',  color: '#3498db', label: '😊 Good' },
        { w: '90%',  color: '#27ae60', label: '💪 Strong' },
        { w: '100%', color: '#1abc9c', label: '🔒 Very Strong' },
    ];

    const level = levels[Math.min(score, 5)];
    fill.style.width = level.w;
    fill.style.background = level.color;
    text.textContent = level.label;
    text.style.color = level.color;
}
</script>

@endsection