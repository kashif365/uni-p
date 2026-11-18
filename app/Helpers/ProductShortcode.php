<?php

namespace App\Helpers;

use App\Models\Product;

/**
 * Turns product shortcodes inside blog HTML into product cards.
 *
 *   [product id="12"]                       single horizontal card
 *   [products ids="12,15,18"]               grid (columns = number of products, max 3)
 *   [products ids="12,15,18,20" columns="2"] grid with a fixed column count (2–4)
 */
class ProductShortcode
{
    const PATTERN = '\[(products?)\b([^\]]*)\]';

    public static function render(?string $html): string
    {
        $html = (string) $html;

        if (!self::has($html)) {
            return $html;
        }

        $products = self::loadProducts($html);

        $replace = function ($m) use ($products) {
            return self::renderShortcode(strtolower($m[1]), self::parseAttributes($m[2]), $products);
        };

        // A shortcode alone in its paragraph replaces the whole <p>, so the card is not nested inside it
        $html = preg_replace_callback('/<p[^>]*>\s*' . self::PATTERN . '\s*<\/p>/i', $replace, $html);

        return preg_replace_callback('/' . self::PATTERN . '/i', $replace, $html);
    }

    public static function has(?string $html): bool
    {
        return (bool) preg_match('/' . self::PATTERN . '/i', (string) $html);
    }

    // Removes shortcodes, for plain-text uses such as excerpts and meta descriptions
    public static function strip(?string $html): string
    {
        return trim(preg_replace('/' . self::PATTERN . '/i', '', (string) $html));
    }

    private static function loadProducts(string $html)
    {
        preg_match_all('/' . self::PATTERN . '/i', $html, $matches, PREG_SET_ORDER);

        $ids = [];
        foreach ($matches as $m) {
            $ids = array_merge($ids, self::ids(self::parseAttributes($m[2])));
        }

        return Product::active()->whereIn('id', array_unique($ids))->get()->keyBy('id');
    }

    private static function renderShortcode(string $tag, array $attributes, $products): string
    {
        $items = collect(self::ids($attributes))
            ->map(fn ($id) => $products->get($id))
            ->filter()
            ->values();

        // Missing or disabled products simply disappear from the article
        if ($items->isEmpty()) {
            return '';
        }

        if ($tag === 'product' || $items->count() === 1) {
            return view('frontend.partials.product-card', ['product' => $items->first()])->render();
        }

        $columns = (int) ($attributes['columns'] ?? min($items->count(), 3));
        $columns = max(2, min(4, $columns));

        return view('frontend.partials.product-grid', ['products' => $items, 'columns' => $columns])->render();
    }

    private static function parseAttributes(string $raw): array
    {
        $raw = html_entity_decode(strip_tags($raw), ENT_QUOTES | ENT_HTML5);
        preg_match_all('/(\w+)\s*=\s*["\']?([^"\']*?)["\']?(?=\s+\w+\s*=|\s*$)/', trim($raw), $pairs, PREG_SET_ORDER);

        $attributes = [];
        foreach ($pairs as $pair) {
            $attributes[strtolower($pair[1])] = trim($pair[2]);
        }

        return $attributes;
    }

    private static function ids(array $attributes): array
    {
        $value = $attributes['ids'] ?? $attributes['id'] ?? '';

        return array_values(array_filter(array_map('intval', explode(',', $value))));
    }
}
