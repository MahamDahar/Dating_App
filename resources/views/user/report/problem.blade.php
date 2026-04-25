@extends('layouts.user')
@section('usercontent')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Report a Problem</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.discover') }}">
                                <ion-icon name="home-outline"></ion-icon>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Report a Problem</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <ion-icon name="checkmark-circle-outline" class="me-2"></ion-icon>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ion-icon name="alert-circle-outline" class="me-2"></ion-icon>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-7 mx-auto">

                {{-- Info Card --}}
                <div class="card mb-4" style="border-left: 4px solid #7c3aed;">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <ion-icon name="information-circle-outline" style="font-size:28px;color:#7c3aed;flex-shrink:0;"></ion-icon>
                        <div>
                            <p class="mb-0 small text-muted">
                                Use this form to report any technical issues, bugs, or problems you're experiencing.
                                Our support team will review your report and get back to you as soon as possible.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Report Form --}}
                <div class="card">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold">
                            <ion-icon name="flag-outline" class="me-2" style="color:#7c3aed;"></ion-icon>
                            Submit a Problem Report
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.report.problem.store') }}">
                            @csrf

                            {{-- Problem Type --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Problem Type <span class="text-danger">*</span></label>
                                <select name="subject" class="form-select @error('subject') is-invalid @enderror" required>
                                    <option value="">— Select a problem type —</option>
                                    <optgroup label="Technical Issues">
                                        <option value="App is crashing"           {{ old('subject') == 'App is crashing' ? 'selected' : '' }}>🔴 App is crashing</option>
                                        <option value="Page not loading"          {{ old('subject') == 'Page not loading' ? 'selected' : '' }}>🔴 Page not loading</option>
                                        <option value="Chat not working"          {{ old('subject') == 'Chat not working' ? 'selected' : '' }}>🔴 Chat not working</option>
                                        <option value="Photos not uploading"      {{ old('subject') == 'Photos not uploading' ? 'selected' : '' }}>🔴 Photos not uploading</option>
                                        <option value="Notifications not working" {{ old('subject') == 'Notifications not working' ? 'selected' : '' }}>🔴 Notifications not working</option>
                                    </optgroup>
                                    <optgroup label="Account Issues">
                                        <option value="Cannot login"              {{ old('subject') == 'Cannot login' ? 'selected' : '' }}>🟡 Cannot login</option>
                                        <option value="Profile not saving"        {{ old('subject') == 'Profile not saving' ? 'selected' : '' }}>🟡 Profile not saving</option>
                                        <option value="Account was blocked"       {{ old('subject') == 'Account was blocked' ? 'selected' : '' }}>🟡 Account was blocked</option>
                                        <option value="Premium not activated"     {{ old('subject') == 'Premium not activated' ? 'selected' : '' }}>🟡 Premium not activated</option>
                                    </optgroup>
                                    <optgroup label="Other">
                                        <option value="Billing issue"             {{ old('subject') == 'Billing issue' ? 'selected' : '' }}>🔵 Billing issue</option>
                                        <option value="Privacy concern"           {{ old('subject') == 'Privacy concern' ? 'selected' : '' }}>🔵 Privacy concern</option>
                                        <option value="Other"                     {{ old('subject') == 'Other' ? 'selected' : '' }}>🔵 Other</option>
                                    </optgroup>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Describe the Problem <span class="text-danger">*</span></label>
                                <textarea name="description" rows="5"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Please describe the problem in detail. Include what you were doing when it happened, any error messages you saw, etc."
                                    maxlength="1000" required>{{ old('description') }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted ms-auto" id="charCount">0 / 1000</small>
                                </div>
                            </div>

                            {{-- Device Info (optional) --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Device / Browser <span class="text-muted fw-normal">(optional)</span></label>
                                <input type="text" name="device_info" class="form-control"
                                    placeholder="e.g. Chrome on Windows 11, Safari on iPhone 14"
                                    value="{{ old('device_info') }}" maxlength="255">
                                <small class="text-muted">This helps us reproduce and fix the issue faster.</small>
                            </div>

                            {{-- Submit --}}
                            <div class="d-flex gap-3 align-items-center">
                                <button type="submit" class="btn btn-primary px-5">
                                    <ion-icon name="send-outline" class="me-1"></ion-icon>
                                    Submit Report
                                </button>
                                <a href="{{ route('user.discover') }}" class="btn btn-light px-4">Cancel</a>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- Contact Support --}}
                <div class="card mt-4">
                    <div class="card-body text-center py-4">
                        <ion-icon name="mail-outline" style="font-size:32px;color:#7c3aed;"></ion-icon>
                        <h6 class="mt-2">Need urgent help?</h6>
                        <p class="text-muted small mb-3">You can also reach our support team directly via email.</p>
                        <a href="mailto:support@fobia.com" class="btn btn-outline-primary btn-sm px-4">
                            <ion-icon name="mail-outline" class="me-1"></ion-icon>
                            support@fobia.com
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    // Character counter for description
    const textarea  = document.querySelector('textarea[name="description"]');
    const charCount = document.getElementById('charCount');
    if (textarea && charCount) {
        textarea.addEventListener('input', function () {
            charCount.textContent = this.value.length + ' / 1000';
            charCount.style.color = this.value.length > 900 ? '#dc2626' : '#6b7280';
        });
    }
</script>

@endsection