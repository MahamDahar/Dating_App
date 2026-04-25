@extends('layouts.user')
@section('usercontent')
<div class="page-content-wrapper">
      <div class="page-content">
<div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Privacy & Terms</div>
</div>

<div class="row">
    <div class="col-lg-9 mx-auto">

        {{-- Sticky Tab Navigation --}}
        <ul class="nav nav-pills mb-4 flex-wrap gap-2">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="pill" href="#privacy">
                    🔒 Privacy Policy
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#terms">
                    📄 Terms & Conditions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#guidelines">
                    👥 Community Guidelines
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#cookies">
                    🍪 Cookie Policy
                </a>
            </li>
        </ul>

        <div class="tab-content">

            @foreach([
                'privacy'    => 'privacy_policy',
                'terms'      => 'terms_conditions',
                'guidelines' => 'community_guidelines',
                'cookies'    => 'cookie_policy',
            ] as $tab => $key)

            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $tab }}">
                @if(isset($pages[$key]))
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">{{ $pages[$key]->title }}</h5>
                        <small class="text-muted">
                            Last updated: {{ $pages[$key]->last_updated_at?->format('d M Y') ?? 'N/A' }}
                        </small>
                    </div>
                    <div class="card-body">
                        <div style="white-space: pre-line; line-height: 1.8;">
                            {{ $pages[$key]->content }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @endforeach

        </div>

    </div>
</div>
</div>
</div>

@endsection
```

---

## ✅ Summary Flow
```
Admin opens Privacy & Terms page
        ↓
Sees 4 tabs: Privacy / Terms / Guidelines / Cookies
        ↓
Clicks a tab → edits content → clicks Save
        ↓
Content saved in DB with last_updated_at timestamp
        ↓
User opens Privacy & Terms page
        ↓
Sees same 4 tabs with latest content