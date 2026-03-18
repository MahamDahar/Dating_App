@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
<div class="page-content">

  {{-- Breadcrumb --}}
  <div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Notifications</div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0 align-items-center">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a></li>
          <li class="breadcrumb-item active">Notifications</li>
        </ol>
      </nav>
    </div>
    <div class="ms-auto d-flex gap-2">
      {{-- Mark all read --}}
      <button class="btn btn-sm btn-outline-primary" id="markAllReadBtn">
        <ion-icon name="checkmark-done-outline"></ion-icon> Mark All Read
      </button>
      {{-- Clear all --}}
      <form method="POST" action="{{ route('admin.notifications.clear') }}"
        onsubmit="return confirm('Clear all notifications?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger">
          <ion-icon name="trash-outline"></ion-icon> Clear All
        </button>
      </form>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Stats row --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="card radius-10 border-0 shadow-sm text-center py-3">
        <h4 class="mb-0 text-primary">{{ $notifications->total() }}</h4>
        <small class="text-muted">Total</small>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card radius-10 border-0 shadow-sm text-center py-3">
        <h4 class="mb-0 text-danger">{{ $unreadCount }}</h4>
        <small class="text-muted">Unread</small>
      </div>
    </div>
  </div>

  {{-- Notifications List --}}
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
      <strong>🔔 All Notifications</strong>
    </div>
    <div class="card-body p-0">
      @forelse($notifications as $n)
      <div class="d-flex align-items-start gap-3 p-3 border-bottom notification-item {{ $n->is_read ? '' : 'bg-light' }}"
           data-id="{{ $n->id }}">

        {{-- Icon --}}
        <div class="notify-icon rounded-circle d-flex align-items-center justify-content-center flex-shrink-0
          {{ $n->color === 'danger'  ? 'bg-danger'  :
            ($n->color === 'success' ? 'bg-success' :
            ($n->color === 'warning' ? 'bg-warning' :
            ($n->color === 'info'    ? 'bg-info'    : 'bg-primary'))) }}
          text-white"
          style="width:42px;height:42px;font-size:18px;">
          <ion-icon name="{{ $n->icon }}"></ion-icon>
        </div>

        {{-- Content --}}
        <div class="flex-grow-1">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h6 class="mb-1" style="font-size:14px;font-weight:600">
                {{ $n->title }}
                @if(!$n->is_read)
                  <span class="badge bg-danger ms-1" style="font-size:10px">New</span>
                @endif
              </h6>
              <p class="mb-0 text-muted" style="font-size:13px">{{ $n->message }}</p>
              <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
            </div>
            <div class="d-flex gap-2 ms-2">
              @if($n->url)
              <a href="{{ $n->url }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px">
                View
              </a>
              @endif
              <form method="POST" action="{{ route('admin.notifications.destroy', $n->id) }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:12px">
                  <ion-icon name="trash-outline"></ion-icon>
                </button>
              </form>
            </div>
          </div>
        </div>

      </div>
      @empty
      <div class="text-center py-5 text-muted">
        <ion-icon name="notifications-off-outline" style="font-size:48px;opacity:.3"></ion-icon>
        <p class="mt-2">No notifications yet</p>
      </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="card-footer bg-white">
      {{ $notifications->links() }}
    </div>
    @endif
  </div>

</div>
</div>

<script>
// Mark all read via AJAX
document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
    fetch('{{ route("admin.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(() => {
        // Remove all "New" badges and bg-light
        document.querySelectorAll('.notification-item').forEach(el => {
            el.classList.remove('bg-light');
            el.querySelector('.badge')?.remove();
        });
        // Update bell count to 0
        const badge = document.querySelector('.notify-badge');
        if (badge) badge.textContent = '0';
    });
});
</script>

@endsection