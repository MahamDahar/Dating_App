@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
<div class="page-content">

  {{-- Breadcrumb --}}
  <div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Edit Role Permissions</div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0 align-items-center">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.management.index') }}">Admin Management</a></li>
          <li class="breadcrumb-item active">{{ $role->display_name }}</li>
        </ol>
      </nav>
    </div>
    <div class="ms-auto">
      <a href="{{ route('admin.management.index') }}" class="btn btn-sm btn-outline-secondary">← Back</a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif

  {{-- Role Header --}}
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex align-items-center gap-3">
      <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#e85d75,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:22px">🎭</div>
      <div>
        <h5 class="mb-0">{{ $role->display_name }}
          <span class="badge bg-{{ $role->color }} ms-2">{{ $role->name }}</span>
        </h5>
        <small class="text-muted">{{ $role->description }}</small>
      </div>
      <div class="ms-auto">
        <button class="btn btn-sm btn-outline-success" id="selectAll">✅ Select All</button>
        <button class="btn btn-sm btn-outline-danger ms-1" id="clearAll">❌ Clear All</button>
      </div>
    </div>
  </div>

  {{-- Permissions Form --}}
  <form method="POST" action="{{ route('admin.management.update-role', $role->id) }}">
    @csrf @method('PUT')

    <div class="row g-3">
      @foreach($permissions as $group => $perms)
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <strong style="font-size:13px">
              @php
                $icons = [
                  'Dashboard'        => '📊',
                  'User Management'  => '👥',
                  'Moderation'       => '🛡️',
                  'Finance'          => '💰',
                  'Engagement'       => '📣',
                  'System'           => '⚙️',
                ];
              @endphp
              {{ $icons[$group] ?? '📌' }} {{ $group }}
            </strong>
            {{-- Group select all --}}
            <button type="button" class="btn btn-xs btn-outline-secondary group-select-all"
              style="font-size:11px;padding:2px 8px"
              data-group="{{ Str::slug($group) }}">
              Select All
            </button>
          </div>
          <div class="card-body">
            @foreach($perms as $perm)
            <div class="form-check mb-2">
              <input class="form-check-input perm-checkbox group-{{ Str::slug($group) }}"
                type="checkbox"
                name="permissions[]"
                value="{{ $perm->id }}"
                id="perm_{{ $perm->id }}"
                {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}>
              <label class="form-check-label" for="perm_{{ $perm->id }}" style="font-size:13px">
                {{ $perm->display_name }}
                <small class="text-muted d-block" style="font-size:11px">{{ $perm->name }}</small>
              </label>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="mt-4 d-flex gap-2">
      <button type="submit" class="btn btn-primary">
        💾 Save Permissions
      </button>
      <a href="{{ route('admin.management.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>

  </form>

</div>
</div>

<script>
// Select All
document.getElementById('selectAll')?.addEventListener('click', () => {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = true);
});

// Clear All
document.getElementById('clearAll')?.addEventListener('click', () => {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
});

// Group Select All
document.querySelectorAll('.group-select-all').forEach(btn => {
    btn.addEventListener('click', () => {
        const group = btn.dataset.group;
        const boxes = document.querySelectorAll('.group-' + group);
        const allChecked = [...boxes].every(cb => cb.checked);
        boxes.forEach(cb => cb.checked = !allChecked);
        btn.textContent = allChecked ? 'Select All' : 'Clear All';
    });
});
</script>

@endsection