@extends('frontend.layouts.app')

@section('title', 'Privacy Policy - Campus Deals | Your Privacy Matters')
@section('description', 'Learn how Campus Deals protects your privacy and handles your personal information. Our comprehensive privacy policy explains our data practices.')
@section('keywords', 'Privacy Policy, Campus Deals, data protection, privacy rights, GDPR compliance, personal information')

@push('styles')
<link rel="preload" href="{{ asset('frontend_assets/css/fonts.css') }}" as="style" crossorigin>
<link rel="preload" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<style>
/* Dynamic Color Variables */
:root {
    --privacy-primary-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --privacy-primary-light: {{ $settings['primary_color'] ?? '#2951c4' }}20;
    --privacy-primary-lighter: {{ $settings['primary_color'] ?? '#2951c4' }}10;
    --privacy-primary-dark: {{ $settings['primary_color'] ?? '#2951c4' }}CC;
    --privacy-secondary-color: {{ $settings['secondary_color'] ?? '#ff4444' }};
    --privacy-accent-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --privacy-text-color: {{ $settings['text_color'] ?? '#2d3748' }};
    --privacy-heading-color: {{ $settings['text_color'] ?? '#1a202c' }};
    --privacy-background-color: {{ $settings['background_primary_color'] ?? '#ffffff' }};
    --privacy-card-background: {{ $settings['background_primary_color'] ?? '#ffffff' }};
}

/* Privacy Policy Page Styles */
.privacy-hero {
    background: 
        linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url('{{ asset('frontend_assets/images/search-bg.webp') }}') center/cover no-repeat;
    color: white;
    padding: 80px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.privacy-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, var(--privacy-primary-light) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, var(--privacy-primary-light) 0%, transparent 50%),
        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.privacy-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.privacy-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.privacy-hero p {
    font-size: 1.3rem;
    margin-bottom: 30px;
    opacity: 0.95;
    line-height: 1.6;
}

.privacy-main {
    padding: 80px 0;
    background: var(--privacy-background-color);
}

.privacy-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.privacy-intro {
    background: var(--privacy-primary-lighter);
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 50px;
    border-left: 5px solid var(--privacy-primary-color);
}

.privacy-intro h2 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--privacy-heading-color);
    margin-bottom: 20px;
}

.privacy-intro p {
    color: var(--privacy-text-color);
    line-height: 1.6;
    font-size: 1.1rem;
    margin-bottom: 15px;
}

.privacy-intro .last-updated {
    font-weight: 600;
    color: var(--privacy-primary-color);
    font-size: 1rem;
}

.privacy-section {
    margin-bottom: 50px;
    background: var(--privacy-card-background);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 1px solid var(--privacy-primary-lighter);
}

.privacy-section h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--privacy-heading-color);
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 15px;
}

.privacy-section h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, var(--privacy-primary-color) 0%, var(--privacy-primary-dark) 100%);
    border-radius: 2px;
}

.privacy-section h3 {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--privacy-heading-color);
    margin: 30px 0 15px;
}

.privacy-section p {
    color: var(--privacy-text-color);
    line-height: 1.7;
    margin-bottom: 20px;
    font-size: 1rem;
}

.privacy-section ul {
    color: var(--privacy-text-color);
    line-height: 1.7;
    margin-bottom: 20px;
    padding-left: 25px;
}

.privacy-section li {
    margin-bottom: 10px;
}

.privacy-section strong {
    color: var(--privacy-heading-color);
    font-weight: 600;
}

.privacy-section .highlight {
    background: var(--privacy-primary-lighter);
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid var(--privacy-primary-color);
    margin: 20px 0;
}

.privacy-section .highlight p {
    margin: 0;
    color: var(--privacy-primary-dark);
    font-weight: 500;
}

.contact-info {
    background: linear-gradient(135deg, var(--privacy-primary-lighter) 0%, var(--privacy-primary-light) 100%);
    padding: 30px;
    border-radius: 10px;
    margin-top: 30px;
    text-align: center;
}

.contact-info h3 {
    color: var(--privacy-heading-color);
    margin-bottom: 15px;
}

.contact-info p {
    margin: 5px 0;
    color: var(--privacy-text-color);
}

.contact-info a {
    color: var(--privacy-primary-color);
    text-decoration: none;
    font-weight: 600;
}

.contact-info a:hover {
    text-decoration: underline;
}

.table-of-contents {
    background: var(--privacy-primary-lighter);
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 50px;
}

.table-of-contents h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 25px;
    text-align: center;
}

.toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.toc-list li {
    margin-bottom: 10px;
}

.toc-list a {
    color: #6c757d;
    text-decoration: none;
    font-weight: 500;
    padding: 8px 15px;
    display: block;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.toc-list a:hover {
    background: var(--primary-color, #2951c4);
    color: white;
    transform: translateX(5px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .privacy-hero h1 {
        font-size: 2.5rem;
    }
    
    .privacy-hero p {
        font-size: 1.1rem;
    }
    
    .privacy-main {
        padding: 60px 0;
    }
    
    .privacy-container {
        padding: 0 15px;
    }
    
    .privacy-section,
    .privacy-intro {
        padding: 25px 20px;
    }
    
    .privacy-section h2 {
        font-size: 1.5rem;
    }
    
    .privacy-section h3 {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .privacy-hero {
        padding: 60px 0;
    }
    
    .privacy-hero h1 {
        font-size: 2rem;
    }
    
    .privacy-section,
    .privacy-intro {
        padding: 20px 15px;
    }
    
    .privacy-section h2 {
        font-size: 1.3rem;
    }
    
    .table-of-contents {
        padding: 20px 15px;
    }
}
</style>
@endpush

@section('content')
<div class="privacy-hero">
    <div class="privacy-hero-content">
        <h1>Privacy</h1>
        <p>Use sample information when trying this university demo.</p>
    </div>
</div>
<div class="privacy-main">
    <div class="privacy-container">
        <div class="privacy-section">
            <h2>Data in the local demo</h2>
            <p>The demo saves information you enter in the account, newsletter, and feedback forms in its local database. It uses a session cookie to keep you signed in. Coupon activity can record your session and IP address.</p>
            <p>In the default local setup, outgoing email is written to the application log.</p>
        </div>
    </div>
</div>
@endsection
