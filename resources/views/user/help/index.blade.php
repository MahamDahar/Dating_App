@extends('layouts.user')
@section('usercontent')
 <!-- start page content wrapper-->
    <div class="page-content-wrapper">
        <!-- start page content-->
        <div class="page-content">
<div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Help & FAQ</div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">

        {{-- Search Bar --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="input-group">
                    <span class="input-group-text"><ion-icon name="search-outline"></ion-icon></span>
                    <input type="text" id="faqSearch" class="form-control" placeholder="Search your question...">
                </div>
            </div>
        </div>

        {{-- FAQ List grouped by category --}}
        @forelse($faqs as $category => $items)
        <div class="card mb-3 faq-category">
            <div class="card-header">
                <h6 class="mb-0 fw-bold">
                    <ion-icon name="help-circle-outline"></ion-icon>
                    {{ $category }}
                </h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush faq-list">
                    @foreach($items as $faq)
                    <li class="list-group-item faq-item">
                        <p class="fw-semibold mb-1 faq-question">{{ $faq->question }}</p>
                        <p class="text-muted mb-0 small faq-answer">{{ $faq->answer }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <ion-icon name="help-buoy-outline" style="font-size:48px;"></ion-icon>
                <p class="mt-3">No FAQs available yet.</p>
            </div>
        </div>
        @endforelse

        {{-- Contact Support --}}
        <div class="card mt-2">
            <div class="card-body text-center py-4">
                <ion-icon name="mail-outline" style="font-size:32px;color:#7c3aed;"></ion-icon>
                <h6 class="mt-2">Still need help?</h6>
                <p class="text-muted small">Can't find your answer? Contact our support team.</p>
                <a href="mailto:support@fobia.com" class="btn btn-primary btn-sm px-4">Contact Support</a>
            </div>
        </div>

    </div>
</div>

</div>
</div>

{{-- Live Search Script --}}
<script>
document.getElementById('faqSearch').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    document.querySelectorAll('.faq-item').forEach(item => {
        const q = item.querySelector('.faq-question').textContent.toLowerCase();
        const a = item.querySelector('.faq-answer').textContent.toLowerCase();
        item.style.display = (q.includes(query) || a.includes(query)) ? '' : 'none';
    });
});
</script>

@endsection