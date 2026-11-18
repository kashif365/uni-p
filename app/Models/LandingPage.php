<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPage extends Model
{
    protected $fillable = [
        'store_id',
        'featured_coupon_id',
        'slug',
        'heading',
        'subheading',
        'disclosure',
        'intro_content',
        'meta_title',
        'meta_description',
        'status',
        'no_index',
    ];

    protected $casts = [
        'status' => 'boolean',
        'no_index' => 'boolean',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function featuredCoupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'featured_coupon_id');
    }
}
