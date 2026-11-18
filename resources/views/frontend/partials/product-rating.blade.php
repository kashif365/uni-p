<div class="pb-rating" aria-label="Rated {{ $rating }} out of 5">
    <span class="pb-rating__stars" style="--pb-rating: {{ $rating / 5 * 100 }}%">★★★★★</span>
    <span class="pb-rating__value">{{ number_format($rating, 1) }}</span>
</div>
