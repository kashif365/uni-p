@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Product</h4>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-secondary">⬅ Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.products.form')
            </form>
        </div>
    </div>
</div>
@endsection
