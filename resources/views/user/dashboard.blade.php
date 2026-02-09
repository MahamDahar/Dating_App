@extends('layouts.user')

@section('title', 'Dashboard')
@section('page_heading', 'Dashboard')

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-1">Welcome, {{ auth()->user()->name }}!</h4>
                    <p class="text-muted mb-0">Apna dashboard – yahan se aap apna profile aur activity dekh sakte hain.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-user fa-2x text-primary mb-2"></i>
                    <h5 class="mb-0">My Profile</h5>
                    <p class="text-muted small mb-0">Coming soon</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                    <h5 class="mb-0">Matches</h5>
                    <p class="text-muted small mb-0">Coming soon</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-cog fa-2x text-secondary mb-2"></i>
                    <h5 class="mb-0">Settings</h5>
                    <p class="text-muted small mb-0">Coming soon</p>
                </div>
            </div>
        </div>
    </div>
@endsection
