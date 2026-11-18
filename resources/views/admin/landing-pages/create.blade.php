@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="mb-3"><h4 class="mb-1">Create Landing Page</h4><p class="text-muted mb-0">Build a focused offer page for an existing store.</p></div>
    <form method="POST" action="{{ route('admin.landing-pages.store') }}">
        @csrf
        @include('admin.landing-pages.form')
    </form>
</div>
@endsection
