<?php

/**
 * Runtime Forefront CMS configuration (lead UX, portfolio filters, tag hubs).
 * Seeded into the database where applicable; safe to use without running seeders.
 */
return [
    'service_lead_config' => [
        'hmis-digital-health-solutions-kenya' => [
            'inquiry_type' => 'hmis-demo',
            'cta_label' => 'Request HMIS Demo',
            'sidebar_title' => 'Request HMIS Demo',
            'sidebar_text' => 'Book a facility walkthrough and see HMIS modules, SHA workflows, and reporting in action.',
        ],
        'custom-software-web-development-kenya' => [
            'inquiry_type' => 'software-quote',
            'cta_label' => 'Get Project Quote',
            'sidebar_title' => 'Get a Project Quote',
            'sidebar_text' => 'Tell us about your portal, app, or integration requirements — we respond within 24 hours.',
        ],
        'digital-strategy-branding-marketing-kenya' => [
            'inquiry_type' => 'marketing-consult',
            'cta_label' => 'Brand Consultation',
            'sidebar_title' => 'Brand Consultation',
            'sidebar_text' => 'Discuss positioning, SEO, and digital communications aligned to your institution.',
        ],
        'political-public-engagement-kenya' => [
            'inquiry_type' => 'campaign-strategy',
            'cta_label' => 'Campaign Strategy Session',
            'sidebar_title' => 'Campaign Strategy Session',
            'sidebar_text' => 'Confidential planning for websites, outreach tools, and 2027 election readiness.',
        ],
    ],

    'portfolio_pillars' => [
        'all' => ['label' => 'All', 'slugs' => []],
        'hmis' => ['label' => 'HMIS & Digital Health', 'slugs' => ['county-referral-hospital-hmis', 'ngo-community-health-emr']],
        'software' => ['label' => 'Software & Web', 'slugs' => ['government-citizen-services-portal', 'mpesa-ecommerce-retail-kenya', 'sacco-member-portal-kenya']],
        'political' => ['label' => 'Political & Public Engagement', 'slugs' => ['political-campaign-digital-hub']],
    ],

    'tag_hub_intros' => [
        'hmis' => 'Guides and case studies on hospital management systems, HMIS procurement, and digital health in Kenya — for hospital boards, county health teams, and NGO programmes.',
        'emr' => 'Electronic medical records implementation insights for clinics, hospitals, and community health programmes across Kenya.',
        'digital-health' => 'Digital health strategy, hospital digitization, and health IT content from Forefront Solutions.',
        'sha-integration' => 'Social Health Authority billing integration guides for Kenyan hospitals and HMIS vendors.',
        'election-digital-strategy' => 'Election digital strategy, campaign infrastructure, and voter outreach for Kenya\'s 2027 cycle.',
        'campaign-branding' => 'Political branding, campaign creative, and constituency communications for Kenyan candidates and parties.',
        'voter-engagement' => 'Voter engagement platforms, supporter CRM, and public participation tools for campaigns and advocacy groups.',
        'web-development' => 'Custom web development, Laravel applications, and institutional portals built for Kenyan organizations.',
        'laravel' => 'Laravel development guides for secure, maintainable software in Kenya.',
        'm-pesa-integration' => 'M-Pesa Daraja integration patterns for e-commerce, billing, and member portals.',
        'seo' => 'SEO and digital marketing guides for healthcare, NGO, and institutional websites in Kenya.',
        'digital-marketing' => 'Digital marketing strategy and execution for Kenyan SMEs, NGOs, and public-sector programmes.',
    ],

    'inquiry_ux' => [
        'default' => [
            'contact_headline' => 'Tell Us Your Vision',
            'sticky_label' => 'Request HMIS Demo',
            'sticky_utm' => 'sticky_hmis_demo',
            'whatsapp_message' => 'Hi, I\'d like to book an HMIS demo for our facility.',
        ],
        'hmis-demo' => [
            'contact_headline' => 'Request Your HMIS Demo',
            'sticky_label' => 'Request HMIS Demo',
            'sticky_utm' => 'sticky_hmis_demo',
            'whatsapp_message' => 'Hi, I\'d like to book an HMIS demo for our facility.',
        ],
        'hmis-checklist' => [
            'contact_headline' => 'Get the HMIS Procurement Checklist',
            'sticky_label' => 'HMIS Checklist',
            'sticky_utm' => 'sticky_hmis_checklist',
            'whatsapp_message' => 'Hi, please send me the HMIS procurement checklist.',
        ],
        'software-quote' => [
            'contact_headline' => 'Get Your Project Quote',
            'sticky_label' => 'Get Project Quote',
            'sticky_utm' => 'sticky_software_quote',
            'whatsapp_message' => 'Hi, I\'d like a quote for a software/web project.',
        ],
        'marketing-consult' => [
            'contact_headline' => 'Book a Brand Consultation',
            'sticky_label' => 'Brand Consultation',
            'sticky_utm' => 'sticky_marketing_consult',
            'whatsapp_message' => 'Hi, I\'d like to discuss digital strategy and branding.',
        ],
        'campaign-strategy' => [
            'contact_headline' => 'Book a Campaign Strategy Session',
            'sticky_label' => 'Campaign Strategy',
            'sticky_utm' => 'sticky_campaign_strategy',
            'whatsapp_message' => 'Hi, I\'d like a confidential campaign strategy session.',
        ],
        'portfolio' => [
            'contact_headline' => 'Discuss a Similar Project',
            'sticky_label' => 'Discuss Project',
            'sticky_utm' => 'sticky_portfolio_detail',
            'whatsapp_message' => 'Hi, I\'d like to discuss a project similar to one on your portfolio.',
        ],
    ],

    /** Outcome metrics shown on portfolio/service detail pages (keyed by content slug). */
    'content_detail_metrics' => [
        'county-referral-hospital-hmis' => [
            ['value' => '450+', 'label' => 'Daily OPD visits'],
            ['value' => '12', 'label' => 'Clinical modules'],
            ['value' => '8 wks', 'label' => 'Go-live timeline'],
            ['value' => '99.2%', 'label' => 'Billing uptime'],
        ],
        'ngo-community-health-emr' => [
            ['value' => '120+', 'label' => 'CHU sites linked'],
            ['value' => '6', 'label' => 'Programme dashboards'],
            ['value' => '4 wks', 'label' => 'Pilot rollout'],
            ['value' => '100%', 'label' => 'Donor reporting'],
        ],
        'hmis-digital-health-solutions-kenya' => [
            ['value' => '12+', 'label' => 'HMIS modules'],
            ['value' => 'SHA', 'label' => 'Billing ready'],
            ['value' => 'MOH', 'label' => 'Reporting aligned'],
            ['value' => '24h', 'label' => 'Support response'],
        ],
    ],

    /** Per-inquiry contact form UX (fields, copy, success messages). */
    'contact_form' => [
        'default' => [
            'description' => 'Free consultation — tell us your project goals and we respond within 24 hours.',
            'message_label' => 'Project Details',
            'message_placeholder' => 'Tell us about your project goals, timeline, and budget...',
            'submit_label' => 'Send Message',
            'single_step' => false,
            'require_service' => false,
            'show_upload' => true,
            'success_message' => 'Thank you! We\'ll respond within 24 hours.',
        ],
        'hmis-demo' => [
            'description' => 'Book a facility walkthrough — HMIS modules, SHA workflows, and reporting. We respond within 24 hours.',
            'message_label' => 'Facility & Requirements',
            'message_placeholder' => 'Hospital or county name, bed capacity, modules needed (OPD, IPD, pharmacy, SHA billing)...',
            'submit_label' => 'Request HMIS Demo',
            'single_step' => false,
            'require_service' => false,
            'show_upload' => false,
            'success_message' => 'Thank you! Expect a demo scheduling call within 24 hours.',
        ],
        'hmis-checklist' => [
            'description' => 'Enter your details — we email the HMIS procurement checklist (Kenya 2026) within 24 hours.',
            'message_label' => 'Institution (optional)',
            'message_placeholder' => 'Hospital or county name, procurement stage (optional)...',
            'submit_label' => 'Send Checklist',
            'single_step' => true,
            'require_service' => false,
            'show_upload' => false,
            'default_message' => 'Please send the HMIS procurement checklist.',
            'success_message' => 'Thank you! We\'ll email the HMIS procurement checklist within 24 hours.',
        ],
        'software-quote' => [
            'description' => 'Describe your portal, app, or integration — we respond with scope and timeline within 24 hours.',
            'message_label' => 'Project Requirements',
            'message_placeholder' => 'Portal, app, or integration scope, users, integrations (M-Pesa, SHA, etc.)...',
            'submit_label' => 'Get Project Quote',
            'single_step' => false,
            'require_service' => false,
            'show_upload' => true,
            'success_message' => 'Thank you! We\'ll send a project quote outline within 24 hours.',
        ],
        'marketing-consult' => [
            'description' => 'Discuss positioning, SEO, and digital communications for your institution.',
            'message_label' => 'Brand & Marketing Goals',
            'message_placeholder' => 'Institution type, current digital presence, goals for the next 6–12 months...',
            'submit_label' => 'Book Consultation',
            'single_step' => false,
            'require_service' => false,
            'show_upload' => false,
            'success_message' => 'Thank you! We\'ll confirm your brand consultation within 24 hours.',
        ],
        'campaign-strategy' => [
            'description' => 'Confidential planning for websites, outreach tools, and 2027 election readiness.',
            'message_label' => 'Campaign Context',
            'message_placeholder' => 'Constituency, race level, timeline, and current digital infrastructure...',
            'submit_label' => 'Book Strategy Session',
            'single_step' => false,
            'require_service' => false,
            'show_upload' => false,
            'success_message' => 'Thank you! We\'ll arrange a confidential strategy session within 24 hours.',
        ],
        'portfolio' => [
            'description' => 'Tell us about a similar project — scope, timeline, and institution type.',
            'message_label' => 'Project Scope',
            'message_placeholder' => 'Institution type, project scope, and preferred timeline...',
            'submit_label' => 'Discuss Project',
            'single_step' => false,
            'require_service' => false,
            'show_upload' => false,
            'success_message' => 'Thank you! We\'ll respond with next steps within 24 hours.',
        ],
    ],

    /** Pillar quick-links on the contact page. */
    'contact_pillar_links' => [
        ['label' => 'HMIS & Digital Health', 'inquiry_type' => 'hmis-demo', 'accent' => 'pillar-border-hmis'],
        ['label' => 'Software & Web', 'inquiry_type' => 'software-quote', 'accent' => 'pillar-border-software'],
        ['label' => 'Branding & Marketing', 'inquiry_type' => 'marketing-consult', 'accent' => 'pillar-border-branding'],
        ['label' => 'Public Engagement', 'inquiry_type' => 'campaign-strategy', 'accent' => 'pillar-border-campaigns'],
    ],

    /** Site search UX — suggested queries and content-type filters. */
    'search_suggested_queries' => [
        ['label' => 'HMIS Kenya', 'q' => 'HMIS Kenya'],
        ['label' => 'SHA integration', 'q' => 'SHA integration'],
        ['label' => 'web development', 'q' => 'web development'],
        ['label' => 'election digital strategy', 'q' => 'election digital strategy'],
        ['label' => 'Laravel', 'q' => 'Laravel'],
    ],

    'search_type_filters' => [
        '' => 'All',
        'portfolio' => 'Case studies',
        'services' => 'Services',
        'blog' => 'Insights',
    ],

    /** Footer service column — slug => label (CMS-aligned). */
    'footer_service_links' => [
        'hmis-digital-health-solutions-kenya' => 'HMIS & Digital Health',
        'custom-software-web-development-kenya' => 'Software & Web Development',
        'digital-strategy-branding-marketing-kenya' => 'Branding & Marketing',
        'political-public-engagement-kenya' => 'Political & Public Engagement',
    ],

    'nav_primary_cta' => [
        'label' => 'Request HMIS Demo',
        'url' => '/contact?inquiry_type=hmis-demo#contact-form',
        'utm' => 'nav_primary_cta',
    ],

    'inquiry_service_map' => [
        'hmis-demo' => 'hmis-digital-health-solutions-kenya',
        'hmis-checklist' => 'hmis-digital-health-solutions-kenya',
        'software-quote' => 'custom-software-web-development-kenya',
        'marketing-consult' => 'digital-strategy-branding-marketing-kenya',
        'campaign-strategy' => 'political-public-engagement-kenya',
    ],

    /** Show full-screen preloader (disabled by default for Core Web Vitals). */
    'show_preloader' => env('FOREFRONT_SHOW_PRELOADER', false),

    /**
     * Lead magnet copy + UTM sources — shared by lead-magnet-band and lead-magnet-cta.
     * Change here once to update both surfaces.
     */
    'lead_magnets' => [
        'hmis-checklist' => [
            'eyebrow' => 'Free Resource',
            'title' => 'HMIS Procurement Checklist (Kenya 2026)',
            'text' => 'Free checklist for hospital boards evaluating HMIS vendors — modules, SHA integration, training, and TCO.',
            'button' => 'Download Checklist',
            'inquiry_type' => 'hmis-checklist',
            'utm_band' => 'lead_magnet_home_band',
            'utm_inline' => 'lead_magnet_hmis_checklist',
        ],
        'hmis-demo' => [
            'eyebrow' => 'HMIS Demo',
            'title' => 'See Forefront HMIS in Action',
            'text' => 'Book a guided demo for your hospital or county health team — modules, workflows, and reporting.',
            'button' => 'Request HMIS Demo',
            'inquiry_type' => 'hmis-demo',
            'utm_band' => 'lead_magnet_home_band_demo',
            'utm_inline' => 'lead_magnet_hmis_demo',
        ],
        'default' => [
            'eyebrow' => 'HMIS Demo',
            'title' => 'Request Your HMIS Demo',
            'text' => 'Book a guided demo for your hospital or county health team — we respond within 24 hours.',
            'button' => 'Request HMIS Demo',
            'inquiry_type' => 'hmis-demo',
            'utm_band' => 'lead_magnet_home_band_general',
            'utm_inline' => 'lead_magnet_general',
        ],
    ],

    /**
     * Hero variant → photography (preferred over generated WebP/SVG).
     * Variants: 1=home, 2=about, 3=portfolio, 4=services, 5=search/tags, 6=insights, 7=contact
     */
    'hero_images' => [
        1 => 'images/hero-home.jpg',
        2 => 'images/about-hero.jpg',
        3 => 'images/portfolio-hero.jpg',
        4 => 'images/services-hero.jpg',
        5 => 'images/search-hero.jpg',
        6 => 'images/blog-hero.jpg',
        7 => 'images/contact-hero.jpg',
    ],
];
