@extends('admin.layouts.app')

@section('title', 'Sitemap Manager')

@section('content')
<div class="container-xxl container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Sitemap Manager</h5>
                    <a href="{{ route('admin.sitemap.download') }}" class="btn btn-outline-primary {{ !$sitemapExists ? 'disabled' : '' }}" {{ !$sitemapExists ? 'aria-disabled="true" tabindex="-1"' : '' }}>
                        <i class="ri-download-line me-1"></i>Download Sitemap
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

                    <div class="card border mb-4">
                        <div class="card-body">
                            <h6 class="mb-3">Current Sitemap Status</h6>

                            @if($sitemapExists)
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div><strong>File:</strong> sitemap.xml</div>
                                        <div><strong>Location:</strong> {{ str_replace('\\', '/', $sitemapPath) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Size:</strong> {{ number_format($sitemapSize / 1024, 2) }} KB</div>
                                        <div><strong>Last Updated:</strong> {{ \Carbon\Carbon::createFromTimestamp($sitemapLastModified)->format('d M Y, h:i A') }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <strong>Live URL:</strong>
                                    <a href="{{ url('/sitemap.xml') }}" target="_blank" rel="noopener noreferrer">
                                        {{ url('/sitemap.xml') }} <i class="ri-external-link-line"></i>
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    No sitemap.xml found yet. Upload one below.
                                    <br><small>Will be saved to: <code>{{ env('WEBROOT_PATH') ? rtrim(env('WEBROOT_PATH'), '/\\') : public_path() }}/sitemap.xml</code></small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('admin.sitemap.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="sitemap_preview" class="form-label">Sitemap XML Preview</label>
                            <textarea
                                id="sitemap_preview"
                                class="form-control font-monospace"
                                rows="14"
                                readonly
                            >{{ old('sitemap_preview', $currentSitemapContent ?? '') }}</textarea>
                            <div class="form-text">When you select a file, its XML content will appear here as preview.</div>
                        </div>

                          <div class="mb-3">
                            <label for="sitemap_file" class="form-label">Upload New Sitemap XML</label>
                            <input
                                class="form-control"
                                type="file"
                                id="sitemap_file"
                                name="sitemap_file"
                                accept=".xml,application/xml,text/xml"
                                required
                            >
                            <div class="form-text">Select a valid .xml file. This will replace the existing sitemap.xml file.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="ri-upload-2-line me-1"></i>Update Sitemap
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('sitemap_file');
    var preview = document.getElementById('sitemap_preview');

    if (!fileInput || !preview) {
        return;
    }

    fileInput.addEventListener('change', function (event) {
        var file = event.target.files && event.target.files[0] ? event.target.files[0] : null;

        if (!file) {
            return;
        }

        var reader = new FileReader();

        reader.onload = function (loadEvent) {
            preview.value = loadEvent.target && typeof loadEvent.target.result === 'string'
                ? loadEvent.target.result
                : '';
        };

        reader.onerror = function () {
            preview.value = 'Could not preview selected file.';
        };

        reader.readAsText(file);
    });
});
</script>
@endpush
