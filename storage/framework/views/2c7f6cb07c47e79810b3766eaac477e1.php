


<?php $__env->startPush('head'); ?>
    <?php if($items instanceof \Illuminate\Pagination\LengthAwarePaginator && ($items->currentPage() > 1 || $items->hasMorePages())): ?>
        <?php if($items->currentPage() > 1): ?>
            <link rel="prev" href="<?php echo e($items->previousPageUrl()); ?>">
        <?php endif; ?>
        <?php if($items->hasMorePages()): ?>
            <link rel="next" href="<?php echo e($items->nextPageUrl()); ?>">
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('seo'); ?>
    <?php $__currentLoopData = $pageSchemas ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if (isset($component)) { $__componentOriginal1ff444a6761d54b4237649bd3eed67fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.json-ld','data' => ['data' => $schema]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.json-ld'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($schema)]); ?>
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
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php
        $tabs = [
            'all' => 'All',
            'blog' => 'Insights',
            'portfolio' => 'Case studies',
            'services' => 'Services',
        ];
        $totalCount = $items instanceof \Illuminate\Pagination\LengthAwarePaginator ? $items->total() : $items->count();
        $pageCount = $items->count();
        $from = $totalCount > 0 && $items instanceof \Illuminate\Pagination\LengthAwarePaginator ? $items->firstItem() : 0;
        $to = $totalCount > 0 && $items instanceof \Illuminate\Pagination\LengthAwarePaginator ? $items->lastItem() : 0;
        $activeTabLabel = $tabs[$type] ?? 'All';
        $tagChips = collect($tabs)->map(fn ($label, $key) => [
            'label' => $label,
            'url' => route('tags.show', $tag->slug) . ($key === 'all' ? '' : '?type=' . $key),
            'active' => $type === $key,
        ])->values()->all();
        $tagSummary = $totalCount > 0
            ? 'Showing ' . $from . '–' . $to . ' of ' . $totalCount . ' ' . \Illuminate\Support\Str::plural('item', $totalCount)
                . ($type !== 'all' ? ' in <span class="font-semibold text-neutral-700">' . e($activeTabLabel) . '</span>' : '')
            : null;
    ?>

    <?php if (isset($component)) { $__componentOriginald85159ac95308cdeb145f8a1717e7ea9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald85159ac95308cdeb145f8a1717e7ea9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hub-shell','data' => ['breadcrumb' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Insights', 'url' => route('insights.index')],
            ['label' => 'Tag: ' . $tag->name],
        ],'heroPreload' => hero_asset(5)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hub-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Insights', 'url' => route('insights.index')],
            ['label' => 'Tag: ' . $tag->name],
        ]),'hero-preload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(5))]); ?>
         <?php $__env->slot('hero', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'standard','title' => $tagIntro ? $tag->name . ' — Content Hub' : $tag->name,'subtitle' => $tagIntro ?? ('Case studies, services, and insights tagged ' . $tag->name . ' for Kenyan hospitals, NGOs, and institutions.'),'tagline' => 'Filter by content type below, or browse everything in this topic cluster.','imageUrl' => hero_asset(5),'imageSrcset' => hero_asset_srcset(5),'badges' => ['HMIS', 'Software', 'Branding', 'Campaigns'],'ctaUrl' => hub_cta_url('tag_hero_' . $tag->slug)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'standard','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tagIntro ? $tag->name . ' — Content Hub' : $tag->name),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tagIntro ?? ('Case studies, services, and insights tagged ' . $tag->name . ' for Kenyan hospitals, NGOs, and institutions.')),'tagline' => 'Filter by content type below, or browse everything in this topic cluster.','image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(5)),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset_srcset(5)),'badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['HMIS', 'Software', 'Branding', 'Campaigns']),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('tag_hero_' . $tag->slug))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d24c5a1535570606fe4612a7be01ba8)): ?>
<?php $attributes = $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8; ?>
<?php unset($__attributesOriginal8d24c5a1535570606fe4612a7be01ba8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d24c5a1535570606fe4612a7be01ba8)): ?>
<?php $component = $__componentOriginal8d24c5a1535570606fe4612a7be01ba8; ?>
<?php unset($__componentOriginal8d24c5a1535570606fe4612a7be01ba8); ?>
<?php endif; ?>
         <?php $__env->endSlot(); ?>

        <?php if (isset($component)) { $__componentOriginalc8470b2526938015b24c695b6aaab05f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8470b2526938015b24c695b6aaab05f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.filter-chip-nav','data' => ['label' => 'Tag content types','items' => $tagChips,'resultSummary' => $tagSummary]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.filter-chip-nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tag content types','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tagChips),'result-summary' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tagSummary)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8470b2526938015b24c695b6aaab05f)): ?>
<?php $attributes = $__attributesOriginalc8470b2526938015b24c695b6aaab05f; ?>
<?php unset($__attributesOriginalc8470b2526938015b24c695b6aaab05f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8470b2526938015b24c695b6aaab05f)): ?>
<?php $component = $__componentOriginalc8470b2526938015b24c695b6aaab05f; ?>
<?php unset($__componentOriginalc8470b2526938015b24c695b6aaab05f); ?>
<?php endif; ?>

    <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="tag-results-heading">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => $tag->name,'headingId' => 'tag-results-heading','title' => $type === 'all' ? 'Everything tagged #' . $tag->name : $activeTabLabel . ' tagged #' . $tag->name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tag->name),'heading-id' => 'tag-results-heading','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($type === 'all' ? 'Everything tagged #' . $tag->name : $activeTabLabel . ' tagged #' . $tag->name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c)): ?>
<?php $attributes = $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c; ?>
<?php unset($__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c)): ?>
<?php $component = $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c; ?>
<?php unset($__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c); ?>
<?php endif; ?>

            <?php if($items->isEmpty()): ?>
                <div class="card-base p-8 text-center border-t-4 border-t-primary-500">
                    <p class="section-label mb-2">No matches</p>
                    <h3 class="text-xl font-black text-neutral-900 mb-2">Nothing published in this category yet</h3>
                    <p class="text-sm text-neutral-600 mb-6">
                        We haven&rsquo;t published anything tagged <strong>#<?php echo e($tag->name); ?></strong> in this filter yet.
                    </p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="<?php echo e(route('tags.show', $tag->slug)); ?>" class="btn-secondary text-sm px-5 py-2.5 min-h-[48px]">View all types</a>
                        <a href="<?php echo e(route('insights.index')); ?>" class="btn-primary text-sm px-5 py-2.5 min-h-[48px]">Browse insights</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="content-card-grid">
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal862d753875cd5d17661e132338b4e343 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal862d753875cd5d17661e132338b4e343 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.content-card','data' => ['item' => $item,'type' => search_content_card_type($item->type),'style' => 'animation-delay: '.e($loop->index * 0.07).'s;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.content-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item),'type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(search_content_card_type($item->type)),'style' => 'animation-delay: '.e($loop->index * 0.07).'s;']); ?>
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages()): ?>
                    <nav class="mt-10 flex justify-center" aria-label="Tag results pagination">
                        <?php echo e($items->appends(['type' => $type !== 'all' ? $type : null])->links('pagination::tailwind')); ?>

                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php if (isset($component)) { $__componentOriginal03b5006f52c5c5b41290402a897a208b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b5006f52c5c5b41290402a897a208b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.page-closer','data' => ['title' => 'Found something relevant?','subtitle' => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.','primaryUrl' => hub_cta_url('tag_footer_' . $tag->slug),'primaryLabel' => 'Request HMIS Demo','secondaryUrl' => route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form','secondaryLabel' => 'Get HMIS Checklist','size' => 'default']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.page-closer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Found something relevant?','subtitle' => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.','primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('tag_footer_' . $tag->slug)),'primary-label' => 'Request HMIS Demo','secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'),'secondary-label' => 'Get HMIS Checklist','size' => 'default']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03b5006f52c5c5b41290402a897a208b)): ?>
<?php $attributes = $__attributesOriginal03b5006f52c5c5b41290402a897a208b; ?>
<?php unset($__attributesOriginal03b5006f52c5c5b41290402a897a208b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03b5006f52c5c5b41290402a897a208b)): ?>
<?php $component = $__componentOriginal03b5006f52c5c5b41290402a897a208b; ?>
<?php unset($__componentOriginal03b5006f52c5c5b41290402a897a208b); ?>
<?php endif; ?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald85159ac95308cdeb145f8a1717e7ea9)): ?>
<?php $attributes = $__attributesOriginald85159ac95308cdeb145f8a1717e7ea9; ?>
<?php unset($__attributesOriginald85159ac95308cdeb145f8a1717e7ea9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald85159ac95308cdeb145f8a1717e7ea9)): ?>
<?php $component = $__componentOriginald85159ac95308cdeb145f8a1717e7ea9; ?>
<?php unset($__componentOriginald85159ac95308cdeb145f8a1717e7ea9); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/frontend/tag.blade.php ENDPATH**/ ?>