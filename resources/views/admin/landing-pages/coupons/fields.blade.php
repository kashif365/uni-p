@php($coupon = $coupon ?? null)
@if($errors->any())<div class="alert alert-danger"><strong>Please fix the following:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<div class="card mb-4"><div class="card-body">
    <label for="landing_page_id" class="form-label">Landing page</label>
    <select name="landing_page_id" id="landing_page_id" class="form-select @error('landing_page_id') is-invalid @enderror">
        <option value="">Not attached to a landing page yet</option>
        @foreach($landingPages as $page)<option value="{{ $page->id }}" {{ (string) old('landing_page_id', $selectedLandingPageId ?? '') === (string) $page->id ? 'selected' : '' }}>{{ $page->store->store_name }} — /landing/{{ $page->slug }}</option>@endforeach
    </select>
    <small class="text-muted">Optional — you can attach this coupon to a landing page now or later by editing it.</small>
</div></div>

<div class="card mb-4"><div class="card-header"><h5 class="mb-0">Coupon details</h5></div><div class="card-body"><div class="row g-3">
    <div class="col-md-8"><label class="form-label">Coupon title *</label><input type="text" name="coupon_title" class="form-control" maxlength="255" value="{{ old('coupon_title', $coupon->coupon_title ?? '') }}" required></div>
    <div class="col-md-4"><label class="form-label">Coupon code</label><input type="text" name="coupon_code" class="form-control" maxlength="255" value="{{ old('coupon_code', $coupon->coupon_code ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label">Category</label><select name="category_id" class="form-select"><option value="">Select category</option>@foreach($categories as $category)<option value="{{ $category->id }}" {{ (string) old('category_id', $coupon->category_id ?? '') === (string) $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Event</label><select name="event_id" class="form-select"><option value="">Select event</option>@foreach($events as $event)<option value="{{ $event->id }}" {{ (string) old('event_id', $coupon->event_id ?? '') === (string) $event->id ? 'selected' : '' }}>{{ $event->event_name }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Submitted by</label><input type="text" name="submitted_by" class="form-control" value="{{ old('submitted_by', $coupon->submitted_by ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label">Affiliate URL</label><input type="url" name="affiliate_url" class="form-control" maxlength="255" value="{{ old('affiliate_url', $coupon->affiliate_url ?? '') }}"></div>
    <div class="col-md-5"><label class="form-label">Available from</label><input type="date" name="date_available" class="form-control" value="{{ old('date_available', $coupon->date_available ?? '') }}"></div>
    <div class="col-md-5"><label class="form-label">Expires on</label><input type="date" name="date_expiry" class="form-control" value="{{ old('date_expiry', $coupon->date_expiry ?? '') }}"></div>
    <div class="col-md-2 d-flex align-items-end"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="expiry_soon" id="expiry_soon" value="1" {{ old('expiry_soon', $coupon->expiry_soon ?? 0) ? 'checked' : '' }}><label class="form-check-label" for="expiry_soon">Expiry soon</label></div></div>
    <div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="3" class="form-control">{{ old('description', $coupon->description ?? '') }}</textarea></div>
    <div class="col-12"><label class="form-label">Terms and conditions</label><textarea name="terms" rows="4" class="form-control">{{ old('terms', $coupon->terms ?? '') }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Cover image</label><input type="file" name="cover_logo" class="form-control" accept="image/*"><input type="hidden" name="cover_logo_path" id="cover_logo_path">@if($coupon?->cover_logo)<img src="{{ asset($coupon->cover_logo) }}" alt="" class="img-thumbnail mt-2" style="max-height:90px">@endif</div>
</div><hr><div class="d-flex flex-wrap gap-4">
    @foreach(['exclusive'=>'Exclusive','featured'=>'Featured','recommended'=>'Recommended','verified'=>'Verified','hot_deals'=>'Hot deal','student_offer'=>'Student offer'] as $name => $label)<div class="form-check"><input class="form-check-input" type="checkbox" name="{{ $name }}" id="{{ $name }}" value="1" {{ old($name, $coupon->$name ?? 0) ? 'checked' : '' }}><label class="form-check-label" for="{{ $name }}">{{ $label }}</label></div>@endforeach
</div></div></div>
<div class="d-flex gap-2 mb-4"><button type="submit" class="btn btn-primary">{{ $coupon ? 'Update Coupon' : 'Create Coupon' }}</button><a href="{{ route('admin.landing-pages.coupons.index') }}" class="btn btn-secondary">Cancel</a></div>
