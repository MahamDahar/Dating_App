@extends('layouts.user')

@section('usercontent')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width:860px;">
            <div class="card-body p-4 p-lg-5">
                <h4 class="fw-bold mb-1">Verify Photo</h4>
                <p class="text-muted mb-4">Take a live selfie and we will compare it with your main profile photo. Minimum 80% match is required.</p>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-semibold">Main Profile Photo</h6>
                        <div class="border rounded-4 overflow-hidden bg-light d-flex align-items-center justify-content-center" style="aspect-ratio:3/4;">
                            @if($mainPhoto)
                                <img src="{{ asset('storage/'.$mainPhoto->path) }}" alt="Main photo" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <span class="text-muted">Main photo not found</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-semibold">Upload Live Selfie</h6>
                        <form method="POST" action="{{ route('user.verification.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file" class="form-control" name="selfie" accept="image/*" capture="user" required>
                                <small class="text-muted">Use front camera and make sure your face is clear.</small>
                            </div>
                            <button type="submit" class="btn btn-dark w-100">Scan and Verify</button>
                        </form>
                    </div>
                </div>

                @if($latest)
                    <hr class="my-4">
                    <h6 class="fw-semibold mb-3">Last Attempt</h6>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="badge {{ $latest->status === 'approved' ? 'bg-success' : ($latest->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ ucfirst($latest->status) }}
                            </span>
                            @if(!is_null($latest->score))
                                <span class="ms-2 text-muted">Score: {{ number_format((float)$latest->score, 2) }}%</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ optional($latest->created_at)->diffForHumans() }}</small>
                    </div>
                    @if($latest->reason)
                        <p class="text-muted mt-2 mb-0">{{ $latest->reason }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
