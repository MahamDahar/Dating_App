@extends('layouts.admin')
@section('admincontent')
<div class="page-content-wrapper">
      <div class="page-content">
<div class="card col-lg-7 mx-auto">
    <div class="card-header"><h5>{{ isset($faq) ? 'Edit FAQ' : 'Add New FAQ' }}</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
            @csrf
            @if(isset($faq)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="General"  {{ old('category', $faq->category ?? '') == 'General'  ? 'selected' : '' }}>General</option>
                    <option value="Account"  {{ old('category', $faq->category ?? '') == 'Account'  ? 'selected' : '' }}>Account</option>
                    <option value="Matching" {{ old('category', $faq->category ?? '') == 'Matching' ? 'selected' : '' }}>Matching</option>
                    <option value="Premium"  {{ old('category', $faq->category ?? '') == 'Premium'  ? 'selected' : '' }}>Premium</option>
                    <option value="Safety"   {{ old('category', $faq->category ?? '') == 'Safety'   ? 'selected' : '' }}>Safety</option>
                    <option value="Privacy"  {{ old('category', $faq->category ?? '') == 'Privacy'  ? 'selected' : '' }}>Privacy</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Question</label>
                <input type="text" name="question" class="form-control"
                       value="{{ old('question', $faq->question ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Answer</label>
                <textarea name="answer" class="form-control" rows="4" required>{{ old('answer', $faq->answer ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Display Order</label>
                <input type="number" name="order" class="form-control" min="0"
                       value="{{ old('order', $faq->order ?? 0) }}">
                <small class="text-muted">Lower number = appears first</small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" value="1"
                       {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">Active (visible to users)</label>
            </div>

            <button type="submit" class="btn btn-primary">
                {{ isset($faq) ? 'Update FAQ' : 'Save FAQ' }}
            </button>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
</div>
</div>
@endsection
```

---

## ✅ Summary Flow
```
Admin adds FAQ (question + answer + category + order)
            ↓
Saved in DB with is_active = true
            ↓
User opens Help & FAQ page
            ↓
FAQs load grouped by category in simple list
            ↓
User can live-search through all questions
            ↓
Bottom of page → Contact Support button