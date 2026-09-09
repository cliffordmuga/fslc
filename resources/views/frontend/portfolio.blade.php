{{-- resources/views/frontend/portfolio.blade.php --}}
@extends('layouts.guest')

@section('seo')
    @foreach ($pageSchemas ?? [] as $schema)
        <x-seo.json-ld :data="$schema" />
    @endforeach
@endsection

@section('content')

    @php
        $foundedYear = setting('founded_year', '2015');
        $activePillar = $pillar ?? 'all';
        $activeLabel = $pillars[$activePillar]['label'] ?? 'All';
        $resultCount = $portfolioItems->count();
        $pillarFilterActive = [
            'all' => 'bg-neutral-900 text-white border-neutral-900',
            'hmis' => 'pillar-filter-active-hmis',
            'software' => 'pillar-filter-active-software',
            'political' => 'pillar-filter-active-political',
        ];
        $pillarChips = collect($pillars ?? [])->map(fn ($config, $key) => [
            'label' => $config['label'],
            'url' => $key === 'all' ? route('portfolio.index') : route('portfolio.index', ['pillar' => $key]),
            'active' => $activePillar === $key,
            'class' => $activePillar === $key
                ? ($pillarFilterActive[$key] ?? 'bg-neutral-900 text-white border-neutral-900')
                : null,
        ])->values()->all();
    @endphp

    <x-cms.hub-shell
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Portfolio']]"
        :hero-preload="hero_asset(3)">
        <x-slot:hero>
            <x-cms.hero-section
                theme="dark"
                size="standard"
                title="Case Studies & Projects"
                :image-url="hero_asset(3)"
                :image-srcset="hero_asset_srcset(3)"
                subtitle="HMIS deployments, software platforms, institutional portals, and campaign digital hubs — explore work across three practice areas."
                tagline="Real deployments for hospitals, government, NGOs, and campaigns in Kenya."
                :badges="['HMIS', 'Software', 'Branding', 'Campaigns']"
                :cta="$portfolioCta"
                :cta-url="hub_cta_url('portfolio_hero')" />
        </x-slot:hero>

        <x-slot:stats>
            <x-cms.stats-strip
                compact
                :columns="4"
                :items="[
                    ['value' => (string) ($totalProjects ?? $resultCount), 'label' => 'Published case studies'],
                    ['value' => '3', 'label' => 'Portfolio practice areas'],
                    ['value' => 'HMIS', 'label' => 'Primary practice'],
                    ['value' => $foundedYear, 'label' => 'Serving Kenya since'],
                ]" />
        </x-slot:stats>

        <x-cms.filter-chip-nav
            label="Portfolio practice areas"
            :items="$pillarChips"
            wrap
            :result-summary="'Showing ' . $resultCount . ' ' . \Illuminate\Support\Str::plural('case study', $resultCount) . ($activePillar !== 'all' ? ' in <span class=&quot;font-semibold text-neutral-700&quot;>' . e($activeLabel) . '</span>' : '')" />

        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="portfolio-heading">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <x-cms.section-heading
                    label="Portfolio"
                    heading-id="portfolio-heading"
                    :title="$activePillar === 'all' ? 'All case studies' : $activeLabel"
                    intro="Explore HMIS deployments, institutional portals, e-commerce platforms, and campaign digital hubs — filtered by practice area. Branding and communications work is integrated within these case studies until standalone brand projects are published." />

                <x-cms.content-grid
                    :items="$portfolioItems"
                    type="portfolio"
                    :show-title="false"
                    embedded
                    :show-cta-banner="false"
                    :per-page="9"
                    :columns="4"
                    :highlight-slug="$activePillar === 'all' ? 'county-referral-hospital-hmis' : null"
                    :empty-headline="$activePillar !== 'all' ? 'No case studies in this practice area yet' : null"
                    :empty-message="$activePillar !== 'all' ? 'Try another filter or view all published projects while we add more work in this category.' : null" />
            </div>
        </section>

        <x-cms.page-closer
            title="Ready to Start Your Project?"
            subtitle="HMIS implementation, custom software, or campaign platform — request a demo or download the procurement checklist."
            :primary-url="hub_cta_url('portfolio_footer_cta')"
            primary-label="Request HMIS Demo"
            :secondary-url="route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'"
            secondary-label="Get HMIS Checklist" />
    </x-cms.hub-shell>

@endsection
