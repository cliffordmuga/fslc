


<?php $__env->startPush('head'); ?>
    <?php if($query !== '' && $results instanceof \Illuminate\Pagination\LengthAwarePaginator && ($results->currentPage() > 1 || $results->hasMorePages())): ?>
        <?php if($results->currentPage() > 1): ?>
            <link rel="prev" href="<?php echo e($results->previousPageUrl()); ?>">
        <?php endif; ?>
        <?php if($results->hasMorePages()): ?>
            <link rel="next" href="<?php echo e($results->nextPageUrl()); ?>">
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
        use Illuminate\Support\Str;
        $hasQuery = $query !== '';
        $totalCount = $hasQuery && $results instanceof \Illuminate\Pagination\LengthAwarePaginator ? $results->total() : 0;
        $pageCount = $hasQuery ? $results->count() : 0;
        $from = $hasQuery && $totalCount > 0 ? $results->firstItem() : 0;
        $to = $hasQuery && $totalCount > 0 ? $results->lastItem() : 0;
        $crumbQuery = Str::limit($query, 40);
        $pillarLinks = config('forefront.contact_pillar_links', []);
        $searchTypeChips = $hasQuery
            ? collect($typeFilters)->map(fn ($label, $typeKey) => [
                'label' => $label,
                'url' => route('search', array_filter(['q' => $query, 'type' => $typeKey !== '' ? $typeKey : null])),
                'active' => ($activeType ?? '') === (string) $typeKey,
            ])->values()->all()
            : [];
        $searchSummary = $hasQuery && $totalCount > 0
            ? 'Showing ' . $from . '–' . $to . ' of ' . $totalCount . ' ' . Str::plural('result', $totalCount)
                . ($activeType !== '' ? ' in <span class="font-semibold text-neutral-700">' . e($activeTypeLabel) . '</span>' : '')
            : null;
    ?>

    <?php if (isset($component)) { $__componentOriginald85159ac95308cdeb145f8a1717e7ea9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald85159ac95308cdeb145f8a1717e7ea9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hub-shell','data' => ['breadcrumb' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $hasQuery ? 'Search: ' . $crumbQuery : 'Search'],
        ],'heroPreload' => hero_asset(5)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hub-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $hasQuery ? 'Search: ' . $crumbQuery : 'Search'],
        ]),'hero-preload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(5))]); ?>
         <?php $__env->slot('hero', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'standard','title' => $hasQuery ? 'Search Results' : 'Search Insights & Portfolio','subtitle' => $hasQuery ? 'for \'' . e($query) . '\'' : 'Find HMIS guides, software case studies, and digital strategy content for Kenyan institutions.','imageUrl' => hero_asset(5),'imageSrcset' => hero_asset_srcset(5),'tagline' => 'HMIS · Software · Branding · Public Engagement — practical content for Kenyan institutions.','badges' => ['HMIS', 'Software', 'Branding', 'Campaigns'],'ctaUrl' => hub_cta_url('search_hero')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'standard','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hasQuery ? 'Search Results' : 'Search Insights & Portfolio'),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hasQuery ? 'for \'' . e($query) . '\'' : 'Find HMIS guides, software case studies, and digital strategy content for Kenyan institutions.'),'image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(5)),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset_srcset(5)),'tagline' => 'HMIS · Software · Branding · Public Engagement — practical content for Kenyan institutions.','badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['HMIS', 'Software', 'Branding', 'Campaigns']),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('search_hero'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.filter-chip-nav','data' => ['label' => 'Search content types','items' => $searchTypeChips,'resultSummary' => $searchSummary,'sticky' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.filter-chip-nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Search content types','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($searchTypeChips),'result-summary' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($searchSummary),'sticky' => true]); ?>
             <?php $__env->slot('prefix', null, []); ?> 
                <form action="<?php echo e(route('search')); ?>" method="GET" class="relative max-w-xl mb-4">
                    <label for="search-query" class="sr-only">Search</label>
                    <input id="search-query"
                           type="search"
                           name="q"
                           value="<?php echo e($query); ?>"
                           placeholder="Search HMIS, software, case studies…"
                           class="input-base w-full pl-9 pr-4 py-2.5 text-sm min-h-[48px]"
                           aria-label="Search query"
                           autocomplete="off">
                    <?php if($activeType !== ''): ?>
                        <input type="hidden" name="type" value="<?php echo e($activeType); ?>">
                    <?php endif; ?>
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

    <?php if(! $hasQuery): ?>
        
        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="search-start-heading">
            <div class="page-container">
                <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'Search','title' => 'What are you looking for?','intro' => 'Search published case studies, services, and insights — or jump straight to a practice area below.','headingId' => 'search-start-heading']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Search','title' => 'What are you looking for?','intro' => 'Search published case studies, services, and insights — or jump straight to a practice area below.','heading-id' => 'search-start-heading']); ?>
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

                <?php if(! empty($suggestedQueries)): ?>
                    <div class="mb-10">
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500 mb-3">Popular searches</p>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $suggestedQueries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $suggestion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('search', ['q' => $suggestion['q']])); ?>"
                                   class="inline-flex items-center px-4 py-2.5 text-sm font-medium border border-neutral-200 bg-white text-neutral-700 hover:border-primary-400 hover:text-primary-700 transition-colors">
                                    <?php echo e($suggestion['label']); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(! empty($pillarLinks)): ?>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500 mb-3">Browse by pillar</p>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <?php $__currentLoopData = $pillarLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pillar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('contact', ['inquiry_type' => $pillar['inquiry_type']])); ?>#contact-form"
                                   class="card-base p-4 border-t-4 <?php echo e($pillar['accent']); ?> group">
                                    <p class="text-xs font-bold text-neutral-900 group-hover:text-primary-600 transition-colors"><?php echo e($pillar['label']); ?></p>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mt-10 flex flex-wrap gap-4 text-sm">
                    <a href="<?php echo e(route('portfolio.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        See case studies
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="<?php echo e(route('services.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        Explore services
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="<?php echo e(route('insights.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        Browse insights
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </section>

    <?php elseif($results->isEmpty()): ?>
        
        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="search-no-results-heading">
            <div class="max-w-3xl mx-auto px-6 lg:px-8 text-center">
                <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'No matches','title' => 'No results for “' . $query . '”','intro' => 'We publish guides on HMIS procurement, hospital digitization, election digital strategy, and institutional software — try a different term or request a demo.','headingId' => 'search-no-results-heading','class' => 'text-center border-0 pb-0 mb-6 [&>div]:justify-center [&_h2]:mx-auto [&_p]:mx-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'No matches','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('No results for “' . $query . '”'),'intro' => 'We publish guides on HMIS procurement, hospital digitization, election digital strategy, and institutional software — try a different term or request a demo.','heading-id' => 'search-no-results-heading','class' => 'text-center border-0 pb-0 mb-6 [&>div]:justify-center [&_h2]:mx-auto [&_p]:mx-auto']); ?>
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

                <?php if(! empty($suggestedQueries)): ?>
                    <p class="text-sm text-neutral-600 mb-3">Try searching for:</p>
                    <div class="flex flex-wrap justify-center gap-2 mb-8">
                        <?php $__currentLoopData = $suggestedQueries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $suggestion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('search', ['q' => $suggestion['q']])); ?>"
                               class="inline-flex items-center px-4 py-2.5 text-sm font-semibold border border-neutral-200 bg-white text-primary-600 hover:border-primary-400 transition-colors">
                                <?php echo e($suggestion['label']); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <div class="flex flex-wrap justify-center gap-3 mb-8">
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['href' => generate_utm_url(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form', 'search_no_results'),'variant' => 'primary','class' => 'text-sm uppercase tracking-wider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(generate_utm_url(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form', 'search_no_results')),'variant' => 'primary','class' => 'text-sm uppercase tracking-wider']); ?>
                        Request HMIS Demo
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['href' => route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form','variant' => 'secondary','class' => 'text-sm uppercase tracking-wider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'),'variant' => 'secondary','class' => 'text-sm uppercase tracking-wider']); ?>
                        Get HMIS Checklist
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                </div>

                <p class="text-sm text-neutral-500">
                    Or explore our
                    <a href="<?php echo e(route('portfolio.index')); ?>" class="text-primary-600 hover:underline font-medium">case studies</a>
                    and
                    <a href="<?php echo e(route('services.index')); ?>" class="text-primary-600 hover:underline font-medium">services</a>.
                </p>
            </div>
        </section>

    <?php else: ?>
        
        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="search-results-heading">
            <div class="page-container">
                <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'Results','title' => $totalCount . ' ' . Str::plural('match', $totalCount) . ' for “' . $query . '”','intro' => $activeType !== '' ? 'Filtered to ' . strtolower($activeTypeLabel) . '.' : null,'headingId' => 'search-results-heading']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Results','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalCount . ' ' . Str::plural('match', $totalCount) . ' for “' . $query . '”'),'intro' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeType !== '' ? 'Filtered to ' . strtolower($activeTypeLabel) . '.' : null),'heading-id' => 'search-results-heading']); ?>
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

                <div class="content-card-grid">
                    <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal862d753875cd5d17661e132338b4e343 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal862d753875cd5d17661e132338b4e343 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.content-card','data' => ['item' => $result,'type' => search_content_card_type($result->type),'searchQuery' => $query,'style' => 'animation-delay: '.e($loop->index * 0.07).'s;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.content-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($result),'type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(search_content_card_type($result->type)),'search-query' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($query),'style' => 'animation-delay: '.e($loop->index * 0.07).'s;']); ?>
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

                <?php if($results instanceof \Illuminate\Pagination\LengthAwarePaginator && $results->hasPages()): ?>
                    <nav class="mt-10 flex justify-center" aria-label="Search results pagination">
                        <?php echo e($results->appends(array_filter(['q' => $query, 'type' => $activeType ?: null]))->links('pagination::tailwind')); ?>

                    </nav>
                <?php endif; ?>

                <div class="mt-10 pt-6 border-t border-neutral-100 flex flex-wrap gap-4 text-sm text-neutral-600">
                    <span>Explore more:</span>
                    <a href="<?php echo e(route('services.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        Our services
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="<?php echo e(route('portfolio.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        Case studies
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="<?php echo e(route('insights.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        Insights hub
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal03b5006f52c5c5b41290402a897a208b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b5006f52c5c5b41290402a897a208b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.page-closer','data' => ['title' => $hasQuery && ! $results->isEmpty() ? 'Found something close?' : 'Need help finding the right solution?','subtitle' => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.','primaryUrl' => hub_cta_url('search_footer_cta'),'primaryLabel' => 'Request HMIS Demo','secondaryUrl' => route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form','secondaryLabel' => 'Get HMIS Checklist','size' => 'default']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.page-closer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hasQuery && ! $results->isEmpty() ? 'Found something close?' : 'Need help finding the right solution?'),'subtitle' => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.','primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('search_footer_cta')),'primary-label' => 'Request HMIS Demo','secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'),'secondary-label' => 'Get HMIS Checklist','size' => 'default']); ?>
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

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/frontend/search.blade.php ENDPATH**/ ?>