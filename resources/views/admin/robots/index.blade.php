@extends('admin.layouts.app')

@section('title', 'Robots.txt Manager')

@section('content')
<div class="container-xxl container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Robots.txt Manager</h5>
                    <a href="{{ route('admin.robots.download') }}"
                       class="btn btn-outline-primary {{ !$robotsExists ? 'disabled' : '' }}"
                       {{ !$robotsExists ? 'aria-disabled="true" tabindex="-1"' : '' }}>
                        <i class="ri-download-line me-1"></i>Download robots.txt
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Status Card --}}
                    <div class="card border mb-4">
                        <div class="card-body">
                            <h6 class="mb-3">Current robots.txt Status</h6>
                            @if($robotsExists)
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div><strong>File:</strong> robots.txt</div>
                                        <div><strong>Location:</strong> {{ str_replace('\\', '/', $robotsPath) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Size:</strong> {{ number_format($robotsSize / 1024, 2) }} KB</div>
                                        <div><strong>Last Updated:</strong> {{ \Carbon\Carbon::createFromTimestamp($robotsLastModified)->format('d M Y, h:i A') }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <strong>Live URL:</strong>
                                    <a href="{{ url('/robots.txt') }}" target="_blank" rel="noopener noreferrer">
                                        {{ url('/robots.txt') }} <i class="ri-external-link-line"></i>
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    No robots.txt found. Save the content below to create one.
                                </div>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('admin.robots.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Direct Edit Textarea --}}
                        <div class="mb-3">
                            <label for="robots_content" class="form-label fw-semibold">Edit robots.txt</label>
                            <textarea
                                id="robots_content"
                                name="robots_content"
                                class="form-control font-monospace"
                                rows="16"
                                spellcheck="false"
                            >{{ old('robots_content', $currentContent ?? '') }}</textarea>
                            <div class="form-text">Edit directly above, or upload a file below to replace the content.</div>
                        </div>

                        {{-- File Upload --}}
                        <div class="mb-4">
                            <label for="robots_file" class="form-label fw-semibold">Upload robots.txt File <span class="text-muted fw-normal">(optional — overrides textarea)</span></label>
                            <input
                                class="form-control"
                                type="file"
                                id="robots_file"
                                name="robots_file"
                                accept=".txt,text/plain"
                            >
                            <div class="form-text">Uploading a file replaces the textarea content. Must be a .txt file.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>Save robots.txt
                        </button>
                    </form>
                </div>
            </div>

            {{-- Quick Reference --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="ri-information-line me-1"></i>robots.txt Quick Reference</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-2 fw-semibold">Common Directives</p>
                            <table class="table table-sm table-bordered mb-0">
                                <tbody>
                                    <tr><td><code>User-agent: *</code></td><td>Applies to all bots</td></tr>
                                    <tr><td><code>Disallow: /admin/</code></td><td>Block a path</td></tr>
                                    <tr><td><code>Allow: /</code></td><td>Allow everything</td></tr>
                                    <tr><td><code>Sitemap: URL</code></td><td>Point to your sitemap</td></tr>
                                    <tr><td><code>Crawl-delay: 10</code></td><td>Delay between requests (sec)</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2 fw-semibold">Example</p>
                            <pre class="bg-light rounded p-3 mb-0" style="font-size:12px;">User-agent: *
Disallow: /admin/
Disallow: /login
Disallow: /password/

User-agent: Googlebot
Allow: /

Sitemap: {{ url('/sitemap.xml') }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('robots_file');
    var textarea  = document.getElementById('robots_content');

    if (!fileInput || !textarea) return;

    // When a file is chosen, load its text into the textarea
    fileInput.addEventListener('change', function (e) {
        var file = e.target.files && e.target.files[0] ? e.target.files[0] : null;
        if (!file) return;

        var reader = new FileReader();
        reader.onload = function (ev) {
            textarea.value = typeof ev.target.result === 'string' ? ev.target.result : '';
        };
        reader.onerror = function () {
            textarea.value = 'Could not read the selected file.';
        };
        reader.readAsText(file);
    });
});
</script>
@endpush
