@extends('layouts.user')
@section('usercontent')

    {{-- ══ ADMIN ONLY: Back to Admin Panel Button ══ --}}
    @if(session('admin_user_id'))
    <div style="position:fixed;bottom:24px;right:24px;z-index:9999;">
        <a href="{{ route('admin.return') }}"
           style="display:flex;align-items:center;gap:8px;
                  background:linear-gradient(135deg,#0f0f14,#2d1b4e);
                  color:white;padding:12px 20px;border-radius:50px;
                  font-family:'DM Sans',sans-serif;font-size:13px;font-weight:700;
                  text-decoration:none;box-shadow:0 4px 20px rgba(0,0,0,.4);
                  border:1px solid rgba(255,255,255,.15);
                  transition:transform .2s,box-shadow .2s;"
           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 28px rgba(0,0,0,.5)'"
           onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(0,0,0,.4)'">
            🛡️ Back to Admin Panel
        </a>
    </div>
    @endif

    <!-- start page content wrapper-->
    <div class="page-content-wrapper">
      <!-- start page content-->
      <div class="page-content">

        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Dashboard</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item"><a href="javascript:;">
                    <ion-icon name="home-outline"></ion-icon>
                  </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">eCommerce</li>
              </ol>
            </nav>
          </div>
          <div class="ms-auto">
            <div class="btn-group">
              <button type="button" class="btn btn-outline-primary">Settings</button>
              <button type="button"
                class="btn btn-outline-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
              </button>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="javascript:;">Action</a>
                <a class="dropdown-item" href="javascript:;">Another action</a>
                <a class="dropdown-item" href="javascript:;">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:;">Separated link</a>
              </div>
            </div>
          </div>
        </div>
        <!--end breadcrumb-->

        <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-4">

            <!-- Total Revenue -->
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Revenue</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-purple">
                                <ion-icon name="wallet-outline"></ion-icon>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">${{ number_format($totalRevenue ?? 0, 2) }}</h4>
                            </div>
                            <div class="ms-auto">0%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Customers</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-info">
                                <ion-icon name="people-outline"></ion-icon>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{ number_format($totalCustomers ?? 0) }}</h4>
                            </div>
                            <div class="ms-auto">0%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Orders</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
                                <ion-icon name="bag-handle-outline"></ion-icon>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{ number_format($totalOrders ?? 0) }}</h4>
                            </div>
                            <div class="ms-auto">0%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversion Rate -->
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Conversion Rate</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-success">
                                <ion-icon name="bar-chart-outline"></ion-icon>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{ number_format($conversionRate ?? 0, 2) }}%</h4>
                            </div>
                            <div class="ms-auto">0%</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--end row-->

      </div>
      <!-- end page content-->
    </div>
    <!--end page content wrapper-->

@endsection