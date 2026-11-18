@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Landing Pages</h4>
                <small class="text-muted">Manage dedicated store offer pages at /landing/store-name.</small>
            </div>
            <a href="{{ route('admin.landing-pages.create') }}" class="btn btn-primary btn-sm">+ Add Landing Page</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Store</th>
                            <th>URL</th>
                            <th>Top offer</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($landingPages as $page)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($page->store?->store_logo)
                                            <img src="{{ asset($page->store->store_logo) }}" alt="" width="42" height="42" style="object-fit:contain;border:1px solid #eee;border-radius:8px;padding:4px;">
                                        @endif
                                        <strong>{{ $page->store?->store_name ?? 'Deleted store' }}</strong>
                                    </div>
                                </td>
                                <td><code>/landing/{{ $page->slug }}</code></td>
                                <td>{{ $page->featuredCoupon?->coupon_title ?? 'Automatic first offer' }}</td>
                                <td>
                                    <span class="badge bg-{{ $page->status ? 'success' : 'secondary' }}">
                                        {{ $page->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $page->updated_at->format('d M Y') }}</td>
                                <td class="text-nowrap">
                                    @if($page->status)
                                        <a href="{{ route('landing.show', $page->slug) }}" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                                    @endif
                                    <a href="{{ route('admin.landing-pages.edit', $page) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.landing-pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this landing page? The store and coupons will not be deleted.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No landing pages have been created.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
