@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
  <div class="page-content">

    <div class="page-breadcrumb d-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3">Edit Plan</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0 align-items-center">
            <li class="breadcrumb-item">
              <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
            </li>
            <li class="breadcrumb-item">
              <a href="{{ route('admin.subscriptions.plans.index') }}">Plans</a>
            </li>
            <li class="breadcrumb-item active">{{ $plan->name }}</li>
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

    @php
      $featuresValue = $plan->features;
      if (is_array($featuresValue)) {
        $featuresValue = json_encode($featuresValue);
      }
      $featuresValue = $featuresValue ?? '';
    @endphp

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white">
        <strong>Plan Details</strong>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.subscriptions.plans.update', $plan->id) }}">
          @csrf
          @method('PUT')

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Name</label>
              <input name="name" class="form-control" value="{{ old('name', $plan->name) }}" required />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Slug</label>
              <input name="slug" class="form-control" value="{{ old('slug', $plan->slug) }}" required />
            </div>

            <div class="col-12">
              <label class="form-label fw-bold">Description</label>
              <textarea name="description" class="form-control" rows="3">{{ old('description', $plan->description) }}</textarea>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-bold">Price</label>
              <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $plan->price) }}" required />
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Duration Days</label>
              <input type="number" step="1" min="0" name="duration_days" class="form-control" value="{{ old('duration_days', $plan->duration_days) }}" required />
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Sort Order</label>
              <input type="number" step="1" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $plan->sort_order) }}" required />
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Badge Color</label>
              <input name="badge_color" class="form-control" value="{{ old('badge_color', $plan->badge_color) }}" />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Badge Icon</label>
              <input name="badge_icon" class="form-control" value="{{ old('badge_icon', $plan->badge_icon) }}" />
            </div>

            <div class="col-md-6">
              <input type="hidden" name="is_active" value="0" />
              <label class="form-label fw-bold">Active</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} />
                <label class="form-check-label">Visible to users</label>
              </div>
            </div>
            <div class="col-md-6">
              <input type="hidden" name="is_featured" value="0" />
              <label class="form-label fw-bold">Featured</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured', $plan->is_featured) ? 'checked' : '' }} />
                <label class="form-check-label">Show as popular</label>
              </div>
            </div>

            <div class="col-12">
              <label class="form-label fw-bold">Features</label>
              <textarea name="features" class="form-control" rows="4">{{ old('features', $featuresValue) }}</textarea>
              <div class="text-muted" style="font-size:12px;margin-top:6px">
                Accepts JSON array or comma/newline separated list.
              </div>
            </div>

            <div class="col-12 d-flex gap-2">
              <button class="btn btn-primary">Save Changes</button>
              <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

@endsection

