@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
<div class="page-content">

  {{-- Breadcrumb --}}
  <div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Marketing</div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0 align-items-center">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a></li>
          <li class="breadcrumb-item active">Marketing</li>
        </ol>
      </nav>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif

  {{-- Stats --}}
  <div class="row row-cols-2 row-cols-lg-4 g-3 mb-4">
    <div class="col"><div class="card radius-10 border-0 shadow-sm text-center py-3"><h4 class="mb-0 text-primary">{{ number_format($stats['emails_sent']) }}</h4><small class="text-muted">Emails Sent</small></div></div>
    <div class="col"><div class="card radius-10 border-0 shadow-sm text-center py-3"><h4 class="mb-0 text-success">{{ number_format($stats['push_sent']) }}</h4><small class="text-muted">Push Sent</small></div></div>
    <div class="col"><div class="card radius-10 border-0 shadow-sm text-center py-3"><h4 class="mb-0 text-warning">{{ $stats['promo_codes'] }}</h4><small class="text-muted">Active Promos</small></div></div>
    <div class="col"><div class="card radius-10 border-0 shadow-sm text-center py-3"><h4 class="mb-0 text-info">{{ $stats['banners'] }}</h4><small class="text-muted">Active Banners</small></div></div>
  </div>

  {{-- Tabs --}}
  <ul class="nav nav-tabs mb-4" id="marketingTabs">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#emailTab">📧 Email Campaigns</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pushTab">🔔 Push Notifications</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#promoTab">🎟 Promo Codes</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#bannerTab">📢 Announcements</a></li>
  </ul>

  <div class="tab-content">

    {{-- ═══════════════════════
         EMAIL CAMPAIGNS TAB
    ═══════════════════════ --}}
    <div class="tab-pane fade show active" id="emailTab">
      <div class="row g-4">

        {{-- Send Email Form --}}
        <div class="col-lg-5">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>📧 Send Email Campaign</strong></div>
            <div class="card-body">
              <form method="POST" action="{{ route('admin.marketing.send-email') }}">
                @csrf
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Campaign Title</label>
                  <input type="text" name="title" class="form-control" placeholder="e.g. Ramadan Special" required>
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Email Subject</label>
                  <input type="text" name="subject" class="form-control" placeholder="Email subject line" required>
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Target Audience</label>
                  <select name="target" class="form-select">
                    <option value="all">All Users</option>
                    <option value="premium">Premium Users Only</option>
                    <option value="free">Free Users Only</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Email Body (HTML allowed)</label>
                  <textarea name="body" class="form-control" rows="6" placeholder="Write your email content..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100"
                  onclick="return confirm('Send this email to all targeted users?')">
                  📧 Send Campaign
                </button>
              </form>
            </div>
          </div>
        </div>

        {{-- Email History --}}
        <div class="col-lg-7">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>📋 Campaign History</strong></div>
            <div class="card-body p-0">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr><th>Title</th><th>Target</th><th>Sent To</th><th>Date</th></tr>
                </thead>
                <tbody>
                  @forelse($emailCampaigns as $c)
                  <tr>
                    <td style="font-size:13px;font-weight:600">{{ $c->title }}</td>
                    <td><span class="badge bg-info">{{ ucfirst($c->target) }}</span></td>
                    <td style="font-size:13px">{{ number_format($c->sent_count) }} users</td>
                    <td style="font-size:12px;color:#888">{{ $c->sent_at?->format('d M Y') ?? '—' }}</td>
                  </tr>
                  @empty
                  <tr><td colspan="4" class="text-center py-3 text-muted">No campaigns yet</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- ═══════════════════════
         PUSH NOTIFICATIONS TAB
    ═══════════════════════ --}}
    <div class="tab-pane fade" id="pushTab">
      <div class="row g-4">

        <div class="col-lg-5">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>🔔 Send Push Notification</strong></div>
            <div class="card-body">
              <form method="POST" action="{{ route('admin.marketing.send-push') }}">
                @csrf
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Title</label>
                  <input type="text" name="title" class="form-control" placeholder="Notification title" required>
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Message</label>
                  <textarea name="message" class="form-control" rows="3" placeholder="Notification message..." required></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Target</label>
                  <select name="target" class="form-select">
                    <option value="all">All Users</option>
                    <option value="premium">Premium Only</option>
                    <option value="free">Free Only</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-success w-100"
                  onclick="return confirm('Send push notification to targeted users?')">
                  🔔 Send Notification
                </button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-7">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>📋 Push History</strong></div>
            <div class="card-body p-0">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr><th>Title</th><th>Target</th><th>Sent To</th><th>Date</th></tr>
                </thead>
                <tbody>
                  @forelse($pushCampaigns as $p)
                  <tr>
                    <td style="font-size:13px;font-weight:600">{{ $p->title }}</td>
                    <td><span class="badge bg-success">{{ ucfirst($p->target) }}</span></td>
                    <td style="font-size:13px">{{ number_format($p->sent_count) }} users</td>
                    <td style="font-size:12px;color:#888">{{ $p->sent_at?->format('d M Y') ?? '—' }}</td>
                  </tr>
                  @empty
                  <tr><td colspan="4" class="text-center py-3 text-muted">No push campaigns yet</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- ═══════════════════════
         PROMO CODES TAB
    ═══════════════════════ --}}
    <div class="tab-pane fade" id="promoTab">
      <div class="row g-4">

        <div class="col-lg-4">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>🎟 Create Promo Code</strong></div>
            <div class="card-body">
              <form method="POST" action="{{ route('admin.marketing.store-promo') }}">
                @csrf
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Code</label>
                  <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. SAVE20" required maxlength="20">
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Description</label>
                  <input type="text" name="description" class="form-control" placeholder="e.g. 20% off for new users">
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Discount Type</label>
                  <select name="discount_type" class="form-select">
                    <option value="percentage">Percentage (%)</option>
                    <option value="fixed">Fixed Amount ($)</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Discount Value</label>
                  <input type="number" name="discount_value" class="form-control" step="0.01" min="1" placeholder="e.g. 20" required>
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Max Uses <small class="text-muted">(optional)</small></label>
                  <input type="number" name="max_uses" class="form-control" min="1" placeholder="Leave empty for unlimited">
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Expires At <small class="text-muted">(optional)</small></label>
                  <input type="date" name="expires_at" class="form-control">
                </div>
                <button type="submit" class="btn btn-warning w-100">🎟 Create Code</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-8">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>📋 Promo Codes</strong></div>
            <div class="card-body p-0">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr><th>Code</th><th>Discount</th><th>Uses</th><th>Expires</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                  @forelse($promoCodes as $promo)
                  <tr>
                    <td><code style="font-size:13px;font-weight:700">{{ $promo->code }}</code></td>
                    <td style="font-size:13px">
                      {{ $promo->discount_type === 'percentage' ? $promo->discount_value.'%' : '$'.$promo->discount_value }}
                    </td>
                    <td style="font-size:13px">
                      {{ $promo->used_count }}{{ $promo->max_uses ? '/'.$promo->max_uses : '/∞' }}
                    </td>
                    <td style="font-size:12px;color:#888">
                      {{ $promo->expires_at ? $promo->expires_at->format('d M Y') : '∞ Never' }}
                    </td>
                    <td>
                      <span class="badge bg-{{ $promo->is_active ? 'success' : 'secondary' }}">
                        {{ $promo->is_active ? 'Active' : 'Disabled' }}
                      </span>
                    </td>
                    <td>
                      <div class="d-flex gap-1">
                        <form method="POST" action="{{ route('admin.marketing.toggle-promo', $promo->id) }}">
                          @csrf @method('PATCH')
                          <button type="submit" class="btn btn-xs btn-outline-{{ $promo->is_active ? 'warning' : 'success' }}" style="font-size:11px;padding:2px 8px">
                            {{ $promo->is_active ? 'Disable' : 'Enable' }}
                          </button>
                        </form>
                        <form method="POST" action="{{ route('admin.marketing.delete-promo', $promo->id) }}"
                          onsubmit="return confirm('Delete this promo code?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="btn btn-xs btn-outline-danger" style="font-size:11px;padding:2px 8px">Del</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="6" class="text-center py-3 text-muted">No promo codes yet</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- ═══════════════════════
         ANNOUNCEMENTS TAB
    ═══════════════════════ --}}
    <div class="tab-pane fade" id="bannerTab">
      <div class="row g-4">

        <div class="col-lg-5">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>📢 Create Announcement</strong></div>
            <div class="card-body">
              <form method="POST" action="{{ route('admin.marketing.store-announcement') }}">
                @csrf
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Title</label>
                  <input type="text" name="title" class="form-control" placeholder="Announcement title" required>
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Message</label>
                  <textarea name="message" class="form-control" rows="3" placeholder="Announcement message..." required></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Type</label>
                  <select name="type" class="form-select">
                    <option value="info">ℹ️ Info (Blue)</option>
                    <option value="success">✅ Success (Green)</option>
                    <option value="warning">⚠️ Warning (Yellow)</option>
                    <option value="danger">🚨 Danger (Red)</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label" style="font-size:13px;font-weight:600">Target</label>
                  <select name="target" class="form-select">
                    <option value="all">All Users</option>
                    <option value="premium">Premium Only</option>
                    <option value="free">Free Only</option>
                  </select>
                </div>
                <div class="row g-2 mb-3">
                  <div class="col-6">
                    <label class="form-label" style="font-size:12px;font-weight:600">Start Date</label>
                    <input type="datetime-local" name="starts_at" class="form-control form-control-sm">
                  </div>
                  <div class="col-6">
                    <label class="form-label" style="font-size:12px;font-weight:600">End Date</label>
                    <input type="datetime-local" name="ends_at" class="form-control form-control-sm">
                  </div>
                </div>
                <button type="submit" class="btn btn-info w-100 text-white">📢 Publish Announcement</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-7">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>📋 Announcements</strong></div>
            <div class="card-body p-0">
              @forelse($announcements as $ann)
              <div class="d-flex align-items-start gap-3 p-3 border-bottom">
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-{{ $ann->type }}">{{ ucfirst($ann->type) }}</span>
                    <span class="badge bg-secondary" style="font-size:10px">{{ ucfirst($ann->target) }}</span>
                    @if(!$ann->is_active)<span class="badge bg-dark" style="font-size:10px">Inactive</span>@endif
                  </div>
                  <div style="font-weight:600;font-size:13px">{{ $ann->title }}</div>
                  <div style="font-size:12px;color:#666">{{ Str::limit($ann->message, 80) }}</div>
                  <div style="font-size:11px;color:#aaa;margin-top:3px">{{ $ann->created_at->diffForHumans() }}</div>
                </div>
                <div class="d-flex flex-column gap-1">
                  <form method="POST" action="{{ route('admin.marketing.toggle-announcement', $ann->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-xs btn-outline-{{ $ann->is_active ? 'warning' : 'success' }}" style="font-size:11px;padding:2px 8px;width:60px">
                      {{ $ann->is_active ? 'Hide' : 'Show' }}
                    </button>
                  </form>
                  <form method="POST" action="{{ route('admin.marketing.delete-announcement', $ann->id) }}"
                    onsubmit="return confirm('Delete this announcement?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-outline-danger" style="font-size:11px;padding:2px 8px;width:60px">Delete</button>
                  </form>
                </div>
              </div>
              @empty
              <div class="text-center py-4 text-muted">No announcements yet</div>
              @endforelse
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
</div>
@endsection