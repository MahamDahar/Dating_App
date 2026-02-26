@extends('layouts.user')
@section('usercontent')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .form-wrapper {
            font-family: 'Poppins', sans-serif;
            background: #f4f6fb;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 30px 16px;
        }

        .form-card {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
            overflow: hidden;
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 24px 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        .back-arrow {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #333;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background .2s;
        }

        .back-arrow:hover {
            background: #f5f5f5;
        }

        .progress-bar-wrap {
            flex: 1;
            height: 6px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #111;
            border-radius: 10px;
            transition: width 0.4s ease;
        }

        .step-text {
            font-size: 12px;
            color: #888;
            white-space: nowrap;
            font-weight: 500;
        }

        .form-body {
            padding: 28px 24px 20px;
        }

        .form-slide {
            display: none;
            animation: fadeSlide 0.35s ease;
        }

        .form-slide.active {
            display: block;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-title {
            font-size: 20px;
            font-weight: 700;
            color: #111;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .slide-sub {
            font-size: 13px;
            color: #888;
            margin-bottom: 22px;
            line-height: 1.5;
        }

        .pill-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pill {
            padding: 10px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            color: #333;
            user-select: none;
        }

        .pill:hover {
            border-color: #111;
            background: #f9f9f9;
        }

        .pill.selected {
            background: #111;
            color: #fff;
            border-color: #111;
        }

        .suggestion-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .chip {
            padding: 7px 13px;
            background: #f0f0f0;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            color: #333;
            font-weight: 500;
        }

        .chip:hover {
            background: #e0e0e0;
        }

        .form-input {
            width: 100%;
            padding: 13px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: border .2s;
            resize: none;
        }

        .form-input:focus {
            border-color: #111;
        }

        .notif-card {
            background: #f8f8f8;
            border-radius: 14px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }

        .notif-icon {
            font-size: 30px;
        }

        .notif-text {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .notif-text strong {
            font-size: 14px;
            color: #111;
        }

        .notif-text small {
            font-size: 12px;
            color: #888;
        }

        .toggle-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            border-top: 1px solid #f0f0f0;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            inset: 0;
            background: #ccc;
            border-radius: 26px;
            cursor: pointer;
            transition: 0.3s;
        }

        .toggle-slider:before {
            content: "";
            position: absolute;
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
        }

        .toggle-switch input:checked+.toggle-slider {
            background: #111;
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(24px);
        }

        .height-display {
            text-align: center;
            margin-bottom: 16px;
        }

        .height-display span {
            font-size: 40px;
            font-weight: 700;
            color: #111;
        }

        .height-display small {
            display: block;
            color: #888;
            font-size: 13px;
            margin-top: 4px;
        }

        .height-slider {
            width: 100%;
            -webkit-appearance: none;
            height: 6px;
            background: #e0e0e0;
            border-radius: 10px;
            outline: none;
            margin-bottom: 6px;
        }

        .height-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #111;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .height-labels {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #aaa;
            margin-bottom: 10px;
        }

        .interest-section {
            margin-bottom: 18px;
        }

        .section-label {
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 2px solid #f0f0f0;
        }

        .selected-count {
            font-size: 12px;
            color: #888;
            margin-top: 10px;
            font-weight: 500;
        }

        .char-count {
            text-align: right;
            font-size: 12px;
            color: #aaa;
            margin-top: 6px;
        }

        .nav-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px 20px;
            gap: 12px;
            border-top: 1px solid #f5f5f5;
        }

        .btn-prev {
            padding: 13px 20px;
            background: #f5f5f5;
            color: #333;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: background .2s;
        }

        .btn-prev:hover {
            background: #e8e8e8;
        }

        .btn-next {
            flex: 1;
            padding: 13px 20px;
            background: #111;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all .2s;
        }

        .btn-next:hover {
            background: #333;
        }

        .skip-row {
            text-align: center;
            padding: 0 24px 16px;
        }

        .btn-skip {
            background: none;
            border: none;
            color: #aaa;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .done-slide {
            text-align: center;
            padding: 20px 0;
        }

        .done-icon {
            font-size: 65px;
            margin-bottom: 16px;
            animation: bounce 1s ease infinite alternate;
        }

        @keyframes bounce {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-8px);
            }
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: #111;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            margin-top: 16px;
            transition: all .2s;
        }

        .submit-btn:hover {
            background: #333;
        }

        .alert-success {
            background: #e8fdf0;
            color: #1a8c4e;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 13px;
            font-weight: 600;
        }

        .alert-error {
            background: #fde8e8;
            color: #bf1a1a;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 13px;
            font-weight: 600;
        }

        .mt-2 {
            margin-top: 10px;
        }
    </style>
<div class="page-content-wrapper">
    <div class="form-wrapper">
        <div style="width:100%;max-width:520px">

            @if (session('success'))
                <div class="alert-success">✅ {{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert-error">❌ {{ $errors->first() }}</div>
            @endif

            <div class="form-card">

                <div class="form-header">
                    <button type="button" id="backArrow" class="back-arrow">&#8592;</button>
                    <div class="progress-bar-wrap">
                        <div id="progressBar" class="progress-fill" style="width:0%"></div>
                    </div>
                    <span class="step-text" id="stepText">Step 1 of 16</span>
                </div>

                <form id="multiStepForm" method="POST"
                    action="{{ $profile ? route('user.profile.update') : route('user.profile.store') }}">
                    @csrf
                    @if ($profile)
                        @method('PATCH')
                    @endif

                    <div class="form-body">

                        {{-- STEP 1: SECT --}}
                        <div class="form-slide active" data-step="1">
                            <h2 class="slide-title">What sect do you belong to?</h2>
                            <p class="slide-sub">This helps us find compatible matches for you.</p>
                            <div class="pill-group" id="sectGroup">
                                @foreach (['Sunni', 'Shia', 'Ahmadi', 'Nation of Islam', 'Ibadi', 'Just Muslim', 'Prefer not to say'] as $item)
                                    <span class="pill @if (old('sect', $profile->sect ?? '') == $item) selected @endif"
                                        data-value="{{ $item }}">{{ $item }}</span>
                                @endforeach
                            </div>
                            <input type="hidden" name="sect" id="sectInput"
                                value="{{ old('sect', $profile->sect ?? '') }}">
                        </div>

                        {{-- STEP 2: PROFESSION --}}
                        <div class="form-slide" data-step="2">
                            <h2 class="slide-title">What's your profession?</h2>
                            <p class="slide-sub">Select one or type your own.</p>
                            <input type="text" class="form-input" name="profession" id="professionInput"
                                placeholder="e.g. Doctor, Engineer..."
                                value="{{ old('profession', $profile->profession ?? '') }}">
                            <div class="suggestion-chips">
                                @foreach (['Doctor', 'Software Engineer', 'Teacher', 'Lawyer', 'Engineer', 'Accountant', 'Designer', 'Business Owner', 'Nurse', 'Architect', 'Student', 'Homemaker'] as $item)
                                    <span class="chip" data-target="professionInput">{{ $item }}</span>
                                @endforeach
                            </div>
                        </div>

                        {{-- STEP 3: EDUCATION --}}
                        <div class="form-slide" data-step="3">
                            <h2 class="slide-title">What's your education level?</h2>
                            <p class="slide-sub">Select your highest qualification.</p>
                            <div class="pill-group" id="educationGroup">
                                @foreach (['High School', 'Diploma / Vocational', "Bachelor's Degree", "Master's Degree", 'PhD / Doctorate', 'Islamic Education', 'Other'] as $item)
                                    <span class="pill @if (old('education', $profile->education ?? '') == $item) selected @endif"
                                        data-value="{{ $item }}">{{ $item }}</span>
                                @endforeach
                            </div>
                            <input type="hidden" name="education" id="educationInput"
                                value="{{ old('education', $profile->education ?? '') }}">
                        </div>

                        {{-- STEP 4: NOTIFICATIONS --}}
                        <div class="form-slide" data-step="4">
                            <h2 class="slide-title">Don't miss any updates!</h2>
                            <p class="slide-sub">Enable notifications from potential matches.</p>
                            <div class="notif-card">
                                <div class="notif-icon">🔔</div>
                                <div class="notif-text">
                                    <strong>Stay Connected</strong>
                                    <small>Get notified when someone likes or messages you.</small>
                                </div>
                            </div>
                            <div class="toggle-row">
                                <span>Enable Notifications</span>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="notifications_enabled" value="1"
                                        @if (old('notifications_enabled', $profile->notifications_enabled ?? false)) checked @endif>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>

                        {{-- STEP 5: PRIVACY --}}
                        <div class="form-slide" data-step="5">
                            <h2 class="slide-title">Privacy Settings</h2>
                            <p class="slide-sub">Don't want family and friends to see you on Muzz?</p>
                            <div class="notif-card">
                                <div class="notif-icon">🔒</div>
                                <div class="notif-text">
                                    <strong>Hide from Contacts</strong>
                                    <small>Your profile won't show to people in your contacts.</small>
                                </div>
                            </div>
                            <div class="toggle-row">
                                <span>Hide from Contacts</span>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="hide_from_contacts" value="1"
                                        @if (old('hide_from_contacts', $profile->hide_from_contacts ?? false)) checked @endif>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>

                        {{-- STEP 6: NATIONALITY --}}
                        <div class="form-slide" data-step="6">
                            <h2 class="slide-title">What's your nationality?</h2>
                            <p class="slide-sub">Select or type your own.</p>
                            <input type="text" class="form-input" name="nationality" id="nationalityInput"
                                placeholder="e.g. Pakistani, British..."
                                value="{{ old('nationality', $profile->nationality ?? '') }}">
                            <div class="suggestion-chips">
                                @foreach (['Pakistani', 'British', 'American', 'Indian', 'Bangladeshi', 'Saudi Arabian', 'Emirati', 'Turkish', 'Egyptian', 'Malaysian', 'Indonesian', 'Canadian'] as $item)
                                    <span class="chip" data-target="nationalityInput">{{ $item }}</span>
                                @endforeach
                            </div>
                        </div>

                        {{-- STEP 7: GREW UP --}}
                        <div class="form-slide" data-step="7">
                            <h2 class="slide-title">Where did you grow up?</h2>
                            <p class="slide-sub">Select a city or type your own.</p>
                            <input type="text" class="form-input" name="grew_up" id="grewUpInput"
                                placeholder="e.g. Karachi, London..."
                                value="{{ old('grew_up', $profile->grew_up ?? '') }}">
                            <div class="suggestion-chips">
                                @foreach (['Karachi', 'Lahore', 'Islamabad', 'London', 'New York', 'Birmingham', 'Dubai', 'Kuala Lumpur', 'Toronto', 'Istanbul'] as $item)
                                    <span class="chip" data-target="grewUpInput">{{ $item }}</span>
                                @endforeach
                            </div>
                        </div>

                        {{-- STEP 8: ETHNICITY --}}
                        <div class="form-slide" data-step="8">
                            <h2 class="slide-title">What's your ethnicity?</h2>
                            <p class="slide-sub">Select all that apply.</p>
                            <div class="pill-group" id="ethnicityGroup">
                                @foreach (['Arab', 'South Asian', 'African', 'Black British', 'White / Caucasian', 'East Asian', 'Southeast Asian', 'Mixed', 'Other', 'Prefer not to say'] as $item)
                                    <span class="pill @if (str_contains(old('ethnicity', $profile->ethnicity ?? ''), $item)) selected @endif"
                                        data-value="{{ $item }}">{{ $item }}</span>
                                @endforeach
                            </div>
                            <input type="hidden" name="ethnicity" id="ethnicityInput"
                                value="{{ old('ethnicity', $profile->ethnicity ?? '') }}">
                        </div>

                        {{-- STEP 9: HEIGHT --}}
                        <div class="form-slide" data-step="9">
                            <h2 class="slide-title">How tall are you?</h2>
                            <p class="slide-sub">Use the slider or quick select below.</p>
                            <div class="height-display">
                                <span id="heightValue">5'6"</span>
                                <small id="heightCm">168 cm</small>
                            </div>
                            <input type="range" id="heightSlider" min="140" max="220"
                                value="{{ old('height_cm', $profile->height_cm ?? 168) }}" class="height-slider">
                            <div class="height-labels"><span>4'7"</span><span>7'3"</span></div>
                            <div class="suggestion-chips">
                                @foreach ([160 => "5'3", 163 => "5'4", 165 => "5'5", 168 => "5'6", 170 => "5'7", 173 => "5'8", 175 => "5'9", 178 => "5'10", 180 => "5'11", 183 => "6'0"] as $cm => $ft)
                                    <span class="chip height-chip"
                                        data-cm="{{ $cm }}">{{ $ft }}"</span>
                                @endforeach
                            </div>
                            <input type="hidden" name="height_cm" id="heightInput"
                                value="{{ old('height_cm', $profile->height_cm ?? 168) }}">
                        </div>

                        {{-- STEP 10: MARITAL STATUS --}}
                        <div class="form-slide" data-step="10">
                            <h2 class="slide-title">What's your marital status?</h2>
                            <p class="slide-sub">Be honest for the best matches.</p>
                            <div class="pill-group" id="maritalGroup">
                                @foreach (['Never Married', 'Divorced', 'Widowed', 'Separated', 'Married (polygamy)'] as $item)
                                    <span class="pill @if (old('marital_status', $profile->marital_status ?? '') == $item) selected @endif"
                                        data-value="{{ $item }}">{{ $item }}</span>
                                @endforeach
                            </div>
                            <input type="hidden" name="marital_status" id="maritalInput"
                                value="{{ old('marital_status', $profile->marital_status ?? '') }}">
                        </div>

                        {{-- STEP 11: INTENTIONS --}}
                        <div class="form-slide" data-step="11">
                            <h2 class="slide-title">Intentions for marriage?</h2>
                            <p class="slide-sub">This helps set expectations with matches.</p>
                            <div class="pill-group" id="intentionsGroup">
                                @foreach (['Seriously looking', 'Open to options', 'Not sure yet', 'Within 1 year', 'Within 2-3 years'] as $item)
                                    <span class="pill @if (old('marriage_intentions', $profile->marriage_intentions ?? '') == $item) selected @endif"
                                        data-value="{{ $item }}">{{ $item }}</span>
                                @endforeach
                            </div>
                            <input type="hidden" name="marriage_intentions" id="intentionsInput"
                                value="{{ old('marriage_intentions', $profile->marriage_intentions ?? '') }}">
                        </div>

                        {{-- STEP 12: RELIGION PRACTICE --}}
                        <div class="form-slide" data-step="12">
                            <h2 class="slide-title">How do you practise your religion?</h2>
                            <p class="slide-sub">Select the option that best describes you.</p>
                            <div class="pill-group" id="religionGroup">
                                @foreach (['Practising', 'Moderately Practising', 'Not Practising', 'Revert / New Muslim'] as $item)
                                    <span class="pill @if (old('religion_practice', $profile->religion_practice ?? '') == $item) selected @endif"
                                        data-value="{{ $item }}">{{ $item }}</span>
                                @endforeach
                            </div>
                            <input type="hidden" name="religion_practice" id="religionInput"
                                value="{{ old('religion_practice', $profile->religion_practice ?? '') }}">
                        </div>

                        {{-- STEP 13: BORN MUSLIM --}}
                        <div class="form-slide" data-step="13">
                            <h2 class="slide-title">Were you born a Muslim?</h2>
                            <p class="slide-sub">Select the one that applies to you.</p>
                            <div class="pill-group" id="bornMuslimGroup">
                                @foreach (['Yes' => 'Yes, born Muslim', 'No' => 'No, I reverted', 'Prefer not to say' => 'Prefer not to say'] as $val => $label)
                                    <span class="pill @if (old('born_muslim', $profile->born_muslim ?? '') == $val) selected @endif"
                                        data-value="{{ $val }}">{{ $label }}</span>
                                @endforeach
                            </div>
                            <input type="hidden" name="born_muslim" id="bornMuslimInput"
                                value="{{ old('born_muslim', $profile->born_muslim ?? '') }}">
                        </div>

                        {{-- STEP 14: INTERESTS --}}
                        <div class="form-slide" data-step="14">
                            <h2 class="slide-title">What are your interests?</h2>
                            <p class="slide-sub">Select up to <strong>15 interests</strong> to stand out!</p>

                            @php $savedInterests = old('interests', $profile->interests ?? ''); @endphp

                            <div class="interest-section">
                                <div class="section-label">🎨 Arts & Culture</div>
                                <div class="pill-group">
                                    @foreach (['Acting', 'Anime', 'Art galleries', 'Board games', 'Creative writing', 'Design', 'DIY', 'Fashion', 'Film & Cinema', 'Photography', 'Painting', 'Music'] as $item)
                                        <span class="pill interest-pill @if (str_contains($savedInterests, $item)) selected @endif"
                                            data-value="{{ $item }}">{{ $item }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="interest-section">
                                <div class="section-label">⚽ Sports & Fitness</div>
                                <div class="pill-group">
                                    @foreach (['Football', 'Cricket', 'Basketball', 'Gym', 'Running', 'Swimming', 'Cycling', 'Hiking', 'Martial Arts', 'Tennis'] as $item)
                                        <span
                                            class="pill interest-pill @if (str_contains($savedInterests, $item)) selected @endif"
                                            data-value="{{ $item }}">{{ $item }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="interest-section">
                                <div class="section-label">🍽️ Food & Lifestyle</div>
                                <div class="pill-group">
                                    @foreach (['Cooking', 'Baking', 'Trying restaurants', 'Coffee', 'Travelling', 'Reading', 'Gardening', 'Volunteering'] as $item)
                                        <span
                                            class="pill interest-pill @if (str_contains($savedInterests, $item)) selected @endif"
                                            data-value="{{ $item }}">{{ $item }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="interest-section">
                                <div class="section-label">💻 Tech & Learning</div>
                                <div class="pill-group">
                                    @foreach (['Technology', 'Gaming', 'Podcasts', 'Languages', 'Science', 'History', 'Islamic Studies'] as $item)
                                        <span
                                            class="pill interest-pill @if (str_contains($savedInterests, $item)) selected @endif"
                                            data-value="{{ $item }}">{{ $item }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <p class="selected-count">Selected: <span id="interestCount">0</span>/15</p>
                            <input type="hidden" name="interests" id="interestsInput" value="{{ $savedInterests }}">
                        </div>

                        {{-- STEP 15: BIO --}}
                        <div class="form-slide" data-step="15">
                            <h2 class="slide-title">Add your bio</h2>
                            <p class="slide-sub">Tell potential matches about yourself (max 300 characters).</p>
                            <textarea class="form-input" name="bio" id="bioInput" rows="5"
                                placeholder="e.g. I'm a passionate engineer from Karachi...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                            <div class="char-count"><span
                                    id="bioCount">{{ strlen(old('bio', $profile->bio ?? '')) }}</span>/300</div>
                            <div class="suggestion-chips mt-2">
                                <span class="chip bio-starter" data-text="I work as a ">✍️ My Career</span>
                                <span class="chip bio-starter" data-text="In my free time, I enjoy ">✈️ My Hobbies</span>
                                <span class="chip bio-starter" data-text="Deen is very important to me. ">🕌 My
                                    Deen</span>
                                <span class="chip bio-starter" data-text="Family means everything to me. ">👨‍👩‍👧 Family
                                    Values</span>
                            </div>
                        </div>

                        {{-- STEP 16: PERSONALITY --}}
                        <div class="form-slide" data-step="16">
                            <h2 class="slide-title">Describe your personality</h2>
                            <p class="slide-sub">Select up to <strong>5 traits</strong> that describe you best.</p>
                            @php $savedPersonality = old('personality', $profile->personality ?? ''); @endphp
                            <div class="pill-group" id="personalityGroup">
                                @foreach (['Introvert', 'Extrovert', 'Ambivert', 'Ambitious', 'Caring', 'Funny', 'Calm', 'Creative', 'Adventurous', 'Family-oriented', 'Intellectual', 'Spiritual', 'Hardworking', 'Romantic', 'Empathetic'] as $item)
                                    <span class="pill personality-pill @if (str_contains($savedPersonality, $item)) selected @endif"
                                        data-value="{{ $item }}">{{ $item }}</span>
                                @endforeach
                            </div>
                            <p class="selected-count">Selected: <span id="personalityCount">0</span>/5</p>
                            <input type="hidden" name="personality" id="personalityInput"
                                value="{{ $savedPersonality }}">
                        </div>

                        {{-- COMPLETION --}}
                        <div class="form-slide done-slide" data-step="done">
                            <div class="done-icon">🎉</div>
                            <h2 class="slide-title">All Done!</h2>
                            <p class="slide-sub">Your profile is ready. Let's find your perfect match!</p>
                            <button type="submit" class="submit-btn">
                                {{ $profile ? '✅ Update Profile' : '✅ Save Profile' }}
                            </button>
                        </div>

                    </div>{{-- end form-body --}}

                    {{-- Navigation --}}
                    <div class="nav-row" id="navRow">
                        <button type="button" class="btn-prev" id="prevBtn" style="display:none">← Back</button>
                        <button type="button" class="btn-next" id="nextBtn">Continue →</button>
                    </div>
                    <div class="skip-row" id="skipRow">
                        <button type="button" class="btn-skip" id="skipBtn">Skip this step</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const slides = Array.from(document.querySelectorAll(".form-slide"));
            const nextBtn = document.getElementById("nextBtn");
            const prevBtn = document.getElementById("prevBtn");
            const skipBtn = document.getElementById("skipBtn");
            const backArrow = document.getElementById("backArrow");
            const progress = document.getElementById("progressBar");
            const stepText = document.getElementById("stepText");
            const navRow = document.getElementById("navRow");
            const skipRow = document.getElementById("skipRow");
            const TOTAL = slides.length - 1;
            let current = 0;

            // ── Show Slide ──────────────────────────────────
            function showSlide(i) {
                slides.forEach((s, idx) => s.classList.toggle("active", idx === i));
                progress.style.width = ((i / TOTAL) * 100) + "%";
                const isDone = i === TOTAL;
                navRow.style.display = isDone ? "none" : "flex";
                skipRow.style.display = isDone ? "none" : "block";
                prevBtn.style.display = i === 0 ? "none" : "inline-block";
                stepText.textContent = isDone ? "Complete 🎉" : `Step ${i + 1} of ${TOTAL}`;
            }

            nextBtn.addEventListener("click", () => {
                if (current < TOTAL) showSlide(++current);
            });
            prevBtn.addEventListener("click", () => {
                if (current > 0) showSlide(--current);
            });
            skipBtn.addEventListener("click", () => {
                if (current < TOTAL) showSlide(++current);
            });
            backArrow.addEventListener("click", () => {
                if (current > 0) showSlide(--current);
            });
            showSlide(0);

            // ── Single-select pills ──────────────────────────
            function bindSingle(groupId, inputId) {
                document.querySelectorAll("#" + groupId + " .pill").forEach(pill => {
                    pill.addEventListener("click", () => {
                        document.querySelectorAll("#" + groupId + " .pill").forEach(p => p.classList
                            .remove("selected"));
                        pill.classList.add("selected");
                        document.getElementById(inputId).value = pill.dataset.value;
                    });
                });
            }
            bindSingle("sectGroup", "sectInput");
            bindSingle("educationGroup", "educationInput");
            bindSingle("maritalGroup", "maritalInput");
            bindSingle("intentionsGroup", "intentionsInput");
            bindSingle("religionGroup", "religionInput");
            bindSingle("bornMuslimGroup", "bornMuslimInput");

            // ── Interests multi (max 15) ─────────────────────
            let selInterests = document.getElementById("interestsInput").value
                .split(",").map(s => s.trim()).filter(Boolean);

            document.querySelectorAll(".interest-pill").forEach(pill => {
                pill.addEventListener("click", () => {
                    const v = pill.dataset.value;
                    if (pill.classList.contains("selected")) {
                        pill.classList.remove("selected");
                        selInterests = selInterests.filter(x => x !== v);
                    } else {
                        if (selInterests.length >= 15) return;
                        pill.classList.add("selected");
                        selInterests.push(v);
                    }
                    document.getElementById("interestsInput").value = selInterests.join(",");
                    document.getElementById("interestCount").textContent = selInterests.length;
                });
            });
            document.getElementById("interestCount").textContent = selInterests.length;

            // ── Personality multi (max 5) ────────────────────
            let selPersonality = document.getElementById("personalityInput").value
                .split(",").map(s => s.trim()).filter(Boolean);

            document.querySelectorAll(".personality-pill").forEach(pill => {
                pill.addEventListener("click", () => {
                    const v = pill.dataset.value;
                    if (pill.classList.contains("selected")) {
                        pill.classList.remove("selected");
                        selPersonality = selPersonality.filter(x => x !== v);
                    } else {
                        if (selPersonality.length >= 5) return;
                        pill.classList.add("selected");
                        selPersonality.push(v);
                    }
                    document.getElementById("personalityInput").value = selPersonality.join(",");
                    document.getElementById("personalityCount").textContent = selPersonality.length;
                });
            });
            document.getElementById("personalityCount").textContent = selPersonality.length;

            // ── Ethnicity multi ──────────────────────────────
            let selEthnicity = document.getElementById("ethnicityInput").value
                .split(",").map(s => s.trim()).filter(Boolean);

            document.querySelectorAll("#ethnicityGroup .pill").forEach(pill => {
                pill.addEventListener("click", () => {
                    const v = pill.dataset.value;
                    if (pill.classList.contains("selected")) {
                        pill.classList.remove("selected");
                        selEthnicity = selEthnicity.filter(x => x !== v);
                    } else {
                        pill.classList.add("selected");
                        selEthnicity.push(v);
                    }
                    document.getElementById("ethnicityInput").value = selEthnicity.join(",");
                });
            });

            // ── Suggestion chips → text input ───────────────
            document.querySelectorAll(".chip[data-target]").forEach(chip => {
                chip.addEventListener("click", () => {
                    const input = document.getElementById(chip.dataset.target);
                    if (input) input.value = chip.textContent.trim();
                });
            });

            // ── Height Slider ────────────────────────────────
            const slider = document.getElementById("heightSlider");

            function cmToFt(cm) {
                const total = cm / 2.54;
                return Math.floor(total / 12) + "'" + Math.round(total % 12) + '"';
            }

            function updateHeight(cm) {
                document.getElementById("heightValue").textContent = cmToFt(cm);
                document.getElementById("heightCm").textContent = cm + " cm";
                document.getElementById("heightInput").value = cm;
                slider.value = cm;
            }
            slider.addEventListener("input", () => updateHeight(parseInt(slider.value)));
            document.querySelectorAll(".height-chip").forEach(chip => {
                chip.addEventListener("click", () => updateHeight(parseInt(chip.dataset.cm)));
            });
            updateHeight(parseInt(slider.value));

            // ── Bio char count ───────────────────────────────
            const bioInput = document.getElementById("bioInput");
            bioInput.addEventListener("input", () => {
                if (bioInput.value.length > 300) bioInput.value = bioInput.value.slice(0, 300);
                document.getElementById("bioCount").textContent = bioInput.value.length;
            });

            // ── Bio starters ─────────────────────────────────
            document.querySelectorAll(".bio-starter").forEach(chip => {
                chip.addEventListener("click", () => {
                    const text = chip.dataset.text;
                    if (!bioInput.value.includes(text)) {
                        bioInput.value += (bioInput.value ? " " : "") + text;
                        document.getElementById("bioCount").textContent = bioInput.value.length;
                        bioInput.focus();
                    }
                });
            });

        });
    </script>
@endsection
