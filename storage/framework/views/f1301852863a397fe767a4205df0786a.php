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
        $foundedYear = setting('founded_year', '2015');
        $activePillar = $pillar ?? 'all';
        $activeLabel = $pillars[$activePillar]['label'] ?? 'All';
        $resultCount = $portfolioItems->count();
        $pillarFilterActive = [
            'all' => 'bg-neutral-900 text-white border-neutral-900',
            'hmis' => 'pillar-filter-active-hmis',
            'software' => 'pillar-filter-active-software',
            'political' => 'pillar-filter-active-political',
        ];
        $pillarChips = collect($pillars ?? [])->map(fn ($config, $key) => [
            'label' => $config['label'],
            'url' => $key === 'all' ? route('portfolio.index') : route('portfolio.index', ['pillar' => $key]),
            'active' => $activePillar === $key,
            'class' => $activePillar === $key
                ? ($pillarFilterActive[$key] ?? 'bg-neutral-900 text-white border-neutral-900')
                : null,
        ])->values()->all();
    ?>

    <?php if (isset($component)) { $__componentOriginald85159ac95308cdeb145f8a1717e7ea9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald85159ac95308cdeb145f8a1717e7ea9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hub-shell','data' => ['breadcrumb' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Portfolio']],'heroPreload' => hero_asset(3)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hub-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Home', 'url' => route('home')], ['label' => 'Portfolio']]),'hero-preload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(3))]); ?>
         <?php $__env->slot('hero', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'standard','title' => 'Case Studies & Projects','imageUrl' => hero_asset(3),'imageSrcset' => hero_asset_srcset(3),'subtitle' => 'HMIS deployments, software platforms, institutional portals, and campaign digital hubs — explore work across three practice areas.','tagline' => 'Real deployments for hospitals, government, NGOs, and campaigns in Kenya.','badges' => ['HMIS', 'Software', 'Branding', 'Campaigns'],'cta' => $portfolioCta,'ctaUrl' => hub_cta_url('portfolio_hero')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'standard','title' => 'Case Studies & Projects','image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(3)),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset_srcset(3)),'subtitle' => 'HMIS deployments, software platforms, institutional portals, and campaign digital hubs — explore work across three practice areas.','tagline' => 'Real deployments for hospitals, government, NGOs, and campaigns in Kenya.','badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['HMIS', 'Software', 'Branding', 'Campaigns']),'cta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portfolioCta),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('portfolio_hero'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.stats-strip','data' => ['compact' => true,'columns' => 4,'items' => [
                    ['value' => (string) ($totalProjects ?? $resultCount), 'label' => 'Published case studies'],
                    ['value' => '3', 'label' => 'Portfolio practice areas'],
                    ['value' => 'HMIS', 'label' => 'Primary practice'],
                    ['value' => $foundedYear, 'label' => 'Serving Kenya since'],
                ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.stats-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['compact' => true,'columns' => 4,'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    ['value' => (string) ($totalProjects ?? $resultCount), 'label' => 'Published case studies'],
                    ['value' => '3', 'label' => 'Portfolio practice areas'],
                    ['value' => 'HMIS', 'label' => 'Primary practice'],
                    ['value' => $foundedYear, 'label' => 'Serving Kenya since'],
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.filter-chip-nav','data' => ['label' => 'Portfolio practice areas','items' => $pillarChips,'wrap' => true,'resultSummary' => 'Showing ' . $resultCount . ' ' . \Illuminate\Support\Str::plural('case study', $resultCount) . ($activePillar !== 'all' ? ' in <span class=&quot;font-semibold text-neutral-700&quot;>' . e($activeLabel) . '</span>' : '')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.filter-chip-nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Portfolio practice areas','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pillarChips),'wrap' => true,'result-summary' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Showing ' . $resultCount . ' ' . \Illuminate\Support\Str::plural('case study', $resultCount) . ($activePillar !== 'all' ? ' in <span class=&quot;font-semibold text-neutral-700&quot;>' . e($activeLabel) . '</span>' : ''))]); ?>
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

        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="portfolio-heading">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'Portfolio','headingId' => 'portfolio-heading','title' => $activePillar === 'all' ? 'All case studies' : $activeLabel,'intro' => 'Explore HMIS deployments, institutional portals, e-commerce platforms, and campaign digital hubs — filtered by practice area. Branding and communications work is integrated within these case studies until standalone brand projects are published.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Portfolio','heading-id' => 'portfolio-heading','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activePillar === 'all' ? 'All case studies' : $activeLabel),'intro' => 'Explore HMIS deployments, institutional portals, e-commerce platforms, and campaign digital hubs — filtered by practice area. Branding and communications work is integrated within these case studies until standalone brand projects are published.']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.content-grid','data' => ['items' => $portfolioItems,'type' => 'portfolio','showTitle' => false,'embedded' => true,'showCtaBanner' => false,'perPage' => 9,'columns' => 4,'highlightSlug' => $activePillar === 'all' ? 'county-referral-hospital-hmis' : null,'emptyHeadline' => $activePillar !== 'all' ? 'No case studies in this practice area yet' : null,'emptyMessage' => $activePillar !== 'all' ? 'Try another filter or view all published projects while we add more work in this category.' : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.content-grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portfolioItems),'type' => 'portfolio','show-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'embedded' => true,'show-cta-banner' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'per-page' => 9,'columns' => 4,'highlight-slug' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activePillar === 'all' ? 'county-referral-hospital-hmis' : null),'empty-headline' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activePillar !== 'all' ? 'No case studies in this practice area yet' : null),'empty-message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activePillar !== 'all' ? 'Try another filter or view all published projects while we add more work in this category.' : null)]); ?>
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
            </div>
        </section>

        <?php if (isset($component)) { $__componentOriginal03b5006f52c5c5b41290402a897a208b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b5006f52c5c5b41290402a897a208b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.page-closer','data' => ['title' => 'Ready to Start Your Project?','subtitle' => 'HMIS implementation, custom software, or campaign platform — request a demo or download the procurement checklist.','primaryUrl' => hub_cta_url('portfolio_footer_cta'),'primaryLabel' => 'Request HMIS Demo','secondaryUrl' => route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form','secondaryLabel' => 'Get HMIS Checklist']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.page-closer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ready to Start Your Project?','subtitle' => 'HMIS implementation, custom software, or campaign platform — request a demo or download the procurement checklist.','primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('portfolio_footer_cta')),'primary-label' => 'Request HMIS Demo','secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'),'secondary-label' => 'Get HMIS Checklist']); ?>
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

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/frontend/portfolio.blade.php ENDPATH**/ ?>