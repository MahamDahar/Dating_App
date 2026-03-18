@extends('layouts.user')

@section('usercontent')
<div class="page-content-wrapper">
      <div class="page-content">

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Settings</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('user.settings.profile') }}">Settings</a>
                </li>
                <li class="breadcrumb-item active">Profile Visibility</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-6">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
            <ion-icon name="checkmark-circle-outline" style="font-size:18px;"></ion-icon>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                {{-- Header --}}
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,#7c3aed,#a855f7);border-radius:14px;display:flex;align-items:center;justify-content:center;">
                        <ion-icon name="eye-off-outline" style="font-size:22px;color:white;"></ion-icon>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-semibold">Profile Visibility</h5>
                        <small class="text-muted">Control who can see your profile</small>
                    </div>
                </div>

                <hr class="mb-4">

                <form action="{{ route('user.settings.visibility.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <p class="text-muted mb-3" style="font-size:13px;">Choose who can discover and view your profile:</p>

                    {{-- Option 1: Everyone --}}
                    <label class="visibility-option {{ ($currentVisibility ?? 'everyone') === 'everyone' ? 'selected' : '' }}" for="vis_everyone">
                        <div class="d-flex align-items-center gap-3">
                            <div class="vis-icon" style="background:#f0fdf4;">
                                <ion-icon name="globe-outline" style="color:#16a34a;"></ion-icon>
                            </div>
                            <div class="flex-grow-1">
                                <div class="vis-title">Show me to Everyone</div>
                                <div class="vis-desc">Anyone on Fobia can discover and view your profile</div>
                            </div>
                            <input type="radio" name="visibility" id="vis_everyone" value="everyone"
                                {{ ($currentVisibility ?? 'everyone') === 'everyone' ? 'checked' : '' }}>
                        </div>
                    </label>

                    {{-- Option 2: Only people I've liked --}}
                    <label class="visibility-option {{ ($currentVisibility ?? '') === 'liked_only' ? 'selected' : '' }}" for="vis_liked">
                        <div class="d-flex align-items-center gap-3">
                            <div class="vis-icon" style="background:#fdf4ff;">
                                <ion-icon name="heart-outline" style="color:#7c3aed;"></ion-icon>
                            </div>
                            <div class="flex-grow-1">
                                <div class="vis-title">Only People I've Liked</div>
                                <div class="vis-desc">Only users you have liked can see your profile</div>
                            </div>
                            <input type="radio" name="visibility" id="vis_liked" value="liked_only"
                                {{ ($currentVisibility ?? '') === 'liked_only' ? 'checked' : '' }}>
                        </div>
                    </label>

                    {{-- Option 3: Hidden --}}
                    <label class="visibility-option {{ ($currentVisibility ?? '') === 'hidden' ? 'selected' : '' }}" for="vis_hidden">
                        <div class="d-flex align-items-center gap-3">
                            <div class="vis-icon" style="background:#fef2f2;">
                                <ion-icon name="eye-off-outline" style="color:#ef4444;"></ion-icon>
                            </div>
                            <div class="flex-grow-1">
                                <div class="vis-title">Hide me from Everyone</div>
                                <div class="vis-desc">Your profile will not appear in any searches or suggestions</div>
                            </div>
                            <input type="radio" name="visibility" id="vis_hidden" value="hidden"
                                {{ ($currentVisibility ?? '') === 'hidden' ? 'checked' : '' }}>
                        </div>
                    </label>

                    {{-- Info box --}}
                    <div class="d-flex align-items-start gap-2 p-3 rounded-3 mt-3 mb-4"
                        style="background:#f8f5ff;border:1px solid #e9d8fd;">
                        <ion-icon name="information-circle-outline" style="font-size:17px;color:#7c3aed;flex-shrink:0;margin-top:1px;"></ion-icon>
                        <small style="color:#5b21b6;font-size:12px;">
                            Changes take effect immediately after saving. You can update this anytime.
                        </small>
                    </div>

                    {{-- Confirm Button --}}
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn px-5 rounded-pill confirm-btn">
                            <ion-icon name="checkmark-circle-outline" class="me-1"></ion-icon>
                            Confirm
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
   </div>
   </div>
<style>
    .visibility-option {
        display: block;
        border: 2px solid #f0f0f0;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
    }

    .visibility-option:hover {
        border-color: #c4b5fd;
        background: #faf5ff;
    }

    .visibility-option.selected {
        border-color: #7c3aed;
        background: #faf5ff;
    }

    .visibility-option input[type="radio"] {
        accent-color: #7c3aed;
        width: 18px;
        height: 18px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .vis-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .vis-icon ion-icon { font-size: 20px; }

    .vis-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 2px;
    }

    .vis-desc {
        font-size: 12px;
        color: #6c757d;
    }

    .confirm-btn {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        border: none;
        color: white;
        font-weight: 500;
    }

    .confirm-btn:hover {
        opacity: 0.9;
        color: white;
    }
</style>

<script>
    // Highlight selected option on radio change
    document.querySelectorAll('input[name="visibility"]').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.visibility-option').forEach(opt => opt.classList.remove('selected'));
            this.closest('.visibility-option').classList.add('selected');
        });
    });
</script>

@endsection