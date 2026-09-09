<nav class="mb-6 flex flex-wrap gap-2 border-b border-neutral-200 pb-4" aria-label="Analytics sections">
    <a href="{{ route('admin.analytics.index', request()->only(['date_range', 'start_date', 'end_date'])) }}"
       class="px-4 py-2 text-sm font-semibold {{ request()->routeIs('admin.analytics.index') ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50' }}">
        Overview
    </a>
    <a href="{{ route('admin.analytics.content', request()->only(['date_range', 'start_date', 'end_date'])) }}"
       class="px-4 py-2 text-sm font-semibold {{ request()->routeIs('admin.analytics.content') ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50' }}">
        Content performance
    </a>
</nav>
<p class="mb-4 text-xs text-neutral-500">
    Conversion rates use the <strong>leads</strong> table (actual submissions), not page_analytics.leads_generated.
</p>
