{{-- Product grid, rendered from [products ids="X,Y,Z"] in blog content --}}
<div class="pb-grid pb-grid--{{ $columns }}">
    @foreach($products as $product)
        @php $goUrl = route('product.go', $product); @endphp
        <div class="pb-tile">
            @if($product->badge)
                <span class="pb-badge">{{ $product->badge }}</span>
            @endif

            <a href="{{ $goUrl }}" class="pb-tile__media" target="_blank" rel="sponsored nofollow noopener" aria-label="{{ $product->name }}">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                @endif
            </a>

            <div class="pb-tile__body">
                @if($product->brand)
                    <div class="pb-brand">{{ $product->brand }}</div>
                @endif
                <div class="pb-title">{{ $product->name }}</div>

                @if($product->rating)
                    @include('frontend.partials.product-rating', ['rating' => (float) $product->rating])
                @endif

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

            @include('frontend.partials.product-schema', ['product' => $product])
        </div>
    @endforeach
</div>
