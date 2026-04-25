@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
  <div class="page-content">

    <div class="page-breadcrumb d-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3">Subscription Plans</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0 align-items-center">
            <li class="breadcrumb-item">
              <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
            </li>
            <li class="breadcrumb-item">
              <a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a>
            </li>
            <li class="breadcrumb-item active">Plans</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-primary px-4">
          <ion-icon name="add-outline" class="me-1"></ion-icon> Add Plan
        </a>
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

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold">All Plans</h6>
        <span class="badge bg-primary">{{ $plans->total() }} Total</span>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Price</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Featured</th>
                <th>Sort</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($plans as $plan)
                <tr>
                  <td style="font-size:12px;color:#888">{{ $plan->id }}</td>
                  <td style="font-weight:800">{{ $plan->name }}</td>
                  <td style="font-size:12px;color:#666;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                    {{ $plan->slug }}
                  </td>
                  <td style="font-weight:800">
                    ${{ number_format((float)$plan->price, 2) }}
                  </td>
                  <td>{{ (int)$plan->duration_days }} days</td>
                  <td>
                    <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-secondary' }}">
                      {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td>
                    <span class="badge {{ $plan->is_featured ? 'bg-primary' : 'bg-secondary' }}">
                      {{ $plan->is_featured ? 'Yes' : 'No' }}
                    </span>
                  </td>
                  <td>{{ (int)$plan->sort_order }}</td>
                  <td>
                    <a href="{{ route('admin.subscriptions.plans.edit', $plan->id) }}" class="btn btn-sm btn-warning">
                      <ion-icon name="create-outline"></ion-icon> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.subscriptions.plans.destroy', $plan->id) }}"
                          class="d-inline" onsubmit="return confirm('Delete this plan? This will delete related subscriptions too.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">
                        <ion-icon name="trash-outline"></ion-icon> Delete
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="9" class="text-center py-5 text-muted">No plans found</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $plans->links() }}
        </div>
      </div>
    </div>

  </div>
</div>

@endsection

