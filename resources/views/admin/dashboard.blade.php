@extends('layouts.admin')
@section('admincontent')

    <!-- start page content wrapper-->
    <div class="page-content-wrapper">
      <div class="page-content">

        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Dashboard</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item">
                  <a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Overview</li>
              </ol>
            </nav>
          </div>
        </div>
        <!--end breadcrumb-->

        <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-4">

          {{-- Total Users --}}
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
                  <div class="ms-auto text-success">+6.32%</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Total Subscribers --}}
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
                  <div class="ms-auto text-success">+12.45%</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Total Matches --}}
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
                  <div class="ms-auto text-success">+3.12%</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Total Revenue --}}
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
                  <div class="ms-auto text-success">+8.52%</div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!--end row-->

      </div>
    </div>

@endsection