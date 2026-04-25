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
                            <a href="{{ route('user.discover') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-3 fw-bold">Account</h5>
                        <p class="text-muted small mb-4">Manage your profile and preferences.</p>

                        <div class="list-group list-group-flush">
                            <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                                <ion-icon name="person-outline" class="fs-4 text-primary"></ion-icon>
                                <div>
                                    <div class="fw-semibold">My profile</div>
                                    <div class="small text-muted">Edit photos and profile details</div>
                                </div>
                                <ion-icon name="chevron-forward-outline" class="ms-auto text-muted"></ion-icon>
                            </a>
                            <a href="{{ route('user.settings.privacy') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                                <ion-icon name="lock-closed-outline" class="fs-4 text-primary"></ion-icon>
                                <div>
                                    <div class="fw-semibold">Privacy</div>
                                    <div class="small text-muted">Who can see and contact you</div>
                                </div>
                                <ion-icon name="chevron-forward-outline" class="ms-auto text-muted"></ion-icon>
                            </a>
                            <a href="{{ route('user.settings.visibility') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                                <ion-icon name="eye-off-outline" class="fs-4 text-primary"></ion-icon>
                                <div>
                                    <div class="fw-semibold">Profile visibility</div>
                                    <div class="small text-muted">Show or hide your profile</div>
                                </div>
                                <ion-icon name="chevron-forward-outline" class="ms-auto text-muted"></ion-icon>
                            </a>
                            <a href="{{ route('user.settings.password') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                                <ion-icon name="key-outline" class="fs-4 text-primary"></ion-icon>
                                <div>
                                    <div class="fw-semibold">Change password</div>
                                    <div class="small text-muted">Update your login password</div>
                                </div>
                                <ion-icon name="chevron-forward-outline" class="ms-auto text-muted"></ion-icon>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('user.chat.index') }}" class="btn btn-outline-secondary btn-sm">Back to chat</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
