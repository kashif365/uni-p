@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Add New FAQ</h4>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.faqs.store') }}">
                @csrf

                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" class="form-control" name="page_url" placeholder="Page URL"
                        value="{{ old('page_url') }}" required>
                    <label>Page URL *</label>
                    <small class="form-text text-muted">Paste the store page URL, e.g. {{ url('/stores/the-store-slug') }}. This FAQ will only be shown on that page.</small>
                </div>

                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" class="form-control" name="question" placeholder="Question"
                        value="{{ old('question') }}" required>
                    <label>Question *</label>
                </div>

                <div class="form-floating form-floating-outline mb-3">
                    <textarea class="form-control h-px-150" name="answer" placeholder="Answer" required>{{ old('answer') }}</textarea>
                    <label>Answer *</label>
                    <small class="form-text text-muted">Basic HTML (e.g. &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, &lt;table&gt;) is allowed and will be rendered as-is on the store page.</small>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" checked>
                    <label class="form-check-label" for="status">Active (visible on store pages)</label>
                </div>

                <button type="submit" class="btn btn-primary">Save FAQ</button>
            </form>
        </div>
    </div>
</div>
@endsection
