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

<?php $__env->startPush('head'); ?>
    <?php if($blogPosts instanceof \Illuminate\Pagination\LengthAwarePaginator && ($blogPosts->currentPage() > 1 || $blogPosts->hasMorePages())): ?>
        <?php if($blogPosts->currentPage() > 1): ?>
            <link rel="prev" href="<?php echo e($blogPosts->previousPageUrl()); ?>">
        <?php endif; ?>
        <?php if($blogPosts->hasMorePages()): ?>
            <link rel="next" href="<?php echo e($blogPosts->nextPageUrl()); ?>">
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <?php
        $activeCategory = $category ?? '';
        $resultCount = $blogPosts->count();
        $totalCount = $blogPosts instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $blogPosts->total()
            : $resultCount;
        $hubSlugs = $tagHubSlugs ?? ['hmis', 'election-digital-strategy', 'sha-integration', 'digital-health', 'laravel'];
        $categoryChips = collect([['slug' => '', 'name' => 'All', 'hub' => false]])
            ->merge($categories->map(fn ($tag) => ['slug' => $tag->slug, 'name' => $tag->name, 'hub' => in_array($tag->slug, $hubSlugs, true)]))
            ->map(fn ($tag) => [
                'label' => $tag['name'],
                'url' => $tag['slug'] === ''
                    ? route('insights.index')
                    : ($tag['hub']
                        ? route('tags.show', $tag['slug'])
                        : route('insights.index', ['category' => $tag['slug']])),
                'active' => $activeCategory === $tag['slug'],
                'title' => $tag['slug'] === ''
                    ? null
                    : ($tag['hub'] ? 'Browse full ' . $tag['name'] . ' hub' : 'Filter insights by ' . $tag['name']),
            ])
            ->values()
            ->all();
        $insightsSummary = 'Showing ' . $resultCount . ' ' . \Illuminate\Support\Str::plural('article', $resultCount) . ' on this page'
            . ($activeCategory !== '' ? ' in <span class="font-semibold text-neutral-700">' . e($activeCategoryLabel) . '</span>' : '')
            . ($totalCount > $resultCount ? ' · ' . $totalCount . ' total' : '');
    ?>

    <?php if (isset($component)) { $__componentOriginald85159ac95308cdeb145f8a1717e7ea9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald85159ac95308cdeb145f8a1717e7ea9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hub-shell','data' => ['breadcrumb' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Insights']],'heroPreload' => hero_asset(6)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hub-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Home', 'url' => route('home')], ['label' => 'Insights']]),'hero-preload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(6))]); ?>
         <?php $__env->slot('hero', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'standard','title' => 'Insights & Guides','imageUrl' => hero_asset(6),'imageSrcset' => hero_asset_srcset(6),'subtitle' => 'HMIS procurement guides, hospital digitization, election digital strategy, and software development insights for Kenyan hospitals, NGOs, and institutions.','tagline' => 'Practical HMIS, software, and campaign content for Kenyan institutions.','badges' => ['HMIS', 'Software', 'Branding', 'Campaigns'],'cta' => $blogCta ?? null,'ctaUrl' => hub_cta_url('insights_hero')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'standard','title' => 'Insights & Guides','image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(6)),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset_srcset(6)),'subtitle' => 'HMIS procurement guides, hospital digitization, election digital strategy, and software development insights for Kenyan hospitals, NGOs, and institutions.','tagline' => 'Practical HMIS, software, and campaign content for Kenyan institutions.','badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['HMIS', 'Software', 'Branding', 'Campaigns']),'cta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blogCta ?? null),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('insights_hero'))]); ?>
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

         <?php $__env->slot('stats', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginalc11a15779fcbdadbd9313b66a637a9d3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc11a15779fcbdadbd9313b66a637a9d3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.stats-strip','data' => ['compact' => true,'columns' => 3,'items' => [
                    ['value' => (string) $totalCount, 'label' => 'Published guides'],
                    ['value' => (string) $categories->count(), 'label' => 'Topics covered'],
                    ['value' => 'HMIS', 'label' => 'Primary content cluster'],
                ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.stats-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['compact' => true,'columns' => 3,'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    ['value' => (string) $totalCount, 'label' => 'Published guides'],
                    ['value' => (string) $categories->count(), 'label' => 'Topics covered'],
                    ['value' => 'HMIS', 'label' => 'Primary content cluster'],
                ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc11a15779fcbdadbd9313b66a637a9d3)): ?>
<?php $attributes = $__attributesOriginalc11a15779fcbdadbd9313b66a637a9d3; ?>
<?php unset($__attributesOriginalc11a15779fcbdadbd9313b66a637a9d3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc11a15779fcbdadbd9313b66a637a9d3)): ?>
<?php $component = $__componentOriginalc11a15779fcbdadbd9313b66a637a9d3; ?>
<?php unset($__componentOriginalc11a15779fcbdadbd9313b66a637a9d3); ?>
<?php endif; ?>
         <?php $__env->endSlot(); ?>

        <?php if (isset($component)) { $__componentOriginalc8470b2526938015b24c695b6aaab05f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8470b2526938015b24c695b6aaab05f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.filter-chip-nav','data' => ['label' => 'Insight topics','items' => $categoryChips,'resultSummary' => $insightsSummary]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.filter-chip-nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Insight topics','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categoryChips),'result-summary' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($insightsSummary)]); ?>
             <?php $__env->slot('prefix', null, []); ?> 
                <form action="<?php echo e(route('search')); ?>" method="get" class="relative flex-1 max-w-md mb-4 lg:mb-0 lg:mr-4">
                    <label for="insights-search" class="sr-only">Search insights</label>
                    <input id="insights-search"
                           type="search"
                           name="q"
                           placeholder="Search all insights — press Enter"
                           class="input-base w-full pl-9 pr-4 py-2.5 text-sm min-h-[48px]"
                           autocomplete="off">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </form>
             <?php $__env->endSlot(); ?>
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

    
    <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="insights-heading">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'Insights','headingId' => 'insights-heading','title' => $activeCategory !== '' ? $activeCategoryLabel : 'Latest guides & case studies','intro' => 'Guides on HMIS procurement, SHA integration, Laravel development, and election digital strategy — filter by topic, open a dedicated tag hub, or search the full library.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Insights','heading-id' => 'insights-heading','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeCategory !== '' ? $activeCategoryLabel : 'Latest guides & case studies'),'intro' => 'Guides on HMIS procurement, SHA integration, Laravel development, and election digital strategy — filter by topic, open a dedicated tag hub, or search the full library.']); ?>
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

            <?php if (isset($component)) { $__componentOriginalccd1207879c8f5f2949401f3c8efcd0b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalccd1207879c8f5f2949401f3c8efcd0b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.content-grid','data' => ['items' => $blogPosts,'type' => 'blog','showTitle' => false,'embedded' => true,'showCtaBanner' => false,'showLoadMore' => false,'perPage' => 9,'columns' => 3,'highlightSlug' => 'best-hmis-kenya-hospital-evaluation-guide','emptyHeadline' => $activeCategory !== '' ? 'No articles in this topic yet' : null,'emptyMessage' => $activeCategory !== '' ? 'Try another topic, browse a tag hub, or view all insights.' : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.content-grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blogPosts),'type' => 'blog','show-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'embedded' => true,'show-cta-banner' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'show-load-more' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'per-page' => 9,'columns' => 3,'highlight-slug' => 'best-hmis-kenya-hospital-evaluation-guide','empty-headline' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeCategory !== '' ? 'No articles in this topic yet' : null),'empty-message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeCategory !== '' ? 'Try another topic, browse a tag hub, or view all insights.' : null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalccd1207879c8f5f2949401f3c8efcd0b)): ?>
<?php $attributes = $__attributesOriginalccd1207879c8f5f2949401f3c8efcd0b; ?>
<?php unset($__attributesOriginalccd1207879c8f5f2949401f3c8efcd0b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalccd1207879c8f5f2949401f3c8efcd0b)): ?>
<?php $component = $__componentOriginalccd1207879c8f5f2949401f3c8efcd0b; ?>
<?php unset($__componentOriginalccd1207879c8f5f2949401f3c8efcd0b); ?>
<?php endif; ?>

            <?php if($blogPosts instanceof \Illuminate\Pagination\LengthAwarePaginator && $blogPosts->hasPages()): ?>
                <div class="mt-10 border-t border-neutral-200 pt-6">
                    <?php echo e($blogPosts->links()); ?>

                </div>
            <?php endif; ?>

            <div class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-neutral-600 border-t border-neutral-200 pt-6">
                <span>Ready to implement what you read?</span>
                <a href="<?php echo e(route('services.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                    Explore our services
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="<?php echo e(route('portfolio.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                    See case studies
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    <?php if (isset($component)) { $__componentOriginal03b5006f52c5c5b41290402a897a208b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b5006f52c5c5b41290402a897a208b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.page-closer','data' => ['title' => 'Ready to Act on These Insights?','subtitle' => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.','primaryUrl' => hub_cta_url('insights_footer_cta'),'primaryLabel' => 'Request HMIS Demo','secondaryUrl' => route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form','secondaryLabel' => 'Get HMIS Checklist']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.page-closer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ready to Act on These Insights?','subtitle' => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.','primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('insights_footer_cta')),'primary-label' => 'Request HMIS Demo','secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'),'secondary-label' => 'Get HMIS Checklist']); ?>
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

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/frontend/blog.blade.php ENDPATH**/ ?>