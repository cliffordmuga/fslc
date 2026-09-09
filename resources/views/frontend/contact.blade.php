{{-- resources/views/frontend/contact.blade.php --}}
@extends('layouts.guest')

@section('seo')
    @foreach ($pageSchemas ?? [] as $schema)
        <x-seo.json-ld :data="$schema" />
    @endforeach
@endsection

@section('content')

    @php
        $foundedYear = setting('founded_year', '2015');
        $headline = $leadUx['contact_headline'] ?? 'Tell Us Your Vision';
        $waMessage = $leadUx['whatsapp_message'] ?? 'Hi, I\'d like to get in touch about a project.';
        $isChecklist = $inquiryType === 'hmis-checklist';
        $showPillarChooser = $inquiryType === 'general';
    @endphp

    <x-cms.hub-shell
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Contact']]"
        :hero-preload="hero_asset(7)">
        <x-slot:hero>
            <x-cms.hero-section
                theme="dark"
                size="standard"
                title="Contact Forefront Solutions"
                :image-url="hero_asset(7)"
                :image-srcset="hero_asset_srcset(7)"
                subtitle="Request an HMIS demo, software quote, brand consultation, or campaign strategy session — we respond within 24 hours."
                tagline="HMIS · Software · Branding · Public Engagement — one focused partner for Kenyan institutions."
                :badges="['HMIS', 'Software', 'Branding', 'Campaigns']"
                :cta-url="hub_cta_url('contact_hero')" />
        </x-slot:hero>

        <x-slot:stats>
            <x-cms.stats-strip
        compact
        :columns="4"
        :items="[
            ['value' => ($stats['response_time'] ?? '24') . 'h', 'label' => 'Average response time'],
            ['value' => '4', 'label' => 'Core service pillars'],
            ['value' => 'HMIS', 'label' => 'Primary practice'],
            ['value' => $foundedYear, 'label' => 'Serving Kenya since'],
        ]" />
        </x-slot:stats>

    @if ($isChecklist)
        <x-cms.lead-magnet-band type="hmis-checklist" />
    @endif

    {{-- Main contact section --}}
    <section id="contact-form" class="py-10 lg:py-14 bg-neutral-50 border-t border-neutral-200 scroll-mt-24" aria-labelledby="contact-form-heading">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            @if ($showPillarChooser && ! empty($pillarLinks))
                <div class="mb-8">
                    <p class="section-label mb-2">Which pillar fits your project?</p>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        @foreach ($pillarLinks as $pillar)
                            <a href="{{ route('contact', ['inquiry_type' => $pillar['inquiry_type']]) }}#contact-form"
                               class="card-base p-4 border-t-4 {{ $pillar['accent'] }} group {{ $inquiryType === $pillar['inquiry_type'] ? 'ring-2 ring-primary-500' : '' }}">
                                <p class="text-xs font-bold text-neutral-900 group-hover:text-primary-600 transition-colors">{{ $pillar['label'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Mobile contact shortcuts --}}
            @if (! empty($companyInfo['phone']))
                <div class="lg:hidden mb-6 grid grid-cols-2 gap-3">
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyInfo['phone']) }}"
                       class="flex items-center justify-center gap-2 border border-neutral-200 bg-white py-3 text-sm font-semibold text-neutral-800 hover:border-primary-400 transition-colors">
                        Call us
                    </a>
                    @php $waNum = preg_replace('/[^0-9]/', '', $companyInfo['phone']); @endphp
                    @if ($waNum)
                        <a href="https://wa.me/{{ $waNum }}?text={{ rawurlencode($waMessage) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="flex items-center justify-center gap-2 bg-[#25D366] text-white py-3 text-sm font-semibold hover:bg-[#1ebe5d] transition-colors">
                            WhatsApp
                        </a>
                    @endif
                </div>
            @endif

            <div class="grid lg:grid-cols-5 gap-8 lg:gap-12">

                {{-- Form --}}
                <div class="lg:col-span-3">
                    {{-- Trust badges --}}
                    <div class="flex flex-wrap gap-2 mb-5">
                        @foreach (['Reply within 24h', 'No spam, ever', 'Free consultation'] as $badge)
                            <x-ui.badge tone="outline-light" size="sm" class="gap-1.5 tracking-wider">
                                <svg class="w-3 h-3 text-primary-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $badge }}
                            </x-ui.badge>
                        @endforeach
                    </div>

                    @if ($formSuccess ?? false)
                        @php
                            $successTitle = contact_form_ux($inquiryType)['success_message']
                                ?? session('success')
                                ?? "Thank you! We'll respond within 24 hours.";
                        @endphp
                        <x-ui.alert type="success" class="mb-6 p-4" :title="$successTitle">
                            @if ($isChecklist)
                                Check your inbox — we send the checklist from {{ $companyInfo['email'] ?? setting('email') }}.
                            @elseif ($inquiryType === 'hmis-demo')
                                Our team will contact you to schedule a facility walkthrough.
                            @else
                                We typically respond within {{ $stats['response_time'] ?? '24' }} hours on business days.
                            @endif
                        </x-ui.alert>
                    @endif

                    <p class="section-label mb-2">Get in touch</p>
                    <h2 id="contact-form-heading" class="scroll-mt-24 text-2xl lg:text-3xl font-black text-neutral-900 mb-2 tracking-tight">
                        {{ $headline }}
                    </h2>
                    <p class="text-sm text-neutral-600 mb-6 max-w-2xl leading-relaxed">
                        {{ $formUx['description'] ?? '' }}
                    </p>

                    @if ($contactPage?->excerpt ?? null)
                        <p class="text-sm text-neutral-500 mb-6 border-l-2 border-neutral-300 pl-3 max-w-2xl">
                            {{ $contactPage->excerpt }}
                        </p>
                    @endif

                    <x-cms.contact-form
                        :route="route('contact.store')"
                        :leadSource="$contactPage"
                        :inquiry-type="$inquiryType"
                        :services="$services"
                        :preselected-service-id="$preselectedServiceId"
                        :single-step="(bool) ($formUx['single_step'] ?? false)"
                        :require-service="(bool) ($formUx['require_service'] ?? true)"
                        :show-upload="(bool) ($formUx['show_upload'] ?? true)"
                        :hide-service-when-preselected="! ($formUx['require_service'] ?? true) && ! empty($preselectedServiceId)"
                        hide-header
                        :message-label="$formUx['message_label'] ?? 'Project Details'"
                        :message-placeholder="$formUx['message_placeholder'] ?? 'Tell us about your project...'"
                        :submit-label="$formUx['submit_label'] ?? 'Send Message'"
                        class="max-w-none border border-neutral-200 border-t-4 border-t-primary-500 bg-white p-6 lg:p-8"
                        source-content-id="{{ $contactPage->id ?? null }}" />
                </div>

                {{-- Sidebar --}}
                <aside class="lg:col-span-2 space-y-6 lg:sticky lg:top-20 self-start">
                    <x-cms.contact-info-card
                        :address="$companyInfo['address'] ?? setting('address', 'Nairobi, Kenya')"
                        :email="$companyInfo['email'] ?? setting('email')"
                        :phone="$companyInfo['phone'] ?? setting('phone')"
                        :whatsapp-message="$waMessage" />

                    <x-cms.stats-strip
                        compact
                        :columns="1"
                        :standalone="false"
                        class="border border-neutral-200 bg-white p-4 text-left !grid-cols-1"
                        :items="[
                            ['value' => ($stats['response_time'] ?? '24') . 'h', 'label' => 'Average response'],
                            ['value' => ($stats['satisfaction'] ?? '98') . '%', 'label' => 'Client satisfaction'],
                        ]" />

                    @if (! empty($companyInfo['maps_embed_url'] ?? null))
                        <div class="border border-neutral-200 overflow-hidden">
                            <iframe src="{{ $companyInfo['maps_embed_url'] }}"
                                    class="w-full h-56 border-0"
                                    loading="lazy" title="Our Location"
                                    allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    <x-cms.page-closer
        :show-magnet="! $isChecklist"
        title="Prefer to Talk First?"
        subtitle="Request an HMIS demo or download the procurement checklist — we respond within 24 hours."
        :primary-url="hub_cta_url('contact_footer_cta')"
        primary-label="Request HMIS Demo"
        :secondary-url="route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'"
        secondary-label="Get HMIS Checklist"
        size="default" />

    </x-cms.hub-shell>

@endsection
