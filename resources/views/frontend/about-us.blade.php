@extends('frontend.layouts.app')

@section('title', $aboutPage->meta_title ?? 'About Us - Campus Deals | Your Trusted Discount Code Platform')
@section('description', $aboutPage->meta_description ?? 'Learn about Campus Deals - the UK\'s leading discount code platform. Discover our mission to help you save money with verified voucher codes and exclusive deals.')

@if($aboutPage && $aboutPage->canonical_url)
    <link rel="canonical" href="{{ $aboutPage->canonical_url }}">
@endif

@if($aboutPage && $aboutPage->meta_keywords)
    <meta name="keywords" content="{{ $aboutPage->meta_keywords }}">
@else
    <meta name="keywords" content="About Campus Deals, discount code platform, voucher codes UK, money saving, exclusive deals">
@endif

@if($aboutPage && $aboutPage->schema && trim($aboutPage->schema) !== '' && trim($aboutPage->schema) !== 'test')
    @php
        $schemaContent = trim($aboutPage->schema);
        // Check if it's already wrapped in script tag
        $isScriptTag = preg_match('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>/i', $schemaContent);
        // Check if it contains JSON structure
        $isJson = preg_match('/\{.*"@context".*\}/s', $schemaContent) || preg_match('/\{.*"@type".*\}/s', $schemaContent);
    @endphp
    @if($isScriptTag || $isJson)
        @push('head_scripts')
        @if(!$isScriptTag)
            <script type="application/ld+json">
        @endif
        {!! $schemaContent !!}
        @if(!$isScriptTag)
            </script>
        @endif
        @endpush
    @endif
@endif

@push('styles')
<link rel="preload" href="{{ asset('frontend_assets/css/fonts.css') }}" as="style" crossorigin>
<link rel="preload" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<style>
/* Dynamic Color Variables */
:root {
    --about-primary-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --about-primary-light: {{ $settings['primary_color'] ?? '#2951c4' }}20;
    --about-primary-lighter: {{ $settings['primary_color'] ?? '#2951c4' }}10;
    --about-primary-dark: {{ $settings['primary_color'] ?? '#2951c4' }}CC;
    --about-secondary-color: {{ $settings['secondary_color'] ?? '#ff4444' }};
    --about-accent-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --about-text-color: {{ $settings['text_color'] ?? '#2d3748' }};
    --about-heading-color: {{ $settings['text_color'] ?? '#1a202c' }};
    --about-background-color: {{ $settings['background_primary_color'] ?? '#ffffff' }};
    --about-card-background: {{ $settings['background_primary_color'] ?? '#ffffff' }};
}

/* About Us Page Styles */
.about-hero {
    background: 
        linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url('{{ asset('frontend_assets/images/search-bg.webp') }}') center/cover no-repeat;
    color: white;
    padding: 80px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.about-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, var(--about-primary-light) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, var(--about-primary-light) 0%, transparent 50%),
        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.about-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.about-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.about-hero p {
    font-size: 1.3rem;
    margin-bottom: 30px;
    opacity: 0.95;
    line-height: 1.6;
}

.about-stats {
    display: flex;
    justify-content: center;
    gap: 60px;
    margin-top: 40px;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    display: block;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.9;
}

.about-section {
    padding: 80px 0;
    background: var(--about-background-color);
}

.about-section:nth-child(even) {
    background: var(--about-primary-lighter);
}

.section-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--about-heading-color);
    margin-bottom: 20px;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(135deg, var(--about-primary-color) 0%, var(--about-primary-dark) 100%);
    border-radius: 2px;
}

.section-subtitle {
    font-size: 1.2rem;
    color: var(--about-text-color);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.mission-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
    margin-top: 60px;
}

.mission-card {
    background: var(--about-card-background);
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid var(--about-primary-lighter);
}

.mission-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border-color: var(--about-primary-light);
}

.mission-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--about-primary-color) 0%, var(--about-primary-dark) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 2rem;
    color: white;
}

.mission-card h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--about-heading-color);
    margin-bottom: 15px;
}

.mission-card p {
    color: var(--about-text-color);
    line-height: 1.6;
    font-size: 1rem;
}

.values-section {
    background: linear-gradient(135deg, var(--about-primary-lighter) 0%, var(--about-primary-light) 100%);
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 60px;
}

.value-item {
    background: var(--about-card-background);
    padding: 30px 25px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    border: 1px solid var(--about-primary-lighter);
}

.value-item:hover {
    transform: translateY(-5px);
    border-color: var(--about-primary-light);
}

.value-icon {
    font-size: 2.5rem;
    margin-bottom: 20px;
    color: var(--about-primary-color);
}

.value-item h4 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--about-heading-color);
    margin-bottom: 15px;
}

.value-item p {
    color: var(--about-text-color);
    line-height: 1.5;
    font-size: 0.95rem;
}

.team-section {
    background: white;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 40px;
    margin-top: 60px;
}

.team-member {
    background: white;
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.team-member:hover {
    transform: translateY(-10px);
}

.member-avatar {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 3rem;
    color: white;
    font-weight: 700;
}

.member-name {
    font-size: 1.4rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
}

.member-role {
    color: #667eea;
    font-weight: 500;
    margin-bottom: 15px;
}

.member-bio {
    color: #6c757d;
    line-height: 1.5;
    font-size: 0.95rem;
}

.cta-section {
    background: var(--about-primary-color);
    color: white;
    padding: 80px 0;
    text-align: center;
}

.cta-content {
    max-width: 600px;
    margin: 0 auto;
    padding: 0 20px;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.cta-text {
    font-size: 1.2rem;
    margin-bottom: 40px;
    opacity: 0.95;
    line-height: 1.6;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-btn {
    background: white;
    color: var(--about-primary-color);
    padding: 15px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    border: 2px solid white;
}

.cta-btn:hover {
    background: transparent;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.cta-btn.secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.cta-btn.secondary:hover {
    background: white;
    color: var(--about-primary-color);
}

/* Responsive Design */
@media (max-width: 768px) {
    .about-hero h1 {
        font-size: 2.5rem;
    }
    
    .about-hero p {
        font-size: 1.1rem;
    }
    
    .about-stats {
        gap: 30px;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .mission-grid,
    .values-grid,
    .team-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-btn {
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 480px) {
    .about-hero {
        padding: 60px 0;
    }
    
    .about-hero h1 {
        font-size: 2rem;
    }
    
    .about-section {
        padding: 60px 0;
    }
    
    .mission-card,
    .team-member {
        padding: 25px 20px;
    }
    
    .cta-section {
        padding: 60px 0;
    }
    
    .cta-title {
        font-size: 2rem;
    }
}
</style>
@endpush

@section('content')

<!-- About Us Hero Section -->
<div class="about-hero">
    <div class="about-hero-content">
        <h1>About Campus Deals</h1>
        <p>Campus Deals is a university project that demonstrates a searchable student coupon directory.</p>
        
    </div>
</div>

<!-- Call to Action Section -->
<div class="cta-section">
    <div class="cta-content">
        <h2 class="cta-title">Explore the Demo</h2>
        <p class="cta-text">Browse sample stores and try copying a fictional coupon code.</p>
        
        <div class="cta-buttons">
            <a href="{{ route('categories') }}" class="cta-btn">Browse Categories</a>
            <a href="{{ route('all-brands') }}" class="cta-btn secondary">View All Stores</a>
        </div>
    </div>
</div>

@endsection
