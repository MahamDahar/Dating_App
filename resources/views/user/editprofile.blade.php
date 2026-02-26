@extends('layouts.user')
@section('usercontent')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

.edit-page {
    font-family: 'Poppins', sans-serif;
    background: #f4f6fb;
    min-height: 100vh;
    padding: 28px 16px 60px;
}

.edit-container {
    max-width: 680px;
    margin: 0 auto;
}

/* ── Page Title ── */
.page-title {
    font-size: 22px;
    font-weight: 700;
    color: #111;
    margin-bottom: 6px;
}
.page-sub {
    font-size: 13px;
    color: #888;
    margin-bottom: 24px;
}

/* ── Alerts ── */
.alert-success {
    background: #e8fdf0; color: #1a8c4e;
    padding: 12px 18px; border-radius: 10px;
    margin-bottom: 20px; font-size: 13px; font-weight: 600;
    border-left: 4px solid #1a8c4e;
}
.alert-error {
    background: #fde8e8; color: #bf1a1a;
    padding: 12px 18px; border-radius: 10px;
    margin-bottom: 20px; font-size: 13px; font-weight: 600;
    border-left: 4px solid #bf1a1a;
}

/* ── Info Card ── */
.info-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    margin-bottom: 16px;
    overflow: hidden;
}

.card-head {
    padding: 14px 20px;
    background: #f9f9f9;
    border-bottom: 1px solid #f0f0f0;
    font-size: 12px;
    font-weight: 700;
    color: #555;
    text-transform: uppercase;
    letter-spacing: .6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ── Info Row ── */
.info-row {
    display: flex;
    align-items: center;
    padding: 14px 20px;
    border-bottom: 1px solid #f5f5f5;
    gap: 16px;
}
.info-row:last-child { border-bottom: none; }

.lbl {
    color: #888;
    font-size: 13px;
    font-weight: 500;
    min-width: 160px;
    flex-shrink: 0;
}

/* ── Inputs ── */
.info-row input[type="text"],
.info-row input[type="number"] {
    flex: 1;
    padding: 9px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
    color: #111;
    outline: none;
    transition: border .2s, box-shadow .2s;
    background: #fafafa;
}
.info-row input[type="text"]:focus,
.info-row input[type="number"]:focus {
    border-color: #111;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(17,17,17,0.06);
}

/* ── Select ── */
.info-row select {
    flex: 1;
    padding: 9px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
    color: #111;
    outline: none;
    cursor: pointer;
    background: #fafafa;
    transition: border .2s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23888' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
}
.info-row select:focus { border-color: #111; background-color: #fff; }

/* ── Textarea ── */
.textarea-wrap { padding: 16px 20px; }
textarea.styled-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
    color: #111;
    outline: none;
    resize: vertical;
    min-height: 100px;
    transition: border .2s, box-shadow .2s;
    background: #fafafa;
}
textarea.styled-textarea:focus {
    border-color: #111;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(17,17,17,0.06);
}
.char-count { text-align: right; font-size: 11px; color: #aaa; margin-top: 5px; }

/* ── Toggle / Checkbox ── */
.toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid #f5f5f5;
}
.toggle-row:last-child { border-bottom: none; }
.toggle-lbl { font-size: 13px; color: #333; font-weight: 500; }
.toggle-sub  { font-size: 11px; color: #aaa; margin-top: 2px; }

.toggle-switch { position: relative; display: inline-block; width: 48px; height: 26px; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; inset: 0;
    background: #d1d5db; border-radius: 26px;
    cursor: pointer; transition: .3s;
}
.toggle-slider:before {
    content: "";
    position: absolute;
    height: 20px; width: 20px;
    left: 3px; bottom: 3px;
    background: white; border-radius: 50%; transition: .3s;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
}
.toggle-switch input:checked + .toggle-slider { background: #111; }
.toggle-switch input:checked + .toggle-slider:before { transform: translateX(22px); }

/* ── Action Buttons ── */
.action-bar {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    flex-wrap: wrap;
}
.btn-save {
    flex: 1;
    padding: 14px 24px;
    background: #111;
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    transition: all .2s;
    letter-spacing: .3px;
}
.btn-save:hover { background: #333; transform: translateY(-1px); }
.btn-save:active { transform: translateY(0); }

.btn-cancel {
    padding: 14px 22px;
    background: #f5f5f5;
    color: #555;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: background .2s;
}
.btn-cancel:hover { background: #e8e8e8; color: #333; }
</style>

<div class="edit-page">
<div class="edit-container">

    <div class="page-title">✏️ Edit Profile</div>
    <div class="page-sub">Update your information to get better matches.</div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">❌ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('user.profile.update') }}">
        @csrf
        @method('PATCH')

        {{-- ── RELIGION & IDENTITY ── --}}
        <div class="info-card">
            <div class="card-head">☪️ Religion & Identity</div>

            <div class="info-row">
                <span class="lbl">Sect</span>
                <select name="sect">
                    <option value="">-- Select --</option>
                    @foreach(['Sunni','Shia','Ahmadi','Nation of Islam','Ibadi','Just Muslim','Prefer not to say'] as $s)
                        <option value="{{ $s }}" @selected(old('sect', $profile->sect) == $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="info-row">
                <span class="lbl">Religion Practice</span>
                <select name="religion_practice">
                    <option value="">-- Select --</option>
                    @foreach(['Practising','Moderately Practising','Not Practising','Revert / New Muslim'] as $r)
                        <option value="{{ $r }}" @selected(old('religion_practice', $profile->religion_practice) == $r)>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <div class="info-row">
                <span class="lbl">Born Muslim</span>
                <select name="born_muslim">
                    <option value="">-- Select --</option>
                    @foreach(['Yes' => 'Yes, born Muslim', 'No' => 'No, I reverted', 'Prefer not to say' => 'Prefer not to say'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('born_muslim', $profile->born_muslim) == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ── PERSONAL INFO ── --}}
        <div class="info-card">
            <div class="card-head">👤 Personal Info</div>

            <div class="info-row">
                <span class="lbl">Nationality</span>
                <input type="text" name="nationality"
                       value="{{ old('nationality', $profile->nationality) }}"
                       placeholder="e.g. Pakistani, British...">
            </div>

            <div class="info-row">
                <span class="lbl">Grew Up</span>
                <input type="text" name="grew_up"
                       value="{{ old('grew_up', $profile->grew_up) }}"
                       placeholder="e.g. Karachi, London...">
            </div>

            <div class="info-row">
                <span class="lbl">Ethnicity</span>
                <input type="text" name="ethnicity"
                       value="{{ old('ethnicity', implode(', ', $profile->ethnicity_array ?? [])) }}"
                       placeholder="e.g. South Asian, Arab (comma separated)">
            </div>

            <div class="info-row">
                <span class="lbl">Height (cm)</span>
                <input type="number" name="height_cm"
                       value="{{ old('height_cm', $profile->height_cm) }}"
                       placeholder="e.g. 170" min="100" max="250">
            </div>

            <div class="info-row">
                <span class="lbl">Marital Status</span>
                <select name="marital_status">
                    <option value="">-- Select --</option>
                    @foreach(['Never Married','Divorced','Widowed','Separated','Married (polygamy)'] as $m)
                        <option value="{{ $m }}" @selected(old('marital_status', $profile->marital_status) == $m)>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ── CAREER & EDUCATION ── --}}
        <div class="info-card">
            <div class="card-head">💼 Career & Education</div>

            <div class="info-row">
                <span class="lbl">Profession</span>
                <input type="text" name="profession"
                       value="{{ old('profession', $profile->profession) }}"
                       placeholder="e.g. Doctor, Engineer...">
            </div>

            <div class="info-row">
                <span class="lbl">Education</span>
                <select name="education">
                    <option value="">-- Select --</option>
                    @foreach(["High School","Diploma / Vocational","Bachelor's Degree","Master's Degree","PhD / Doctorate","Islamic Education","Other"] as $e)
                        <option value="{{ $e }}" @selected(old('education', $profile->education) == $e)>{{ $e }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ── MARRIAGE ── --}}
        <div class="info-card">
            <div class="card-head">💍 Marriage Intentions</div>

            <div class="info-row">
                <span class="lbl">Intentions</span>
                <select name="marriage_intentions">
                    <option value="">-- Select --</option>
                    @foreach(['Seriously looking','Open to options','Not sure yet','Within 1 year','Within 2-3 years'] as $i)
                        <option value="{{ $i }}" @selected(old('marriage_intentions', $profile->marriage_intentions) == $i)>{{ $i }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ── INTERESTS & PERSONALITY ── --}}
        <div class="info-card">
            <div class="card-head">⭐ Interests & Personality</div>

            <div class="info-row">
                <span class="lbl">Interests</span>
                <input type="text" name="interests"
                       value="{{ old('interests', implode(', ', $profile->interests_array ?? [])) }}"
                       placeholder="Comma separated e.g. Cricket, Cooking...">
            </div>

            <div class="info-row">
                <span class="lbl">Personality</span>
                <input type="text" name="personality"
                       value="{{ old('personality', implode(', ', $profile->personality_array ?? [])) }}"
                       placeholder="e.g. Caring, Ambitious, Funny...">
            </div>
        </div>

        {{-- ── BIO ── --}}
        <div class="info-card">
            <div class="card-head">📝 Bio</div>
            <div class="textarea-wrap">
                <textarea name="bio" id="bioInput" class="styled-textarea"
                          placeholder="Tell potential matches about yourself...">{{ old('bio', $profile->bio) }}</textarea>
                <div class="char-count"><span id="bioCount">{{ strlen(old('bio', $profile->bio ?? '')) }}</span>/300</div>
            </div>
        </div>

        {{-- ── SETTINGS ── --}}
        <div class="info-card">
            <div class="card-head">⚙️ Settings</div>

            <div class="toggle-row">
                <div>
                    <div class="toggle-lbl">🔔 Enable Notifications</div>
                    <div class="toggle-sub">Get notified about new matches and messages</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="notifications_enabled" value="1"
                           {{ old('notifications_enabled', $profile->notifications_enabled) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="toggle-row">
                <div>
                    <div class="toggle-lbl">🔒 Hide from Contacts</div>
                    <div class="toggle-sub">Your profile won't appear to people in your contacts</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="hide_from_contacts" value="1"
                           {{ old('hide_from_contacts', $profile->hide_from_contacts) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        {{-- ── ACTIONS ── --}}
        <div class="action-bar">
            <a href="{{ route('user.userprofile') }}" class="btn-cancel">← Cancel</a>
            <button type="submit" class="btn-save">💾 Save Changes</button>
        </div>

    </form>
</div>
</div>

<script>
// Bio char count
const bio = document.getElementById('bioInput');
const cnt = document.getElementById('bioCount');
bio.addEventListener('input', () => {
    if (bio.value.length > 300) bio.value = bio.value.slice(0, 300);
    cnt.textContent = bio.value.length;
});
</script>

@endsection