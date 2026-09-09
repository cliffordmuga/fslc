@extends('layouts.guest')

@section('seo')
    @foreach ($pageSchemas ?? [] as $schema)
        <x-seo.json-ld :data="$schema" />
    @endforeach
@endsection

@section('content')

    <x-cms.hub-shell
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $pageKey === 'privacy' ? 'Privacy Policy' : 'Terms & Conditions'],
        ]"
        :hero-preload="hero_asset(2)">
        <x-slot:hero>
            <x-cms.hero-section
                theme="dark"
                size="standard"
                :title="$title"
                :subtitle="$pageKey === 'privacy'
                    ? 'How we collect, use, and protect personal information submitted through this website.'
                    : 'Terms governing use of this website and services provided by ' . setting('company_name', config('app.name')) . '.'"
                :image-url="hero_asset(2)"
                :image-srcset="hero_asset_srcset(2)"
                tagline="HMIS · Software · Branding · Public Engagement — Forefront Solutions Kenya."
                :badges="['Legal']"
                :cta-url="hub_cta_url('legal_hero')" />
        </x-slot:hero>

    <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-label="{{ $title }}">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="prose-custom prose-sm max-w-none">
                @if ($content)
                    {!! render_cms_content($content->content) !!}
                @else
                    @if ($pageKey === 'privacy')
                        <h2>Information we collect</h2>
                        <p>
                            We may collect information you submit through forms (such as your name, email address, phone number, and message).
                            We may also collect basic technical information (such as browser type and pages visited) for analytics and site improvement.
                        </p>
                        <h2>How we use information</h2>
                        <ul>
                            <li>To respond to inquiries and provide requested services</li>
                            <li>To improve website performance, content, and user experience</li>
                            <li>To monitor spam and protect site integrity</li>
                        </ul>
                        <h2>Sharing</h2>
                        <p>
                            We do not sell your personal information. We may share information only when necessary to provide services,
                            comply with legal obligations, or protect our rights.
                        </p>
                        <h2>Data retention</h2>
                        <p>
                            We retain inquiries and submissions for as long as necessary for business, security, and compliance purposes.
                        </p>
                    @else
                        <h2>Use of the website</h2>
                        <p>You agree to use this website lawfully and not to misuse forms, content, or features.</p>
                        <h2>Intellectual property</h2>
                        <p>
                            Unless otherwise stated, website content is owned by
                            {{ setting('company_name', config('app.name')) }} and may not be copied without permission.
                        </p>
                        <h2>Service inquiries</h2>
                        <p>
                            Submitting an inquiry does not guarantee availability or acceptance of a project.
                            Project terms, pricing, and timelines are agreed in writing.
                        </p>
                        <h2>Third-party links</h2>
                        <p>This site may link to third-party websites. We are not responsible for their content or privacy practices.</p>
                        <h2>Limitation of liability</h2>
                        <p>We are not liable for damages arising from use of this website, except where prohibited by law.</p>
                    @endif

                    <h2>Contact</h2>
                    <p>
                        Questions? <a href="{{ hub_cta_url('legal_contact') }}">Request an HMIS demo</a>
                        or <a href="{{ route('contact', ['inquiry_type' => 'hmis-checklist']) }}#contact-form">download the HMIS checklist</a>.
                    </p>
                @endif

                <p class="text-sm text-neutral-500 mt-10 not-prose">
                    Last updated: {{ ($content?->updated_at ?? now())->format('F j, Y') }}
                </p>
            </div>
        </div>
    </section>

    <x-cms.page-closer
        title="Questions about our policies?"
        subtitle="Request an HMIS demo or speak with our team — we respond within 24 hours."
        :primary-url="hub_cta_url('legal_footer')"
        primary-label="Request HMIS Demo"
        :secondary-url="route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'"
        secondary-label="Get HMIS Checklist"
        size="default" />

    </x-cms.hub-shell>

@endsection
