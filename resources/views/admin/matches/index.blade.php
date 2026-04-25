@extends('layouts.admin')
@section('admincontent')

<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Page Header --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Matches</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active">Matches</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0" style="color:#7c3aed;">{{ $totalMatches }}</h4>
                    <small class="text-muted">Total Matches</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-success">{{ $proposalMatches }}</h4>
                    <small class="text-muted">💍 Accepted Proposals</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center py-3">
                    <h4 class="fw-bold mb-0 text-danger">{{ $likeMatches }}</h4>
                    <small class="text-muted">❤️ Mutual Likes</small>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#proposals">
                    💍 Accepted Proposals
                    <span class="badge bg-success ms-1">{{ $proposalMatches }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#likes">
                    ❤️ Mutual Likes
                    <span class="badge bg-danger ms-1">{{ $likeMatches }}</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ── ACCEPTED PROPOSALS ── --}}
            <div class="tab-pane fade show active" id="proposals">
                <div class="card">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold">Matches via Accepted Proposals</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Sender</th>
                                        <th>Receiver</th>
                                        <th>Message</th>
                                        <th>Accepted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($acceptedProposals as $proposal)
                                    <tr>
                                        <td>{{ $proposal->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $proposal->sender->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                     class="rounded-circle" width="36" height="36"
                                                     style="object-fit:cover;">
                                                <div>
                                                    <div class="fw-semibold">{{ $proposal->sender->name }}</div>
                                                    <small class="text-muted">{{ $proposal->sender->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $proposal->receiver->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                     class="rounded-circle" width="36" height="36"
                                                     style="object-fit:cover;">
                                                <div>
                                                    <div class="fw-semibold">{{ $proposal->receiver->name }}</div>
                                                    <small class="text-muted">{{ $proposal->receiver->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted small">{{ Str::limit($proposal->message, 50) }}</span>
                                        </td>
                                        <td>{{ $proposal->accepted_at?->format('d M Y') ?? '—' }}</td>
                                        <td>
                                            <span class="badge bg-success">✅ Matched</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <ion-icon name="heart-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                            <p class="mt-2">No accepted proposals yet.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($acceptedProposals->hasPages())
                    <div class="card-footer">
                        {{ $acceptedProposals->links() }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── MUTUAL LIKES ── --}}
            <div class="tab-pane fade" id="likes">
                <div class="card">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-bold">Matches via Mutual Likes</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>User 1</th>
                                        <th>User 2</th>
                                        <th>Liked At</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mutualLikes as $index => $like)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $like->liker->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                     class="rounded-circle" width="36" height="36"
                                                     style="object-fit:cover;">
                                                <div>
                                                    <div class="fw-semibold">{{ $like->liker->name }}</div>
                                                    <small class="text-muted">{{ $like->liker->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $like->liked->profile_photo ?? asset('admin/assets/images/avatars/06.png') }}"
                                                     class="rounded-circle" width="36" height="36"
                                                     style="object-fit:cover;">
                                                <div>
                                                    <div class="fw-semibold">{{ $like->liked->name }}</div>
                                                    <small class="text-muted">{{ $like->liked->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $like->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-danger">❤️ Mutual Like</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <ion-icon name="heart-dislike-outline" style="font-size:48px;color:#d1d5db;"></ion-icon>
                                            <p class="mt-2">No mutual likes yet.</p>
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
    </div>
</div>

@endsection