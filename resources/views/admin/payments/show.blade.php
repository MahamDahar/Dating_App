@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
<div class="page-content">

  <div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Payment Detail</div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0 align-items-center">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
          <li class="breadcrumb-item active">#{{ $payment->id }}</li>
        </ol>
      </nav>
    </div>
    <div class="ms-auto">
      <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">← Back</a>
    </div>
  </div>

  <div class="row justify-content-center">
  <div class="col-lg-7">

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>💳 Payment #{{ $payment->id }}</strong>
        <span class="badge bg-{{ $payment->statusColor() }} px-3 py-2" style="font-size:13px">
          {{ $payment->statusIcon() }} {{ ucfirst($payment->status) }}
        </span>
      </div>
      <div class="card-body">

        {{-- Amount --}}
        <div class="text-center py-4 mb-3" style="background:#f0fdf4;border-radius:12px">
          <div style="font-size:42px;font-weight:900;color:#22c55e">
            ${{ number_format($payment->amount, 2) }}
          </div>
          <div style="font-size:13px;color:#888">{{ $payment->currency }}</div>
        </div>

        {{-- Details --}}
        <table class="table table-borderless mb-0">
          <tr>
            <td style="font-size:13px;color:#888;width:40%">User</td>
            <td style="font-size:13px;font-weight:600">
              {{ $payment->user?->name ?? 'Deleted User' }}
              <div style="font-size:11px;color:#888;font-weight:400">{{ $payment->user?->email }}</div>
            </td>
          </tr>
          <tr>
            <td style="font-size:13px;color:#888">Plan</td>
            <td>
              @if($payment->plan)
                <span class="badge bg-{{ $payment->plan->badge_color }}">{{ $payment->plan->name }}</span>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
          </tr>
          <tr>
            <td style="font-size:13px;color:#888">Payment Method</td>
            <td>
              <span class="badge {{ $payment->payment_method === 'stripe' ? 'bg-primary' : 'bg-secondary' }}">
                {{ $payment->payment_method === 'stripe' ? '💳 Stripe' : '✍️ Manual' }}
              </span>
            </td>
          </tr>
          <tr>
            <td style="font-size:13px;color:#888">Transaction ID</td>
            <td style="font-size:12px;font-family:monospace">{{ $payment->transaction_id ?? '—' }}</td>
          </tr>
          @if($payment->stripe_session_id)
          <tr>
            <td style="font-size:13px;color:#888">Stripe Session</td>
            <td style="font-size:12px;font-family:monospace">{{ $payment->stripe_session_id }}</td>
          </tr>
          @endif
          <tr>
            <td style="font-size:13px;color:#888">Paid At</td>
            <td style="font-size:13px">{{ $payment->paid_at?->format('d M Y, h:i A') ?? '—' }}</td>
          </tr>
          <tr>
            <td style="font-size:13px;color:#888">Created At</td>
            <td style="font-size:13px">{{ $payment->created_at->format('d M Y, h:i A') }}</td>
          </tr>
          @if($payment->notes)
          <tr>
            <td style="font-size:13px;color:#888">Notes</td>
            <td style="font-size:13px">{{ $payment->notes }}</td>
          </tr>
          @endif
        </table>

      </div>
    </div>

  </div>
  </div>

</div>
</div>
@endsection