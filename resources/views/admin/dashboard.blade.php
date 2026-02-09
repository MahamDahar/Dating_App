@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_heading', 'Dashboard')

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-1">Welcome, {{ auth()->user()->name }}!</h4>
                    <p class="text-muted mb-0">Admin dashboard – yahan se aap site manage kar sakte hain.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                    <h5 class="mb-0">Users</h5>
                    <p class="text-muted small mb-0">Coming soon</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                    <h5 class="mb-0">Reports</h5>
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
