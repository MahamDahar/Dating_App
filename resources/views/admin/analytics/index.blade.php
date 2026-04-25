@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
<div class="page-content">

  {{-- Breadcrumb --}}
  <div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Analytics</div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0 align-items-center">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a></li>
          <li class="breadcrumb-item active">Analytics</li>
        </ol>
      </nav>
    </div>
    {{-- Period Filter --}}
    <div class="ms-auto d-flex gap-2">
      @foreach(['7'=>'7 Days','30'=>'30 Days','90'=>'90 Days'] as $val=>$label)
        <a href="{{ route('admin.analytics.index') }}?period={{ $val }}"
           class="btn btn-sm {{ $period == $val ? 'btn-primary' : 'btn-outline-secondary' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>
  </div>

  {{-- ── Stats Cards ── --}}
  <div class="row row-cols-2 row-cols-lg-4 g-3 mb-4">
    @php
      $cards = [
        ['label'=>'Total Users',       'value'=>number_format($stats['total_users']),       'icon'=>'people-outline',        'color'=>'bg-gradient-purple', 'sub'=>'+'.$stats['new_users'].' new'],
        ['label'=>'Active Users',      'value'=>number_format($stats['active_users']),      'icon'=>'pulse-outline',          'color'=>'bg-gradient-info',   'sub'=>'Last 7 days'],
        ['label'=>'Subscribers',       'value'=>number_format($stats['total_subscribers']), 'icon'=>'diamond-outline',        'color'=>'bg-gradient-success','sub'=>'Active plans'],
        ['label'=>'Total Revenue',     'value'=>'$'.number_format($stats['total_revenue'],2),'icon'=>'wallet-outline',        'color'=>'bg-gradient-warning','sub'=>'All time'],
        ['label'=>'Total Matches',     'value'=>number_format($stats['total_matches']),     'icon'=>'heart-outline',          'color'=>'bg-gradient-danger', 'sub'=>'All time'],
        ['label'=>'Reports',           'value'=>number_format($stats['total_reports']),     'icon'=>'warning-outline',        'color'=>'bg-gradient-danger', 'sub'=>'Total reports'],
        ['label'=>'Blocked Accounts',  'value'=>number_format($stats['total_blocked']),     'icon'=>'ban-outline',            'color'=>'bg-gradient-purple', 'sub'=>'By admin'],
        ['label'=>'New Registrations', 'value'=>number_format($stats['new_users']),         'icon'=>'person-add-outline',     'color'=>'bg-gradient-info',   'sub'=>'Last '.$period.' days'],
      ];
    @endphp
    @foreach($cards as $card)
    <div class="col">
      <div class="card radius-10 border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-start gap-2">
            <div><p class="mb-0 fs-6" style="font-size:13px!important">{{ $card['label'] }}</p></div>
            <div class="ms-auto widget-icon-small text-white {{ $card['color'] }}">
              <ion-icon name="{{ $card['icon'] }}"></ion-icon>
            </div>
          </div>
          <div class="d-flex align-items-center mt-2">
            <div><h4 class="mb-0" style="font-size:20px">{{ $card['value'] }}</h4></div>
            <div class="ms-auto" style="font-size:11px;color:#888">{{ $card['sub'] }}</div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <div class="row g-4">

    {{-- ── New Registrations Chart ── --}}
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong>📈 New Registrations</strong>
          <small class="text-muted">Last {{ $period }} days</small>
        </div>
        <div class="card-body">
          <canvas id="regChart" height="100"></canvas>
        </div>
      </div>
    </div>

    {{-- ── Gender Breakdown ── --}}
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><strong>👥 Gender Breakdown</strong></div>
        <div class="card-body">
          <canvas id="genderChart" height="200"></canvas>
          <div class="mt-3">
            @foreach($genderStats as $gender => $count)
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span style="font-size:13px">{{ $gender ?? 'Unknown' }}</span>
              <span class="badge bg-primary">{{ number_format($count) }}</span>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    {{-- ── Revenue Chart ── --}}
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong>💰 Revenue</strong>
          <small class="text-muted">Last {{ $period }} days</small>
        </div>
        <div class="card-body">
          <canvas id="revChart" height="100"></canvas>
        </div>
      </div>
    </div>

    {{-- ── Top Cities ── --}}
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><strong>📍 Top Cities</strong></div>
        <div class="card-body p-0">
          @forelse($topCountries as $city)
          <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <span style="font-size:13px">📍 {{ $city->city }}</span>
            <span class="badge bg-info">{{ $city->count }}</span>
          </div>
          @empty
          <p class="text-muted text-center py-3" style="font-size:13px">No data</p>
          @endforelse
        </div>
      </div>
    </div>

    {{-- ── Subscription Plan Stats ── --}}
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><strong>💎 Subscription Plans</strong></div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>Plan</th>
                <th>Total</th>
                <th>Active</th>
                <th>Revenue</th>
              </tr>
            </thead>
            <tbody>
              @foreach($planStats as $plan)
              <tr>
                <td>
                  <span class="badge bg-{{ $plan->badge_color }}">{{ $plan->name }}</span>
                </td>
                <td style="font-size:13px">{{ $plan->total_subs }}</td>
                <td>
                  <span class="badge bg-success">{{ $plan->active_subs }}</span>
                </td>
                <td style="font-size:13px;font-weight:600">
                  ${{ number_format($plan->active_subs * $plan->price, 2) }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-body pt-0">
          <canvas id="planChart" height="160"></canvas>
        </div>
      </div>
    </div>

    {{-- ── Reports Chart ── --}}
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong>🚨 Reports & Blocks</strong>
          <small class="text-muted">Last 30 days</small>
        </div>
        <div class="card-body">
          <canvas id="reportChart" height="160"></canvas>
          <div class="row mt-3 text-center">
            <div class="col-6">
              <h5 class="mb-0 text-danger">{{ $stats['total_reports'] }}</h5>
              <small class="text-muted">Total Reports</small>
            </div>
            <div class="col-6">
              <h5 class="mb-0 text-secondary">{{ $stats['total_blocked'] }}</h5>
              <small class="text-muted">Blocked Accounts</small>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
</div>

<script>
const regLabels  = @json($regLabels);
const regData    = @json($regData);
const revLabels  = @json($revLabels);
const revData    = @json($revData);
const reportLbls = @json($reportLabels);
const reportData = @json($reportData);
const planNames  = @json($planStats->pluck('name'));
const planActive = @json($planStats->pluck('active_subs'));
const gLabels    = @json($genderStats->keys());
const gData      = @json($genderStats->values());

// ── Registrations Chart ──
new Chart(document.getElementById('regChart'), {
    type: 'line',
    data: {
        labels: regLabels,
        datasets: [{
            label: 'New Users',
            data: regData,
            borderColor: '#7c3aed',
            backgroundColor: 'rgba(124,58,237,.1)',
            fill: true, tension: .4,
            pointBackgroundColor: '#7c3aed',
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

// ── Revenue Chart ──
new Chart(document.getElementById('revChart'), {
    type: 'bar',
    data: {
        labels: revLabels,
        datasets: [{
            label: 'Revenue ($)',
            data: revData,
            backgroundColor: 'rgba(34,197,94,.7)',
            borderColor: '#16a34a',
            borderWidth: 1, borderRadius: 6,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// ── Plan Chart (Doughnut) ──
new Chart(document.getElementById('planChart'), {
    type: 'doughnut',
    data: {
        labels: planNames,
        datasets: [{
            data: planActive,
            backgroundColor: ['#6b7280','#3b82f6','#f59e0b','#8b5cf6'],
            borderWidth: 2,
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// ── Reports Chart ──
new Chart(document.getElementById('reportChart'), {
    type: 'bar',
    data: {
        labels: reportLbls,
        datasets: [{
            label: 'Reports',
            data: reportData,
            backgroundColor: 'rgba(239,68,68,.7)',
            borderRadius: 4,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

// ── Gender Chart ──
new Chart(document.getElementById('genderChart'), {
    type: 'pie',
    data: {
        labels: gLabels,
        datasets: [{
            data: gData,
            backgroundColor: ['#e85d75','#7c3aed','#3b82f6','#6b7280'],
            borderWidth: 2,
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>

@endsection