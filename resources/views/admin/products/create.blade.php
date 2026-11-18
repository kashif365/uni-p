@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Add Product</h4>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-secondary">⬅ Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @include('admin.products.form')
            </form>
        </div>
    </div>
</div>
@endsection
