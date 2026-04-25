@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
  <div class="page-content">

    <div class="page-breadcrumb d-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3">Subscription User</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0 align-items-center">
            <li class="breadcrumb-item">
              <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
            </li>
            <li class="breadcrumb-item">
              <a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a>
            </li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
          </ol>
        </nav>
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

    <div class="row g-4">
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white">
            <strong>User</strong>
          </div>
          <div class="card-body">
            <div style="font-weight:800;font-size:18px">{{ $user->name }}</div>
            <div class="text-muted" style="font-size:13px">{{ $user->email }}</div>
            <div class="text-muted" style="font-size:13px">Username: {{ $user->username ?? '-' }}</div>

            <hr />

            <h6 class="mb-3">Activate / Renew</h6>
            <form method="POST" action="{{ route('admin.subscriptions.users.activate', $user->id) }}">
              @csrf

              <div class="mb-3">
                <label class="form-label" style="font-weight:700;font-size:13px">Plan</label>
                <select name="plan_id" class="form-select" required>
                  @foreach($plans as $p)
                    <option value="{{ $p->id }}" {{ $activeSubscription && $activeSubscription->plan_id == $p->id ? 'selected' : '' }}>
                      {{ $p->name }} ({{ $p->price == 0 ? 'Free' : '$'.number_format($p->price,2) }}, {{ $p->duration_days }} days)
                    </option>
                  @endforeach
                </select>
              </div>

              @php
                $defaultAmount = $activeSubscription?->plan?->price ?? ($plans->first()?->price ?? 0);
              @endphp

              <div class="mb-3">
                <label class="form-label" style="font-weight:700;font-size:13px">Amount Paid</label>
                <input type="number" step="0.01" min="0" name="amount_paid" class="form-control" value="{{ old('amount_paid', $defaultAmount) }}" required />
                <div class="text-muted" style="font-size:12px;margin-top:6px">
                  Tip: amount ko invoice/record ke hisaab se set karein.
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label" style="font-weight:700;font-size:13px">Starts At (optional)</label>
                <input type="datetime-local" name="starts_at"
                  class="form-control"
                  value="{{ old('starts_at') ?? now()->format('Y-m-d\\TH:i') }}" />
              </div>

              <div class="mb-3">
                <label class="form-label" style="font-weight:700;font-size:13px">Notes (optional)</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
              </div>

              <button type="submit" class="btn btn-primary w-100">Activate / Renew</button>
            </form>

            @if($activeSubscription)
              <hr />
              <h6 class="mb-3">Cancel Active</h6>
              <form method="POST" action="{{ route('admin.subscriptions.users.cancel', $user->id) }}"
                    onsubmit="return confirm('Cancel active subscription for this user?')">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">Cancel Active Subscription</button>
              </form>
            @else
              <div class="alert alert-secondary mt-3 mb-0">
                No active subscription found.
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Subscriptions History</strong>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Starts</th>
                    <th>Expires</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($subscriptions as $s)
                    <tr>
                      <td>
                        @if($s->plan)
                          <span class="badge bg-{{ $s->plan->badge_color }}">{{ $s->plan->name }}</span>
                        @else
                          —
                        @endif
                      </td>
                      <td style="font-weight:800">${{ number_format($s->amount_paid, 2) }}</td>
                      <td>
                        <span class="badge bg-{{ $s->statusColor() }}">{{ ucfirst($s->status) }}</span>
                      </td>
                      <td style="font-size:12px;color:#666">{{ $s->starts_at?->format('d M Y') ?? '—' }}</td>
                      <td style="font-size:12px;color:#666">{{ $s->expires_at?->format('d M Y') ?? '—' }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No subscriptions found</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Payment Records</strong>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Date</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Transaction</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($payments as $p)
                    <tr>
                      <td style="font-size:12px;color:#666">{{ $p->paid_at?->format('d M Y') ?? '—' }}</td>
                      <td>
                        @if($p->plan)
                          <span class="badge bg-{{ $p->plan->badge_color }}">{{ $p->plan->name }}</span>
                        @else
                          —
                        @endif
                      </td>
                      <td style="font-weight:800">${{ number_format($p->amount, 2) }}</td>
                      <td>
                        <span class="badge {{ $p->payment_method === 'stripe' ? 'bg-primary' : 'bg-secondary' }}">
                          {{ $p->payment_method === 'stripe' ? 'Stripe' : 'Manual' }}
                        </span>
                      </td>
                      <td>
                        <span class="badge bg-{{ $p->statusColor() }}">{{ ucfirst($p->status) }}</span>
                      </td>
                      <td style="font-size:12px;color:#666;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $p->transaction_id ?? '—' }}
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No payments found</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

@endsection

