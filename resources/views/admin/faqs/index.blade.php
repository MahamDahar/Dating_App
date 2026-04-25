@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
      <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">FAQs Management</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">FAQs</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary px-4">
                    <ion-icon name="add-outline" class="me-1"></ion-icon> Add New FAQ
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold">All FAQs</h6>
                <span class="badge bg-primary">{{ $faqs->count() }} Total</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Question</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $faq)
                            <tr>
                                <td>{{ $faq->id }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $faq->category }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($faq->question, 60) }}</td>
                                <td>{{ $faq->order }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.faqs.toggle', $faq) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-{{ $faq->is_active ? 'success' : 'secondary' }}">
                                            {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm btn-warning">
                                        <ion-icon name="create-outline"></ion-icon> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                                          class="d-inline" onsubmit="return confirm('Delete this FAQ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <ion-icon name="trash-outline"></ion-icon> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <ion-icon name="help-circle-outline" style="font-size:48px;"></ion-icon>
                                    <p class="mt-2 mb-3">No FAQs added yet.</p>
                                    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary btn-sm px-4">
                                        + Add First FAQ
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection