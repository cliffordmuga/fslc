{{-- resources/views/frontend/details.blade.php --}}
{{-- reusable view for displaying content details for different content types e.g portfolio, services, blog, etc. --}}
@extends('layouts.guest')

@php
    $type = $content->type;
    $hubLabel = content_hub_label($type);
    $leadConfig = $leadConfig ?? [];
    $isHmis = content_is_hmis_related($content);
    $inquiryType = $detailInquiryType ?? content_inquiry_type_for_detail($content, $type, $leadConfig);
    $heroVariant = content_detail_hero_variant($type);
    $heroUrl = hero_asset($heroVariant);
    $heroSrcset = hero_asset_srcset($heroVariant);
    $checklistUrl = route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form';

    $schemaType = match ($type) {
        'portfolio' => 'CreativeWork',
        'blog'      => 'BlogPosting',
        'services'  => 'Service',
        default     => 'Article',
    };

    $indexRoute = match ($type) {
        'portfolio' => 'portfolio.index',
        'services'  => 'services.index',
        'blog'      => 'insights.index',
        default     => null,
    };

    $sidebarCtaLabel = $leadConfig['cta_label'] ?? match ($type) {
        'portfolio' => $isHmis ? 'Request HMIS Demo' : 'Discuss a Similar Project',
        'blog'      => 'Request HMIS Demo',
        'services'  => 'Get Project Quote',
        default     => 'Request HMIS Demo',
    };
    $sidebarTitle = $leadConfig['sidebar_title'] ?? match ($type) {
        'portfolio' => $isHmis ? 'Request HMIS Demo' : 'Discuss a Similar Project',
        'blog'      => 'Request HMIS Guidance',
        'services'  => 'Request a Quote',
        default     => 'Request a Quote',
    };
    $sidebarText = $leadConfig['sidebar_text'] ?? match ($type) {
        'portfolio' => $isHmis
            ? 'Book a facility walkthrough and see HMIS modules, SHA workflows, and reporting in action.'
            : 'Tell us your scope and timeline — we\'ll outline a clear path forward for a similar engagement.',
        'blog'      => 'HMIS demos, procurement guidance, and software quotes — we respond within 24 hours.',
        'services'  => 'Free consultation — tell us your project goals and we\'ll give you a clear path forward.',
        default     => 'Free consultation — tell us your project goals and we\'ll give you a clear path forward.',
    };

    $contactParams = array_filter([
        'inquiry_type' => $inquiryType,
        'service' => $type === 'services' ? $content->slug : null,
    ]);
    $contactUrl = route('contact', $contactParams) . '#contact-form';

    $readingTime = $type === 'blog' ? content_reading_time_minutes($content->content) : null;
    $articleHeadings = $type === 'blog' ? content_article_headings($content->content) : [];
    $showArticleToc = count($articleHeadings) >= 3;
    $hasLeadMagnetShortcode = str_contains($content->content ?? '', '[lead-magnet');
    $showAutoLeadMagnet = $isHmis && in_array($type, ['services', 'blog'], true) && ! $hasLeadMagnetShortcode;

    $featuredTestimonial = ! empty($testimonials) && $testimonials->count() > 0
        ? $testimonials->first()
        : null;

    $crumbItems = [['label' => 'Home', 'url' => route('home')]];
    if ($indexRoute) {
        $crumbItems[] = ['label' => $hubLabel, 'url' => route($indexRoute)];
    }
    $crumbItems[] = ['label' => $content->title];

    $heroSubtitle = null;
    if ($type === 'blog' && ($content->published_at || $readingTime)) {
        $parts = [];
        if ($content->published_at) {
            $parts[] = $content->published_at->format('d M Y');
        }
        if ($readingTime) {
            $parts[] = "{$readingTime} min read";
        }
        $heroSubtitle = implode(' · ', $parts);
    } elseif ($content->published_at) {
        $heroSubtitle = $content->published_at->format('d M Y');
    }

    $heroTagline = filled($content->excerpt)
        ? \Illuminate\Support\Str::limit(strip_tags($content->excerpt), 120)
        : "Explore this {$hubLabel} from Forefront Solutions — HMIS, software, and digital transformation in Kenya.";

    $detailBadges = match ($type) {
        'portfolio' => $isHmis ? ['HMIS', 'Case Study'] : ['Portfolio', 'Case Study'],
        'services'  => ['Services'],
        'blog'      => ['Insights'],
        default     => [ucfirst($type)],
    };

    $relatedSectionLabel = match ($type) {
        'blog' => 'Related articles',
        default => 'More ' . $hubLabel,
    };
@endphp

@push('head')
    @if ($heroUrl)
        <link rel="preload" href="{{ $heroUrl }}" as="image">
    @endif
@endpush

@section('seo')
    @foreach ($pageSchemas ?? [] as $schema)
        <x-seo.json-ld :data="$schema" />
    @endforeach
@endsection

@section('content')

    {{-- Detail hero — same band height and styling as hub pages --}}
    <x-cms.hero-section
        theme="dark"
        size="standard"
        :title="$content->title"
        :subtitle="$heroSubtitle"
        :tagline="$heroTagline"
        :badges="$detailBadges"
        :image-url="$heroUrl"
        :image-srcset="$heroSrcset"
        :cta-url="hub_cta_url('detail_hero_' . $type, $inquiryType, $contactUrl)"
        :cta-label="$sidebarCtaLabel"
        :suppress-cta="$type === 'blog'" />

    <div class="bg-white border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <x-cms.breadcrumb :items="$crumbItems" />
        </div>
    </div>

    {{-- ── Content + sidebar ───────────────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 lg:py-14">
        <div class="grid lg:grid-cols-4 gap-8 lg:gap-12">

            {{-- Main content --}}
            <article id="detail-article" class="lg:col-span-3">

                    {{-- Mobile quick CTA (desktop uses sticky sidebar + layout bar) --}}
                    <div class="lg:hidden mb-6 bg-neutral-900 border-t-4 border-primary-500 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-400 mb-1">Next Step</p>
                        <p class="text-sm text-neutral-300 mb-3 leading-snug">{{ $sidebarTitle }}</p>
                        <a href="{{ generate_utm_url($contactUrl, 'details_' . $type . '_mobile_' . $content->id) }}"
                           class="block w-full text-center bg-white text-neutral-900 hover:bg-primary-50 py-3 text-sm font-bold uppercase tracking-wider transition-colors">
                            {{ $sidebarCtaLabel }} &rarr;
                        </a>
                    </div>

                    {{-- Excerpt / subtitle --}}
                    @php
                        $subtitleFallback = match ($type) {
                            'portfolio' => 'A case study delivering measurable outcomes for Kenyan institutions.',
                            'services'  => 'A focused service pillar for hospitals, government, NGOs, and campaigns.',
                            'blog'      => 'Expert insights on HMIS, elections, software, and digital strategy in Kenya.',
                            default     => 'In-depth content from Forefront Solutions.',
                        };
                        $subtitle = $content->excerpt ?? $subtitleFallback;
                    @endphp
                    <p class="text-base text-neutral-600 leading-relaxed mb-6 border-l-4 border-primary-500 pl-4 py-1">
                        {{ $subtitle }}
                    </p>

                    {{-- Featured image (single display — not duplicated in hero) --}}
                    @if ($content->images && $content->images->where('collection', 'featured')->isNotEmpty())
                        <figure class="mb-8">
                            <x-cms.responsive-image
                                :model="$content" collection="featured"
                                class="w-full aspect-[16/9] object-cover border border-neutral-200"
                                :alt="$content->title . ' – Featured Image'"
                                loading="lazy" />
                            @php $featuredImage = $content->images->where('collection', 'featured')->first(); @endphp
                            @if ($featuredImage?->alt_text)
                                <figcaption class="mt-2 text-xs text-neutral-500 border-l-2 border-neutral-300 pl-3">
                                    {{ $featuredImage->alt_text }}
                                </figcaption>
                            @endif
                        </figure>
                    @endif

                    {{-- Table of contents (long insight articles) --}}
                    @if ($showArticleToc)
                        <nav class="mb-8 p-4 border border-neutral-200 bg-neutral-50" aria-label="Table of contents">
                            <p class="text-xs font-semibold uppercase tracking-[0.15em] text-neutral-500 mb-3">In this article</p>
                            <ol class="space-y-2 text-sm">
                                @foreach ($articleHeadings as $heading)
                                    <li>
                                        <a href="#{{ $heading['id'] }}"
                                           class="text-primary-600 hover:text-primary-800 font-medium transition-colors">
                                            {{ $heading['text'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    @endif

                    {{-- Body --}}
                    <div class="prose-custom prose-sm lg:prose-base max-w-none text-neutral-700 leading-relaxed">
                        @if ($type === 'blog')
                            {!! render_cms_article($content->content) !!}
                        @else
                            {!! render_cms_content($content->content) !!}
                        @endif
                    </div>

                    {{-- Auto lead magnet for HMIS service/insight pages without shortcode --}}
                    @if ($showAutoLeadMagnet)
                        <div class="mt-8">
                            <x-cms.lead-magnet-band type="hmis-checklist" />
                        </div>
                    @endif

                    {{-- Gallery --}}
                    @if (in_array($type, ['portfolio', 'services', 'blog']) && $content->gallery_for_view->isNotEmpty())
                        @php
                            $galleryTitle = match ($type) {
                                'portfolio' => 'Project Gallery',
                                'services'  => 'Service Gallery',
                                default     => 'Gallery',
                            };
                        @endphp
                        <x-cms.content-gallery :content="$content" :title="$galleryTitle" />
                    @endif

                    {{-- Outcome metrics --}}
                    @if (!empty($metrics) && in_array($type, ['portfolio', 'services']))
                        <div class="mt-8 border border-neutral-200 border-t-4 border-t-primary-500 grid grid-cols-2 md:grid-cols-4">
                            @foreach ($metrics as $metric)
                                <div class="p-5 border-r border-neutral-200 last:border-r-0 text-center">
                                    <div class="text-3xl font-black font-mono text-primary-600 mb-1">{{ $metric['value'] }}</div>
                                    <p class="text-xs text-neutral-500 uppercase tracking-wider">{{ $metric['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Tags --}}
                    @if ($content->tags && $content->tags->count() > 0)
                        <section class="mt-8 pt-6 border-t border-neutral-200" aria-labelledby="tags-heading">
                            <h3 id="tags-heading" class="text-xs font-semibold uppercase tracking-[0.15em] text-neutral-500 mb-3">
                                Related Topics
                            </h3>
                            <nav class="flex flex-wrap gap-2" aria-label="Related topics">
                                @foreach ($content->tags as $tag)
                                    @php
                                        $tagUrl = route('tags.show', $tag->slug) .
                                            (in_array($type, ['blog', 'portfolio', 'services']) ? '?type=' . $type : '');
                                    @endphp
                                    <a href="{{ $tagUrl }}"
                                       class="inline-block px-3 py-1 text-xs font-semibold bg-neutral-100 border border-neutral-200 text-neutral-600 hover:border-neutral-400 hover:text-neutral-900 transition-colors uppercase tracking-wider">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </nav>
                        </section>
                    @endif

                    {{-- Contextual cross-links --}}
                    @if (in_array($type, ['portfolio', 'services', 'blog']))
                        <div class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-neutral-600 border-t border-neutral-200 pt-6">
                            <span>
                                @if ($type === 'portfolio')
                                    Explore related capabilities
                                @elseif ($type === 'services')
                                    See this work in practice
                                @else
                                    Ready to implement what you read?
                                @endif
                            </span>
                            @if ($type === 'portfolio' || $type === 'blog')
                                <a href="{{ route('services.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                                    Explore our services
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endif
                            @if ($type === 'services' || $type === 'blog')
                                <a href="{{ route('portfolio.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                                    See case studies
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endif
                            @if ($type === 'portfolio' || $type === 'services')
                                <a href="{{ route('insights.index') }}" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                                    Read insights
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Single featured testimonial (no carousel) --}}
                    @if ($featuredTestimonial)
                        <x-cms.featured-testimonial
                            :testimonial="$featuredTestimonial"
                            title="What Clients Say"
                            inline
                            class="mt-8 pt-6" />
                    @endif

            </article>

            {{-- Sidebar --}}
            <aside class="lg:col-span-1 lg:sticky lg:top-20 self-start space-y-6" role="complementary" aria-label="Sidebar">

                {{-- Inverted CTA --}}
                <div class="bg-neutral-900 border-t-4 border-primary-500 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-400 mb-2">Next Step</p>
                    <h4 class="text-lg font-black text-white mb-2 tracking-tight">{{ $sidebarTitle }}</h4>
                    <p class="text-sm text-neutral-400 mb-5 leading-relaxed">
                        {{ $sidebarText }}
                    </p>
                    <a href="{{ generate_utm_url($contactUrl, 'details_' . $type . '_' . $content->id) }}"
                       class="block w-full text-center bg-white text-neutral-900 hover:bg-primary-50 py-3 text-sm font-bold uppercase tracking-wider transition-colors">
                        {{ $sidebarCtaLabel }} &rarr;
                    </a>
                    @if ($isHmis || $type === 'blog')
                        <a href="{{ generate_utm_url($checklistUrl, 'details_' . $type . '_checklist_' . $content->id) }}"
                           class="block w-full text-center mt-3 border border-neutral-600 text-neutral-300 hover:text-white hover:border-neutral-400 py-2.5 text-xs font-bold uppercase tracking-wider transition-colors">
                            Download HMIS Checklist
                        </a>
                    @endif
                </div>

                {{-- Quick actions --}}
                @if ($content->ctas && $content->ctas->count() > 0)
                    <div class="border border-neutral-200 p-5">
                        <h4 class="text-xs font-semibold uppercase tracking-[0.15em] text-neutral-500 mb-4">Quick Actions</h4>
                        @foreach ($content->ctas->take(3) as $cta)
                            <x-cms.cta-button :cta="$cta" variant="outline" size="md"
                                class="w-full mb-3 btn-secondary text-sm py-2.5"
                                :url="generate_utm_url($cta->action, 'details_cta_' . $content->id)" />
                        @endforeach
                    </div>
                @endif

                {{-- Related items --}}
                @if (!empty($relatedItems) && $relatedItems->count() > 0)
                    <div class="border border-neutral-200 p-5">
                        <h4 class="text-xs font-semibold uppercase tracking-[0.15em] text-neutral-500 mb-4">
                            {{ $relatedSectionLabel }}
                        </h4>
                        <ul class="space-y-4">
                            @foreach ($relatedItems->take(5) as $related)
                                <li>
                                    @php $thumb = $related->images?->where('collection', 'featured')->first(); @endphp
                                    <a href="{{ generate_utm_url($related->url, 'details_related_' . $type . '_' . $content->id) }}"
                                       class="group flex items-start gap-3 hover:text-primary-600 transition-colors">
                                        @if ($thumb)
                                            <img src="{{ $thumb->image_url }}" alt="{{ $related->title }}"
                                                 class="w-12 h-12 object-cover flex-shrink-0 border border-neutral-200"
                                                 loading="lazy">
                                        @else
                                            <div class="w-12 h-12 bg-neutral-100 border border-neutral-200 flex-shrink-0 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="text-sm text-neutral-700 group-hover:text-primary-600 leading-snug line-clamp-2 transition-colors">
                                            {{ $related->title }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </aside>
        </div>
    </div>

    {{-- Breadcrumb schema --}}
    @php
        $breadcrumbData = [
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => array_values(array_filter([
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                $indexRoute ? [
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => $hubLabel,
                    'item'     => route($indexRoute),
                ] : null,
                [
                    '@type'    => 'ListItem',
                    'position' => $indexRoute ? 3 : 2,
                    'name'     => $content->title,
                    'item'     => $content->url ?? request()->url(),
                ],
            ])),
        ];
    @endphp
    <x-seo.json-ld :data="$breadcrumbData" />

    {{-- Process timeline — compact on services only --}}
    @if ($type === 'services')
        <x-cms.process-timeline
            compact
            section-label="Process"
            title="How we deliver"
            heading-id="details-process-heading"
            class="bg-neutral-50 border-t border-neutral-200"
            :cta-url="route('contact', ['inquiry_type' => $inquiryType]) . '#contact-form'"
            :cta-label="$sidebarCtaLabel" />
    @endif

    {{-- Footer CTA --}}
    @php
        $useHmisFooter = $isHmis || $type === 'blog' || ($type === 'services' && ! empty($leadConfig));
        $footerCtaTitle = match ($type) {
            'portfolio' => $isHmis ? 'Ready to Deploy HMIS at Your Facility?' : 'Ready to Start a Similar Project?',
            'services'  => $leadConfig['sidebar_title'] ?? config('forefront.inquiry_ux.default.sticky_label', 'Request HMIS Demo'),
            'blog'      => 'Ready to Act on These Insights?',
            default     => 'Let\'s Work Together',
        };
        $footerCtaSubtitle = match ($type) {
            'portfolio' => $isHmis
                ? 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.'
                : 'HMIS, software, or campaign platform — discuss scope and timeline with our team.',
            'services'  => $leadConfig['sidebar_text'] ?? 'Free consultation — no obligation.',
            'blog'      => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.',
            default     => 'We respond within 24 hours.',
        };
        $footerCtaLabel = $useHmisFooter
            ? ($leadConfig['cta_label'] ?? 'Request HMIS Demo')
            : match ($type) {
                'portfolio' => 'Discuss a Similar Project',
                default     => 'Get a Free Consultation',
            };
        $footerSecondaryUrl = $useHmisFooter ? $checklistUrl : null;
        $footerSecondaryLabel = $useHmisFooter ? 'Get HMIS Checklist' : null;
    @endphp
    <x-cms.footer-cta
        :title="$footerCtaTitle"
        :subtitle="$footerCtaSubtitle"
        :primary-url="generate_utm_url($contactUrl, 'details_' . $type . '_footer')"
        :primary-label="$footerCtaLabel"
        :secondary-url="$footerSecondaryUrl"
        :secondary-label="$footerSecondaryLabel"
        :show-whatsapp="false" />

@endsection
