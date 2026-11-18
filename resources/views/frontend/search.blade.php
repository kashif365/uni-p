{{-- @extends('frontend.layouts.app')

@section('title', 'Search Results for "' . $query . '" | Campus Deals')
@section('description', 'Search results for "' . $query . '" - Find the best discount codes and voucher codes from top UK brands. Browse stores, coupons, and categories.')
@section('keywords', 'search discount codes, search voucher codes, find deals, search coupons, ' . $query)

@section('content')
<div class="Sec">
    <div class="Wrp">
        <h1>Search Results for "{{ $query }}"</h1>
        
        @if($stores->count() > 0 || $coupons->count() > 0 || $categories->count() > 0)
            <!-- Stores Results -->
            @if($stores->count() > 0)
            <div class="search-section">
                <h2>Stores ({{ $stores->count() }})</h2>
                <div class="stores-grid">
                    @foreach($stores as $store)
                    <div class="store-item">
                        <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">
                            @if($store->store_logo)
                                <img src="{{ asset( $store->store_logo) }}" alt="{{ $store->store_name }}" width="150" height="150">
                            @else
                                <div class="store-placeholder">{{ substr($store->store_name, 0, 2) }}</div>
                            @endif
                            <h3>{{ $store->store_name }}</h3>
                        </a>
                    </div>
                    @endforeach
                </div>
                @if($stores->hasPages())
                    <div class="pagination-wrapper">
                        {{ $stores->links() }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Coupons Results -->
            @if($coupons->count() > 0)
            <div class="search-section">
                <h2>Coupons & Offers ({{ $coupons->count() }})</h2>
                <div class="cpns">
                    @foreach($coupons as $coupon)
                    <div class="cpn {{ $coupon->exclusive ? 'exclusive' : '' }} {{ $coupon->verified ? 'verified' : '' }}" data-id="{{ $coupon->id }}">
                        <div class="imgs cvr">
                            @if($coupon->cover_logo)
                                <img decoding="async" class="cvr" src="{{ asset( $coupon->cover_logo) }}" alt="{{ $coupon->coupon_title }}" title="{{ $coupon->coupon_title }}" width="328" height="160">
                            @endif
                            @if($coupon->store && $coupon->store->store_logo)
                                <a href="{{ route('store', $coupon->store->seo_url) }}" title="{{ $coupon->store->store_name }}">
                                    <img decoding="async" loading="lazy" src="{{ asset( $coupon->store->store_logo) }}" alt="{{ $coupon->store->store_name }} discount code" title="{{ $coupon->store->store_name }} discount code" width="80" height="80">
                                </a>
                            @endif
                        </div>
                        <div class="cnt">
                            <div class="str-vrf">
                                @if($coupon->store)
                                    <a href="{{ route('store', $coupon->store->seo_url) }}" title="{{ $coupon->store->store_name }}">{{ $coupon->store->store_name }}</a>
                                @endif
                                @if($coupon->verified)
                                    <span>Verified</span>
                                @endif
                            </div>
                            <h3>{{ $coupon->coupon_title }}</h3>
                            <div class="trm-cnt">
                                @if($coupon->terms)
                                    <button aria-label="View Terms" class="ctb">View Terms</button>
                                @endif
                                <span>{{ $coupon->sort_order ?? '0' }} Used</span>
                            </div>
                            @if($coupon->coupon_code)
                                <button class="cpBtn" aria-label="Reveal Code" data-code="{{ $coupon->coupon_code }}">Reveal Code</button>
                            @else
                                <a href="{{ $coupon->affiliate_url }}" class="cpBtn" target="_blank" rel="nofollow">Get Deal</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($coupons->hasPages())
                    <div class="pagination-wrapper">
                        {{ $coupons->links() }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Categories Results -->
            @if($categories->count() > 0)
            <div class="search-section">
                <h2>Categories ({{ $categories->count() }})</h2>
                <div class="cat-grid">
                    @foreach($categories as $category)
                    <div class="cat-item">
                        <a href="{{ route('category', $category->seo_url) }}" title="{{ $category->category_name }}">
                            @if($category->media)
                                <img src="{{ asset( $category->media) }}" alt="{{ $category->category_name }}" width="100" height="100">
                            @else
                                <div class="cat-icon">{{ substr($category->category_name, 0, 1) }}</div>
                            @endif
                            <h3>{{ $category->category_name }}</h3>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @else
            <div class="no-results">
                <h2>No results found for "{{ $query }}"</h2>
                <p>Try searching for different keywords or browse our categories:</p>
                <div class="suggestions">
                    <a href="{{ route('categories') }}" class="btn">Browse Categories</a>
                    <a href="{{ route('top-discounts') }}" class="btn">Top Discounts</a>
                    <a href="{{ route('all-brands') }}" class="btn">All Brands</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection --}}

@extends('frontend.layouts.app')

@section('title', 'Search Results for "' . $query . '" | Campus Deals')
@section('description', 'Search results for "' . $query . '" - Find the best discount codes and voucher codes from top UK brands. Browse stores, coupons, and categories.')
@section('keywords', 'search discount codes, search voucher codes, find deals, search coupons, ' . $query)

@push('styles')
<style>
    :root {
        --search-primary: #DE1A58;
        --search-dark: #111111;
        --search-text: #1f2937;
        --search-muted: #6b7280;
        --search-bg: #f8f8fa;
        --search-card: #ffffff;
        --search-border: rgba(17, 17, 17, 0.08);
        --search-shadow: 0 12px 30px rgba(17, 17, 17, 0.06);
        --search-radius: 24px;
    }

    .search-results-page {
        background: linear-gradient(180deg, #fff8fb 0%, #f7f7f8 100%);
        padding: 36px 0 70px;
    }

    .search-results-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .search-page-hero {
        margin-bottom: 28px;
        padding: 34px 34px 30px;
        border-radius: 28px;
        background:
            radial-gradient(circle at top right, rgba(222, 26, 88, 0.12), transparent 30%),
            linear-gradient(135deg, #111111 0%, #1e1e1e 100%);
        color: #ffffff;
        box-shadow: 0 20px 40px rgba(17, 17, 17, 0.12);
    }

    .search-page-hero h1 {
        margin: 0 0 12px;
        font-size: clamp(30px, 5vw, 4px);
        line-height: 1.02;
        font-weight: 900;
        letter-spacing: -1px;
    }

    .search-page-hero h1 span {
        color: #f8a7c2;
    }

    .search-page-hero p {
        margin: 0;
        font-size: 17px;
        line-height: 1.7;
        color: rgba(255,255,255,0.82);
        max-width: 760px;
    }

    .search-sections-stack {
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .search-section-card {
        background: var(--search-card);
        border: 1px solid var(--search-border);
        border-radius: var(--search-radius);
        padding: 28px;
        box-shadow: var(--search-shadow);
    }

    .search-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .search-section-head h2 {
        margin: 0;
        font-size: clamp(24px, 3vw, 38px);
        font-weight: 800;
        color: var(--search-dark);
        letter-spacing: -0.5px;
    }

    .search-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        padding: 0 14px;
        border-radius: 999px;
        background: rgba(222, 26, 88, 0.08);
        color: var(--search-primary);
        font-size: 15px;
        font-weight: 800;
        border: 1px solid rgba(222, 26, 88, 0.12);
    }

    /* stores */
    .stores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 22px;
    }

    .store-item {
        height: 100%;
    }

    .store-item a {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 14px;
        min-height: 220px;
        padding: 24px 18px 20px;
        border-radius: 22px;
        text-decoration: none;
        transition: all 0.28s ease;
    }

    .store-item a:hover {
        transform: translateY(-5px);
        text-decoration: none;
        border-color: rgba(222, 26, 88, 0.16);
    }

    .store-item img,
    .store-placeholder {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #f1e7eb;
        box-shadow: 0 8px 18px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        object-fit: contain;
        padding: 14px;
    }

    .store-placeholder {
        font-size: 28px;
        font-weight: 800;
        color: var(--search-primary);
        background: #fdf1f6;
        text-transform: uppercase;
        padding: 0;
    }

    .store-item h3 {
        margin: 0;
        font-size: 18px;
        line-height: 1.3;
        font-weight: 700;
        color: var(--search-text);
    }

    .store-item a:hover h3 {
        color: var(--search-primary);
    }

    /* coupons */
    .cpns {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 22px;
    }

    .cpn {
        background: #fff;
        border-radius: 22px;
        /* overflow: hidden; */
        border: 1px solid rgba(0,0,0,0.08);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .cpn:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 35px rgba(0,0,0,0.08);
        box-shadow: 0 18px 35px rgba(0,0,0,0.08);
        border-color: rgba(222, 26, 88, 0.16);
    }

    .imgs.cvr {
        position: relative;
        display: block;
        width: 100%;
        min-height: 180px;
        background: linear-gradient(135deg, #fff5f8 0%, #f8f8fb 100%);
        width: 100%;
        min-height: 180px;
        background: linear-gradient(135deg, #fff5f8 0%, #f8f8fb 100%);
        border-bottom: 1px solid rgba(17, 17, 17, 0.08);
        overflow: visible;
        padding-bottom: 0;
        overflow: visible;
        padding-bottom: 0;
    }

    .imgs.cvr > img.cvr {
        width: 100%;
        height: 180px;
        height: 180px;
        object-fit: cover;
        display: block;
    }

    .imgs.cvr > a {
        position: absolute;
        left: 50%;
        bottom: -42px;
        transform: translateX(-50%);
        width: 92px;
        height: 92px;
        left: 50%;
        bottom: -42px;
        transform: translateX(-50%);
        width: 92px;
        height: 92px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        border: 4px solid #fff;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        border: 4px solid #fff;
        overflow: hidden;
        padding: 0;
        z-index: 3;
        padding: 0;
        z-index: 3;
    }

    .imgs.cvr > a img {
        width: 72%;
        height: 72%;
        width: 72%;
        height: 72%;
        object-fit: contain;
        padding: 0;
        padding: 0;
        display: block;
    }

    .cnt {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 56px 22px 22px;
        gap: 12px;
        align-items: center;
        text-align: center;
        padding: 56px 22px 22px;
        gap: 12px;
    }

    .str-vrf {
        display: flex;
        align-items: center;
        justify-content: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        width: 100%;
        width: 100%;
    }

    .str-vrf a {
        text-decoration: none;
        font-size: 13px;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.5px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: var(--search-dark);
    }

    .str-vrf span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: rgba(222, 26, 88, 0.08);
        color: var(--search-primary);
        border: 1px solid rgba(222, 26, 88, 0.12);
    }

    .cnt h3 {
        margin: 0;
        font-size: 22px;
        line-height: 1.28;
        font-size: 22px;
        line-height: 1.28;
        font-weight: 800;
        color: var(--search-dark);
        letter-spacing: -0.4px;
        max-width: 100%;
        letter-spacing: -0.4px;
        max-width: 100%;
    }

    .trm-cnt {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 2px;
        width: 100%;
        width: 100%;
    }

    .ctb {
        border: 1px solid rgba(17, 17, 17, 0.12);
        background: #fff;
        color: var(--search-text);
        border-radius: 12px;
        min-height: 42px;
        padding: 0 16px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .ctb:hover {
        border-color: rgba(222, 26, 88, 0.2);
        color: var(--search-primary);
    }

    .trm-cnt > span {
        font-size: 14px;
        color: var(--search-muted);
        font-weight: 600;
    }

    .cpBtn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 52px;
        border: none;
        border-radius: 16px;
        background: #DE1A58;
        color: #ffffff;
        text-decoration: none;
        font-size: 18px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 14px 28px rgba(222, 26, 88, 0.18);
        margin-top: 6px;
        margin-top: 6px;
    }

    .cpBtn:hover {
        background: #111111;
        color: #ffffff;
        text-decoration: none;
        transform: translateY(-2px);
    }

    /* categories */
    .cat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 22px;
    }

    .cat-item {
        height: 100%;
    }

    .cat-item a {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 14px;
        min-height: 220px;
        padding: 24px 18px 20px;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(17, 17, 17, 0.06);
        box-shadow: 0 8px 22px rgba(17, 17, 17, 0.04);
        text-decoration: none;
        transition: all 0.28s ease;
    }

    .cat-item a:hover {
        transform: translateY(-5px);
        text-decoration: none;
        border-color: rgba(222, 26, 88, 0.16);
        box-shadow: 0 16px 32px rgba(17, 17, 17, 0.08);
    }

    .cat-item img,
    .cat-icon {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #f1e7eb;
        box-shadow: 0 8px 18px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        object-fit: contain;
        padding: 14px;
    }

    .cat-icon {
        font-size: 28px;
        font-weight: 800;
        color: var(--search-primary);
        background: #fdf1f6;
        text-transform: uppercase;
        padding: 0;
    }

    .cat-item h3 {
        margin: 0;
        font-size: 18px;
        line-height: 1.3;
        font-weight: 700;
        color: var(--search-text);
    }

    .cat-item a:hover h3 {
        color: var(--search-primary);
    }

    /* no results */
    .no-results {
        text-align: center;
        padding: 70px 24px;
        background: #ffffff;
        border: 1px solid rgba(17, 17, 17, 0.08);
        border-radius: 28px;
        box-shadow: var(--search-shadow);
    }

    .no-results h2 {
        margin: 0 0 12px;
        font-size: clamp(28px, 4vw, 42px);
        line-height: 1.1;
        color: var(--search-dark);
        font-weight: 800;
    }

    .no-results p {
        margin: 0 0 24px;
        color: var(--search-muted);
        font-size: 16px;
    }

    .suggestions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .suggestions .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 0 18px;
        border-radius: 999px;
        background: #DE1A58;
        color: #ffffff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: all 0.25s ease;
    }

    .suggestions .btn:hover {
        background: #111111;
        color: #ffffff;
        text-decoration: none;
    }

    .pagination-wrapper {
        margin-top: 24px;
    }

    @media (max-width: 767px) {
        .search-results-page {
            padding: 24px 0 50px;
        }

        .search-results-wrap {
            padding: 0 16px;
        }

        .search-page-hero {
            padding: 24px 20px;
            border-radius: 22px;
        }

        .search-section-card {
            padding: 20px;
            border-radius: 20px;
        }

        .search-section-head {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .cpns {
            grid-template-columns: 1fr;
        }

        .imgs.cvr {
            min-height: 160px;
            min-height: 160px;
        }

        .imgs.cvr > img.cvr {
            height: 160px;
            height: 160px;
        }

        .imgs.cvr > a {
            width: 82px;
            height: 82px;
            bottom: -38px;
        }

        .cnt {
            padding: 52px 18px 18px;
        }

        .cnt h3 {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .cpBtn {
            font-size: 16px;
            min-height: 48px;
        }

        .suggestions {
            flex-direction: column;
        }
    }
</style>

<style>
    .coupons-offers-section {
        background: #ffffff;
        border: 1px solid #ece7eb;
        border-radius: 28px;
        padding: 34px;
        box-shadow: 0 10px 30px rgba(17, 17, 17, 0.04);
        margin-bottom: 30px;
    }

    .coupons-offers-section .search-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 26px;
    }

    .coupons-offers-section .search-section-head h2 {
        margin: 0;
        font-size: clamp(28px, 3vw, 40px);
        line-height: 1.1;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.03em;
    }

    .coupons-offers-section .search-count-badge {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 800;
        color: #e2185b;
        background: #fff4f7;
        border: 1px solid #f3c7d5;
        flex-shrink: 0;
    }

    .coupon-cards-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .cpn {
        background: linear-gradient(180deg, #ffffff 0%, #fffafb 100%);
        border: 1px solid #ece7eb;
        border-radius: 24px;
        /* overflow: hidden; */
        transition: all 0.28s ease;
        box-shadow: 0 6px 18px rgba(17, 17, 17, 0.04);
    }

    .cpn:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(17, 17, 17, 0.08);
        border-color: #f1d3dd;
    }

    .coupon-card-inner {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .cpn-cover {
        width: 100%;
        height: 220px;
        background: linear-gradient(135deg, #fdf2f6 0%, #fff 100%);
        overflow: hidden;
        position: relative;
    }

    .cpn-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .cnt {
        padding: 26px 24px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .cpn-logo-wrap {
        /* margin-top: -58px; */
        margin-bottom: 14px;
        position: relative;
        z-index: 2;
    }

    .cpn-logo {
        width: 92px;
        height: 92px;
        border-radius: 50%;
        background: #ffffff;
        border: 4px solid #ffffff;
        box-shadow: 0 10px 24px rgba(17, 17, 17, 0.10);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .cpn-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .cpn-initial {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #e2185b 0%, #b31247 100%);
        color: #ffffff;
        font-size: 30px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .cpn-store {
        font-size: 15px;
        font-weight: 700;
        color: #e2185b;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .cpn-title {
        margin: 0;
        font-size: clamp(20px, 2vw, 30px);
        line-height: 1.25;
        font-weight: 800;
        color: #0f172a;
        max-width: 900px;
    }

    .cpn-action {
        width: 100%;
        margin-top: 22px;
    }

    .cpBtn {
        width: 100%;
        min-height: 56px;
        border: none;
        outline: none;
        border-radius: 14px;
        background: linear-gradient(135deg, #ec1561 0%, #d90f58 100%);
        color: #ffffff !important;
        font-size: 20px;
        font-weight: 700;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
        box-shadow: 0 10px 20px rgba(236, 21, 97, 0.18);
    }

    .cpBtn:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #d90f58 0%, #c20e4f 100%);
        color: #ffffff !important;
        text-decoration: none !important;
        box-shadow: 0 14px 28px rgba(236, 21, 97, 0.24);
    }

    .pagination-wrapper {
        margin-top: 26px;
    }

    @media (max-width: 991px) {
        .coupons-offers-section {
            padding: 24px;
            border-radius: 22px;
        }

        .cpn-cover {
            height: 190px;
        }

        .cpn-logo {
            width: 82px;
            height: 82px;
        }

        .cpn-logo-wrap {
            margin-top: -50px;
        }

        .cpBtn {
            min-height: 52px;
            font-size: 18px;
        }
    }

    @media (max-width: 767px) {
        .coupons-offers-section {
            padding: 18px;
            border-radius: 18px;
        }

        .coupons-offers-section .search-section-head {
            margin-bottom: 18px;
        }

        .coupons-offers-section .search-section-head h2 {
            font-size: 28px;
        }

        .coupons-offers-section .search-count-badge {
            width: 44px;
            height: 44px;
            font-size: 15px;
        }

        .cpn {
            border-radius: 18px;
        }

        .cpn-cover {
            height: 160px;
        }

        .cnt {
            padding: 22px 16px 18px;
        }

        .cpn-logo {
            width: 72px;
            height: 72px;
        }

        .cpn-logo-wrap {
            margin-top: 44px;
            margin-bottom: 12px;
        }

        .cpn-store {
            font-size: 14px;
        }

        .cpn-title {
            font-size: 22px;
        }

        .cpBtn {
            min-height: 48px;
            font-size: 16px;
            border-radius: 12px;
        }
    }
</style>

@endpush

@section('content')
<div class="search-results-page">
    <div class="search-results-wrap">
        <div class="search-page-hero">
            <h1>Search Results for <span>"{{ $query }}"</span></h1>
            <p>Browse matching stores, verified coupons, and relevant categories in one clean view.</p>
        </div>

        @if($stores->count() > 0 || $coupons->count() > 0 || $categories->count() > 0)
            <div class="search-sections-stack">

                @if($stores->count() > 0)
                    <section class="search-section-card">
                        <div class="search-section-head">
                            <h2>Stores</h2>
                            <span class="search-count-badge">{{ $stores->count() }}</span>
                        </div>

                        <div class="stores-grid">
                            @foreach($stores as $store)
                                <div class="store-item">
                                    <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">
                                        @if($store->store_logo)
                                            <img src="{{ asset($store->store_logo) }}"
                                                 alt="{{ $store->store_name }}"
                                                 width="150"
                                                 height="150"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="store-placeholder" style="display:none;">{{ substr($store->store_name, 0, 2) }}</div>
                                        @else
                                            <div class="store-placeholder">{{ substr($store->store_name, 0, 2) }}</div>
                                        @endif
                                        <h3>{{ $store->store_name }}</h3>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        @if($stores->hasPages())
                            <div class="pagination-wrapper">
                                {{ $stores->links() }}
                            </div>
                        @endif
                    </section>
                @endif

                {{-- @if($coupons->count() > 0)
                    <section class="search-section-card">
                        <div class="search-section-head">
                            <h2>Coupons & Offers</h2>
                            <span class="search-count-badge">{{ $coupons->count() }}</span>
                        </div>
                        @foreach($coupons as $coupon)
                        <div class="cpn {{ $coupon->exclusive ? 'exclusive' : '' }} {{ $coupon->verified ? 'verified' : '' }}" data-id="{{ $coupon->id }}">

                            <!-- Cover Image -->
                            @if($coupon->cover_logo)
                                <div class="cpn-cover">
                                    <img src="{{ asset($coupon->cover_logo) }}"
                                        alt="{{ $coupon->coupon_title }}"
                                        onerror="this.style.display='none'">
                                </div>
                            @endif

                            <!-- Content -->
                            <div class="cnt">

                                <!-- Store Logo Circle -->
                                <div class="cpn-logo">
                                    @if($coupon->store && $coupon->store->store_logo)
                                        <img src="{{ asset($coupon->store->store_logo) }}"
                                            alt="{{ $coupon->store->store_name }}"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <span class="cpn-initial" style="display:none;">
                                            {{ strtoupper(substr($coupon->store->store_name ?? 'S', 0, 1)) }}
                                        </span>
                                    @else
                                        <span class="cpn-initial">
                                            {{ strtoupper(substr($coupon->store->store_name ?? 'S', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                            <!-- Content -->
                            <div class="cnt">

                                <!-- Store Logo Circle -->
                                <div class="cpn-logo">
                                    @if($coupon->store && $coupon->store->store_logo)
                                        <img src="{{ asset($coupon->store->store_logo) }}"
                                            alt="{{ $coupon->store->store_name }}"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <span class="cpn-initial" style="display:none;">
                                            {{ strtoupper(substr($coupon->store->store_name ?? 'S', 0, 1)) }}
                                        </span>
                                    @else
                                        <span class="cpn-initial">
                                            {{ strtoupper(substr($coupon->store->store_name ?? 'S', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Store Name -->
                                @if($coupon->store)
                                    <div class="cpn-store">{{ $coupon->store->store_name }}</div>
                                @endif
                                <!-- Store Name -->
                                @if($coupon->store)
                                    <div class="cpn-store">{{ $coupon->store->store_name }}</div>
                                @endif

                                <!-- Title -->
                                <h3 class="cpn-title">{{ $coupon->coupon_title }}</h3>

                                <!-- Button -->
                                @if($coupon->coupon_code)
                                    <button class="cpBtn" data-code="{{ $coupon->coupon_code }}">Reveal Code</button>
                                @else
                                    <a href="{{ $coupon->affiliate_url }}" class="cpBtn" target="_blank">Get Deal</a>
                                @endif

                            </div>
                        </div>
                        @endforeach

                        @if($coupons->hasPages())
                            <div class="pagination-wrapper">
                                {{ $coupons->links() }}
                            </div>
                        @endif
                    </section>
                @endif --}}
                @if($coupons->count() > 0)
                    <section class="search-section-card coupons-offers-section">
                        <div class="search-section-head">
                            <h2>Coupons & Offers</h2>
                            <span class="search-count-badge">{{ $coupons->count() }}</span>
                        </div>

                        <div class="coupon-cards-grid">
                            @foreach($coupons as $coupon)
                            <div class="cpn {{ $coupon->exclusive ? 'exclusive' : '' }} {{ $coupon->verified ? 'verified' : '' }}" data-id="{{ $coupon->id }}">

                                <div class="coupon-card-inner">
                                    <!-- Cover Image -->
                                    @if($coupon->cover_logo)
                                        <div class="cpn-cover">
                                            <img src="{{ asset($coupon->cover_logo) }}"
                                                alt="{{ $coupon->coupon_title }}"
                                                onerror="this.style.display='none'">
                                        </div>
                                    @endif

                                    <!-- Content -->
                                    <div class="cnt">

                                        <!-- Store Logo -->
                                        <div class="cpn-logo-wrap">
                                            <div class="cpn-logo">
                                                @if($coupon->store && $coupon->store->store_logo)
                                                    <img src="{{ asset($coupon->store->store_logo) }}"
                                                        alt="{{ $coupon->store->store_name }}"
                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <span class="cpn-initial" style="display:none;">
                                                        {{ strtoupper(substr($coupon->store->store_name ?? 'S', 0, 1)) }}
                                                    </span>
                                                @else
                                                    <span class="cpn-initial">
                                                        {{ strtoupper(substr($coupon->store->store_name ?? 'S', 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Store Name -->
                                        @if($coupon->store)
                                            <div class="cpn-store">{{ $coupon->store->store_name }}</div>
                                        @endif

                                        <!-- Title -->
                                        <h3 class="cpn-title">{{ $coupon->coupon_title }}</h3>

                                        <!-- Button -->
                                        <div class="cpn-action">
                                            @if($coupon->coupon_code)
                                                <button class="cpBtn" data-code="{{ $coupon->coupon_code }}">Reveal Code</button>
                                            @else
                                                <a href="{{ $coupon->affiliate_url }}" class="cpBtn" target="_blank">Get Deal</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            @endforeach
                        </div>

                        @if($coupons->hasPages())
                            <div class="pagination-wrapper">
                                {{ $coupons->links() }}
                            </div>
                        @endif
                    </section>
                    @endif


                @if($categories->count() > 0)
                    <section class="search-section-card">
                        <div class="search-section-head">
                            <h2>Categories</h2>
                            <span class="search-count-badge">{{ $categories->count() }}</span>
                        </div>

                        <div class="cat-grid">
                            @foreach($categories as $category)
                                <div class="cat-item">
                                    <a href="{{ route('category', $category->seo_url) }}" title="{{ $category->category_name }}">
                                        @if($category->media)
                                            <img src="{{ asset($category->media) }}"
                                                 alt="{{ $category->category_name }}"
                                                 width="100"
                                                 height="100"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="cat-icon" style="display:none;">{{ substr($category->category_name, 0, 1) }}</div>
                                        @else
                                            <div class="cat-icon">{{ substr($category->category_name, 0, 1) }}</div>
                                        @endif
                                        <h3>{{ $category->category_name }}</h3>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

            </div>
        @else
            <div class="no-results">
                <h2>No results found for "{{ $query }}"</h2>
                <p>Try searching with a different keyword or explore popular pages below.</p>
                <div class="suggestions">
                    <a href="{{ route('categories') }}" class="btn">Browse Categories</a>
                    <a href="{{ route('top-discounts') }}" class="btn">Top Discounts</a>
                    <a href="{{ route('all-brands') }}" class="btn">All Brands</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
