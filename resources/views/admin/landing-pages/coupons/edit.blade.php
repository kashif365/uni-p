@extends('admin.layouts.app')
@section('content')
<div class="container mt-4">
    <div class="mb-3"><h4 class="mb-1">Edit Landing Page Coupon</h4><p class="text-muted mb-0">{{ $coupon->coupon_title }}</p></div>
    <form method="POST" action="{{ route('admin.landing-pages.coupons.update', $coupon) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.landing-pages.coupons.fields')</form>
</div>
@endsection
