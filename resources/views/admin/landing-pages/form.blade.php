@php($editing = $landingPage->exists)

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the following:</strong>
        <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Page content</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7 mb-3">
                        <label for="store_id" class="form-label">Store <span class="text-danger">*</span></label>
                        <select name="store_id" id="store_id" class="form-select @error('store_id') is-invalid @enderror" required>
                            <option value="">Select a store</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" data-slug="{{ $store->seo_url }}" {{ (string) old('store_id', $landingPage->store_id) === (string) $store->id ? 'selected' : '' }}>
                                    {{ $store->store_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Each store can have one landing page.</small>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label for="slug" class="form-label">URL slug</label>
                        <div class="input-group">
                            <span class="input-group-text">/landing/</span>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $landingPage->slug) }}" placeholder="store-name">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="heading" class="form-label">Main heading</label>
                    <input type="text" name="heading" id="heading" class="form-control" maxlength="255" value="{{ old('heading', $landingPage->heading) }}" placeholder="Leave blank to use Store Coupons & Promo Codes">
                </div>

                <div class="mb-3">
                    <label for="subheading" class="form-label">Offer summary</label>
                    <input type="text" name="subheading" id="subheading" class="form-control" maxlength="255" value="{{ old('subheading', $landingPage->subheading) }}" placeholder="Leave blank to generate the verified offer count and date">
                </div>

                <div class="mb-3">
                    <label for="featured_coupon_id" class="form-label">Today's top offer</label>
                    <select name="featured_coupon_id" id="featured_coupon_id" class="form-select" data-selected="{{ old('featured_coupon_id', $landingPage->featured_coupon_id) }}">
                        <option value="">Use the first active coupon automatically</option>
                        @foreach($coupons as $coupon)
                            <option value="{{ $coupon->id }}" {{ (string) old('featured_coupon_id', $landingPage->featured_coupon_id) === (string) $coupon->id ? 'selected' : '' }}>{{ $coupon->coupon_title }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">The available options update when the store changes.</small>
                </div>

                <div class="mb-3">
                    <label for="disclosure" class="form-label">Affiliate disclosure</label>
                    <textarea name="disclosure" id="disclosure" rows="3" class="form-control" maxlength="1000" placeholder="When visitors buy through links on our site, we may earn a commission.">{{ old('disclosure', $landingPage->disclosure) }}</textarea>
                </div>

                <div class="mb-0">
                    <label for="intro_content" class="form-label">Additional store information</label>
                    <textarea name="intro_content" id="intro_content" rows="6" class="form-control" placeholder="Optional content shown below the offer list.">{{ old('intro_content', $landingPage->intro_content) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Publishing</h5></div>
            <div class="card-body">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $landingPage->status) ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">Active and publicly accessible</label>
                </div>
                <div class="form-check form-switch mt-3">
                    <input class="form-check-input" type="checkbox" name="no_index" id="no_index" value="1" {{ old('no_index', $landingPage->exists ? $landingPage->no_index : true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="no_index">Prevent search-engine indexing</label>
                </div>
                <small class="text-muted d-block mt-1">Adds noindex and removes only this landing page from the sitemap. The regular store page is unaffected.</small>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">SEO</h5></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="meta_title" class="form-label">Meta title</label>
                    <input type="text" name="meta_title" id="meta_title" class="form-control" maxlength="255" value="{{ old('meta_title', $landingPage->meta_title) }}">
                </div>
                <div class="mb-0">
                    <label for="meta_description" class="form-label">Meta description</label>
                    <textarea name="meta_description" id="meta_description" rows="5" class="form-control" maxlength="1000">{{ old('meta_description', $landingPage->meta_description) }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update Landing Page' : 'Create Landing Page' }}</button>
    <a href="{{ route('admin.landing-pages.index') }}" class="btn btn-secondary">Cancel</a>
    @if($editing && $landingPage->status)
        <a href="{{ route('landing.show', $landingPage->slug) }}" target="_blank" class="btn btn-outline-info ms-auto">Preview public page</a>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const store = document.getElementById('store_id');
    const slug = document.getElementById('slug');
    const coupon = document.getElementById('featured_coupon_id');
    const endpoint = @json(route('admin.landing-pages.store-coupons', ['store' => '__STORE__']));
    let slugWasEdited = slug.value.trim() !== '';

    slug.addEventListener('input', () => { slugWasEdited = true; });
    store.addEventListener('change', async function () {
        const option = this.options[this.selectedIndex];
        if (!slugWasEdited && option && option.dataset.slug) slug.value = option.dataset.slug;

        coupon.innerHTML = '<option value="">Use the first active coupon automatically</option>';
        if (!this.value) return;

        try {
            const response = await fetch(endpoint.replace('__STORE__', this.value), { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error('Unable to load coupons');
            const items = await response.json();
            items.forEach(item => coupon.add(new Option(item.coupon_title, item.id)));
        } catch (error) {
            console.error(error);
        }
    });
});
</script>
