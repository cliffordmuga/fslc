{{-- Public site <head> SEO + assets. Expects $seoData / $defaultSeoData in scope. --}}
@php
    $seoData    = $seoData ?? ($defaultSeoData ?? defaultSeoData());
    $title      = $seoData['title'] ?? null;
    $description = $seoData['description'] ?? null;
    $canonical  = $seoData['canonical_url'] ?? canonical_url();
    $ogTitle    = $seoData['og_title'] ?? $title;
    $ogDesc     = $seoData['og_description'] ?? $description;
    $ogImage    = $seoData['og_image'] ?? cdn_asset('images/default-og-image.png');
    $noindex    = (bool) ($seoData['noindex'] ?? false);
    $nofollow   = (bool) ($seoData['nofollow'] ?? false);
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ meta_title($title) }}</title>
<meta name="description" content="{{ meta_description($description) }}">
@php $keywordsMeta = meta_keywords($seoData['keywords'] ?? null); @endphp
@if ($keywordsMeta)
    <meta name="keywords" content="{{ $keywordsMeta }}">
@endif
@if ($noindex || $nofollow)
    <meta name="robots" content="{{ $noindex ? 'noindex' : 'index' }}, {{ $nofollow ? 'nofollow' : 'follow' }}">
@endif
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDesc }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:type" content="{{ $seoData['og_type'] ?? 'website' }}">
<meta property="og:locale" content="en_KE">
<meta property="og:site_name" content="{{ setting('company_name', config('app.name')) }}">

<meta name="twitter:card" content="{{ $seoData['twitter_card'] ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDesc }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
<link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" sizes="32x32">
<link rel="apple-touch-icon" href="{{ asset('images/favicon.svg') }}">
<meta name="theme-color" content="{{ setting('primary_color', '#0ea5e9') }}">

@isset($seoData['preload_image'])
    <link rel="preload" as="image" href="{{ $seoData['preload_image'] }}">
@endisset

@stack('head')

@if (! empty($seoData['structured_data']))
    <x-seo.json-ld :data="$seoData['structured_data']" />
@endif

@yield('seo')

@if (isset($content) && ($content->type ?? null) !== 'intro')
    @php
        $crumbs = [['name' => 'Home', 'url' => url('/')]];
        $type = $content->type ?? null;
        if ($type === 'portfolio') {
            $crumbs[] = ['name' => 'Portfolio', 'url' => route('portfolio.index')];
        } elseif ($type === 'services') {
            $crumbs[] = ['name' => 'Services', 'url' => route('services.index')];
        } elseif ($type === 'blog') {
            $crumbs[] = ['name' => 'Insights', 'url' => route('insights.index')];
        } elseif (in_array($type, ['mission', 'vision', 'about'], true)) {
            $crumbs[] = ['name' => 'About', 'url' => route('about')];
        }
        $crumbs[] = ['name' => $content->title ?? 'Page', 'url' => url()->current()];
    @endphp
    <x-seo.json-ld :data="breadcrumb_schema($crumbs)" />
@endif

<x-seo.json-ld :data="organization_schema()" />

@if (request()->routeIs('home'))
    @php
        $localBiz = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => setting('company_name', config('app.name', env('APP_NAME', 'App'))),
            'url' => url('/'),
            'logo' => cdn_asset('images/logo.png'),
            'image' => cdn_asset('images/default-og-image.png'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => setting('address', 'Nairobi, Kenya'),
                'addressLocality' => setting('address_city', 'Nairobi'),
                'addressCountry' => 'KE',
            ],
            'areaServed' => ['KE', 'East Africa'],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'url' => route('contact'),
                'availableLanguage' => ['English', 'Swahili'],
            ],
            'sameAs' => collect([
                setting('facebook_url'),
                setting('twitter_url'),
                setting('linkedin_url'),
                setting('instagram_url'),
            ])->filter()->values()->toArray(),
        ];
    @endphp
    <x-seo.json-ld :data="$localBiz" />
@endif

<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
<link rel="dns-prefetch" href="https://fonts.bunny.net">
<link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap"
      as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"></noscript>

<x-seo.critical-css />
@php
    $appStylesheet = \Illuminate\Support\Facades\Vite::asset('resources/css/app.css');
@endphp
<link rel="preload" href="{{ $appStylesheet }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ $appStylesheet }}"></noscript>
@vite('resources/js/app.js')
<x-seo.analytics />
<style>
    :root { {{ brand_theme_css_vars() }} }
</style>
