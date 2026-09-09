
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'items',
    'title' => null,
    'subtitle' => null,
    'type' => 'portfolio',
    'perPage' => 6,
    'showTitle' => true,
    'embedded' => false,
    'showCtaBanner' => true,
    'showLoadMore' => false,
    'highlightSlug' => null,
    'columns' => 3,
    'emptyHeadline' => null,
    'emptyMessage' => null,
    'globalCta' => null,
    'ctaBannerUrl' => null,
    'ctaBannerText' => 'Request HMIS Demo',
    'ctaBannerHeadline' => null,
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
    'items',
    'title' => null,
    'subtitle' => null,
    'type' => 'portfolio',
    'perPage' => 6,
    'showTitle' => true,
    'embedded' => false,
    'showCtaBanner' => true,
    'showLoadMore' => false,
    'highlightSlug' => null,
    'columns' => 3,
    'emptyHeadline' => null,
    'emptyMessage' => null,
    'globalCta' => null,
    'ctaBannerUrl' => null,
    'ctaBannerText' => 'Request HMIS Demo',
    'ctaBannerHeadline' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $subtitle = $subtitle ?? null;
    $isPaginated = $items instanceof \Illuminate\Pagination\LengthAwarePaginator;
    $displayItems = $isPaginated ? $items : $items->take($perPage ?? 6);
    $wrapperTag = $embedded ? 'div' : 'section';
    $wrapperClass = $embedded
        ? 'py-0 bg-transparent'
        : 'py-14 lg:py-20 bg-neutral-50/50';
    $innerClass = $embedded ? '' : 'max-w-7xl mx-auto px-6 lg:px-8';
    $gridClass = match ((int) $columns) {
        4 => 'content-card-grid content-card-grid-xl',
        default => 'content-card-grid',
    };
    $emptyTitle = $emptyHeadline ?? ('No ' . ($title ? strtolower($title) : ($type === 'blog' ? 'articles' : 'items')) . ' available yet');
    $emptyBody = $emptyMessage ?? "Check back soon — we're always adding new work and insights.";
?>

<<?php echo e($wrapperTag); ?> <?php echo e($attributes->merge(['class' => $wrapperClass])); ?> <?php if($showTitle && !$embedded): ?> aria-labelledby="grid-title-<?php echo e($type); ?>" <?php endif; ?>>
    <div <?php if($innerClass): ?> class="<?php echo e($innerClass); ?>" <?php endif; ?>>
        <?php if($showTitle): ?>
            <div class="text-center mb-8 lg:mb-10">
                <h2 id="grid-title-<?php echo e($type); ?>"
                    class="text-3xl md:text-4xl font-black tracking-tight text-neutral-900 mb-3 lg:mb-4">
                    <?php echo e($title); ?>

                </h2>
                <?php if($subtitle): ?>
                    <p class="text-base lg:text-lg text-neutral-600 max-w-3xl mx-auto font-light leading-relaxed">
                        <?php echo e($subtitle); ?>

                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="<?php echo e($gridClass); ?>">
            <?php $__empty_1 = true; $__currentLoopData = $displayItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if (isset($component)) { $__componentOriginal862d753875cd5d17661e132338b4e343 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal862d753875cd5d17661e132338b4e343 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.content-card','data' => ['item' => $item,'type' => $type,'highlighted' => $highlightSlug && $item->slug === $highlightSlug,'style' => 'animation-delay: '.e($loop->index * 0.07).'s;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.content-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($type),'highlighted' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($highlightSlug && $item->slug === $highlightSlug),'style' => 'animation-delay: '.e($loop->index * 0.07).'s;']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal862d753875cd5d17661e132338b4e343)): ?>
<?php $attributes = $__attributesOriginal862d753875cd5d17661e132338b4e343; ?>
<?php unset($__attributesOriginal862d753875cd5d17661e132338b4e343); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal862d753875cd5d17661e132338b4e343)): ?>
<?php $component = $__componentOriginal862d753875cd5d17661e132338b4e343; ?>
<?php unset($__componentOriginal862d753875cd5d17661e132338b4e343); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-16 border border-neutral-200 border-t-4 border-t-primary-500 bg-neutral-50 text-center px-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-600 mb-3">Nothing Here Yet</p>
                    <h3 class="text-xl font-black text-neutral-900 tracking-tight mb-2">
                        <?php echo e($emptyTitle); ?>

                    </h3>
                    <p class="text-sm text-neutral-500 mb-6 max-w-xs mx-auto leading-relaxed">
                        <?php echo e($emptyBody); ?>

                    </p>
                    <?php if($type === 'portfolio' && ($emptyMessage ?? null)): ?>
                        <a href="<?php echo e(route('portfolio.index')); ?>"
                           class="inline-flex items-center gap-2 px-5 py-2.5 border border-neutral-300 text-neutral-700 text-xs font-bold uppercase tracking-wider hover:bg-neutral-50 transition-colors mr-3">
                            View All Case Studies
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('contact')); ?>"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white text-xs font-bold uppercase tracking-wider hover:bg-primary-700 transition-colors">
                        Discuss Your Project
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if($showCtaBanner): ?>
            <?php
                $bannerUrl = $ctaBannerUrl ?? hub_cta_url(($title ? \Illuminate\Support\Str::slug($title) : $type) . '_grid');
                $bannerHeadline = $ctaBannerHeadline ?? (
                    str_contains($ctaBannerText ?? '', 'View All') ? ($type === 'service' ? 'Explore all our services.' : ($type === 'portfolio' ? 'View our full portfolio.' : 'Explore more.')) : 'Ready to start your project?'
                );
            ?>
            <?php if (isset($component)) { $__componentOriginale2fd6f279fa6e38b24f5831a7c2e8828 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2fd6f279fa6e38b24f5831a7c2e8828 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.cta-banner','data' => ['cta' => $globalCta ?? null,'class' => 'mt-10 lg:mt-12 animate-fade-in','url' => $bannerUrl,'headline' => $bannerHeadline,'buttonText' => $ctaBannerText]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.cta-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['cta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($globalCta ?? null),'class' => 'mt-10 lg:mt-12 animate-fade-in','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($bannerUrl),'headline' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($bannerHeadline),'buttonText' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ctaBannerText)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale2fd6f279fa6e38b24f5831a7c2e8828)): ?>
<?php $attributes = $__attributesOriginale2fd6f279fa6e38b24f5831a7c2e8828; ?>
<?php unset($__attributesOriginale2fd6f279fa6e38b24f5831a7c2e8828); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale2fd6f279fa6e38b24f5831a7c2e8828)): ?>
<?php $component = $__componentOriginale2fd6f279fa6e38b24f5831a7c2e8828; ?>
<?php unset($__componentOriginale2fd6f279fa6e38b24f5831a7c2e8828); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>
</<?php echo e($wrapperTag); ?>>

<?php
    $gridSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $title ?? ucfirst($type),
        'itemListElement' => $displayItems
            ->map(function ($item, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@type' => 'CreativeWork',
                        'name' => $item->title,
                        'url' => $item->url,
                        'image' => ($item->featured_image ?? $item->featured_image_url ?? null) ?: asset('images/default-og-image.png'),
                    ],
                ];
            })
            ->values()
            ->toArray(),
    ];
?>
<?php if (isset($component)) { $__componentOriginal1ff444a6761d54b4237649bd3eed67fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.json-ld','data' => ['data' => $gridSchema]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.json-ld'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($gridSchema)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $attributes = $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $component = $__componentOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/content-grid.blade.php ENDPATH**/ ?>