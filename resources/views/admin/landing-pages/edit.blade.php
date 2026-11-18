@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="mb-3"><h4 class="mb-1">Edit Landing Page</h4><p class="text-muted mb-0">{{ $landingPage->store?->store_name }} · /landing/{{ $landingPage->slug }}</p></div>
    <form method="POST" action="{{ route('admin.landing-pages.update', $landingPage) }}">
        @csrf
        @method('PUT')
        @include('admin.landing-pages.form')
    </form>
</div>
@endsection
