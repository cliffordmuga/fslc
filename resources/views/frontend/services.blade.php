{{-- resources/views/frontend/services.blade.php --}}
@extends('layouts.guest')

@section('seo')
    @foreach ($pageSchemas ?? [] as $schema)
        <x-seo.json-ld :data="$schema" />
    @endforeach
@endsection

@section('content')

    @php
        $foundedYear = setting('founded_year', '2015');
        $pillarGuide = [
            ['audience' => 'Hospitals & counties', 'pillar' => 'HMIS & Digital Health', 'slug' => 'hmis-digital-health-solutions-kenya', 'accent' => 'pillar-border-hmis'],
            ['audience' => 'Government & SACCOs', 'pillar' => 'Software & Web', 'slug' => 'custom-software-web-development-kenya', 'accent' => 'pillar-border-software'],
            ['audience' => 'NGOs & institutions', 'pillar' => 'Branding & Marketing', 'slug' => 'digital-strategy-branding-marketing-kenya', 'accent' => 'pillar-border-branding'],
            ['audience' => 'Campaigns & advocacy', 'pillar' => 'Public Engagement', 'slug' => 'political-public-engagement-kenya', 'accent' => 'pillar-border-campaigns'],
        ];
    @endphp

    <x-cms.hub-shell
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Services']]"
        :hero-preload="hero_asset(4)">
        <x-slot:hero>
            <x-cms.hero-section
                theme="dark"
                size="standard"
                title="Core Services"
                :image-url="hero_asset(4)"
                :image-srcset="hero_asset_srcset(4)"
                subtitle="HMIS & digital health, custom software, branding & marketing, and political & public engagement — built for Kenyan hospitals, government, NGOs, and campaigns."
                tagline="Focused capabilities for procurement teams and decision-makers who need depth, not a generic agency."
                :badges="['HMIS', 'Software', 'Branding', 'Campaigns']"
                :cta="$servicesCta"
                :cta-url="hub_cta_url('services_hero')" />
        </x-slot:hero>

        <x-slot:stats>
            <x-cms.stats-strip
                compact
                :columns="4"
                :items="[
                    ['value' => '4', 'label' => 'Focused service pillars'],
                    ['value' => 'HMIS', 'label' => 'Primary practice'],
                    ['value' => '24h', 'label' => 'Average response time'],
                    ['value' => $foundedYear, 'label' => 'Serving Kenya since'],
                ]" />
        </x-slot:stats>

    <x-cms.process-timeline
        compact
        section-label="Process"
        title="How we deliver"
        heading-id="services-process-heading"
        class="bg-neutral-50 border-t border-neutral-200"
        :cta-url="route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form'"
        cta-label="Request HMIS Demo" />

    {{-- ── Services grid ─────────────────────────────────────────────────────────── --}}
    <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="services-heading">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <x-cms.section-heading
                label="What We Offer"
                heading-id="services-heading"
                title="Focused services for Kenyan institutions"
                :intro="$positioning ?? setting('site_description')" />

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-8">
                @foreach ($pillarGuide as $guide)
                    <a href="{{ route('services.show', $guide['slug']) }}"
                       class="card-base p-4 border-t-4 {{ $guide['accent'] }} group">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-neutral-500 mb-1">{{ $guide['audience'] }}</p>
                        <p class="text-xs font-bold text-neutral-900 group-hover:text-primary-600 transition-colors">{{ $guide['pillar'] }}</p>
                    </a>
                @endforeach
            </div>

            <x-cms.content-grid
                :items="$services"
                type="service"
                :show-title="false"
                embedded
                :show-cta-banner="false"
                :per-page="4"
                :columns="4"
                highlight-slug="hmis-digital-health-solutions-kenya" />

            <p class="mt-8 text-sm text-neutral-600 border-t border-neutral-200 pt-6">
                Want proof before you enquire?
                <a href="{{ route('portfolio.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                    See HMIS & software case studies
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </p>
        </div>
    </section>

    <x-cms.page-closer
        title="Which Pillar Fits Your Project?"
        subtitle="HMIS demo, software quote, brand consultation, or campaign strategy — request a demo or download the procurement checklist."
        :primary-url="hub_cta_url('services_footer_cta')"
        primary-label="Request HMIS Demo"
        :secondary-url="route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'"
        secondary-label="Get HMIS Checklist" />

    </x-cms.hub-shell>

@endsection
