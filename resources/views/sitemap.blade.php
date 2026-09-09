@extends('layouts.guest')

@section('content')

    <x-cms.hero-section
        theme="dark"
        size="standard"
        title="Sitemap"
        subtitle="Quick links to major sections — case studies, services, insights, and contact."
        :image-url="hero_asset(5)"
        :image-srcset="hero_asset_srcset(5)"
        tagline="Navigate Forefront Solutions — HMIS, software, branding, and public engagement."
        :badges="['Navigation']"
        cta-url="{{ generate_utm_url(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form', 'sitemap_hero') }}" />

    <div class="bg-white border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <x-cms.breadcrumb :items="[['label' => 'Home', 'url' => route('home')], ['label' => 'Sitemap']]" />
        </div>
    </div>

    <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="sitemap-heading">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="mb-8 border-b border-neutral-200 pb-4">
                <p class="section-label mb-1">Navigation</p>
                <h2 id="sitemap-heading" class="text-2xl lg:text-3xl font-black text-neutral-900 tracking-tight">Site sections</h2>
                @if (!empty($lastGenerated))
                    <p class="mt-2 text-sm text-neutral-500">Last generated: {{ \Carbon\Carbon::parse($lastGenerated)->diffForHumans() }}</p>
                @endif
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ([
                    ['route' => 'home', 'label' => 'Home'],
                    ['route' => 'about', 'label' => 'About'],
                    ['route' => 'services.index', 'label' => 'Services'],
                    ['route' => 'portfolio.index', 'label' => 'Portfolio'],
                    ['route' => 'insights.index', 'label' => 'Insights'],
                    ['route' => 'contact', 'label' => 'Contact', 'params' => ['inquiry_type' => 'hmis-demo']],
                    ['route' => 'privacy', 'label' => 'Privacy'],
                    ['route' => 'terms', 'label' => 'Terms'],
                    ['route' => 'sitemap.xml', 'label' => 'XML Sitemap', 'external' => true],
                ] as $link)
                    @php
                        $href = ($link['external'] ?? false)
                            ? route($link['route'])
                            : (isset($link['params'])
                                ? route($link['route'], $link['params']) . (str_contains($link['route'], 'contact') ? '#contact-form' : '')
                                : route($link['route']));
                    @endphp
                    <a href="{{ $href }}"
                       class="card-base p-4 text-sm font-semibold text-neutral-800 hover:text-primary-600 hover-lift transition-colors">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <x-cms.lead-magnet-band type="hmis-checklist" />

    <x-cms.footer-cta
        title="Ready to get started?"
        subtitle="Request an HMIS demo or download the procurement checklist — we respond within 24 hours."
        :primary-url="generate_utm_url(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form', 'sitemap_footer')"
        primary-label="Request HMIS Demo"
        :secondary-url="route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'"
        secondary-label="Get HMIS Checklist"
        :show-whatsapp="false" />

@endsection
