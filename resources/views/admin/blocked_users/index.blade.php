@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Blocked Users</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Blocked Users</li>
                    </ol>
                </nav>
            </div>
            {{-- Manual Block Button --}}
            <div class="ms-auto">
                <button class="btn btn-danger btn-sm px-4" data-bs-toggle="modal" data-bs-target="#manualBlockModal">
                    <ion-icon name="ban-outline" class="me-1"></ion-icon> Block a User
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <ion-icon name="checkmark-circle-outline" class="me-2"></ion-icon>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Stats --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0" style="color:#7c3aed;">{{ $totalBlocked }}</h4>
                    <small class="text-muted">Total Blocks</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-danger">{{ $totalAdminBanned }}</h4>
                    <small class="text-muted">🚫 Admin Banned</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-warning">{{ $totalUserBlocks }}</h4>
                    <small class="text-muted">👤 User-to-User Blocks</small>
                </div>
            </div>
        </div>

        {{-- Search --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.blocked_users.index') }}"
                      class="d-flex gap-3 align-items-center">
                    <input type="text" name="search" class="form-control form-control-sm"
                           style="max-width:300px;" placeholder="🔍 Search by name or email..."
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm px-4">Search</button>
                    <a href="{{ route('admin.blocked_users.index') }}" class="btn btn-light btn-sm">Reset</a>
                </form>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#adminBanned">
                    🚫 Admin Banned
                    <span class="badge bg-danger ms-1">{{ $totalAdminBanned }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#userBlocks">
                    👤 User-to-User Blocks
                    <span class="badge bg-warning ms-1">{{ $totalUserBlocks }}</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ── TAB 1: Admin Banned Users ── --}}
            <div class="tab-pane fade show active" id="adminBanned">
                <div class="card">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold">Users Banned by Admin</h6>
                        <span class="badge bg-danger">{{ $adminBannedUsers->total() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Ban Reason</th>
                                        <th>Banned At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($adminBannedUsers as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $user->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                     class="rounded-circle" width="36" height="36"
                                                     style="object-fit:cover; border:2px solid #dc2626;">
                                                <div>
                                                    <div class="fw-semibold">{{ $user->name }}</div>
                                                    <span class="badge bg-danger" style="font-size:9px;">🚫 Banned</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $user->block_reason ?? 'No reason provided' }}
                                            </small>
                                        </td>
                                        <td>
                                            <small>{{ $user->blocked_at ? \Carbon\Carbon::parse($user->blocked_at)->format('d M Y') : '—' }}</small>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.blocked_users.unblock', $user) }}"
                                                  onsubmit="return confirm('Unblock {{ $user->name }}?')">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <ion-icon name="checkmark-circle-outline"></ion-icon> Unblock
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <ion-icon name="shield-checkmark-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                            <p class="mt-2">No admin banned users found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($adminBannedUsers->hasPages())
                    <div class="card-footer">
                        {{ $adminBannedUsers->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── TAB 2: User-to-User Blocks ── --}}
            <div class="tab-pane fade" id="userBlocks">
                <div class="card">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold">User-to-User Blocks</h6>
                        <span class="badge bg-warning">{{ $userBlocks->total() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Blocker</th>
                                        <th></th>
                                        <th>Blocked</th>
                                        <th>Blocked At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($userBlocks as $block)
                                    <tr>
                                        <td>{{ $block->id }}</td>

                                        {{-- Blocker --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $block->user->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                     class="rounded-circle" width="36" height="36"
                                                     style="object-fit:cover;">
                                                <div>
                                                    <div class="fw-semibold">{{ $block->user->name ?? '—' }}</div>
                                                    <small class="text-muted">{{ $block->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Arrow --}}
                                        <td class="text-center">
                                            <span style="font-size:18px;">🚫</span>
                                        </td>

                                        {{-- Blocked --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $block->blocked->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                     class="rounded-circle" width="36" height="36"
                                                     style="object-fit:cover; opacity:0.6;">
                                                <div>
                                                    <div class="fw-semibold">{{ $block->blocked->name ?? '—' }}</div>
                                                    <small class="text-muted">{{ $block->blocked->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <small class="text-muted">
                                                {{ $block->created_at->diffForHumans() }}
                                            </small>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <ion-icon name="ban-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                            <p class="mt-2">No user-to-user blocks found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($userBlocks->hasPages())
                    <div class="card-footer">
                        {{ $userBlocks->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ── Manual Block Modal ── --}}
<div class="modal fade" id="manualBlockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🚫 Manually Block a User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.blocked_users.block.search') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Search User by Name or Email</label>
                        <input type="text" name="search_user" id="searchUserInput"
                               class="form-control" placeholder="Type name or email..."
                               autocomplete="off">
                        <div id="searchResults" class="mt-2"></div>
                    </div>
                    <input type="hidden" name="user_id" id="selectedUserId">
                    <div id="selectedUserInfo" class="alert alert-info py-2 d-none"></div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ban Reason <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="text" name="reason" class="form-control"
                               placeholder="e.g. Harassment, Fake profile...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4">🚫 Block User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Live search for user in modal
    let searchTimeout;
    document.getElementById('searchUserInput')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        if (query.length < 2) {
            document.getElementById('searchResults').innerHTML = '';
            return;
        }
        searchTimeout = setTimeout(() => {
            fetch(`{{ route('admin.blocked_users.search') }}?q=${encodeURIComponent(query)}`)
                .then(r => r.json())
                .then(users => {
                    const container = document.getElementById('searchResults');
                    if (users.length === 0) {
                        container.innerHTML = '<small class="text-muted">No users found.</small>';
                        return;
                    }
                    container.innerHTML = users.map(u => `
                        <div class="d-flex align-items-center gap-2 p-2 border rounded mb-1 cursor-pointer"
                             style="cursor:pointer;"
                             onclick="selectUser(${u.id}, '${u.name}', '${u.email}')">
                            <img src="${u.profile_photo ?? '{{ asset('admin/assets/images/avatars/06.png') }}'}"
                                 class="rounded-circle" width="32" height="32" style="object-fit:cover;">
                            <div>
                                <div style="font-size:13px;font-weight:600;">${u.name}</div>
                                <small class="text-muted">${u.email}</small>
                            </div>
                        </div>
                    `).join('');
                });
        }, 400);
    });

    function selectUser(id, name, email) {
        document.getElementById('selectedUserId').value = id;
        document.getElementById('searchResults').innerHTML = '';
        document.getElementById('searchUserInput').value = name;
        const info = document.getElementById('selectedUserInfo');
        info.textContent = `Selected: ${name} (${email})`;
        info.classList.remove('d-none');
    }
</script>

@endsection