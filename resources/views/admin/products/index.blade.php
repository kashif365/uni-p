@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Manage Products</h4>
                <small class="text-muted">Add a product to a blog post with its shortcode, or use the "Insert Product" button in the blog editor.</small>
            </div>
            <div>
                <button id="bulk-delete-btn" class="btn btn-danger btn-sm mx-3" disabled>Delete Selected</button>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Add Product</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table id="productsTable" class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th width="30">☰</th>
                        <th>SL</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Shortcode</th>
                        <th>Clicks</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr data-id="{{ $product->id }}">
                            <td><input type="checkbox" value="{{ $product->id }}" class="rowCheckbox"></td>
                            <td class="reorder-handle" style="cursor:move;">☰</td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="" style="width:48px;height:48px;object-fit:contain;" class="rounded border">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">{{ $product->name }}</div>
                                <small class="text-muted">{{ $product->brand }}{{ $product->brand && $product->merchant_name ? ' · ' : '' }}{{ $product->merchant_name }}</small>
                            </td>
                            <td>{{ $product->display_price ?? '-' }}</td>
                            <td>
                                <code>{{ $product->shortcode }}</code>
                                <button type="button" class="btn btn-sm btn-text-secondary p-1 copy-shortcode" data-code="{{ $product->shortcode }}" title="Copy">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </td>
                            <td>{{ number_format($product->clicks) }}</td>
                            <td>
                                @if($product->status)
                                    <span class="badge bg-success">Enabled</span>
                                @else
                                    <span class="badge bg-danger">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline single-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-state-row">
                            <td colspan="10" class="text-center text-muted py-4">No products yet. Click "+ Add Product" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    initDataTableWithFeatures({
        tableSelector: '#productsTable',
        bulkDeleteBtnSelector: '#bulk-delete-btn',
        selectAllSelector: '#selectAll',
        rowHandleSelector: 'td.reorder-handle',
        reorderUrl: "{{ route('admin.products.reorder') }}",
        csrfToken: "{{ csrf_token() }}",
        bulkDeleteUrl: "{{ route('admin.products.bulkDelete') }}"
    });

    $(document).on('click', '.copy-shortcode', function () {
        navigator.clipboard.writeText($(this).data('code')).then(() => {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Shortcode copied', showConfirmButton: false, timer: 1200 });
        });
    });
});
</script>
@endsection
