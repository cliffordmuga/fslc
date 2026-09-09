{{-- Shared page closer: optional lead magnet + footer CTA --}}
@props([
    'magnetType' => 'hmis-checklist',
    'showMagnet' => true,
    'title' => 'Ready to Digitize Your Hospital or Institution?',
    'subtitle' => 'Request an HMIS demo or speak with our team about software, branding, or campaign platforms.',
    'primaryUrl' => null,
    'primaryLabel' => 'Request HMIS Demo',
    'secondaryUrl' => null,
    'secondaryLabel' => 'Get HMIS Checklist',
    'showWhatsapp' => false,
    'size' => 'large',
])

@if ($showMagnet)
    <x-cms.lead-magnet-band :type="$magnetType" />
@endif

<x-cms.footer-cta
    :title="$title"
    :subtitle="$subtitle"
    :primary-url="$primaryUrl ?? hub_cta_url('page_footer_primary', 'hmis-demo')"
    :primary-label="$primaryLabel"
    :secondary-url="$secondaryUrl ?? hub_cta_url('page_footer_secondary', 'hmis-checklist')"
    :secondary-label="$secondaryLabel"
    :show-whatsapp="$showWhatsapp"
    :size="$size"
    {{ $attributes }} />
