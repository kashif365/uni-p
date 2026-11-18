@extends('frontend.layouts.app')

@section('title', 'Browse Discount Codes by Category | Campus Deals')
@section('description', 'Browse discount codes and voucher codes organized by category. Find deals for Fashion, Electronics, Food, Travel, Health & Beauty, and more.')
@section('keywords', 'discount codes by category, voucher codes by category, category deals, shopping categories, discount categories')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend_assets/css/store.css') }}">
<style>
/* Dynamic Color Variables */
:root {
    --categories-primary-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --categories-primary-light: {{ $settings['primary_color'] ?? '#2951c4' }}20;
    --categories-primary-lighter: {{ $settings['primary_color'] ?? '#2951c4' }}10;
    --categories-primary-dark: {{ $settings['primary_color'] ?? '#2951c4' }}CC;
    --categories-secondary-color: {{ $settings['secondary_color'] ?? '#ff4444' }};
    --categories-accent-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --categories-text-color: {{ $settings['text_color'] ?? '#2d3748' }};
    --categories-heading-color: {{ $settings['text_color'] ?? '#1a202c' }};
    --categories-background-color: {{ $settings['background_primary_color'] ?? '#ffffff' }};
    --categories-card-background: {{ $settings['background_primary_color'] ?? '#ffffff' }};
}

/* Categories Layout - Clean Large Circle Design */
.Sec.bg {
    background-color: #f7f7f7;
    padding: 28px 0;
}

.Wrp {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 24px;
}

.bx {
    background-color: #ffffff;
    border-radius: 20px;
    padding: 34px 30px 36px;
    margin: 20px 0;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0,0,0,0.05);
}

.ttl {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    border-bottom: 1px solid rgba(0,0,0,0.06);
    padding-bottom: 16px;
}

.ttl h3 {
    font-size: 30px;
    font-weight: 800;
    color: var(--categories-heading-color);
    margin: 0;
    letter-spacing: -0.4px;
}

.ttl h3 a {
    color: var(--categories-heading-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.ttl h3 a:hover {
    color: var(--categories-primary-color);
}

.ttl a {
    color: var(--categories-text-color);
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    transition: color 0.3s ease;
}

.ttl a:hover {
    color: var(--categories-primary-color);
}

.imgs {
    display: flex;
    gap: 18px;
    margin-bottom: 34px;
    flex-wrap: wrap;
    justify-content: flex-start;
}

.imgs a {
    background: #fff;
    border: 1px solid #e9e9e9;
    border-radius: 12px;
    padding: 12px;
    transition: all 0.25s ease;
    width: 112px;
    height: 112px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.imgs a:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
}

.imgs img {
    width: 86px;
    height: 86px;
    object-fit: contain;
}

/* MAIN BRAND GRID */
.brand-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(220px, 1fr));
    gap: 34px 30px;
    margin-top: 24px;
    align-items: start;
}

/* EACH CARD */
.brand-card {
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    transition: transform 0.25s ease;
    text-align: center;
    padding: 4px 8px;
}

.brand-card:hover {
    transform: translateY(-4px);
    text-decoration: none;
}

/* BIGGER CIRCLE */
.brand-avatar {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: #fff;
    border: 1px solid #f0e7eb;
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
}

.brand-card:hover .brand-avatar {
    transform: scale(1.04);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    border-color: rgba(222, 26, 88, 0.18);
}

.brand-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    background: #fff;
}

/* FALLBACK LETTER */
.brand-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 800;
    color: #DE1A58;
    background: #fdf1f6;
    text-transform: uppercase;
}

/* BIGGER TITLE */
.brand-title {
    color: #1f2937;
    font-size: 17px;
    line-height: 1.18;
    text-align: center;
    word-break: break-word;
    max-width: 200px;
    font-weight: 500;
    transition: color 0.25s ease;
    letter-spacing: -0.2px;
}

.brand-card:hover .brand-title {
    color: #DE1A58;
}

.no-categories {
    text-align: center;
    padding: 40px 20px;
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.06);
}

.no-categories h3 {
    font-size: 24px;
    color: var(--categories-heading-color);
    margin-bottom: 10px;
}

.no-categories p {
    font-size: 16px;
    color: var(--categories-text-color);
}

/* LARGE DESKTOP */
@media (max-width: 1200px) {
    .brand-grid {
        grid-template-columns: repeat(4, minmax(180px, 1fr));
        gap: 30px 24px;
    }

    .brand-avatar {
        width: 92px;
        height: 92px;
    }

    .brand-title {
        font-size: 16px;
    }
}

/* TABLET */
@media (max-width: 992px) {
    .brand-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 28px 22px;
    }

    .imgs {
        justify-content: flex-start;
    }

    .brand-avatar {
        width: 90px;
        height: 90px;
    }

    .brand-title {
        font-size: 16px;
        max-width: 180px;
    }
}

/* SMALL TABLET */
@media (max-width: 768px) {
    .brand-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px 18px;
    }

    .ttl {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .imgs {
        gap: 12px;
    }

    .imgs a {
        width: 90px;
        height: 90px;
    }

    .imgs img {
        width: 68px;
        height: 68px;
    }

    .brand-avatar {
        width: 82px;
        height: 82px;
    }

    .brand-title {
        font-size: 15px;
        line-height: 1.2;
    }
}

/* MOBILE — horizontal swipe slider */
@media (max-width: 576px) {
    .bx {
        padding: 22px 16px 26px;
        border-radius: 16px;
    }

    .ttl h3 {
        font-size: 24px;
    }

    /* Switch grid → horizontal scroll slider */
    .brand-grid {
        display: flex;
        flex-direction: row;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        gap: 14px;
        /* bleed to container edges; leading padding aligns with content above */
        padding: 4px 0 10px 16px;
        margin: 0 -16px;
    }

    .brand-grid::-webkit-scrollbar { display: none; }

    /* trailing spacer so last card has breathing room at the right edge */
    .brand-grid::after {
        content: '';
        flex-shrink: 0;
        width: 16px;
    }

    /* ~3.5 cards visible at once — peek of next card signals swipeability */
    .brand-card {
        flex: 0 0 28vw;
        min-width: 88px;
        max-width: 115px;
        scroll-snap-align: start;
    }

    .brand-avatar {
        width: 76px;
        height: 76px;
    }

    .brand-initial {
        font-size: 24px;
    }

    .brand-title {
        font-size: 13px;
        max-width: 100%;
    }
}
</style>
@endpush

@section('content')
<!-- Page title <start> -->
<div class="pgHd">
  <div class="Wrp">
    <!-- Breadcrumb <start> -->
    <ul class="brdcrb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
        <a href="{{ route('home') }}" class="link" itemprop="item">
          <span itemprop="name">Home</span>
          <meta itemprop="position" content="1">
        </a>
      </li>
          <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
          <a href="javascript:;" class="link" itemprop="item">
            <span itemprop="name">Categories</span>
            <meta itemprop="position" content="2">
          </a>
        </li>
    </ul>
    <!-- Breadcrumb <end> -->
          <!-- Top 20 Discounts Head <start> -->
      <div class="dcHd" style="background-image: url('assets/images/svg/dc20.svg');">
        <h1>Browse By Categories</h1>
      </div>
      <!-- Top 20 Discounts Head <end> -->

  </div>
</div>
<!-- Page title <end> -->

<!-- Dynamic Categories Section -->
<div class="Sec bg">
  <div class="Wrp">
    <div>
      @if(isset($categories) && count($categories) > 0)
        @foreach($categories as $category)
          <div class="bx">
            <div class="ttl">
              <h2><a href="{{ route('category', $category->seo_url) }}" title="{{ $category->category_name }}">{{ $category->category_name }}</a></h2>
              <a href="{{ route('category', $category->seo_url) }}" title="{{ $category->category_name }}">View All <i class="bp_visit"></i></a>
            </div>
            <div class="lnks">
              @if($category->brands && count($category->brands) > 0)

                <div class="brand-grid">
                  @foreach($category->brands as $brand)
                    <a class="brand-card" href="{{ route('store', $brand->seo_url) }}" title="{{ $brand->store_name }}">
                      <div class="brand-avatar">
                        @if(!empty($brand->store_logo))
                          <img src="{{ asset(ltrim($brand->store_logo, '/')) }}"
                               alt="{{ $brand->store_name }}"
                               loading="lazy"
                               decoding="async"
                               onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                          <span class="brand-initial" style="display:none;">{{ strtoupper(substr($brand->store_name ?? 'B', 0, 1)) }}</span>
                        @else
                          <span class="brand-initial">{{ strtoupper(substr($brand->store_name ?? 'B', 0, 1)) }}</span>
                        @endif
                      </div>
                      <span class="brand-title">{{ $brand->store_name }}</span>
                    </a>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        @endforeach
      @else
        <div class="no-categories">
          <h3>No categories available</h3>
          <p>Please check back later for available categories.</p>
        </div>
      @endif
    </div>
  </div>
</div>

<!-- Enhanced Coupon Modal -->
<div id="couponModal" aria-hidden="true" style="display:none;">
    <div class="cm-overlay"></div>

    <!-- Main Voucher Code Popup -->
    <div class="cm-main-popup" role="dialog" aria-modal="true" aria-label="Coupon Code Popup">
        <button class="cm-close" aria-label="Close popup">&times;</button>

        <!-- Main Popup Content -->
        <div class="cm-main-content">
            <h3 class="cm-title" id="cmTitle">Here is your code</h3>

            <div class="cm-code-section">
                <div class="cm-code-display" id="cmCode">CODE123</div>
                <button class="cm-copy-btn" id="cmCopy">Copy Code</button>
                <button class="cm-redirect-btn" id="cmRedirect">Visit Store</button>
            </div>

            <div class="cm-note" id="cmNote">
                <p>Copy the code above and use it at checkout to get your discount!</p>
            </div>
        </div>
    </div>

    <!-- Email Subscription Popup -->
    <div class="cm-email-popup" role="dialog" aria-modal="true" aria-label="Email Subscription Popup">
        <div class="cm-email-content">
            <div class="cm-brand-logo">
                <div class="cm-brand-circle" id="cmBrandLogo">
                    <span id="cmBrandText">STORE</span>
                </div>
            </div>

            <h3 class="cm-email-title" id="cmEmailTitle">Get More Deals!</h3>
            <p class="cm-email-subtitle text-center">Subscribe to get exclusive offers and discounts</p>

            <form class="cm-email-form" id="cmEmailForm">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit">Subscribe</button>
            </form>

            <p class="cm-email-privacy">We respect your privacy. Unsubscribe at any time.</p>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Coupon Modal Styles */
#couponModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    padding: 20px;
    box-sizing: border-box;
}

.cm-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
}

/* Main Voucher Code Popup */
.cm-main-popup {
  position: relative;
  top: 20px;
  margin: auto;
    width: 480px;
    max-width: calc(50% - 30px);
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    overflow: hidden;
    z-index: 2;
}

/* Email Subscription Popup */
.cm-email-popup {
  position: relative;
  margin: auto;
  top: 40px;
    width: 480px;
    max-width: calc(50% - 30px);
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    overflow: hidden;
    z-index: 2;
}

.cm-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    z-index: 3;
}

.cm-close:hover {
    color: #000;
}

.cm-main-content {
    text-align: center;
}

.cm-title {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 25px;
    line-height: 1.2;
}

.cm-code-section {
    margin: 20px 0;
}

.cm-code-display {
    background: var(--categories-primary-lighter);
    border: 2px dashed var(--categories-primary-color);
    border-radius: 8px;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: var(--categories-primary-color);
    margin-bottom: 15px;
    font-family: monospace;
}

.cm-copy-btn {
    background: var(--categories-primary-color);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    width: 100%;
}

.cm-copy-btn:hover {
    background: var(--categories-primary-dark);
}

.cm-redirect-btn {
    background: var(--categories-secondary-color, #333);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    margin-top: 10px;
    width: 100%;
}

.cm-redirect-btn:hover {
    background: var(--categories-text-color, #555);
}

.cm-note {
    color: #6b7280;
    font-size: 15px;
    margin: 20px 0;
    line-height: 1.6;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Email Popup Content */
.cm-brand-logo {
    margin-bottom: 15px;
}

.cm-brand-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--categories-primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-weight: bold;
    font-size: 14px;
}

.cm-email-title {
  text-align: center;
  font-size: 22px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}

.cm-email-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 15px;
}

.cm-email-form input {
    padding: 12px 14px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.cm-email-form input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.cm-email-form button {
    width: 100%;
    background: var(--categories-primary-color);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.cm-email-form button:hover {
    background: var(--categories-primary-dark);
}

.cm-email-privacy {
    font-size: 11px;
    color: #6b7280;
    line-height: 1.4;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    #couponModal {
        gap: 15px;
    }

    .cm-main-popup {
        width: 95%;
        max-width: 450px;
    }

    .cm-email-popup {
        width: 95%;
        max-width: 450px;
    }

    .cm-main-content {
        padding: 25px 20px 20px;
    }

    .cm-email-content {
        padding: 20px 15px 15px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Prevent double-init
  if (window.__couponModalInit) return;
  window.__couponModalInit = true;

  const modal = document.getElementById('couponModal');
  if (!modal) return;

  const overlay = modal.querySelector('.cm-overlay');
  const closeBtn = modal.querySelector('.cm-close');
  const cmCode = document.getElementById('cmCode');
  const cmCopy = document.getElementById('cmCopy');
  const cmTitle = document.getElementById('cmTitle');
  const cmNote = document.getElementById('cmNote');
  const cmEmailTitle = document.getElementById('cmEmailTitle');
  const cmEmailForm = document.getElementById('cmEmailForm');
  const cmBrandLogo = document.getElementById('cmBrandLogo');
  const cmBrandText = document.getElementById('cmBrandText');

  function openModal(code, affiliate, store, title) {
    if (cmCode) cmCode.textContent = code || '';
    if (cmTitle) cmTitle.textContent = title || 'Here is your code';
    if (cmEmailTitle) cmEmailTitle.textContent = `Get More ${store} Deals!`;

    // Store affiliate URL for redirect button
    window.currentAffiliateUrl = affiliate;

    if (cmBrandLogo && cmBrandText) {
      if (store && store !== 'Store') {
        cmBrandText.textContent = store.substring(0,5).toUpperCase();
      } else {
        cmBrandText.textContent = 'STORE';
      }
    }

    // Hide Copy Code button if "No code required" is displayed
    if (cmCopy && cmCode) {
      if (code === 'No code required' || code === '' || !code) {
        cmCopy.style.display = 'none';
      } else {
        cmCopy.style.display = 'block';
      }
    }

    modal.style.display = 'block';
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  }

  // Store link click handlers - Direct redirect without popup
  document.querySelectorAll('.brand-grid .brand-card').forEach(link => {
    link.addEventListener('click', function(e) {
      // Allow normal link behavior - direct redirect to store page
      // No preventDefault() - let the link work normally
      // No popup - direct navigation to store page
    });
  });

  // Copy button
  if (cmCopy) {
    cmCopy.addEventListener('click', function() {
      const code = cmCode ? cmCode.textContent : '';
      if (code && code !== 'No code required') {
        navigator.clipboard.writeText(code).then(function() {
          const originalText = cmCopy.textContent;
          cmCopy.textContent = 'Copied!';
          cmCopy.style.backgroundColor = '#218838';

          setTimeout(function() {
            cmCopy.textContent = originalText;
            cmCopy.style.backgroundColor = '#28a745';
          }, 2000);
        }).catch(function(err) {
          console.error('Could not copy text: ', err);
          alert('Coupon Code: ' + code);
        });
      } else if (code === 'No code required') {
        // For deals without codes, just show message
        const originalText = cmCopy.textContent;
        cmCopy.textContent = 'No Code Needed!';
        cmCopy.style.backgroundColor = '#218838';

        setTimeout(function() {
          cmCopy.textContent = originalText;
          cmCopy.style.backgroundColor = '#28a745';
        }, 2000);
      }
    });
  }

  // Visit Store button
  const cmRedirect = document.getElementById('cmRedirect');
  if (cmRedirect) {
    cmRedirect.addEventListener('click', function() {
      const currentAffiliate = window.currentAffiliateUrl || '#';
      if (currentAffiliate && currentAffiliate !== '#') {
        window.open(currentAffiliate, '_blank');
      }
    });
  }

  // Email form
  if (cmEmailForm) {
    cmEmailForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const email = this.querySelector('input[type="email"]').value;
      if (email) {
        // Here you can add AJAX call to subscribe
        alert('Thank you for subscribing!');
        closeModal();
      }
    });
  }

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (overlay) overlay.addEventListener('click', closeModal);

  // show modal if params present
  try {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('show_coupon') === '1') {
      const code = urlParams.get('code') || '';
      const affiliate = urlParams.get('affiliate') || '#';
      const store = urlParams.get('store') || 'Store';
      const title = urlParams.get('title') || 'Here is your code';

      openModal(code, affiliate, store, title);

      // If no code, show "No code required" message
      if (!code) {
        if (cmCode) cmCode.textContent = 'No code required';
        if (cmCopy) {
          cmCopy.disabled = true;
          cmCopy.style.opacity = '0.6';
          cmCopy.style.cursor = 'not-allowed';
        }
      }

      history.replaceState({}, '', window.location.pathname);
    }
  } catch (e) {
    console.log('URL params not supported');
  }
});
</script>
@endpush
