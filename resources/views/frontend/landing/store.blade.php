@php
    $branding = \App\Helpers\SettingsHelper::getBranding();
    $primary = $branding['primary_color'] ?? '#2951c4';
    $brandName = $store->store_name;
    $pageTitle = $landingPage->meta_title ?: ($landingPage->heading ?: $brandName.' Coupons & Promo Codes');
    $pageDescription = $landingPage->meta_description ?: 'Browse verified '.$brandName.' coupon codes, promotional offers and online deals.';
    $activeCount = $coupons->count();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    @if($landingPage->no_index)
        <meta name="robots" content="noindex, nofollow">
    @endif
    <link rel="canonical" href="{{ route('landing.show', $landingPage->slug) }}">
    @if($branding['site_favicon_url'])<link rel="icon" href="{{ $branding['site_favicon_url'] }}">@endif
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}">
    <style>
        :root { --lp-primary: {{ $primary }}; --lp-ink: #0f172a; --lp-muted: #64748b; --lp-line: #dbe2ea; --lp-soft: #f5f7fb; }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; background: #fff; color: var(--lp-ink); font-family: Poppins, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        button, a { font: inherit; }
        .lp-topline { height: 4px; background: var(--lp-primary); }
        .lp-sitebar { border-bottom: 1px solid #edf0f4; background: #fff; }
        .lp-sitebar-inner { width: min(1180px, calc(100% - 40px)); min-height: 68px; margin: auto; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
        .lp-site-logo { display: inline-flex; align-items: center; max-width: 180px; color: var(--lp-ink); font-weight: 800; text-decoration: none; }
        .lp-site-logo img { display: block; max-width: 100%; max-height: 46px; object-fit: contain; }
        .lp-back { color: var(--lp-muted); font-size: .84rem; font-weight: 650; text-decoration: none; }
        .lp-back:hover { color: var(--lp-primary); }
        .lp-hero { background: linear-gradient(180deg, #fff 0%, #f8fafc 100%); border-bottom: 1px solid #edf0f4; }
        .lp-hero-inner { width: min(1180px, calc(100% - 40px)); min-height: 150px; margin: auto; display: grid; grid-template-columns: 230px 1fr; gap: 44px; align-items: center; }
        .lp-hero-copy { padding: 30px 0; }
        .lp-hero-copy-head { display: block; }
        .lp-hero-brandmark { display: none; }
        .lp-hero h1 { margin: 0 0 10px; color: var(--lp-ink); font-size: clamp(1.8rem, 4vw, 2.75rem); line-height: 1.12; letter-spacing: -.04em; }
        .lp-summary { margin: 0; color: var(--lp-muted); font-size: .78rem; font-weight: 750; letter-spacing: .08em; text-transform: uppercase; }
        .lp-layout { width: min(1180px, calc(100% - 40px)); margin: 0 auto; display: grid; grid-template-columns: 230px minmax(0, 1fr); gap: 44px; align-items: start; }
        .lp-rail { position: sticky; top: 22px; margin-top: -112px; padding-bottom: 40px; z-index: 2; }
        .lp-brandmark { width: 176px; height: 176px; display: grid; place-items: center; overflow: hidden; border: 8px solid #fff; border-radius: 50%; background: var(--lp-primary); box-shadow: 0 16px 35px rgba(15,23,42,.18); color: #fff; text-align: center; }
        .lp-brandmark img { width: 100%; height: 100%; padding: 25px; object-fit: contain; background: #fff; }
        .lp-brand-initials { padding: 18px; font-size: 1.35rem; font-weight: 800; letter-spacing: .06em; overflow-wrap: anywhere; }
        .lp-disclosure { margin: 28px 0 0; color: #475569; font-size: .78rem; line-height: 1.65; }
        .lp-store-link { display: inline-flex; align-items: center; gap: 6px; margin-top: 18px; color: var(--lp-primary); font-size: .8rem; font-weight: 750; text-decoration: none; }
        .lp-main { min-width: 0; padding: 34px 0 70px; }
        .lp-list { display: grid; gap: 14px; }
        .lp-offer { position: relative; border: 1px solid var(--lp-line); border-radius: 15px; background: #fff; overflow: hidden; box-shadow: 0 5px 14px rgba(15,23,42,.035); transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
        .lp-offer:hover { transform: translateY(-2px); border-color: color-mix(in srgb, var(--lp-primary) 42%, white); box-shadow: 0 13px 28px rgba(15,23,42,.08); }
        .lp-offer-top { padding: 8px 15px; background: var(--lp-ink); color: #fff; font-size: .72rem; font-weight: 800; }
        .lp-offer-body { display: grid; grid-template-columns: 105px minmax(0,1fr) 165px; gap: 18px; align-items: center; padding: 22px 20px 14px; }
        .lp-saving { color: var(--lp-primary); font-size: 1.45rem; font-weight: 800; line-height: .98; letter-spacing: -.04em; text-transform: uppercase; }
        .lp-saving small { display: block; margin-top: 5px; color: var(--lp-muted); font-size: .68rem; font-weight: 650; letter-spacing: .04em; }
        .lp-type { display: inline-block; margin-bottom: 7px; padding: 3px 7px; border-radius: 4px; background: #eef2f7; color: #334155; font-size: .63rem; font-weight: 750; }
        .lp-offer h2 { margin: 0 0 9px; font-size: 1.08rem; font-weight: 650; line-height: 1.4; letter-spacing: -.015em; }
        .lp-badges { display: flex; flex-wrap: wrap; gap: 6px; }
        .lp-badge { padding: 3px 7px; border-radius: 5px; background: #f1f5f9; color: #475569; font-size: .61rem; font-weight: 700; }
        .lp-badge--verified { background: #dcfce7; color: #166534; }
        .lp-badge--expiry { background: #fff7ed; color: #9a3412; }
        .lp-used { margin-top: 8px; color: var(--lp-muted); font-size: .68rem; font-weight: 650; }
        .lp-used strong { color: var(--lp-ink); font-weight: 800; }
        .lp-action { width: 100%; min-height: 46px; padding: 11px 16px; border: 0; border-radius: 999px; background: var(--lp-primary); color: #fff; font-weight: 800; cursor: pointer; box-shadow: 0 8px 18px color-mix(in srgb, var(--lp-primary) 25%, transparent); transition: transform .18s ease, filter .18s ease; }
        .lp-action:hover { transform: translateY(-1px); filter: brightness(1.08); }
        .lp-action:focus-visible, .lp-details summary:focus-visible, a:focus-visible { outline: 3px solid var(--lp-primary); outline-offset: 3px; }
        .lp-action.is-revealed { background: var(--lp-ink); letter-spacing: .08em; }
        .lp-details { margin: 0 20px; border-top: 1px solid #e8edf2; }
        .lp-details summary { padding: 10px 0 12px; color: #475569; font-size: .69rem; font-weight: 700; text-align: right; cursor: pointer; list-style: none; }
        .lp-details summary::-webkit-details-marker { display: none; }
        .lp-details summary::after { content: '+'; margin-left: 8px; color: var(--lp-primary); font-size: 1rem; }
        .lp-details[open] summary::after { content: '−'; }
        .lp-details-copy { padding: 0 0 16px; color: var(--lp-muted); font-size: .78rem; line-height: 1.7; }
        .lp-empty { padding: 50px 24px; border: 1px dashed var(--lp-line); border-radius: 16px; text-align: center; }
        .lp-empty h2 { margin: 0 0 8px; }
        .lp-empty p { margin: 0; color: var(--lp-muted); }
        .lp-intro { margin-top: 28px; padding: 28px; border-radius: 16px; background: var(--lp-soft); color: #475569; font-size: .9rem; line-height: 1.8; }
        .lp-intro h2, .lp-intro h3 { color: var(--lp-ink); }
        .lp-footer { padding: 28px 20px; border-top: 1px solid #edf0f4; color: var(--lp-muted); font-size: .75rem; text-align: center; }
        .lp-toast { position: fixed; right: 22px; bottom: 22px; z-index: 20; padding: 12px 18px; border-radius: 10px; background: var(--lp-ink); color: #fff; font-size: .8rem; font-weight: 700; box-shadow: 0 12px 28px rgba(15,23,42,.25); transform: translateY(140%); opacity: 0; transition: .25s ease; }
        .lp-toast.is-visible { transform: translateY(0); opacity: 1; }
        .lpm-overlay { position: fixed; inset: 0; z-index: 30; display: none; align-items: flex-start; justify-content: center; padding: 20px; overflow-y: auto; background: rgba(15,23,42,.62); }
        .lpm-overlay.is-visible { display: flex; }
        .lpm-popup { position: relative; top: 20px; width: min(480px, 100%); padding: 30px; border-radius: 16px; background: #fff; box-shadow: 0 20px 45px rgba(0,0,0,.3); }
        .lpm-close { position: absolute; top: 14px; right: 17px; border: 0; background: none; color: var(--lp-muted); font-size: 25px; cursor: pointer; }
        .lpm-content { text-align: center; }
        .lpm-title { display: block; margin: 0 28px 20px; color: var(--lp-ink); font-size: 1.3rem; font-weight: 800; }
        .lpm-code { margin-bottom: 15px; padding: 15px; border: 2px dashed var(--lp-primary); border-radius: 8px; background: var(--lp-soft); color: var(--lp-primary); font-size: 1.12rem; font-weight: 800; letter-spacing: .04em; overflow-wrap: anywhere; }
        .lpm-copy, .lpm-visit { width: 100%; padding: 12px 24px; border: 0; border-radius: 7px; color: #fff; font-weight: 750; cursor: pointer; }
        .lpm-copy { background: var(--lp-primary); }
        .lpm-visit { margin-top: 10px; background: var(--lp-ink); }
        .lpm-note { margin: 18px 0 0; color: var(--lp-muted); font-size: .8rem; }
        @media (max-width: 820px) {
            .lp-hero-inner { grid-template-columns: 1fr; min-height: auto; }
            .lp-hero-copy { padding: 28px 0 34px; }
            .lp-hero-copy-head { display: flex; align-items: center; gap: 14px; margin-bottom: 10px; }
            .lp-hero-copy-head h1 { margin: 0; }
            .lp-hero-brandmark { display: flex; flex: 0 0 auto; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 16px; overflow: hidden; background: var(--lp-primary); color: #fff; text-align: center; box-shadow: 0 8px 16px rgba(15,23,42,.16); }
            .lp-hero-brandmark img { width: 100%; height: 100%; padding: 8px; object-fit: contain; background: #fff; }
            .lp-hero-brandmark span { padding: 4px; font-size: .58rem; font-weight: 800; letter-spacing: .04em; overflow-wrap: anywhere; }
            .lp-layout { grid-template-columns: 1fr; gap: 0; }
            .lp-rail { position: static; display: flex; align-items: center; gap: 18px; margin: 24px 0 0; padding: 0; }
            .lp-rail .lp-brandmark { display: none; }
            .lp-disclosure { margin: 0; }
            .lp-store-link { margin-top: 8px; }
            .lp-main { padding-top: 24px; }
        }
        @media (max-width: 620px) {
            .lp-sitebar-inner, .lp-hero-inner, .lp-layout { width: min(100% - 24px, 1180px); }
            .lp-sitebar-inner { min-height: 60px; }
            .lp-back { font-size: .72rem; }
            .lp-hero-brandmark { width: 48px; height: 48px; border-radius: 14px; }
            .lp-offer-body { grid-template-columns: 74px minmax(0,1fr); gap: 13px; padding: 18px 14px 12px; }
            .lp-saving { font-size: 1.08rem; }
            .lp-action-wrap { grid-column: 1 / -1; }
            .lp-action { min-height: 44px; }
            .lp-details { margin: 0 14px; }
            .lp-rail { align-items: flex-start; }
        }
    </style>
</head>
<body>
    <div class="lp-topline"></div>
    <header class="lp-sitebar">
        <div class="lp-sitebar-inner">
            <a href="{{ route('home') }}" class="lp-site-logo" aria-label="{{ $branding['site_name'] }} home">
                @if($branding['site_logo_url'])
                    <img src="{{ $branding['site_logo_url'] }}" alt="{{ $branding['site_name'] }}">
                @else
                    {{ $branding['site_name'] }}
                @endif
            </a>
            <a class="lp-back" href="{{ route('store', $store->seo_url) }}">View full {{ $brandName }} store page →</a>
        </div>
    </header>

    <section class="lp-hero">
        <div class="lp-hero-inner">
            <div aria-hidden="true"></div>
            <div class="lp-hero-copy">
                <div class="lp-hero-copy-head">
                    <div class="lp-hero-brandmark">
                        @if($store->store_logo)
                            <img src="{{ asset($store->store_logo) }}" alt="{{ $brandName }} logo">
                        @else
                            <span>{{ $brandName }}</span>
                        @endif
                    </div>
                    <h1>{{ $landingPage->heading ?: $brandName.' Coupons & Promo Codes' }}</h1>
                </div>
                <p class="lp-summary">{{ $landingPage->subheading ?: $activeCount.' verified '.Str::plural('offer', $activeCount).' updated '.now()->format('F jS, Y') }}</p>
            </div>
        </div>
    </section>

    <div class="lp-layout">
        <aside class="lp-rail">
            <div class="lp-brandmark">
                @if($store->store_logo)
                    <img src="{{ asset($store->store_logo) }}" alt="{{ $brandName }} logo">
                @else
                    <span class="lp-brand-initials">{{ $brandName }}</span>
                @endif
            </div>
            <div>
                <p class="lp-disclosure">{{ $landingPage->disclosure ?: 'When you buy through links on our site, we may earn a commission.' }}</p>
                <a class="lp-store-link" href="{{ $store->affiliate_url ?: route('store', $store->seo_url) }}" rel="nofollow sponsored" target="_blank">Visit {{ $brandName }} <span aria-hidden="true">↗</span></a>
            </div>
        </aside>

        <main class="lp-main">
            @if($coupons->isNotEmpty())
                <div class="lp-list">
                    @foreach($coupons as $coupon)
                        @php
                            preg_match('/(?:£|\$)?\s*\d+(?:\.\d+)?\s*(?:%|OFF|off)?/', $coupon->coupon_title, $savingMatch);
                            $saving = trim($savingMatch[0] ?? ($coupon->coupon_code ? 'Promo code' : 'Special deal'));
                            $affiliateUrl = $coupon->affiliate_url ?: $store->affiliate_url ?: route('store', $store->seo_url);
                            $isTop = $loop->first;
                        @endphp
                        <article class="lp-offer" id="offer-{{ $coupon->id }}">
                            @if($isTop)<div class="lp-offer-top">Today's top offer</div>@endif
                            <div class="lp-offer-body">
                                <div class="lp-saving">
                                    {{ $saving }}
                                    <small>{{ $coupon->coupon_code ? 'discount code' : 'online offer' }}</small>
                                </div>
                                <div>
                                    <span class="lp-type">{{ $coupon->coupon_code ? 'Code' : 'Sale' }}</span>
                                    <h2>{{ $coupon->coupon_title }}</h2>
                                    <div class="lp-badges">
                                        @if($coupon->verified)<span class="lp-badge lp-badge--verified">Verified</span>@endif
                                        @if($coupon->expiry_soon)<span class="lp-badge lp-badge--expiry">Expiring soon</span>@endif
                                        @if($coupon->today_usage_count > 0)<span class="lp-badge">{{ $coupon->today_usage_count }} {{ Str::plural('shopper', $coupon->today_usage_count) }} used this today</span>@endif
                                        @if($coupon->exclusive)<span class="lp-badge">Exclusive</span>@endif
                                    </div>
                                    <div class="lp-used" data-coupon-id="{{ $coupon->id }}">
                                        <strong class="lp-used-total">{{ number_format($coupon->used_count ?? 0) }}</strong> Used &middot; <strong class="lp-used-today">{{ $coupon->today_usage_count ?? 0 }}</strong> Today
                                    </div>
                                </div>
                                <div class="lp-action-wrap">
                                    <button type="button"
                                            class="lp-action"
                                            data-coupon-id="{{ $coupon->id }}"
                                            data-code="{{ $coupon->coupon_code }}"
                                            data-affiliate="{{ $affiliateUrl }}"
                                            data-title="{{ $coupon->coupon_title }}">
                                        {{ $coupon->coupon_code ? 'Show Code' : 'Get Deal' }}
                                    </button>
                                </div>
                            </div>
                            @if($coupon->description || $coupon->terms || $coupon->date_expiry)
                                <details class="lp-details">
                                    <summary>See details</summary>
                                    <div class="lp-details-copy">
                                        @if($coupon->description)<p>{{ $coupon->description }}</p>@endif
                                        @if($coupon->terms)<p><strong>Terms:</strong> {{ $coupon->terms }}</p>@endif
                                        @if($coupon->date_expiry)<p><strong>Expires:</strong> {{ \Illuminate\Support\Carbon::parse($coupon->date_expiry)->format('j F Y') }}</p>@endif
                                    </div>
                                </details>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <div class="lp-empty">
                    <h2>Fresh offers are on the way</h2>
                    <p>There are no active {{ $brandName }} offers right now. Please check again soon.</p>
                </div>
            @endif

            @if($landingPage->intro_content)
                <section class="lp-intro">{!! $landingPage->intro_content !!}</section>
            @endif
        </main>
    </div>

    <footer class="lp-footer">© {{ now()->year }} {{ $branding['site_name'] }}. Offer availability and retailer terms may change.</footer>
    <div class="lp-toast" role="status" aria-live="polite">Coupon code copied</div>

    <div class="lpm-overlay" id="lpCouponModal" aria-hidden="true">
        <div class="lpm-popup" role="dialog" aria-modal="true" aria-labelledby="lpmTitle">
            <button type="button" class="lpm-close" id="lpmClose" aria-label="Close coupon dialog">&times;</button>
            <div class="lpm-content">
                <span class="lpm-title" id="lpmTitle">Your offer is ready</span>
                <div class="lpm-code" id="lpmCode">No code required</div>
                <button type="button" class="lpm-copy" id="lpmCopy">Copy Code</button>
                <button type="button" class="lpm-visit" id="lpmVisit">Visit Store</button>
                <p class="lpm-note" id="lpmNote">Copy the code and enter it at checkout.</p>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const trackUrl = @json(route('coupon.track-usage'));
            const toast = document.querySelector('.lp-toast');

            function track(couponId) {
                if (sessionStorage.getItem('landing_coupon_used_' + couponId) === 'true') return;
                fetch(trackUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: JSON.stringify({ coupon_id: couponId })
                }).then(response => response.json()).then(data => {
                    if (!data.success) return;
                    sessionStorage.setItem('landing_coupon_used_' + couponId, 'true');
                    document.querySelectorAll('.lp-used[data-coupon-id="' + couponId + '"]').forEach(function (element) {
                        const total = element.querySelector('.lp-used-total');
                        const today = element.querySelector('.lp-used-today');
                        if (total && data.used_count !== undefined) total.textContent = Number(data.used_count).toLocaleString();
                        if (today && data.today_count !== undefined) today.textContent = data.today_count;
                    });
                }).catch(() => {});
            }

            function showToast(message) {
                toast.textContent = message;
                toast.classList.add('is-visible');
                window.setTimeout(() => toast.classList.remove('is-visible'), 2200);
            }

            const modal = document.getElementById('lpCouponModal');
            const modalTitle = document.getElementById('lpmTitle');
            const modalCode = document.getElementById('lpmCode');
            const modalCopy = document.getElementById('lpmCopy');
            const modalVisit = document.getElementById('lpmVisit');
            const modalClose = document.getElementById('lpmClose');
            const modalNote = document.getElementById('lpmNote');
            let modalAffiliate = '#';
            let returnFocus = null;

            function openCouponModal(button) {
                const code = button.dataset.code || '';
                modalTitle.textContent = button.dataset.title || 'Your offer is ready';
                modalCode.textContent = code || 'No code required';
                modalCopy.hidden = !code;
                modalNote.textContent = code ? 'Copy the code and enter it at checkout.' : 'No code is needed. Continue to the store to claim this deal.';
                modalAffiliate = button.dataset.affiliate || '#';
                returnFocus = button;
                modal.classList.add('is-visible');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                modalClose.focus();
            }

            function closeCouponModal() {
                modal.classList.remove('is-visible');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                if (returnFocus) returnFocus.focus();
            }

            modalClose.addEventListener('click', closeCouponModal);
            modal.addEventListener('click', event => { if (event.target === modal) closeCouponModal(); });
            document.addEventListener('keydown', event => { if (event.key === 'Escape' && modal.classList.contains('is-visible')) closeCouponModal(); });
            modalCopy.addEventListener('click', async function () {
                try {
                    await navigator.clipboard.writeText(modalCode.textContent);
                    showToast('Coupon code copied');
                    modalCopy.textContent = 'Copied!';
                    window.setTimeout(() => { modalCopy.textContent = 'Copy Code'; }, 1800);
                } catch (_) { showToast('Coupon code: ' + modalCode.textContent); }
            });
            modalVisit.addEventListener('click', function () {
                if (modalAffiliate && modalAffiliate !== '#') window.location.href = modalAffiliate;
            });

            const buttons = Array.from(document.querySelectorAll('.lp-action'));
            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    track(button.dataset.couponId);
                    const affiliate = button.dataset.affiliate;
                    if (!affiliate || affiliate === '#') {
                        openCouponModal(button);
                        return;
                    }

                    const popupUrl = new URL(window.location.href);
                    popupUrl.search = '';
                    popupUrl.hash = '';
                    popupUrl.searchParams.set('show_coupon', button.dataset.couponId);
                    const popup = window.open(popupUrl.toString(), '_blank', 'noopener');
                    if (popup) window.location.assign(affiliate);
                    else openCouponModal(button);
                });
            });

            try {
                const couponId = new URLSearchParams(window.location.search).get('show_coupon');
                if (couponId) {
                    const button = buttons.find(item => item.dataset.couponId === couponId);
                    if (button) {
                        openCouponModal(button);
                        history.replaceState({}, '', window.location.pathname);
                    }
                }
            } catch (_) {}
        })();
    </script>
</body>
</html>
