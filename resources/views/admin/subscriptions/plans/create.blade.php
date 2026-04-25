@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
  <div class="page-content">

    <div class="page-breadcrumb d-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3">Create Plan</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0 align-items-center">
            <li class="breadcrumb-item">
              <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
            </li>
            <li class="breadcrumb-item">
              <a href="{{ route('admin.subscriptions.plans.index') }}">Plans</a>
            </li>
            <li class="breadcrumb-item active">Create</li>
          </ol>
        </nav>
      </div>
    </div>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white">
        <strong>Subscription Plan Details</strong>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.subscriptions.plans.store') }}">
          @csrf

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Name</label>
              <input name="name" class="form-control" value="{{ old('name') }}" required />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Slug</label>
              <input name="slug" class="form-control" value="{{ old('slug') }}" required />
            </div>
            <div class="col-12">
              <label class="form-label fw-bold">Description</label>
              <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Price</label>
              <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', 0) }}" required />
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Duration Days</label>
              <input type="number" step="1" min="0" name="duration_days" class="form-control" value="{{ old('duration_days', 30) }}" required />
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Sort Order</label>
              <input type="number" step="1" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" required />
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Badge Color</label>
              <input name="badge_color" class="form-control" value="{{ old('badge_color', 'secondary') }}" />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Badge Icon</label>
              <input name="badge_icon" class="form-control" value="{{ old('badge_icon', 'star-outline') }}" />
            </div>

            <div class="col-md-6">
              <input type="hidden" name="is_active" value="0" />
              <label class="form-label fw-bold">Active</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} />
                <label class="form-check-label">Plan available</label>
              </div>
            </div>
            <div class="col-md-6">
              <input type="hidden" name="is_featured" value="0" />
              <label class="form-label fw-bold">Featured</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} />
                <label class="form-check-label">Show as popular</label>
              </div>
            </div>

            <div class="col-12">
              <label class="form-label fw-bold">Features (JSON array or comma/newline separated)</label>
              <textarea name="features" class="form-control" rows="4" placeholder='["Feature 1","Feature 2"] or "Feature 1, Feature 2"'>{{ old('features') }}</textarea>
              <div class="text-muted" style="font-size:12px;margin-top:6px">
                User premium page pe features list banegi.
              </div>
            </div>

            <div class="col-12">
              <button class="btn btn-primary">Create Plan</button>
              <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-outline-secondary ms-2">Back</a>
            </div>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>

@endsection

