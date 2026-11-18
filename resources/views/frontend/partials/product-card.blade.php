{{-- Single horizontal product card, rendered from [product id="X"] in blog content --}}
@php $goUrl = route('product.go', $product); @endphp
<div class="pb-card">
    @if($product->badge)
        <span class="pb-badge">{{ $product->badge }}</span>
    @endif

    <a href="{{ $goUrl }}" class="pb-card__media" target="_blank" rel="sponsored nofollow noopener" aria-label="{{ $product->name }}">
        @if($product->image)
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
        @endif
    </a>

    <div class="pb-card__body">
        @if($product->brand)
            <div class="pb-brand">{{ $product->brand }}</div>
        @endif
        <div class="pb-title">{{ $product->name }}</div>

        @if($product->rating)
            @include('frontend.partials.product-rating', ['rating' => (float) $product->rating])
        @endif

        @if($product->description)
            <p class="pb-desc">{{ $product->description }}</p>
        @endif

        @if($product->pros_list || $product->cons_list)
            <div class="pb-proscons">
                @if($product->pros_list)
                    <ul class="pb-pros">
                        @foreach($product->pros_list as $pro)
                            <li>{{ $pro }}</li>
                        @endforeach
                    </ul>
                @endif
                @if($product->cons_list)
                    <ul class="pb-cons">
                        @foreach($product->cons_list as $con)
                            <li>{{ $con }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <div class="pb-buy">
            @if($product->display_price)
                <div class="pb-price">
                    <span class="pb-price__now">{{ $product->display_price }}</span>
                    @if($product->on_sale)
                        <span class="pb-price__was">{{ $product->formatPrice($product->price) }}</span>
                    @endif
                </div>
            @endif
            <a href="{{ $goUrl }}" class="pb-btn" target="_blank" rel="sponsored nofollow noopener">{{ $product->button_label }}</a>
        </div>
    </div>

    @include('frontend.partials.product-schema', ['product' => $product])
</div>
