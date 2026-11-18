<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'image',
        'description',
        'price',
        'sale_price',
        'currency',
        'affiliate_url',
        'merchant_name',
        'button_text',
        'badge',
        'rating',
        'pros',
        'cons',
        'status',
        'sort_order',
        'clicks',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'rating' => 'decimal:1',
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id', 'desc');
    }

    // Shortcode an editor pastes into blog content to show this product
    public function getShortcodeAttribute()
    {
        return '[product id="' . $this->id . '"]';
    }

    // True when a lower sale price should be shown next to the struck-through regular price
    public function getOnSaleAttribute()
    {
        return $this->sale_price !== null && $this->price !== null && $this->sale_price < $this->price;
    }

    // Price shown as the main price on the card
    public function getDisplayPriceAttribute()
    {
        $amount = $this->on_sale ? $this->sale_price : ($this->price ?? $this->sale_price);

        return $amount !== null ? $this->formatPrice($amount) : null;
    }

    public function formatPrice($amount)
    {
        $formatted = fmod((float) $amount, 1) == 0
            ? number_format((float) $amount, 0)
            : number_format((float) $amount, 2);

        return ($this->currency ?: '$') . $formatted;
    }

    public function getButtonLabelAttribute()
    {
        if ($this->button_text) {
            return $this->button_text;
        }

        return $this->merchant_name ? 'View at ' . $this->merchant_name : 'Check Price';
    }

    public function getProsListAttribute()
    {
        return $this->splitLines($this->pros);
    }

    public function getConsListAttribute()
    {
        return $this->splitLines($this->cons);
    }

    private function splitLines($value)
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $value))));
    }
}
