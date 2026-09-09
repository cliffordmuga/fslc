
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['item', 'type' => 'portfolio', 'highlighted' => false, 'compact' => true, 'searchQuery' => null]));

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

foreach (array_filter((['item', 'type' => 'portfolio', 'highlighted' => false, 'compact' => true, 'searchQuery' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    use Illuminate\Support\Str;
    $category    = $item->tags->first()?->name ?? content_hub_label($item->type ?? 'portfolio');
    $tags        = $item->tags ?? collect();
    $wordCount   = str_word_count(strip_tags($item->content ?? ''));
    $readingMins = max(1, (int) round($wordCount / 200));

    $appHost     = parse_url(config('app.url', ''), PHP_URL_HOST) ?? '';
    $linkHost    = $item->url ? (parse_url($item->url, PHP_URL_HOST) ?? '') : '';
    $isExternal  = $linkHost && $appHost && $linkHost !== $appHost;
    $linkRel     = $isExternal ? 'nofollow noopener noreferrer' : null;

    $showTags    = $type === 'blog' && $tags->count() > 0;
    $excerptLimit = $compact ? 88 : 120;
    $excerptLines = $compact ? 'line-clamp-1' : 'line-clamp-2';
    $excerptText = Str::limit($item->excerpt ?? strip_tags($item->content ?? ''), $excerptLimit);
    $titleHtml = $searchQuery ? highlight_search_term($item->title, $searchQuery) : e($item->title);
    $excerptHtml = $searchQuery ? highlight_search_term($excerptText, $searchQuery) : e($excerptText);

    $cardClass = 'card-base group flex flex-col bg-white h-full';
    if ($highlighted) {
        $cardClass .= ' border-t-4 border-t-primary-500 ring-1 ring-primary-100';
    }
?>

<article
    <?php if(!empty($item->slug)): ?> id="<?php echo e($item->slug); ?>" <?php endif; ?>
    <?php echo e($attributes->merge(['class' => $cardClass])); ?>

    itemscope itemtype="https://schema.org/CreativeWork">

    
    <div class="relative <?php echo e($compact ? 'aspect-[2/1]' : 'aspect-[16/9]'); ?> overflow-hidden bg-neutral-100" data-img-wrap>
        <div class="absolute inset-0 bg-neutral-200 animate-pulse" data-skeleton aria-hidden="true"></div>
        <?php if (isset($component)) { $__componentOriginal709b67cb8379df6b5da8f3c1045984a7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal709b67cb8379df6b5da8f3c1045984a7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.responsive-image','data' => ['model' => $item,'collection' => 'featured','class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 relative z-10','alt' => $item->title . ' image','loading' => 'lazy']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.responsive-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'collection' => 'featured','class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 relative z-10','alt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->title . ' image'),'loading' => 'lazy']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal709b67cb8379df6b5da8f3c1045984a7)): ?>
<?php $attributes = $__attributesOriginal709b67cb8379df6b5da8f3c1045984a7; ?>
<?php unset($__attributesOriginal709b67cb8379df6b5da8f3c1045984a7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal709b67cb8379df6b5da8f3c1045984a7)): ?>
<?php $component = $__componentOriginal709b67cb8379df6b5da8f3c1045984a7; ?>
<?php unset($__componentOriginal709b67cb8379df6b5da8f3c1045984a7); ?>
<?php endif; ?>

        <div class="absolute inset-0 bg-neutral-900/0 group-hover:bg-neutral-900/25 transition-colors duration-300"></div>

        <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
            <?php if($highlighted): ?>
                <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'brand','size' => 'xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'brand','size' => 'xs']); ?>Primary practice <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
            <?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'light','size' => 'xs','class' => 'border-l-2 border-l-primary-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'light','size' => 'xs','class' => 'border-l-2 border-l-primary-500']); ?>
                <?php echo e($category); ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
        </div>

        <?php if($type === 'blog'): ?>
            <div class="absolute bottom-2 right-2 z-10">
                <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'dark','size' => 'xs','uppercase' => false,'class' => 'font-mono']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'dark','size' => 'xs','uppercase' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'font-mono']); ?>
                    <?php echo e($readingMins); ?>m
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="<?php echo e($compact ? 'p-3.5 sm:p-4' : 'p-5'); ?> flex flex-col flex-1 min-h-0">
        <h3 class="<?php echo e($compact ? 'text-sm mb-1' : 'text-base mb-2'); ?> font-bold text-neutral-900 line-clamp-2 leading-snug group-hover:text-primary-600 transition-colors"
            itemprop="name">
            <?php echo $titleHtml; ?>

        </h3>

        <p class="text-xs sm:text-sm text-neutral-500 <?php echo e($compact ? 'mb-2.5' : 'mb-4'); ?> <?php echo e($excerptLines); ?> leading-relaxed" itemprop="description">
            <?php echo $excerptHtml; ?>

        </p>

        <?php if($showTags): ?>
            <div class="flex flex-wrap gap-1 mb-2.5">
                <?php $__currentLoopData = $tags->take($compact ? 2 : 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'neutral','size' => 'xs','uppercase' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'neutral','size' => 'xs','uppercase' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?><?php echo e($tag->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($tags->count() > ($compact ? 2 : 3)): ?>
                    <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'neutral','size' => 'xs','uppercase' => false,'class' => 'text-neutral-400 bg-neutral-50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'neutral','size' => 'xs','uppercase' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'text-neutral-400 bg-neutral-50']); ?>
                        +<?php echo e($tags->count() - ($compact ? 2 : 3)); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="mt-auto flex items-center justify-between gap-2 <?php echo e($compact ? 'pt-2 border-t border-neutral-100' : 'pt-4 border-t border-neutral-100'); ?>">
            <a href="<?php echo e($item->url); ?>"
                class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors group/link"
                itemprop="url"
                <?php if($isExternal): ?> target="_blank" <?php endif; ?>
                <?php if($linkRel): ?> rel="<?php echo e($linkRel); ?>" <?php endif; ?>
                aria-label="<?php echo e($type === 'blog' ? 'Read' : 'View'); ?>: <?php echo e($item->title); ?>">
                <?php echo e($type === 'blog' ? 'Read' : 'View'); ?>

                <svg class="w-3.5 h-3.5 group-hover/link:translate-x-0.5 transition-transform duration-200"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <?php if($item->published_at && $type === 'blog'): ?>
                <span class="text-[11px] text-neutral-400 font-mono shrink-0">
                    <?php echo e($item->published_at->format('M Y')); ?>

                </span>
            <?php endif; ?>
        </div>
    </div>
</article>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/content-card.blade.php ENDPATH**/ ?>