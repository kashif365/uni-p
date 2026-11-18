@extends('frontend.layouts.app')

@section('title', 'All Brands & Stores A-Z | Campus Deals')
@section('description', 'Browse all brands and stores alphabetically. Find discount codes and voucher codes from your favorite brands. Discover new stores and save money with verified offers.')
@section('keywords', 'all brands, all stores, brand directory, store directory, discount codes A-Z, voucher codes by brand')
@push('styles')
<link rel="preload" href="{{ asset('frontend_assets/css/fonts.css') }}" as="style" crossorigin>
<link rel="preload" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<style>
    /* =========================
   ALL STORES PAGE - CLEAN UI
========================= */

/* Alphabet Bar */
.rg {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 24px;
    justify-content: center;
}

.rg a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 12px;
    background: #ffffff;
    color: #374151;
    text-decoration: none;
    border-radius: 10px;
    border: 1px solid #ececec;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.25s ease;
}

.rg a:hover {
    border-color: #DE1A58;
    color: #DE1A58;
    transform: translateY(-1px);
}

.rg a.active {
    background: #DE1A58;
    color: #ffffff;
    border-color: #DE1A58;
}

/* Section */
.Sec.bg {
    background-color: #f7f7f7;
    padding: 28px 0;
}

.Wrp {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 24px;
}

/* Page title fix */
.pgHd .ttl {
    margin: 0;
    font-size: 34px;
    font-weight: 800;
    color: #111111;
    border: 0 !important;
    padding: 0 !important;
    display: block !important;
}

/* Letter box */
.bx {
    background: #ffffff;
    border-radius: 20px;
    padding: 30px 28px 34px;
    margin: 20px 0;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

/* ONLY box header */
.bx .ttl {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.bx .ttl h3 {
    margin: 0;
    font-size: 30px;
    font-weight: 800;
    line-height: 1.05;
    color: #111111;
    letter-spacing: -0.4px;
    position: relative;
}

.bx .ttl h3::after {
    content: "";
    display: block;
    width: 18px;
    height: 2px;
    border-radius: 10px;
    background: #DE1A58;
    margin-top: 8px;
}

.bx .ttl h3 a {
    color: #111111;
    text-decoration: none;
}

.bx .ttl h3 a:hover {
    color: #DE1A58;
}

.bx .ttl > a {
    color: #4b5563;
    text-decoration: none;
    font-size: 15px;
    font-weight: 600;
    transition: color 0.25s ease;
}

.bx .ttl > a:hover {
    color: #DE1A58;
}

/* Links wrapper reset */
.lnks {
    width: 100%;
}

.lnks > a {
    all: unset;
}

/* Brand grid */
.brand-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(200px, 1fr));
    gap: 34px 26px;
    align-items: start;
}

/* Brand card */
.brand-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    gap: 14px;
    text-align: center;
    text-decoration: none;
    min-width: 0;
    padding: 0 6px;
    transition: transform 0.25s ease;
}

.brand-card:hover {
    transform: translateY(-4px);
    text-decoration: none;
}

/* Brand circle */
.brand-avatar {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #f2e7eb;
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    flex-shrink: 0;
}

.brand-card:hover .brand-avatar {
    transform: scale(1.04);
    border-color: rgba(222, 26, 88, 0.22);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.09);
}

/* Brand logo */
.brand-avatar img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    background: #ffffff;
    padding: 14px;
}

/* Fallback initial */
.brand-initial {
    width: 100%;
    height: 100%;
    /* display: flex !important; */
    align-items: center;
    justify-content: center;
    font-size: 26px;
    font-weight: 800;
    color: #DE1A58;
    background: #fdf1f6;
    text-transform: uppercase;
    line-height: 1;
}

/* Brand title */
.brand-title {
    display: block;
    width: 100%;
    max-width: 210px;
    min-height: 40px;
    color: #1f2937;
    font-size: 15px;
    line-height: 1.3;
    text-align: center;
    font-weight: 500;
    letter-spacing: -0.1px;
    word-break: break-word;
    overflow-wrap: anywhere;
    transition: color 0.25s ease;
}

.brand-card:hover .brand-title {
    color: #DE1A58;
}

/* Empty text */
.lnks p {
    text-align: center;
    padding: 24px 18px;
    margin: 0;
    background: #ffffff;
    border-radius: 14px;
    color: #6b7280;
    font-size: 15px;
    border: 1px solid rgba(0,0,0,0.05);
}

/* Responsive */
@media (max-width: 1200px) {
    .brand-grid {
        grid-template-columns: repeat(4, minmax(170px, 1fr));
        gap: 30px 22px;
    }
}

@media (max-width: 992px) {
    .brand-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 28px 20px;
    }

    .brand-avatar {
        width: 90px;
        height: 90px;
    }
}

@media (max-width: 768px) {
    .rg {
        justify-content: flex-start;
    }

    .bx {
        padding: 24px 20px 28px;
        border-radius: 18px;
    }

    .bx .ttl {
        margin-bottom: 24px;
    }

    .bx .ttl h3 {
        font-size: 26px;
    }

    .brand-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px 16px;
    }

    .brand-avatar {
        width: 82px;
        height: 82px;
    }

    .brand-avatar img {
        padding: 12px;
    }

    .brand-title {
        font-size: 14px;
        min-height: 36px;
    }
}

@media (max-width: 576px) {
    .Wrp {
        padding: 0 16px;
    }

    .Sec.bg {
        padding: 20px 0;
    }

    .bx {
        padding: 20px 16px 24px;
        border-radius: 16px;
    }

    .bx .ttl h3 {
        font-size: 24px;
    }

    .brand-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px 14px;
    }

    .brand-avatar {
        width: 76px;
        height: 76px;
    }

    .brand-avatar img {
        padding: 10px;
    }

    .brand-initial {
        font-size: 22px;
    }

    .brand-title {
        max-width: 140px;
        font-size: 13px;
        min-height: 34px;
    }
}
</style>
@endpush

@section('content')
<!-- Page Content <start> -->
<div class="pgHd">
  <div class="Wrp">
    <!-- Breadcrumb <start> -->
    <ul class="brdcrb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a href="{{ route('home') }}" class="link" itemprop="item">
                <span itemprop="name">Home</span>
                <meta itemprop="position" content="1">
            </a>
        </li>
        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a href="{{ route('all-brands') }}" class="link active" itemprop="item">
                <span itemprop="name">All Stores</span>
                <meta itemprop="position" content="2">
            </a>
        </li>
    </ul>
    <!-- Breadcrumb <end> -->
    <!-- Page title <start> -->
    <h1 class="ttl">All Stores</h1>
    <h2 class="sp" style="text-align: center;margin-top:10px;">Browse All Stores</h2>
    <!-- Page title <end> -->
  </div>
</div>
<!-- sidebar wrp -->
<div class="Sec bg">
  <div class="Wrp">
    <div class="bg">
        <!-- alphabetical <start> -->
        <div class="rg">
            <a href="{{ route('all-brands') }}?q=0-9" title="0-9" class="{{ $query == '0-9' ? 'active' : '' }}">0-9</a>
            <a href="{{ route('all-brands') }}?q=a" title="A" class="{{ $query == 'a' ? 'active' : '' }}">A</a>
            <a href="{{ route('all-brands') }}?q=b" title="B" class="{{ $query == 'b' ? 'active' : '' }}">B</a>
            <a href="{{ route('all-brands') }}?q=c" title="C" class="{{ $query == 'c' ? 'active' : '' }}">C</a>
            <a href="{{ route('all-brands') }}?q=d" title="D" class="{{ $query == 'd' ? 'active' : '' }}">D</a>
            <a href="{{ route('all-brands') }}?q=e" title="E" class="{{ $query == 'e' ? 'active' : '' }}">E</a>
            <a href="{{ route('all-brands') }}?q=f" title="F" class="{{ $query == 'f' ? 'active' : '' }}">F</a>
            <a href="{{ route('all-brands') }}?q=g" title="G" class="{{ $query == 'g' ? 'active' : '' }}">G</a>
            <a href="{{ route('all-brands') }}?q=h" title="H" class="{{ $query == 'h' ? 'active' : '' }}">H</a>
            <a href="{{ route('all-brands') }}?q=i" title="I" class="{{ $query == 'i' ? 'active' : '' }}">I</a>
            <a href="{{ route('all-brands') }}?q=j" title="J" class="{{ $query == 'j' ? 'active' : '' }}">J</a>
            <a href="{{ route('all-brands') }}?q=k" title="K" class="{{ $query == 'k' ? 'active' : '' }}">K</a>
            <a href="{{ route('all-brands') }}?q=l" title="L" class="{{ $query == 'l' ? 'active' : '' }}">L</a>
            <a href="{{ route('all-brands') }}?q=m" title="M" class="{{ $query == 'm' ? 'active' : '' }}">M</a>
            <a href="{{ route('all-brands') }}?q=n" title="N" class="{{ $query == 'n' ? 'active' : '' }}">N</a>
            <a href="{{ route('all-brands') }}?q=o" title="O" class="{{ $query == 'o' ? 'active' : '' }}">O</a>
            <a href="{{ route('all-brands') }}?q=p" title="P" class="{{ $query == 'p' ? 'active' : '' }}">P</a>
            <a href="{{ route('all-brands') }}?q=q" title="Q" class="{{ $query == 'q' ? 'active' : '' }}">Q</a>
            <a href="{{ route('all-brands') }}?q=r" title="R" class="{{ $query == 'r' ? 'active' : '' }}">R</a>
            <a href="{{ route('all-brands') }}?q=s" title="S" class="{{ $query == 's' ? 'active' : '' }}">S</a>
            <a href="{{ route('all-brands') }}?q=t" title="T" class="{{ $query == 't' ? 'active' : '' }}">T</a>
            <a href="{{ route('all-brands') }}?q=u" title="U" class="{{ $query == 'u' ? 'active' : '' }}">U</a>
            <a href="{{ route('all-brands') }}?q=v" title="V" class="{{ $query == 'v' ? 'active' : '' }}">V</a>
            <a href="{{ route('all-brands') }}?q=w" title="W" class="{{ $query == 'w' ? 'active' : '' }}">W</a>
            <a href="{{ route('all-brands') }}?q=x" title="X" class="{{ $query == 'x' ? 'active' : '' }}">X</a>
            <a href="{{ route('all-brands') }}?q=y" title="Y" class="{{ $query == 'y' ? 'active' : '' }}">Y</a>
            <a href="{{ route('all-brands') }}?q=z" title="Z" class="{{ $query == 'z' ? 'active' : '' }}">Z</a>
        </div>
        <!-- alphabetical <end> -->

        {{-- @if(isset($storesByLetter) && count($storesByLetter) > 0)
            @foreach($storesByLetter as $letter => $stores)
                @if($stores->count() > 0)
                <!-- single box <start> -->
                <div class="bx">
                    <div class="ttl">
                        <h3>{{ strtoupper($letter) }}</h3>
                    </div>
                    <div class="lnks">
                        @foreach($stores as $store)
                            <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">{{ $store->store_name }}</a>
                        @endforeach
                    </div>
                </div>
                <!-- single box <end> -->
                @endif
            @endforeach
        @else
            <!-- single box <start> -->
            <div class="bx">
                <div class="ttl">
                    <h3>{{ strtoupper($query ?? 'A') }}</h3>
                </div>
                <div class="lnks">
                    @if(isset($stores) && $stores->count() > 0)
                        @foreach($stores as $store)
                            <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">{{ $store->store_name }}</a>
                        @endforeach
                    @else
                        <p>No stores found for this letter.</p>
                    @endif
                </div>
            </div>
            <!-- single box <end> -->
        @endif --}}
              @if(isset($storesByLetter) && count($storesByLetter) > 0)
    @foreach($storesByLetter as $letter => $stores)
        @if($stores->count() > 0)
            <!-- single box <start> -->
            <div class="bx">
                <div class="ttl">
                    <h3>{{ strtoupper($letter) }}</h3>
                </div>

                <div class="lnks">
                    <div class="brand-grid">
                        @foreach($stores as $store)
                            <a class="brand-card"
                               href="{{ route('store', $store->seo_url) }}"
                               title="{{ $store->store_name }}">

                                <div class="brand-avatar">
                                    @if(!empty($store->store_logo))
                                        <img src="{{ asset(ltrim($store->store_logo, '/')) }}"
                                             alt="{{ $store->store_name }}"
                                             loading="lazy"
                                             decoding="async"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                        <span class="brand-initial" style="display:none;">
                                            {{ strtoupper(substr($store->store_name ?? 'B', 0, 1)) }}
                                        </span>
                                    @else
                                        <span class="brand-initial">
                                            {{ strtoupper(substr($store->store_name ?? 'B', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>

                                <span class="brand-title">{{ $store->store_name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- single box <end> -->
        @endif
    @endforeach
@else
    <!-- single box <start> -->
    <div class="bx">
        <div class="ttl">
            <h3>{{ strtoupper($query ?? 'A') }}</h3>
        </div>

        <div class="lnks">
            @if(isset($stores) && $stores->count() > 0)
                <div class="brand-grid">
                    @foreach($stores as $store)
                        <a class="brand-card"
                           href="{{ route('store', $store->seo_url) }}"
                           title="{{ $store->store_name }}">

                            <div class="brand-avatar">
                                @if(!empty($store->store_logo))
                                    <img src="{{ asset(ltrim($store->store_logo, '/')) }}"
                                         alt="{{ $store->store_name }}"
                                         loading="lazy"
                                         decoding="async"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <span class="brand-initial" style="display:none;">
                                        {{ strtoupper(substr($store->store_name ?? 'B', 0, 1)) }}
                                    </span>
                                @else
                                    <span class="brand-initial">
                                        {{ strtoupper(substr($store->store_name ?? 'B', 0, 1)) }}
                                    </span>
                                @endif
                            </div>

                            <span class="brand-title">{{ $store->store_name }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <p>No stores found for this letter.</p>
            @endif
        </div>
    </div>
    <!-- single box <end> -->
@endif
    </div>
  </div>
</div>
<!-- sidebar wrp <end> -->

@endsection