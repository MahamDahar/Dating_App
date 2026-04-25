@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Privacy & Terms</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Privacy & Terms</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tabs Navigation --}}
        <ul class="nav nav-tabs mb-3" id="legalTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#privacy">
                    <ion-icon name="lock-closed-outline"></ion-icon> Privacy Policy
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#terms">
                    <ion-icon name="document-text-outline"></ion-icon> Terms & Conditions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#guidelines">
                    <ion-icon name="people-outline"></ion-icon> Community Guidelines
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#cookies">
                    <ion-icon name="settings-outline"></ion-icon> Cookie Policy
                </a>
            </li>
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content">

            {{-- Privacy Policy --}}
            <div class="tab-pane fade show active" id="privacy">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Privacy Policy</h6>
                        @if(isset($pages['privacy_policy']))
                            <small class="text-muted">Last updated: {{ $pages['privacy_policy']->last_updated_at?->format('d M Y') }}</small>
                        @endif
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.legal.update', 'privacy_policy') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Page Title</label>
                                <input type="text" name="title" class="form-control"
                                       value="{{ $pages['privacy_policy']->title ?? 'Privacy Policy' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Content</label>
                                <textarea name="content" class="form-control" rows="15">{{ $pages['privacy_policy']->content ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary px-4">
                                <ion-icon name="save-outline"></ion-icon> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Terms & Conditions --}}
            <div class="tab-pane fade" id="terms">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Terms & Conditions</h6>
                        @if(isset($pages['terms_conditions']))
                            <small class="text-muted">Last updated: {{ $pages['terms_conditions']->last_updated_at?->format('d M Y') }}</small>
                        @endif
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.legal.update', 'terms_conditions') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Page Title</label>
                                <input type="text" name="title" class="form-control"
                                       value="{{ $pages['terms_conditions']->title ?? 'Terms & Conditions' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Content</label>
                                <textarea name="content" class="form-control" rows="15">{{ $pages['terms_conditions']->content ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary px-4">
                                <ion-icon name="save-outline"></ion-icon> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Community Guidelines --}}
            <div class="tab-pane fade" id="guidelines">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Community Guidelines</h6>
                        @if(isset($pages['community_guidelines']))
                            <small class="text-muted">Last updated: {{ $pages['community_guidelines']->last_updated_at?->format('d M Y') }}</small>
                        @endif
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.legal.update', 'community_guidelines') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Page Title</label>
                                <input type="text" name="title" class="form-control"
                                       value="{{ $pages['community_guidelines']->title ?? 'Community Guidelines' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Content</label>
                                <textarea name="content" class="form-control" rows="15">{{ $pages['community_guidelines']->content ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary px-4">
                                <ion-icon name="save-outline"></ion-icon> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Cookie Policy --}}
            <div class="tab-pane fade" id="cookies">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Cookie Policy</h6>
                        @if(isset($pages['cookie_policy']))
                            <small class="text-muted">Last updated: {{ $pages['cookie_policy']->last_updated_at?->format('d M Y') }}</small>
                        @endif
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.legal.update', 'cookie_policy') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Page Title</label>
                                <input type="text" name="title" class="form-control"
                                       value="{{ $pages['cookie_policy']->title ?? 'Cookie Policy' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Content</label>
                                <textarea name="content" class="form-control" rows="15">{{ $pages['cookie_policy']->content ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary px-4">
                                <ion-icon name="save-outline"></ion-icon> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        {{-- end tab content --}}

    </div>
</div>

@endsection