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
        $pillarGuide = [
            ['audience' => 'Hospitals & counties', 'pillar' => 'HMIS & Digital Health', 'slug' => 'hmis-digital-health-solutions-kenya', 'accent' => 'pillar-border-hmis'],
            ['audience' => 'Government & SACCOs', 'pillar' => 'Software & Web', 'slug' => 'custom-software-web-development-kenya', 'accent' => 'pillar-border-software'],
            ['audience' => 'NGOs & institutions', 'pillar' => 'Branding & Marketing', 'slug' => 'digital-strategy-branding-marketing-kenya', 'accent' => 'pillar-border-branding'],
            ['audience' => 'Campaigns & advocacy', 'pillar' => 'Public Engagement', 'slug' => 'political-public-engagement-kenya', 'accent' => 'pillar-border-campaigns'],
        ];
    ?>

    <?php if (isset($component)) { $__componentOriginald85159ac95308cdeb145f8a1717e7ea9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald85159ac95308cdeb145f8a1717e7ea9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hub-shell','data' => ['breadcrumb' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Services']],'heroPreload' => hero_asset(4)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hub-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Home', 'url' => route('home')], ['label' => 'Services']]),'hero-preload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(4))]); ?>
         <?php $__env->slot('hero', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'standard','title' => 'Core Services','imageUrl' => hero_asset(4),'imageSrcset' => hero_asset_srcset(4),'subtitle' => 'HMIS & digital health, custom software, branding & marketing, and political & public engagement — built for Kenyan hospitals, government, NGOs, and campaigns.','tagline' => 'Focused capabilities for procurement teams and decision-makers who need depth, not a generic agency.','badges' => ['HMIS', 'Software', 'Branding', 'Campaigns'],'cta' => $servicesCta,'ctaUrl' => hub_cta_url('services_hero')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'standard','title' => 'Core Services','image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(4)),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset_srcset(4)),'subtitle' => 'HMIS & digital health, custom software, branding & marketing, and political & public engagement — built for Kenyan hospitals, government, NGOs, and campaigns.','tagline' => 'Focused capabilities for procurement teams and decision-makers who need depth, not a generic agency.','badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['HMIS', 'Software', 'Branding', 'Campaigns']),'cta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($servicesCta),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('services_hero'))]); ?>
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
                    ['value' => '4', 'label' => 'Focused service pillars'],
                    ['value' => 'HMIS', 'label' => 'Primary practice'],
                    ['value' => '24h', 'label' => 'Average response time'],
                    ['value' => $foundedYear, 'label' => 'Serving Kenya since'],
                ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.stats-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['compact' => true,'columns' => 4,'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    ['value' => '4', 'label' => 'Focused service pillars'],
                    ['value' => 'HMIS', 'label' => 'Primary practice'],
                    ['value' => '24h', 'label' => 'Average response time'],
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

    <?php if (isset($component)) { $__componentOriginal47a6b974428a50244b0b412cf1f5050a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal47a6b974428a50244b0b412cf1f5050a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.process-timeline','data' => ['compact' => true,'sectionLabel' => 'Process','title' => 'How we deliver','headingId' => 'services-process-heading','class' => 'bg-neutral-50 border-t border-neutral-200','ctaUrl' => route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form','ctaLabel' => 'Request HMIS Demo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.process-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['compact' => true,'section-label' => 'Process','title' => 'How we deliver','heading-id' => 'services-process-heading','class' => 'bg-neutral-50 border-t border-neutral-200','cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form'),'cta-label' => 'Request HMIS Demo']); ?>
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

    
    <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="services-heading">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'What We Offer','headingId' => 'services-heading','title' => 'Focused services for Kenyan institutions','intro' => $positioning ?? setting('site_description')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'What We Offer','heading-id' => 'services-heading','title' => 'Focused services for Kenyan institutions','intro' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($positioning ?? setting('site_description'))]); ?>
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

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-8">
                <?php $__currentLoopData = $pillarGuide; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('services.show', $guide['slug'])); ?>"
                       class="card-base p-4 border-t-4 <?php echo e($guide['accent']); ?> group">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-neutral-500 mb-1"><?php echo e($guide['audience']); ?></p>
                        <p class="text-xs font-bold text-neutral-900 group-hover:text-primary-600 transition-colors"><?php echo e($guide['pillar']); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if (isset($component)) { $__componentOriginalccd1207879c8f5f2949401f3c8efcd0b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalccd1207879c8f5f2949401f3c8efcd0b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.content-grid','data' => ['items' => $services,'type' => 'service','showTitle' => false,'embedded' => true,'showCtaBanner' => false,'perPage' => 4,'columns' => 4,'highlightSlug' => 'hmis-digital-health-solutions-kenya']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.content-grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($services),'type' => 'service','show-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'embedded' => true,'show-cta-banner' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'per-page' => 4,'columns' => 4,'highlight-slug' => 'hmis-digital-health-solutions-kenya']); ?>
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

            <p class="mt-8 text-sm text-neutral-600 border-t border-neutral-200 pt-6">
                Want proof before you enquire?
                <a href="<?php echo e(route('portfolio.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                    See HMIS & software case studies
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </p>
        </div>
    </section>

    <?php if (isset($component)) { $__componentOriginal03b5006f52c5c5b41290402a897a208b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b5006f52c5c5b41290402a897a208b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.page-closer','data' => ['title' => 'Which Pillar Fits Your Project?','subtitle' => 'HMIS demo, software quote, brand consultation, or campaign strategy — request a demo or download the procurement checklist.','primaryUrl' => hub_cta_url('services_footer_cta'),'primaryLabel' => 'Request HMIS Demo','secondaryUrl' => route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form','secondaryLabel' => 'Get HMIS Checklist']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.page-closer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Which Pillar Fits Your Project?','subtitle' => 'HMIS demo, software quote, brand consultation, or campaign strategy — request a demo or download the procurement checklist.','primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('services_footer_cta')),'primary-label' => 'Request HMIS Demo','secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'),'secondary-label' => 'Get HMIS Checklist']); ?>
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

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/frontend/services.blade.php ENDPATH**/ ?>