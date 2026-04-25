@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
  <div class="page-content">

    <div class="page-breadcrumb d-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3">Subscriptions</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0 align-items-center">
            <li class="breadcrumb-item">
              <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
            </li>
            <li class="breadcrumb-item active">Subscriptions</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-sm btn-outline-primary">
          Manage Plans
        </a>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          @foreach(['all' => 'All', 'active' => 'Active', 'expired' => 'Expired', 'cancelled' => 'Cancelled'] as $s => $label)
            <a class="btn btn-sm {{ $status === $s ? 'btn-primary' : 'btn-outline-secondary' }}"
               href="{{ route('admin.subscriptions.index') }}?status={{ $s }}&search={{ $search }}">
              {{ $label }}
            </a>
          @endforeach
        </div>

        <div class="d-flex gap-2">
          <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="d-flex gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search user/plan...">
            <button type="submit" class="btn btn-sm btn-outline-success">Search</button>
          </form>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="p-3">
          <div class="row row-cols-2 row-cols-lg-4 g-3">
            <div class="col">
              <div class="text-muted" style="font-size:13px">Active</div>
              <div style="font-weight:800">{{ $stats['active'] }}</div>
            </div>
            <div class="col">
              <div class="text-muted" style="font-size:13px">Expired</div>
              <div style="font-weight:800">{{ $stats['expired'] }}</div>
            </div>
            <div class="col">
              <div class="text-muted" style="font-size:13px">Cancelled</div>
              <div style="font-weight:800">{{ $stats['cancelled'] }}</div>
            </div>
            <div class="col">
              <div class="text-muted" style="font-size:13px">Revenue (Active)</div>
              <div style="font-weight:800">${{ number_format($stats['revenue_active'], 2) }}</div>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>User</th>
                <th>Plan</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Starts</th>
                <th>Expires</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($subscriptions as $sub)
                <tr>
                  <td style="font-size:12px;color:#888">{{ $sub->id }}</td>
                  <td>
                    <div style="font-weight:700">{{ $sub->user?->name ?? 'Deleted' }}</div>
                    <div style="font-size:11px;color:#888">{{ $sub->user?->email ?? '' }}</div>
                  </td>
                  <td>
                    @if($sub->plan)
                      <span class="badge bg-{{ $sub->plan->badge_color }}">{{ $sub->plan->name }}</span>
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td style="font-weight:800">${{ number_format($sub->amount_paid, 2) }}</td>
                  <td>
                    <span class="badge bg-{{ $sub->statusColor() }}">
                      {{ ucfirst($sub->status) }}
                    </span>
                  </td>
                  <td style="font-size:12px;color:#666">{{ $sub->starts_at?->format('d M Y') ?? '—' }}</td>
                  <td style="font-size:12px;color:#666">{{ $sub->expires_at?->format('d M Y') ?? '—' }}</td>
                  <td>
                    <a href="{{ route('admin.subscriptions.users.show', $sub->user_id) }}" class="btn btn-sm btn-outline-primary py-0 px-2">
                      View
                    </a>

                    @if($sub->status === 'active')
                      <form method="POST" action="{{ route('admin.subscriptions.users.cancel', $sub->user_id) }}" class="d-inline" onsubmit="return confirm('Cancel active subscription?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" type="submit">
                          Cancel
                        </button>
                      </form>
                    @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No subscriptions found</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="p-3">{{ $subscriptions->links() }}</div>
      </div>
    </div>

  </div>
</div>

@endsection

