{{-- resources/views/frontend/index.blade.php --}}
@extends('layouts.guest')

@section('seo')
    @foreach ($pageSchemas ?? [] as $schema)
        <x-seo.json-ld :data="$schema" />
    @endforeach
@endsection

@push('head')
    <link rel="preload" href="{{ hero_asset(1) }}" as="image">
@endpush

@section('content')

    @php
        $foundedYear = setting('founded_year', '2015');
    @endphp

    {{-- ── Hero ──────────────────────────────────────────────────────────────── --}}
    <x-cms.hero-section
        theme="dark"
        size="home"
        title="Kenya's HMIS & Digital Transformation Partner"
        subtitle="Hospital management systems, custom software, digital communications, and public engagement platforms for healthcare, government, NGOs, and campaigns."
        tagline="Trusted since {{ $foundedYear }} — focused on four core capabilities, not a generic agency that claims to do everything."
        :badges="['HMIS', 'Software', 'Branding', 'Campaigns']"
        :cta="$homepageCta"
        :image-url="hero_asset(1)"
        :image-srcset="hero_asset_srcset(1)"
        :cta-url="hub_cta_url('home_hero')"
        :metrics="[
            ['value' => '4',    'label' => 'Core Service Pillars'],
            ['value' => '24h',  'label' => 'Avg. Response'],
            ['value' => 'HMIS', 'label' => 'Primary Practice'],
            ['value' => $foundedYear, 'label' => 'Founded'],
        ]" />

    {{-- ── Featured Portfolio ──────────────────────────────────────────────────── --}}
    <section class="py-10 lg:py-14 bg-white border-t border-neutral-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <x-cms.section-heading label="Work" title="Featured Projects">
                <x-slot:action>
                    <a href="{{ route('portfolio.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        All Projects
                        <x-icons.arrow-right class="w-4 h-4" />
                    </a>
                </x-slot:action>
            </x-cms.section-heading>
            <x-cms.content-grid
                :items="$portfolioItems"
                type="portfolio"
                :show-title="false"
                embedded
                :show-cta-banner="false" />
        </div>
    </section>

    {{-- ── Sectors served (social proof after work) ─────────────────────────────── --}}
    <x-cms.trust-strip
        id="sectors-served"
        title="Sectors We Serve"
        compact
        :sectors="['County Health', 'Hospitals & Clinics', 'NGO Programmes', 'Government ICT', 'SACCOs & SMEs', 'Political & Advocacy']">
        <x-slot:description>
            <p class="text-sm text-neutral-600 mb-6 max-w-2xl mx-auto leading-relaxed">
                {{ $intro->excerpt ?? setting('site_description') }}
            </p>
        </x-slot:description>
    </x-cms.trust-strip>

    {{-- ── Process ─────────────────────────────────────────────────────────────── --}}
    <x-cms.process-timeline
        id="our-process"
        compact
        section-label="Process"
        title="Our Simple 4-Step Process"
        heading-id="process-heading"
        :cta-url="hub_cta_url('home_process_cta')"
        cta-label="Request HMIS Demo" />

    {{-- ── Services teaser ────────────────────────────────────────────────────── --}}
    <section class="py-10 lg:py-14 bg-neutral-50 border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <x-cms.section-heading label="Services" title="Core Services">
                <x-slot:action>
                    <a href="{{ route('services.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        All Services
                        <x-icons.arrow-right class="w-4 h-4" />
                    </a>
                </x-slot:action>
            </x-cms.section-heading>
            <x-cms.content-grid
                :items="$services"
                type="service"
                :show-title="false"
                embedded
                :show-cta-banner="false"
                highlight-slug="hmis-digital-health-solutions-kenya" />
        </div>
    </section>

    {{-- ── Latest Insights ─────────────────────────────────────────────────────── --}}
    @if (!empty($latestInsights) && $latestInsights->count() > 0)
        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="latest-insights-heading">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <x-cms.section-heading
                    label="Insights"
                    title="Latest Guides & Case Studies"
                    heading-id="latest-insights-heading">
                    <x-slot:action>
                        <a href="{{ route('insights.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                            All Insights
                            <x-icons.arrow-right class="w-4 h-4" />
                        </a>
                    </x-slot:action>
                </x-cms.section-heading>
                <x-cms.content-grid
                    :items="$latestInsights"
                    type="blog"
                    :show-title="false"
                    embedded
                    :show-cta-banner="false" />
            </div>
        </section>
    @endif

    <x-cms.page-closer
        title="Ready to Digitize Your Hospital or Institution?"
        subtitle="Request an HMIS demo or speak with our team about software, branding, or campaign platforms."
        :primary-url="hub_cta_url('home_footer_primary', 'hmis-demo')"
        primary-label="Request HMIS Demo"
        :secondary-url="hub_cta_url('home_footer_secondary', 'hmis-checklist')"
        secondary-label="Get HMIS Checklist" />

@endsection
