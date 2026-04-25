@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
<div class="page-content">

  {{-- Breadcrumb --}}
  <div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Admin Management</div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0 align-items-center">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a></li>
          <li class="breadcrumb-item active">Admin Management</li>
        </ol>
      </nav>
    </div>
    {{-- Add new admin button --}}
    <div class="ms-auto">
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAdminModal">
        <ion-icon name="person-add-outline"></ion-icon> Add Admin
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif

  <div class="row g-4">

    {{-- ── LEFT: Roles ── --}}
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><strong>🎭 Roles</strong></div>
        <div class="card-body p-0">
          @foreach($roles as $role)
          <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
            <div>
              <span class="badge bg-{{ $role->color }} me-2">{{ $role->display_name }}</span>
              <small class="text-muted">{{ $role->users_count }} admin(s)</small>
              @if($role->description)
                <div style="font-size:12px;color:#888;margin-top:2px">{{ $role->description }}</div>
              @endif
            </div>
            @if($role->name !== 'super_admin')
            <a href="{{ route('admin.management.edit-role', $role->id) }}"
               class="btn btn-sm btn-outline-primary">
              <ion-icon name="settings-outline"></ion-icon> Permissions
            </a>
            @else
            <span class="badge bg-secondary">Full Access</span>
            @endif
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- ── RIGHT: Admin Users ── --}}
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><strong>👥 Admin Users</strong></div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($admins as $admin)
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#e85d75,#7c3aed);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0">
                      {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                    <span style="font-weight:600;font-size:13px">{{ $admin->name }}</span>
                  </div>
                </td>
                <td style="font-size:13px;color:#666">{{ $admin->email }}</td>
                <td>
                  @if($admin->roleModel)
                    <span class="badge bg-{{ $admin->roleModel->color }}">{{ $admin->roleModel->display_name }}</span>
                  @else
                    <span class="badge bg-secondary">No Role</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-2 align-items-center">
                    {{-- Change Role --}}
                    <form method="POST" action="{{ route('admin.management.assign-role') }}" class="d-flex gap-1">
                      @csrf
                      <input type="hidden" name="user_id" value="{{ $admin->id }}">
                      <select name="role_id" class="form-select form-select-sm" style="width:130px;font-size:12px">
                        <option value="">-- Role --</option>
                        @foreach($roles as $role)
                          <option value="{{ $role->id }}" {{ $admin->role_id == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name }}
                          </option>
                        @endforeach
                      </select>
                      <button type="submit" class="btn btn-sm btn-outline-success" title="Assign">✓</button>
                    </form>

                    {{-- Remove Admin --}}
                    @if($admin->roleModel?->name !== 'super_admin')
                    <form method="POST" action="{{ route('admin.management.remove', $admin->id) }}"
                      onsubmit="return confirm('Remove admin access for {{ $admin->name }}?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove">
                        <ion-icon name="person-remove-outline"></ion-icon>
                      </button>
                    </form>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center py-4 text-muted">No admin users found</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
</div>

{{-- ── Add Admin Modal ── --}}
<div class="modal fade" id="addAdminModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.management.create') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" style="font-size:13px;font-weight:600">Full Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px;font-weight:600">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px;font-weight:600">Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:13px;font-weight:600">Role</label>
            <select name="role_id" class="form-select" required>
              <option value="">-- Select Role --</option>
              @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Create Admin</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection