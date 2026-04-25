@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
<div class="page-content">

  <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Dashboard</div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0 align-items-center">
          <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a></li>
          <li class="breadcrumb-item active" aria-current="page">Overview</li>
        </ol>
      </nav>
    </div>
  </div>

  {{-- ── Row 1: Main Stats ── --}}
  <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-4 g-3 mb-3">

    <div class="col">
      <div class="card radius-10">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0 fs-6">Total Users</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-purple">
              <ion-icon name="people-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            <div><h4 class="mb-0">{{ $totalUsers }}</h4></div>
            <div class="ms-auto text-success small">All time</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card radius-10">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0 fs-6">Total Subscribers</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-info">
              <ion-icon name="diamond-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            <div><h4 class="mb-0">{{ $totalSubscribers }}</h4></div>
            <div class="ms-auto text-success small">Premium users</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card radius-10">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0 fs-6">Total Matches</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
              <ion-icon name="heart-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            <div><h4 class="mb-0">{{ $totalMatches }}</h4></div>
            <div class="ms-auto text-success small">All time</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card radius-10">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0 fs-6">Total Revenue</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-success">
              <ion-icon name="wallet-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            <div><h4 class="mb-0">${{ number_format($totalRevenue, 2) }}</h4></div>
            <div class="ms-auto text-success small">Estimated</div>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── Row 2: Today & User Stats ── --}}
  <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-4 g-3">

    {{-- Active Users Today --}}
    <div class="col">
      <div class="card radius-10">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0 fs-6">Active Users</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-warning">
              <ion-icon name="pulse-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            <div><h4 class="mb-0">{{ $activeUsersToday }}</h4></div>
            <div class="ms-auto text-muted small">Today</div>
          </div>
        </div>
      </div>
    </div>

    {{-- New Signups Today --}}
    <div class="col">
      <div class="card radius-10">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0 fs-6">New Signups</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-info">
              <ion-icon name="person-add-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            <div><h4 class="mb-0">{{ $newSignupsToday }}</h4></div>
            <div class="ms-auto text-muted small">Today</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Gender Ratio --}}
    <div class="col">
      <div class="card radius-10">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0 fs-6">Gender Ratio</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
              <ion-icon name="male-female-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            <div>
              <h4 class="mb-0" style="font-size:16px">
                👨 {{ $totalMen }} &nbsp;·&nbsp; 👩 {{ $totalWomen }}
              </h4>
            </div>
          </div>
          {{-- Mini ratio bar --}}
          @php
            $total = $totalMen + $totalWomen;
            $menPct = $total > 0 ? round(($totalMen / $total) * 100) : 50;
          @endphp
          <div class="mt-2" style="height:5px;background:#f0f0f0;border-radius:10px;overflow:hidden">
            <div style="height:100%;width:{{ $menPct }}%;background:linear-gradient(90deg,#7c3aed,#e85d75);border-radius:10px"></div>
          </div>
          <div class="d-flex justify-content-between mt-1">
            <small class="text-muted">Men {{ $menPct }}%</small>
            <small class="text-muted">Women {{ 100 - $menPct }}%</small>
          </div>
        </div>
      </div>
    </div>

    {{-- Verified Users --}}
    <div class="col">
      <div class="card radius-10">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0 fs-6">Verified Users</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-success">
              <ion-icon name="shield-checkmark-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            <div><h4 class="mb-0">{{ $verifiedUsers }}</h4></div>
            <div class="ms-auto text-muted small">
              @if($totalUsers > 0)
                {{ round(($verifiedUsers / $totalUsers) * 100) }}% of total
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>
</div>

@endsection