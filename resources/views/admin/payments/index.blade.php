@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
<div class="page-content">

  {{-- Breadcrumb --}}
  <div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Payments</div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0 align-items-center">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a></li>
          <li class="breadcrumb-item active">Payments</li>
        </ol>
      </nav>
    </div>
    <div class="ms-auto d-flex gap-2">
      {{-- Export --}}
      <a href="{{ route('admin.payments.export') }}?filter={{ $filter }}" class="btn btn-sm btn-outline-success">
        <ion-icon name="download-outline"></ion-icon> Export CSV
      </a>
      {{-- Add Manual --}}
      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#manualPaymentModal">
        <ion-icon name="add-outline"></ion-icon> Manual Payment
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif

  {{-- ── Revenue Stats Cards ── --}}
  <div class="row row-cols-2 row-cols-lg-4 g-3 mb-4">
    <div class="col">
      <div class="card radius-10 border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0" style="font-size:13px">Total Revenue</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-success">
              <ion-icon name="wallet-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-2">
            <h4 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h4>
            <small class="ms-auto text-muted">All time</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card radius-10 border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0" style="font-size:13px">Today</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-info">
              <ion-icon name="today-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-2">
            <h4 class="mb-0">${{ number_format($stats['today_revenue'], 2) }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card radius-10 border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0" style="font-size:13px">This Month</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-purple">
              <ion-icon name="calendar-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-2">
            <h4 class="mb-0">${{ number_format($stats['month_revenue'], 2) }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card radius-10 border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0" style="font-size:13px">This Year</p></div>
            <div class="ms-auto widget-icon-small text-white bg-gradient-warning">
              <ion-icon name="bar-chart-outline"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-2">
            <h4 class="mb-0">${{ number_format($stats['year_revenue'], 2) }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">

    {{-- ── Revenue Chart ── --}}
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong>💰 Revenue Chart</strong>
          <div class="d-flex gap-1">
            @foreach(['daily'=>'Daily','monthly'=>'Monthly','yearly'=>'Yearly'] as $p=>$label)
              <a href="{{ route('admin.payments.index') }}?period={{ $p }}&filter={{ $filter }}"
                 class="btn btn-xs {{ $period==$p ? 'btn-primary' : 'btn-outline-secondary' }}"
                 style="font-size:11px;padding:3px 10px">{{ $label }}</a>
            @endforeach
          </div>
        </div>
        <div class="card-body">
          <canvas id="revenueChart" height="100"></canvas>
        </div>
      </div>
    </div>

    {{-- ── Plan Revenue Breakdown ── --}}
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><strong>💎 Revenue by Plan</strong></div>
        <div class="card-body">
          <canvas id="planChart" height="180"></canvas>
          <div class="mt-3">
            @foreach($planRevenue as $plan)
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="badge bg-{{ $plan->badge_color }}">{{ $plan->name }}</span>
              <span style="font-size:13px;font-weight:600">${{ number_format($plan->revenue ?? 0, 2) }}</span>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── Transactions Table ── --}}
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white">

      {{-- Status filter tabs --}}
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <ul class="nav nav-tabs card-header-tabs mb-0">
          @foreach(['all'=>'All','paid'=>'Paid','pending'=>'Pending','failed'=>'Failed','refunded'=>'Refunded'] as $f=>$label)
          <li class="nav-item">
            <a class="nav-link {{ $filter==$f ? 'active' : '' }}"
               href="{{ route('admin.payments.index') }}?filter={{ $f }}&period={{ $period }}">
              {{ $label }}
              @if($f === 'paid') <span class="badge bg-success ms-1">{{ $stats['total_paid'] }}</span>
              @elseif($f === 'pending') <span class="badge bg-warning text-dark ms-1">{{ $stats['total_pending'] }}</span>
              @elseif($f === 'failed') <span class="badge bg-danger ms-1">{{ $stats['total_failed'] }}</span>
              @elseif($f === 'refunded') <span class="badge bg-secondary ms-1">{{ $stats['total_refunded'] }}</span>
              @endif
            </a>
          </li>
          @endforeach
        </ul>

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.payments.index') }}" class="d-flex gap-2">
          <input type="hidden" name="filter" value="{{ $filter }}">
          <input type="text" name="search" value="{{ $search }}"
            class="form-control form-control-sm" placeholder="Search user or transaction..."
            style="width:220px">
          <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
        </form>
      </div>
    </div>

    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>User</th>
            <th>Plan</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Status</th>
            <th>Transaction ID</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($payments as $payment)
          <tr>
            <td style="font-size:12px;color:#888">{{ $payment->id }}</td>
            <td>
              <div style="font-weight:600;font-size:13px">{{ $payment->user?->name ?? 'Deleted' }}</div>
              <div style="font-size:11px;color:#888">{{ $payment->user?->email ?? '' }}</div>
            </td>
            <td>
              @if($payment->plan)
                <span class="badge bg-{{ $payment->plan->badge_color }}">{{ $payment->plan->name }}</span>
              @else
                <span class="text-muted" style="font-size:12px">—</span>
              @endif
            </td>
            <td style="font-weight:700;font-size:14px;color:#22c55e">
              ${{ number_format($payment->amount, 2) }}
            </td>
            <td>
              <span class="badge {{ $payment->payment_method === 'stripe' ? 'bg-primary' : 'bg-secondary' }}">
                {{ $payment->payment_method === 'stripe' ? '💳 Stripe' : '✍️ Manual' }}
              </span>
            </td>
            <td>
              <span class="badge bg-{{ $payment->statusColor() }}">
                {{ $payment->statusIcon() }} {{ ucfirst($payment->status) }}
              </span>
            </td>
            <td style="font-size:11px;color:#888;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
              {{ $payment->transaction_id ?? '—' }}
            </td>
            <td style="font-size:12px;color:#888">
              {{ $payment->paid_at?->format('d M Y') ?? $payment->created_at->format('d M Y') }}
            </td>
            <td>
              <a href="{{ route('admin.payments.show', $payment->id) }}"
                 class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px">
                👁 View
              </a>
            </td>
          </tr>
          @empty
          <tr><td colspan="9" class="text-center py-4 text-muted">No payments found</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="p-3">{{ $payments->links() }}</div>
    </div>
  </div>

</div>
</div>

{{-- ── Manual Payment Modal ── --}}
<div class="modal fade" id="manualPaymentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">✍️ Add Manual Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.payments.store-manual') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" style="font-size:13px;font-weight:600">User</label>
            <select name="user_id" class="form-select" required>
              <option value="">-- Select User --</option>
              @foreach(\App\Models\User::where('role','user')->orderBy('name')->get() as $u)
                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px;font-weight:600">Plan</label>
            <select name="plan_id" class="form-select" required>
              <option value="">-- Select Plan --</option>
              @foreach($plans as $plan)
                <option value="{{ $plan->id }}">{{ $plan->name }} — ${{ $plan->price }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px;font-weight:600">Amount ($)</label>
            <input type="number" name="amount" class="form-control" step="0.01" min="0" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px;font-weight:600">Payment Date</label>
            <input type="date" name="paid_at" class="form-control" value="{{ date('Y-m-d') }}">
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px;font-weight:600">Notes <small class="text-muted">(optional)</small></label>
            <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Bank transfer, cash payment..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">✅ Add Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
const revenueLabels = @json($labels);
const revenueData   = @json($data);
const planNames     = @json($planRevenue->pluck('name'));
const planRevenues  = @json($planRevenue->pluck('revenue'));

// Revenue Chart
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Revenue ($)',
            data: revenueData,
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34,197,94,.1)',
            fill: true, tension: .4,
            pointBackgroundColor: '#22c55e',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => '$' + v } } }
    }
});

// Plan Chart
new Chart(document.getElementById('planChart'), {
    type: 'doughnut',
    data: {
        labels: planNames,
        datasets: [{
            data: planRevenues,
            backgroundColor: ['#6b7280','#3b82f6','#f59e0b','#8b5cf6'],
            borderWidth: 2,
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>

@endsection