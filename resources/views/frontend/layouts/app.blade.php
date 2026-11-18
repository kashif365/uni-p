<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb#">

    {{-- Preloader overlay — covers white-render phase, removed once CSS+fonts are ready --}}
    <style>
        #pgld {
            position: fixed;
            inset: 0;
            z-index: 999999;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
        }
        #pgld.pgld-out { opacity: 0; pointer-events: none; }
        #pgld-sp {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 4px solid rgba(74,12,152,0.15);
            border-top-color: var(--primary-color, #4a0c98);
            animation: pgld-spin 0.75s linear infinite;
        }
        @keyframes pgld-spin { to { transform: rotate(360deg); } }
    </style>
    <noscript><style>#pgld { display: none; }</style></noscript>

    {{-- Standards mode --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- Branding loaded first so title/OG/canonical can reference it safely --}}
    @php
        $brandingSettings = \App\Helpers\SettingsHelper::getBranding();
        $verificationTags = ($verificationTagPlacements ?? collect());
    @endphp

    {{-- ================================================================
         CORE META
         Title and description appear as early as possible so Google
         recognises them before any large CSS/script blocks.
         ================================================================ --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">

    <title>@yield('title', ($brandingSettings['site_name'] ?? 'Campus Deals') . ' - ' . ($brandingSettings['site_tagline'] ?? 'Discount Codes & Offers'))</title>

    <meta name="description" content="@yield('description', 'Discover the latest UK discount and voucher codes at Campus Deals. Explore exclusive online deals, and save big on your favourite brands. Start saving today!')" />
    <meta name="keywords" content="@yield('keywords', 'Vouchers, Voucher Codes, Discount Vouchers, Promo Codes, Promotional Codes, Campus Deals')" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="data-attr" content="0,Home">
    <meta name="author" content="{{ $brandingSettings['site_name'] ?? 'Campus Deals' }}">

    {{-- ================================================================
         OPEN GRAPH
         ================================================================ --}}
    <meta property="og:title" content="@yield('og_title', ($brandingSettings['site_name'] ?? 'Campus Deals') . ' - Discount Codes & Voucher Codes')" />
    <meta property="og:description" content="@yield('og_description', 'Discover the latest UK discount and voucher codes at Campus Deals. Explore exclusive online deals, and save big on your favourite brands.')" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ $brandingSettings['site_logo_url'] ?? asset('assets/img/icons/logo.png') }}" />
    <meta property="og:site_name" content="{{ $brandingSettings['site_name'] ?? 'Campus Deals' }}" />

    {{-- ================================================================
         CANONICAL
         ================================================================ --}}
    @if(!isset($homePage) || !$homePage || !$homePage->canonical_url)
        <link rel="canonical" href="{{ url()->current() }}">
    @endif

    {{-- ================================================================
         FAVICON
         ================================================================ --}}
    @if($brandingSettings['site_favicon_url'])
        <link rel="shortcut icon" href="{{ $brandingSettings['site_favicon_url'] }}" type="image/png" />
        <link rel="icon" href="{{ $brandingSettings['site_favicon_url'] }}" type="image/png">
        <link rel="mask-icon" href="{{ $brandingSettings['site_favicon_url'] }}">
        <link rel="apple-touch-icon" href="{{ $brandingSettings['site_favicon_url'] }}" />
    @else
        <link rel="shortcut icon" href="{{ asset('assets/img/icons/logo.png') }}" type="image/png" />
        <link rel="icon" href="{{ asset('assets/img/icons/logo.png') }}" type="image/png">
        <link rel="mask-icon" href="{{ asset('assets/img/icons/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('assets/img/icons/logo.png') }}" />
    @endif

    {{-- ================================================================
         THEME / APP META
         ================================================================ --}}
    <meta name="theme-color" content="#4a0c98">
    <meta name="msapplication-navbutton-color" content="#4a0c98">
    <meta name="apple-mobile-web-app-status-bar-style" content="#4a0c98">
    <meta name="apple-mobile-web-app-title" content="{{ $brandingSettings['site_name'] ?? 'Campus Deals' }}">
    <meta name="application-name" content="{{ $brandingSettings['site_name'] ?? 'Campus Deals' }}">

    {{-- ================================================================
         VERIFICATION TAGS (head_start placement)
         ================================================================ --}}
    @foreach($verificationTags->get('head_start', collect()) as $verificationTag)
        {!! $verificationTag->renderTag() !!}
    @endforeach

    {{-- Custom meta tags via @push('meta') --}}
    @stack('meta')

    {{-- ================================================================
         CSS LOADING (Synchronous to prevent FOUC)
         ================================================================ --}}
    @php
        $colorsVersion  = file_exists(public_path('css/colors.css')) ? filemtime(public_path('css/colors.css')) : time();
        $getFileVersion = function($path) {
            $fullPath = public_path($path);
            return file_exists($fullPath) ? filemtime($fullPath) : time();
        };
    @endphp

    {{-- Dynamic brand color overrides (generated at runtime, not bundleable) --}}
    <link rel="stylesheet" href="{{ url('/css/colors.css') }}?v={{ $colorsVersion }}">

    {{-- Vite-bundled frontend CSS: fonts + home + brand + responsive + mobile-optimizations
         (5 requests reduced to 1, minified, cache-busted via content hash) --}}
    @vite(['resources/css/frontend.css'])

    {{-- ================================================================
         CRITICAL INLINE CSS
         Runs BEFORE @stack('styles') so child views cannot cause a color flash.
         ================================================================ --}}
    @php
        $primaryColor = $brandingSettings['primary_color'] ?? '#2951c4';

        $hex = ltrim($primaryColor, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $baseR = hexdec(substr($hex, 0, 2));
        $baseG = hexdec(substr($hex, 2, 2));
        $baseB = hexdec(substr($hex, 4, 2));

        $calcLuminance = function (int $r, int $g, int $b): float {
            $toLinear = function (float $c): float {
                $c = $c / 255;
                return $c <= 0.03928 ? $c / 12.92 : pow(($c + 0.055) / 1.055, 2.4);
            };
            $R = $toLinear($r);
            $G = $toLinear($g);
            $B = $toLinear($b);
            return 0.2126 * $R + 0.7152 * $G + 0.0722 * $B;
        };

        $contrastWithWhite = function (int $r, int $g, int $b) use ($calcLuminance): float {
            $L = $calcLuminance($r, $g, $b);
            return (1.0 + 0.05) / ($L + 0.05);
        };

        $targetContrast = 4.5;
        $factor = 1.0;
        $adjR   = $baseR;
        $adjG   = $baseG;
        $adjB   = $baseB;
        $steps  = 0;

        while ($contrastWithWhite($adjR, $adjG, $adjB) < $targetContrast && $steps < 10) {
            $factor -= 0.08;
            if ($factor <= 0.2) { $factor = 0.2; }
            $adjR = max(0, min(255, (int) round($baseR * $factor)));
            $adjG = max(0, min(255, (int) round($baseG * $factor)));
            $adjB = max(0, min(255, (int) round($baseB * $factor)));
            $steps++;
        }

        $accessiblePrimary = sprintf('#%02X%02X%02X', $adjR, $adjG, $adjB);

        $hoverR = max(0, min(255, (int) round($adjR - ($adjR * 15 / 100))));
        $hoverG = max(0, min(255, (int) round($adjG - ($adjG * 15 / 100))));
        $hoverB = max(0, min(255, (int) round($adjB - ($adjB * 15 / 100))));

        $primaryHover = sprintf('#%02X%02X%02X', $hoverR, $hoverG, $hoverB);
        $primaryColor = $accessiblePrimary;
    @endphp

    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --primary-hover: {{ $primaryHover }};
        }
        html { --primary-color: {{ $primaryColor }}; --primary-hover: {{ $primaryHover }}; }
        body { --primary-color: {{ $primaryColor }}; --primary-hover: {{ $primaryHover }}; }
        .top-promo-bar { background: {{ $primaryColor }} !important; }
        .dsclmr { display: none !important; }
        .skip-to-main:focus { left: 0; top: 0; }
    </style>

    {{-- Custom styles via @push('styles') --}}
    @stack('styles')

    {{-- ================================================================
         SCHEMA (JSON-LD)
         ================================================================ --}}
    @php
        $schemaSiteName    = $brandingSettings['site_name'] ?? 'Campus Deals';
        $schemaLogo        = $brandingSettings['site_logo_url'] ?? asset('assets/img/icons/logo.png');
        $schemaDescription = trim($__env->yieldContent('description', 'Discover the latest UK discount and voucher codes at ' . $schemaSiteName . '.'));
        $schemaGraph = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                [
                    '@type'       => 'WebSite',
                    '@id'         => url('/') . '#website',
                    'url'         => url('/'),
                    'name'        => $schemaSiteName,
                    'description' => $schemaDescription,
                    'inLanguage'  => 'en-GB',
                    'potentialAction' => [
                        '@type'       => 'SearchAction',
                        'target'      => route('search') . '?q={search_term_string}',
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
                [
                    '@type' => 'Organization',
                    '@id'   => url('/') . '#organization',
                    'name'  => $schemaSiteName,
                    'url'   => url('/'),
                    'logo'  => [
                        '@type' => 'ImageObject',
                        'url'   => $schemaLogo,
                    ],
                ],
                [
                    '@type'       => 'WebPage',
                    '@id'         => url()->current() . '#webpage',
                    'url'         => url()->current(),
                    'name'        => trim($__env->yieldContent(
                        'title',
                        ($brandingSettings['site_name'] ?? 'Campus Deals') . ' - ' . ($brandingSettings['site_tagline'] ?? 'Discount Codes & Offers')
                    )),
                    'description' => $schemaDescription,
                    'isPartOf'    => ['@id' => url('/') . '#website'],
                    'inLanguage'  => 'en-GB',
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($schemaGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    {{-- ================================================================
         APP CONFIG (inline JS — must come before jQuery/home.js)
         ================================================================ --}}
    <script>
        window.app_config = {
            url: "{{ rtrim(url('/'), '/') }}/",
            media: "{{ asset('') }}",
            current_url: "{{ url()->current() }}",
            current_url_full: "{{ url()->full() }}",
            csrf_token: "{{ csrf_token() }}",
            Banner_Keyword: "voucher code",
            Logo_Keyword: "discount code",
            uid: "{{ auth()->id() ?? '0' }}"
        };

        // Legacy variables for backward compatibility
        var app_url          = window.app_config.url;
        var app_media        = window.app_config.media;
        var current_url      = window.app_config.current_url;
        var current_url_full = window.app_config.current_url_full;
        var csrf_token       = window.app_config.csrf_token;
        var Banner_Keyword   = window.app_config.Banner_Keyword;
        var Logo_Keyword     = window.app_config.Logo_Keyword;
        var uid              = window.app_config.uid;
    </script>

    {{-- Custom head scripts via @push('head_scripts') --}}
    @stack('head_scripts')

    @foreach($verificationTags->get('head_end', collect()) as $verificationTag)
        {!! $verificationTag->renderTag() !!}
    @endforeach

</head>
<body style="max-width: 1700px; margin: auto;">

    <div id="pgld" aria-hidden="true" role="presentation"><div id="pgld-sp"></div></div>

    @foreach($verificationTags->get('body_start', collect()) as $verificationTag)
        {!! $verificationTag->renderTag() !!}
    @endforeach

    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="skip-to-main" style="position: absolute; left: -9999px; z-index: 999999; padding: 1em; background: var(--primary-color, #2951c4); color: #fff; text-decoration: none; font-weight: 600;">Skip to main content</a>

    <!-- main wrapper -->
    <main class="main" id="main-content">
        @include('frontend.partials.header')

        <div class="crtn"></div>

        <!-- Page content -->
        @yield('content')

        @include('frontend.partials.footer')
    </main>

    <!-- Back to top button -->
    <button id="tpBtn" type="button" tabindex="-1" class="bp_arw-ryt" aria-label="Back to top"></button>

    <script src="{{ asset('frontend_assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend_assets/js/home.js') }}?v={{ $getFileVersion('frontend_assets/js/home.js') }}"></script>

    <script>console.log("version 1.0");</script>

    {{-- Custom body scripts via @push('scripts') --}}
    @stack('scripts')

    @foreach($verificationTags->get('body_end', collect()) as $verificationTag)
        {!! $verificationTag->renderTag() !!}
    @endforeach

    {{-- Dismiss preloader once CSS + fonts are ready (does NOT wait for images) --}}
    <script>
        (function () {
            function dismiss() {
                var el = document.getElementById('pgld');
                if (!el) return;
                el.classList.add('pgld-out');
                setTimeout(function () { el.remove(); }, 320);
            }
            document.addEventListener('DOMContentLoaded', function () {
                if ('fonts' in document) {
                    var t = setTimeout(dismiss, 800);
                    document.fonts.ready.then(function () {
                        clearTimeout(t);
                        dismiss();
                    });
                } else {
                    dismiss();
                }
            });
        })();
    </script>

</body>
</html>
