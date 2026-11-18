@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
    ];
    if ($product->brand) {
        $schema['brand'] = ['@type' => 'Brand', 'name' => $product->brand];
    }
    if ($product->image) {
        $schema['image'] = asset($product->image);
    }
    if ($product->description) {
        $schema['description'] = $product->description;
    }
    $schemaPrice = $product->on_sale ? $product->sale_price : ($product->price ?? $product->sale_price);
    if ($schemaPrice !== null) {
        $schema['offers'] = [
            '@type' => 'Offer',
            'price' => (string) $schemaPrice,
            'url' => $product->affiliate_url,
            'availability' => 'https://schema.org/InStock',
        ];
        if ($product->merchant_name) {
            $schema['offers']['seller'] = ['@type' => 'Organization', 'name' => $product->merchant_name];
        }
    }
@endphp
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
