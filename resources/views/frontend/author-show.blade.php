@extends('frontend.layouts.app')

@section('title', $authorName . ' - Author Profile | Campus Deals')
@section('description', 'Browse all articles and guides written by ' . $authorName . ' on Campus Deals.')

@push('styles')
<link rel="preload" href="{{ asset('frontend_assets/css/fonts.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}" crossorigin>

<style>
/* Author Hero / Profile Card */
.author-hero-section {
    background: #f8f9fa;
    padding: 3.5rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.author-profile-card {
    max-width: 900px;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 16px;
    padding: 2.5rem;
    display: flex;
    gap: 2rem;
    align-items: flex-start;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
}

.author-profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: var(--primary-color, #2951c4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 2.5rem;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

.author-profile-avatar-image {
    object-fit: cover;
}

.author-profile-info {
    flex: 1;
    min-width: 0;
}

.author-profile-name {
    font-size: 0.8125rem;
    font-weight: 700;
    color: var(--primary-color, #2951c4);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 0.75rem;
}

.author-profile-bio {
    color: #475569;
    line-height: 1.8;
    font-size: 1rem;
    white-space: pre-line;
}

/* Posts Section */
.blog-section {
    padding: 4rem 0;
    background: #ffffff;
}

.section-header {
    text-align: center;
    margin-bottom: 2rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.75rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.title-normal {
    font-weight: 400;
    color: #64748b;
}

.title-highlight {
    font-weight: 700;
    color: var(--primary-color, #2951c4);
}

.section-subtitle {
    color: #64748b;
    font-size: 1rem;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto;
}

/* Blog Grid Layout (matches the main blog listing) */
.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 2.5rem;
}

.blog-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.blog-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    border-color: #e2e8f0;
}

.blog-link {
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.blog-image-wrapper {
    position: relative;
    width: 100%;
    height: 220px;
    overflow: hidden;
    background: #f1f5f9;
}

.blog-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.blog-card:hover .blog-image {
    transform: scale(1.05);
}

.blog-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
}

.placeholder-icon {
    font-size: 3rem;
    opacity: 0.5;
}

.blog-category-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: var(--primary-color, #2951c4);
    color: #ffffff;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
}

.blog-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.blog-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.blog-date,
.blog-views {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8125rem;
    color: #64748b;
    font-weight: 500;
}

.blog-date svg,
.blog-views svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

.blog-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex-grow: 1;
}

.blog-excerpt {
    font-size: 0.875rem;
    color: #334155 !important;
    line-height: 1.6;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-footer {
    margin-top: auto;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f5f9;
}

.read-more {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #004085 !important;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.blog-card:hover .read-more {
    gap: 0.75rem;
    color: #003366 !important;
}

.read-more svg {
    width: 16px;
    height: 16px;
    transition: transform 0.3s ease;
}

.blog-card:hover .read-more svg {
    transform: translateX(4px);
}

.blog-view-all {
    text-align: center;
    margin-top: 3rem;
}

.view-all-blog-btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background: var(--primary-color, #2951c4);
    color: #ffffff;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9375rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    border: none;
    cursor: pointer;
}

.view-all-blog-btn:hover {
    background: var(--primary-hover, #cc0000);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.view-all-blog-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.no-blogs {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: #ffffff;
    border-radius: 16px;
    border: 2px dashed #e2e8f0;
}

.no-blogs-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-blogs h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.no-blogs p {
    color: #64748b;
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 1200px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .author-hero-section {
        padding: 2.5rem 0;
    }

    .author-profile-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 2rem 1.5rem;
    }

    .blog-section {
        padding: 3rem 0;
    }

    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .blog-image-wrapper {
        height: 200px;
    }

    .blog-content {
        padding: 1.25rem;
    }

    .blog-title {
        font-size: 1rem;
    }

    .blog-excerpt {
        font-size: 0.8125rem;
    }
}

@media (max-width: 480px) {
    .blog-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .blog-image-wrapper {
        height: 180px;
    }

    .blog-content {
        padding: 1rem;
    }

    .blog-meta {
        gap: 0.75rem;
    }

    .blog-date,
    .blog-views {
        font-size: 0.75rem;
    }
}
</style>
@endpush

@section('content')

<!-- Author Profile -->
<div class="author-hero-section">
    <div class="container">
        <div class="author-profile-card">
            @if($authorAvatar)
                <img src="{{ asset($authorAvatar) }}" alt="{{ $authorName }}" class="author-profile-avatar author-profile-avatar-image" loading="lazy" width="100" height="100">
            @else
                <div class="author-profile-avatar">
                    {{ strtoupper(substr($authorName, 0, 1)) }}
                </div>
            @endif
            <div class="author-profile-info">
                <div class="author-profile-name">{{ $authorName }}</div>
                <div class="author-profile-bio">{{ $authorBio }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Posts by this Author -->
<div class="blog-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span class="title-normal">Posts by</span>
                <span class="title-highlight">{{ $authorName }}</span>
            </h2>
            <p class="section-subtitle">{{ $blogs->total() }} {{ Str::plural('article', $blogs->total()) }} published</p>
        </div>

        <!-- Blog Grid -->
        <div class="blog-grid">
            @include('frontend.partials.blog-content')
        </div>

        <!-- Show More Button -->
        @if($blogs->hasPages() && $blogs->hasMorePages())
        <div class="blog-view-all">
            <button id="loadMoreBtn" class="view-all-blog-btn" data-next-url="{{ $blogs->nextPageUrl() }}">
                Show More Posts →
            </button>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const blogsContainer = document.querySelector('.blog-grid');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const nextUrl = this.getAttribute('data-next-url');
            const originalText = this.innerHTML;

            this.innerHTML = 'Loading...';
            this.disabled = true;

            fetch(nextUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.text())
            .then(html => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                const newBlogs = tempDiv.querySelectorAll('.blog-card');
                newBlogs.forEach(blog => {
                    blogsContainer.appendChild(blog);
                });

                if (newBlogs.length < 6) {
                    this.parentElement.style.display = 'none';
                } else {
                    const currentUrl = new URL(nextUrl);
                    const currentPage = parseInt(currentUrl.searchParams.get('page') || '1');
                    const nextPage = currentPage + 1;
                    currentUrl.searchParams.set('page', nextPage);
                    this.setAttribute('data-next-url', currentUrl.toString());
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error loading more posts:', error);
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    }
});
</script>
@endpush
