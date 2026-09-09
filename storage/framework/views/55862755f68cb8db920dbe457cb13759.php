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
    <link rel="preload" href="<?php echo e(hero_asset(1)); ?>" as="image">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <?php
        $foundedYear = setting('founded_year', '2015');
    ?>

    
    <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'home','title' => 'Kenya\'s HMIS & Digital Transformation Partner','subtitle' => 'Hospital management systems, custom software, digital communications, and public engagement platforms for healthcare, government, NGOs, and campaigns.','tagline' => 'Trusted since '.e($foundedYear).' — focused on four core capabilities, not a generic agency that claims to do everything.','badges' => ['HMIS', 'Software', 'Branding', 'Campaigns'],'cta' => $homepageCta,'imageUrl' => hero_asset(1),'imageSrcset' => hero_asset_srcset(1),'ctaUrl' => hub_cta_url('home_hero'),'metrics' => [
            ['value' => '4',    'label' => 'Core Service Pillars'],
            ['value' => '24h',  'label' => 'Avg. Response'],
            ['value' => 'HMIS', 'label' => 'Primary Practice'],
            ['value' => $foundedYear, 'label' => 'Founded'],
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'home','title' => 'Kenya\'s HMIS & Digital Transformation Partner','subtitle' => 'Hospital management systems, custom software, digital communications, and public engagement platforms for healthcare, government, NGOs, and campaigns.','tagline' => 'Trusted since '.e($foundedYear).' — focused on four core capabilities, not a generic agency that claims to do everything.','badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['HMIS', 'Software', 'Branding', 'Campaigns']),'cta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homepageCta),'image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(1)),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset_srcset(1)),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('home_hero')),'metrics' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['value' => '4',    'label' => 'Core Service Pillars'],
            ['value' => '24h',  'label' => 'Avg. Response'],
            ['value' => 'HMIS', 'label' => 'Primary Practice'],
            ['value' => $foundedYear, 'label' => 'Founded'],
        ])]); ?>
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

    
    <section class="py-10 lg:py-14 bg-white border-t border-neutral-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'Work','title' => 'Featured Projects']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Work','title' => 'Featured Projects']); ?>
                 <?php $__env->slot('action', null, []); ?> 
                    <a href="<?php echo e(route('portfolio.index')); ?>" class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        All Projects
                        <?php if (isset($component)) { $__componentOriginal37a3f047daccd28b87517bd215a12923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37a3f047daccd28b87517bd215a12923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.arrow-right','data' => ['class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal37a3f047daccd28b87517bd215a12923)): ?>
<?php $attributes = $__attributesOriginal37a3f047daccd28b87517bd215a12923; ?>
<?php unset($__attributesOriginal37a3f047daccd28b87517bd215a12923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal37a3f047daccd28b87517bd215a12923)): ?>
<?php $component = $__componentOriginal37a3f047daccd28b87517bd215a12923; ?>
<?php unset($__componentOriginal37a3f047daccd28b87517bd215a12923); ?>
<?php endif; ?>
                    </a>
                 <?php $__env->endSlot(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.content-grid','data' => ['items' => $portfolioItems,'type' => 'portfolio','showTitle' => false,'embedded' => true,'showCtaBanner' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.content-grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portfolioItems),'type' => 'portfolio','show-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'embedded' => true,'show-cta-banner' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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

    
    <?php if (isset($component)) { $__componentOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.trust-strip','data' => ['id' => 'sectors-served','title' => 'Sectors We Serve','compact' => true,'sectors' => ['County Health', 'Hospitals & Clinics', 'NGO Programmes', 'Government ICT', 'SACCOs & SMEs', 'Political & Advocacy']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.trust-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'sectors-served','title' => 'Sectors We Serve','compact' => true,'sectors' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['County Health', 'Hospitals & Clinics', 'NGO Programmes', 'Government ICT', 'SACCOs & SMEs', 'Political & Advocacy'])]); ?>
         <?php $__env->slot('description', null, []); ?> 
            <p class="text-sm text-neutral-600 mb-6 max-w-2xl mx-auto leading-relaxed">
                <?php echo e($intro->excerpt ?? setting('site_description')); ?>

            </p>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90)): ?>
<?php $attributes = $__attributesOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90; ?>
<?php unset($__attributesOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90)): ?>
<?php $component = $__componentOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90; ?>
<?php unset($__componentOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal47a6b974428a50244b0b412cf1f5050a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal47a6b974428a50244b0b412cf1f5050a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.process-timeline','data' => ['id' => 'our-process','compact' => true,'sectionLabel' => 'Process','title' => 'Our Simple 4-Step Process','headingId' => 'process-heading','ctaUrl' => hub_cta_url('home_process_cta'),'ctaLabel' => 'Request HMIS Demo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.process-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'our-process','compact' => true,'section-label' => 'Process','title' => 'Our Simple 4-Step Process','heading-id' => 'process-heading','cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('home_process_cta')),'cta-label' => 'Request HMIS Demo']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal47a6b974428a50244b0b412cf1f5050a)): ?>
<?php $attributes = $__attributesOriginal47a6b974428a50244b0b412cf1f5050a; ?>
<?php unset($__attributesOriginal47a6b974428a50244b0b412cf1f5050a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal47a6b974428a50244b0b412cf1f5050a)): ?>
<?php $component = $__componentOriginal47a6b974428a50244b0b412cf1f5050a; ?>
<?php unset($__componentOriginal47a6b974428a50244b0b412cf1f5050a); ?>
<?php endif; ?>

    
    <section class="py-10 lg:py-14 bg-neutral-50 border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'Services','title' => 'Core Services']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Services','title' => 'Core Services']); ?>
                 <?php $__env->slot('action', null, []); ?> 
                    <a href="<?php echo e(route('services.index')); ?>" class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        All Services
                        <?php if (isset($component)) { $__componentOriginal37a3f047daccd28b87517bd215a12923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37a3f047daccd28b87517bd215a12923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.arrow-right','data' => ['class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal37a3f047daccd28b87517bd215a12923)): ?>
<?php $attributes = $__attributesOriginal37a3f047daccd28b87517bd215a12923; ?>
<?php unset($__attributesOriginal37a3f047daccd28b87517bd215a12923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal37a3f047daccd28b87517bd215a12923)): ?>
<?php $component = $__componentOriginal37a3f047daccd28b87517bd215a12923; ?>
<?php unset($__componentOriginal37a3f047daccd28b87517bd215a12923); ?>
<?php endif; ?>
                    </a>
                 <?php $__env->endSlot(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.content-grid','data' => ['items' => $services,'type' => 'service','showTitle' => false,'embedded' => true,'showCtaBanner' => false,'highlightSlug' => 'hmis-digital-health-solutions-kenya']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.content-grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($services),'type' => 'service','show-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'embedded' => true,'show-cta-banner' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'highlight-slug' => 'hmis-digital-health-solutions-kenya']); ?>
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

    
    <?php if(!empty($latestInsights) && $latestInsights->count() > 0): ?>
        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="latest-insights-heading">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'Insights','title' => 'Latest Guides & Case Studies','headingId' => 'latest-insights-heading']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Insights','title' => 'Latest Guides & Case Studies','heading-id' => 'latest-insights-heading']); ?>
                     <?php $__env->slot('action', null, []); ?> 
                        <a href="<?php echo e(route('insights.index')); ?>" class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                            All Insights
                            <?php if (isset($component)) { $__componentOriginal37a3f047daccd28b87517bd215a12923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37a3f047daccd28b87517bd215a12923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.arrow-right','data' => ['class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal37a3f047daccd28b87517bd215a12923)): ?>
<?php $attributes = $__attributesOriginal37a3f047daccd28b87517bd215a12923; ?>
<?php unset($__attributesOriginal37a3f047daccd28b87517bd215a12923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal37a3f047daccd28b87517bd215a12923)): ?>
<?php $component = $__componentOriginal37a3f047daccd28b87517bd215a12923; ?>
<?php unset($__componentOriginal37a3f047daccd28b87517bd215a12923); ?>
<?php endif; ?>
                        </a>
                     <?php $__env->endSlot(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.content-grid','data' => ['items' => $latestInsights,'type' => 'blog','showTitle' => false,'embedded' => true,'showCtaBanner' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.content-grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($latestInsights),'type' => 'blog','show-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'embedded' => true,'show-cta-banner' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
    <?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal03b5006f52c5c5b41290402a897a208b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b5006f52c5c5b41290402a897a208b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.page-closer','data' => ['title' => 'Ready to Digitize Your Hospital or Institution?','subtitle' => 'Request an HMIS demo or speak with our team about software, branding, or campaign platforms.','primaryUrl' => hub_cta_url('home_footer_primary', 'hmis-demo'),'primaryLabel' => 'Request HMIS Demo','secondaryUrl' => hub_cta_url('home_footer_secondary', 'hmis-checklist'),'secondaryLabel' => 'Get HMIS Checklist']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.page-closer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ready to Digitize Your Hospital or Institution?','subtitle' => 'Request an HMIS demo or speak with our team about software, branding, or campaign platforms.','primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('home_footer_primary', 'hmis-demo')),'primary-label' => 'Request HMIS Demo','secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('home_footer_secondary', 'hmis-checklist')),'secondary-label' => 'Get HMIS Checklist']); ?>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/frontend/index.blade.php ENDPATH**/ ?>