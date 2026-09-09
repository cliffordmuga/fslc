
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'testimonial' => null,
    'title' => 'Trusted by healthcare and institutional leaders',
    'inline' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'testimonial' => null,
    'title' => 'Trusted by healthcare and institutional leaders',
    'inline' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $quote = $testimonial;
    $fallback = [
        'body' => 'Forefront understood our HMIS requirements from day one — modules, SHA billing, and training were delivered on schedule for our facility rollout.',
        'name' => 'Hospital IT Director',
        'role' => 'County referral hospital, Kenya',
        'context' => 'HMIS implementation',
    ];
?>

<section <?php echo e($attributes->merge(['class' => $inline
    ? 'mt-0 py-0 bg-transparent border-t border-neutral-200'
    : 'py-10 lg:py-12 bg-neutral-50 border-t border-neutral-200'])); ?> aria-label="Client testimonial">
    <div class="<?php echo e($inline ? 'max-w-none px-0' : 'max-w-4xl mx-auto px-6 lg:px-8'); ?>">
        <?php if(! $inline): ?>
            <p class="section-label mb-2 text-center">Testimonial</p>
            <h2 class="text-2xl lg:text-3xl font-black text-neutral-900 tracking-tight text-center mb-6"><?php echo e($title); ?></h2>
        <?php else: ?>
            <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-600 mb-2">Client Feedback</p>
            <h2 class="text-xl font-black text-neutral-900 mb-4 tracking-tight"><?php echo e($title); ?></h2>
        <?php endif; ?>
        <figure class="card-base p-6 lg:p-8 bg-white" itemscope itemtype="https://schema.org/Review">
            <blockquote class="text-base lg:text-lg text-neutral-700 leading-relaxed mb-5" itemprop="reviewBody">
                <p>&ldquo;<?php echo e($quote ? \Illuminate\Support\Str::limit($quote->testimonial, 320) : $fallback['body']); ?>&rdquo;</p>
            </blockquote>
            <figcaption class="flex flex-wrap items-center justify-between gap-3 border-t border-neutral-100 pt-4">
                <cite class="not-italic" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <span class="block text-sm font-bold text-neutral-900" itemprop="name">
                        <?php echo e($quote?->client_name ?? $fallback['name']); ?>

                    </span>
                    <span class="block text-xs text-neutral-500 mt-0.5">
                        <?php echo e($quote?->client_title ?? ''); ?>

                        <?php if($quote?->client_title && $quote?->company): ?> at <?php endif; ?>
                        <?php echo e($quote?->company ?? ($quote ? '' : $fallback['role'])); ?>

                    </span>
                </cite>
                <?php if($quote?->project_context ?? (!$quote && ($fallback['context'] ?? null))): ?>
                    <span class="text-[11px] font-semibold uppercase tracking-wider px-2.5 py-1 bg-primary-50 text-primary-700 border border-primary-100">
                        <?php echo e($quote?->project_context ?? $fallback['context']); ?>

                    </span>
                <?php endif; ?>
            </figcaption>
            <?php if($quote?->rating): ?>
                <meta itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                <meta itemprop="ratingValue" content="<?php echo e($quote->rating); ?>">
                <meta itemprop="bestRating" content="5">
            <?php endif; ?>
        </figure>
    </div>
</section>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/featured-testimonial.blade.php ENDPATH**/ ?>