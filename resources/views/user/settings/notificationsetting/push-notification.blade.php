@extends('layouts.user')

@section('usercontent')
    <div class="page-content-wrapper">
        <div class="profile-page">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Settings</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('user.discover') }}"><ion-icon name="home-outline"></ion-icon></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('user.settings.profile') }}">Settings</a>
                            </li>
                            <li class="breadcrumb-item active">Push Notifications</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-7">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4"
                            role="alert">
                            <ion-icon name="checkmark-circle-outline" style="font-size:18px;"></ion-icon>
                            <span>{{ session('success') }}</span>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">

                            {{-- Header --}}
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="notif-icon-wrap" style="background:linear-gradient(135deg,#7c3aed,#a855f7);">
                                    <ion-icon name="phone-portrait-outline"></ion-icon>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-semibold">Push Notifications</h5>
                                    <small class="text-muted">Manage alerts that appear on your device</small>
                                </div>
                            </div>

                            <hr class="mb-0">

                            <form action="{{ route('user.settings.notifications.push.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- New Match --}}
                                <div class="notif-row border-bottom">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="notif-icon-sm" style="background:#fdf4ff;">
                                            <ion-icon name="heart-outline" style="color:#7c3aed;"></ion-icon>
                                        </div>
                                        <div>
                                            <div class="notif-title">New Match</div>
                                            <div class="notif-desc">Get notified when someone matches with you</div>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input notify-toggle" type="checkbox" name="push_new_match"
                                            id="push_new_match" role="switch"
                                            {{ $settings->push_new_match ? 'checked' : '' }}>
                                    </div>
                                </div>

                                {{-- New Message --}}
                                <div class="notif-row border-bottom">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="notif-icon-sm" style="background:#f0fdf4;">
                                            <ion-icon name="chatbubble-outline" style="color:#16a34a;"></ion-icon>
                                        </div>
                                        <div>
                                            <div class="notif-title">New Message</div>
                                            <div class="notif-desc">Get notified when you receive a new message</div>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input notify-toggle" type="checkbox"
                                            name="push_new_message" id="push_new_message" role="switch"
                                            {{ $settings->push_new_message ? 'checked' : '' }}>
                                    </div>
                                </div>

                                {{-- Profile Views --}}
                                <div class="notif-row border-bottom">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="notif-icon-sm" style="background:#eff6ff;">
                                            <ion-icon name="eye-outline" style="color:#2563eb;"></ion-icon>
                                        </div>
                                        <div>
                                            <div class="notif-title">Profile Views</div>
                                            <div class="notif-desc">Know when someone views your profile</div>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input notify-toggle" type="checkbox"
                                            name="push_profile_views" id="push_profile_views" role="switch"
                                            {{ $settings->push_profile_views ? 'checked' : '' }}>
                                    </div>
                                </div>

                                {{-- Likes Received --}}
                                <div class="notif-row">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="notif-icon-sm" style="background:#fff7ed;">
                                            <ion-icon name="thumbs-up-outline" style="color:#ea580c;"></ion-icon>
                                        </div>
                                        <div>
                                            <div class="notif-title">Likes Received</div>
                                            <div class="notif-desc">Get notified when someone likes your profile</div>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input notify-toggle" type="checkbox"
                                            name="push_likes_received" id="push_likes_received" role="switch"
                                            {{ $settings->push_likes_received ? 'checked' : '' }}>
                                    </div>
                                </div>

                                <hr class="mt-3 mb-4">

                                {{-- Master Toggle --}}
                                <div
                                    class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-4 master-toggle-box">
                                    <div class="d-flex align-items-center gap-2">
                                        <ion-icon name="notifications-off-outline"
                                            style="font-size:18px;color:#7c3aed;"></ion-icon>
                                        <span class="fw-medium" style="font-size:14px;color:#7c3aed;">Disable All Push
                                            Notifications</span>
                                    </div>
                                    <button type="button" id="disableAllBtn"
                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size:12px;">
                                        Disable All
                                    </button>
                                </div>

                                {{-- Save --}}
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn save-btn px-5 rounded-pill">
                                        <ion-icon name="save-outline" class="me-1"></ion-icon>
                                        Save Changes
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <style>
                .notif-icon-wrap {
                    width: 48px;
                    height: 48px;
                    border-radius: 14px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .notif-icon-wrap ion-icon {
                    font-size: 22px;
                    color: white;
                }

                .notif-icon-sm {
                    width: 40px;
                    height: 40px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                .notif-icon-sm ion-icon {
                    font-size: 18px;
                }

                .notif-row {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 16px 0;
                }

                .notif-title {
                    font-size: 14px;
                    font-weight: 500;
                }

                .notif-desc {
                    font-size: 12px;
                    color: #6c757d;
                    margin-top: 2px;
                }

                .form-check-input.notify-toggle {
                    width: 44px;
                    height: 24px;
                    cursor: pointer;
                }

                .form-check-input.notify-toggle:checked {
                    background-color: #7c3aed;
                    border-color: #7c3aed;
                }

                .form-check-input.notify-toggle:focus {
                    box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
                    border-color: #7c3aed;
                }

                .master-toggle-box {
                    background: #f8f5ff;
                    border: 1px solid #e9d8fd;
                }

                .save-btn {
                    background: linear-gradient(135deg, #7c3aed, #a855f7);
                    border: none;
                    color: white;
                }

                .save-btn:hover {
                    opacity: 0.9;
                    color: white;
                }
            </style>
        </div>
    </div>
    <script>
        const disableBtn = document.getElementById('disableAllBtn');
        disableBtn.addEventListener('click', function() {
            const toggles = document.querySelectorAll('.notify-toggle');
            const anyOn = [...toggles].some(t => t.checked);
            toggles.forEach(t => t.checked = !anyOn);
            this.textContent = anyOn ? 'Enable All' : 'Disable All';
        });
    </script>
@endsection
