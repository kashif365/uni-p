@php
    $product = $product ?? new \App\Models\Product;
@endphp

@csrf

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Status -->
<div class="d-flex align-items-center justify-content-end mb-3">
    <label>Disabled</label>
    <div class="form-check form-switch mx-2">
        <input type="hidden" name="status" value="0">
        <input class="form-check-input" type="checkbox" name="status" value="1"
            {{ old('status', $product->exists ? $product->status : true) ? 'checked' : '' }}>
    </div>
    <label>Enabled</label>
</div>

<div class="row">
    <!-- Left: details -->
    <div class="col-md-8">
        <div class="row mb-3">
            <div class="col-md-8">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="name" placeholder="Product Name"
                        value="{{ old('name', $product->name) }}" required>
                    <label>Product Name *</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="brand" placeholder="Brand"
                        value="{{ old('brand', $product->brand) }}">
                    <label>Brand</label>
                </div>
            </div>
        </div>

        <div class="form-floating form-floating-outline mb-3">
            <textarea class="form-control h-px-100" name="description" placeholder="Short description">{{ old('description', $product->description) }}</textarea>
            <label>Short Description</label>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="currency" placeholder="$" maxlength="5"
                        value="{{ old('currency', $product->currency ?? '$') }}">
                    <label>Currency Symbol</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating form-floating-outline">
                    <input type="number" step="0.01" min="0" class="form-control" name="price" placeholder="Price"
                        value="{{ old('price', $product->price) }}">
                    <label>Price</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating form-floating-outline">
                    <input type="number" step="0.01" min="0" class="form-control" name="sale_price" placeholder="Sale Price"
                        value="{{ old('sale_price', $product->sale_price) }}">
                    <label>Sale Price</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating form-floating-outline">
                    <input type="number" step="0.1" min="0" max="5" class="form-control" name="rating" placeholder="Rating"
                        value="{{ old('rating', $product->rating) }}">
                    <label>Rating (0–5)</label>
                </div>
            </div>
        </div>

        <div class="form-floating form-floating-outline mb-3">
            <input type="url" class="form-control" name="affiliate_url" placeholder="https://"
                value="{{ old('affiliate_url', $product->affiliate_url) }}" required>
            <label>Affiliate URL *</label>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="merchant_name" placeholder="Amazon"
                        value="{{ old('merchant_name', $product->merchant_name) }}">
                    <label>Merchant (e.g. Amazon)</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="button_text" placeholder="Button text"
                        value="{{ old('button_text', $product->button_text) }}">
                    <label>Button Text</label>
                </div>
                <small class="form-text text-muted">Empty = "View at {Merchant}" or "Check Price".</small>
            </div>
            <div class="col-md-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="badge" placeholder="Editor's Pick"
                        value="{{ old('badge', $product->badge) }}">
                    <label>Badge (e.g. Editor's Pick)</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <textarea class="form-control h-px-100" name="pros" placeholder="One per line">{{ old('pros', $product->pros) }}</textarea>
                    <label>Pros (one per line)</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <textarea class="form-control h-px-100" name="cons" placeholder="One per line">{{ old('cons', $product->cons) }}</textarea>
                    <label>Cons (one per line)</label>
                </div>
            </div>
        </div>
        <small class="form-text text-muted d-block mb-3">Pros and cons are shown on the single (horizontal) card only.</small>
    </div>

    <!-- Right: image -->
    <div class="col-md-4">
        <label>Product Image</label>
        <div class="image-upload-box" onclick="document.getElementById('image').click()">
            <img id="image_preview"
                 src="{{ $product->image ? asset($product->image) : '' }}"
                 style="{{ $product->image ? '' : 'display:none;' }}">
            <svg id="image_placeholder" style="{{ $product->image ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5L8.5,11.5L5,16Z" />
            </svg>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('image').click()">
                <i class="ri-upload-line"></i> Upload
            </button>
            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('image', 'image_preview', 'image_placeholder')">
                <i class="ri-image-line"></i> Media
            </button>
        </div>
        <input type="file" id="image" name="image" style="display:none;" accept="image/*"
               onchange="previewImage(event, 'image_preview', 'image_placeholder')">
        <input type="hidden" id="image_path" name="image_path">

        @if($product->exists)
            <div class="alert alert-secondary mt-4 mb-0">
                <div class="fw-medium mb-1">Shortcode</div>
                <code>{{ $product->shortcode }}</code>
                <div class="small text-muted mt-1">Clicks: {{ number_format($product->clicks) }}</div>
            </div>
        @endif
    </div>
</div>

<div class="text-end">
    <button type="submit" class="btn btn-primary">
        {{ $product->exists ? '💾 Update' : '💾 Save' }}
    </button>
</div>
