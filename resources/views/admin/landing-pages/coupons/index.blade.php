@extends('admin.layouts.app')
@section('content')
<div class="container mt-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="d-flex justify-content-between align-items-center mb-3"><div><h4 class="mb-1">Landing Page Coupons</h4><p class="text-muted mb-0">Coupons can be created first and attached to a landing page later.</p></div><a href="{{ route('admin.landing-pages.coupons.create') }}" class="btn btn-primary">+ Add Coupon</a></div>
    <div class="card"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Coupon</th><th>Landing store</th><th>Type</th><th>Usage</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
    @forelse($coupons as $coupon)<tr><td><strong>{{ $coupon->coupon_title }}</strong>@if($coupon->coupon_code)<br><code>{{ $coupon->coupon_code }}</code>@endif</td><td>{{ $coupon->brand_store ?: 'Not attached yet' }}</td><td>{{ $coupon->coupon_code ? 'Code' : 'Deal' }}</td><td>{{ number_format($coupon->used_count ?? 0) }}</td><td><span class="badge {{ $coupon->status ? 'bg-success' : 'bg-secondary' }}">{{ $coupon->status ? 'Active' : 'Inactive' }}</span></td><td class="text-end">
        <a href="{{ route('admin.landing-pages.coupons.edit', $coupon) }}" class="btn btn-sm btn-primary">Edit</a>
        <form action="{{ route('admin.landing-pages.coupons.update-status', $coupon) }}" method="POST" class="d-inline">@csrf @method('PATCH')<input type="hidden" name="status" value="{{ $coupon->status ? 0 : 1 }}"><button class="btn btn-sm btn-outline-secondary">{{ $coupon->status ? 'Disable' : 'Enable' }}</button></form>
        <form action="{{ route('admin.landing-pages.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this coupon?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
    </td></tr>@empty<tr><td colspan="6" class="text-center text-muted py-4">No landing-page coupons found.</td></tr>@endforelse
    </tbody></table></div></div>
</div>
@endsection
