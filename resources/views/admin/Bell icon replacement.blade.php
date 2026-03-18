{{-- 
  YEH POORA SECTION admin.blade.php mein replace karo
  Purana bell icon dropdown wala section:
  <li class="nav-item dropdown dropdown-large"> ... </li>
  Ko yeh se replace karo:
--}}

<li class="nav-item dropdown dropdown-large">
  <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
    <div class="position-relative">
      @php
        $unreadCount = \App\Models\AdminNotification::unreadCount();
      @endphp
      @if($unreadCount > 0)
        <span class="notify-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
      @endif
      <ion-icon name="notifications-outline"></ion-icon>
    </div>
  </a>
  <div class="dropdown-menu dropdown-menu-end" style="min-width:360px">
    <a href="javascript:;">
      <div class="msg-header d-flex justify-content-between align-items-center">
        <p class="msg-header-title mb-0">Notifications</p>
        <p class="msg-header-clear ms-auto mb-0" id="bellMarkAllRead" style="cursor:pointer">
          Mark all as read
        </p>
      </div>
    </a>
    <div class="header-notifications-list">
      @forelse(\App\Models\AdminNotification::latestUnread(8) as $notif)
      <a class="dropdown-item" href="{{ $notif->url ?? 'javascript:;' }}">
        <div class="d-flex align-items-center">
          <div class="notify text-{{ $notif->color }}">
            <ion-icon name="{{ $notif->icon }}"></ion-icon>
          </div>
          <div class="flex-grow-1">
            <h6 class="msg-name">
              {{ $notif->title }}
              <span class="msg-time float-end">{{ $notif->created_at->diffForHumans() }}</span>
            </h6>
            <p class="msg-info">{{ Str::limit($notif->message, 60) }}</p>
          </div>
        </div>
      </a>
      @empty
      <div class="text-center py-3 text-muted" style="font-size:13px">
        <ion-icon name="notifications-off-outline" style="font-size:28px;display:block;margin:0 auto 6px"></ion-icon>
        No new notifications
      </div>
      @endforelse
    </div>
    <a href="{{ route('admin.notifications.index') }}">
      <div class="text-center msg-footer">View All Notifications</div>
    </a>
  </div>
</li>

{{-- Bell mark all read script — add karo JS section mein --}}
<script>
document.getElementById('bellMarkAllRead')?.addEventListener('click', function(e) {
    e.preventDefault();
    fetch('{{ route("admin.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(() => location.reload());
});
</script>