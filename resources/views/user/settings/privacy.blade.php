@extends('layouts.user')
@section('usercontent')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Privacy Settings</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.discover') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Privacy Settings</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <ion-icon name="checkmark-circle-outline" class="me-2"></ion-icon>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-7 mx-auto">

                <form method="POST" action="{{ route('user.settings.privacy.update') }}">
                    @csrf

                    {{-- ── INTERACTIONS ── --}}
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold">
                                <ion-icon name="people-outline" class="me-2" style="color:#7c3aed;"></ion-icon>
                                Interactions
                            </h6>
                            <small class="text-muted">Control who can interact with you</small>
                        </div>
                        <div class="card-body">

                            {{-- Who can send proposals --}}
                            <div class="row align-items-center py-3 border-bottom">
                                <div class="col-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:38px;height:38px;background:#f3f0ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                            <ion-icon name="paper-plane-outline" style="color:#7c3aed;font-size:18px;"></ion-icon>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold" style="font-size:14px;">Who can send me proposals</p>
                                            <small class="text-muted">Control who can send you a rishta proposal</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <select name="who_can_send_proposals" class="form-select form-select-sm">
                                        <option value="everyone"  {{ $privacy->who_can_send_proposals == 'everyone'  ? 'selected' : '' }}>Everyone</option>
                                        <option value="no_one"    {{ $privacy->who_can_send_proposals == 'no_one'    ? 'selected' : '' }}>No One</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Who can message --}}
                            <div class="row align-items-center py-3">
                                <div class="col-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:38px;height:38px;background:#f0fdf4;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                            <ion-icon name="chatbubble-outline" style="color:#16a34a;font-size:18px;"></ion-icon>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold" style="font-size:14px;">Who can message me</p>
                                            <small class="text-muted">Only accepted matches can message by default</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <select name="who_can_message" class="form-select form-select-sm">
                                        <option value="everyone"     {{ $privacy->who_can_message == 'everyone'     ? 'selected' : '' }}>Everyone</option>
                                        <option value="matches_only" {{ $privacy->who_can_message == 'matches_only' ? 'selected' : '' }}>Matches Only</option>
                                        <option value="no_one"       {{ $privacy->who_can_message == 'no_one'       ? 'selected' : '' }}>No One</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ── VISIBILITY ── --}}
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold">
                                <ion-icon name="eye-outline" class="me-2" style="color:#7c3aed;"></ion-icon>
                                Visibility
                            </h6>
                            <small class="text-muted">Control what others can see about you</small>
                        </div>
                        <div class="card-body">

                            {{-- Who can see photos --}}
                            <div class="row align-items-center py-3 border-bottom">
                                <div class="col-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:38px;height:38px;background:#fff7ed;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                            <ion-icon name="images-outline" style="color:#ea580c;font-size:18px;"></ion-icon>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold" style="font-size:14px;">Who can see my photos</p>
                                            <small class="text-muted">Control who can view your profile photos</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <select name="who_can_see_photos" class="form-select form-select-sm">
                                        <option value="everyone"     {{ $privacy->who_can_see_photos == 'everyone'     ? 'selected' : '' }}>Everyone</option>
                                        <option value="matches_only" {{ $privacy->who_can_see_photos == 'matches_only' ? 'selected' : '' }}>Matches Only</option>
                                        <option value="no_one"       {{ $privacy->who_can_see_photos == 'no_one'       ? 'selected' : '' }}>No One</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Who can see online status --}}
                            <div class="row align-items-center py-3 border-bottom">
                                <div class="col-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:38px;height:38px;background:#f0fdf4;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                            <ion-icon name="radio-button-on-outline" style="color:#16a34a;font-size:18px;"></ion-icon>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold" style="font-size:14px;">Who can see my online status</p>
                                            <small class="text-muted">Control who sees when you're online</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <select name="who_can_see_online" class="form-select form-select-sm">
                                        <option value="everyone"     {{ $privacy->who_can_see_online == 'everyone'     ? 'selected' : '' }}>Everyone</option>
                                        <option value="matches_only" {{ $privacy->who_can_see_online == 'matches_only' ? 'selected' : '' }}>Matches Only</option>
                                        <option value="no_one"       {{ $privacy->who_can_see_online == 'no_one'       ? 'selected' : '' }}>No One</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Who can see last active --}}
                            <div class="row align-items-center py-3">
                                <div class="col-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:38px;height:38px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                            <ion-icon name="time-outline" style="color:#2563eb;font-size:18px;"></ion-icon>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold" style="font-size:14px;">Who can see my last active time</p>
                                            <small class="text-muted">Control who sees when you were last active</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <select name="who_can_see_last_active" class="form-select form-select-sm">
                                        <option value="everyone"     {{ $privacy->who_can_see_last_active == 'everyone'     ? 'selected' : '' }}>Everyone</option>
                                        <option value="matches_only" {{ $privacy->who_can_see_last_active == 'matches_only' ? 'selected' : '' }}>Matches Only</option>
                                        <option value="no_one"       {{ $privacy->who_can_see_last_active == 'no_one'       ? 'selected' : '' }}>No One</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ── SEARCH ENGINES ── --}}
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold">
                                <ion-icon name="search-outline" class="me-2" style="color:#7c3aed;"></ion-icon>
                                Search & Discovery
                            </h6>
                            <small class="text-muted">Control how your profile is discovered</small>
                        </div>
                        <div class="card-body">

                            <div class="row align-items-center py-2">
                                <div class="col-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:38px;height:38px;background:#fdf4ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                            <ion-icon name="globe-outline" style="color:#9333ea;font-size:18px;"></ion-icon>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold" style="font-size:14px;">Show profile on search engines</p>
                                            <small class="text-muted">Allow Google & other search engines to index your profile</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <select name="show_on_search_engines" class="form-select form-select-sm">
                                        <option value="1" {{ $privacy->show_on_search_engines ? 'selected' : '' }}>Yes — Allow</option>
                                        <option value="0" {{ !$privacy->show_on_search_engines ? 'selected' : '' }}>No — Hide</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Save Button --}}
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary px-5">
                            <ion-icon name="save-outline" class="me-1"></ion-icon>
                            Save Privacy Settings
                        </button>
                        <a href="{{ route('user.discover') }}" class="btn btn-light px-4">Cancel</a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection