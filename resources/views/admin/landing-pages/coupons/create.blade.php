@extends('admin.layouts.app')
@section('content')
<div class="container mt-4">
    <div class="mb-3"><h4 class="mb-1">Add Landing Page Coupon</h4><p class="text-muted mb-0">This uses DiscountVault's normal coupon records and also appears on the regular store page.</p></div>
    @if($landingPages->isEmpty())
        <div class="alert alert-info">No landing pages exist yet. You can still create the coupon now and attach it after creating a landing page.</div>
    @endif
    <form method="POST" action="{{ route('admin.landing-pages.coupons.store') }}" enctype="multipart/form-data">@csrf @include('admin.landing-pages.coupons.fields', ['coupon' => null])</form>
</div>
@endsection
