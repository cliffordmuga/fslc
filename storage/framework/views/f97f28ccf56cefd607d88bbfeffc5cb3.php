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
        $headline = $leadUx['contact_headline'] ?? 'Tell Us Your Vision';
        $waMessage = $leadUx['whatsapp_message'] ?? 'Hi, I\'d like to get in touch about a project.';
        $isChecklist = $inquiryType === 'hmis-checklist';
        $showPillarChooser = $inquiryType === 'general';
    ?>

    <?php if (isset($component)) { $__componentOriginald85159ac95308cdeb145f8a1717e7ea9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald85159ac95308cdeb145f8a1717e7ea9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hub-shell','data' => ['breadcrumb' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Contact']],'heroPreload' => hero_asset(7)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hub-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Home', 'url' => route('home')], ['label' => 'Contact']]),'hero-preload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(7))]); ?>
         <?php $__env->slot('hero', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'standard','title' => 'Contact Forefront Solutions','imageUrl' => hero_asset(7),'imageSrcset' => hero_asset_srcset(7),'subtitle' => 'Request an HMIS demo, software quote, brand consultation, or campaign strategy session — we respond within 24 hours.','tagline' => 'HMIS · Software · Branding · Public Engagement — one focused partner for Kenyan institutions.','badges' => ['HMIS', 'Software', 'Branding', 'Campaigns'],'ctaUrl' => hub_cta_url('contact_hero')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'standard','title' => 'Contact Forefront Solutions','image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(7)),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset_srcset(7)),'subtitle' => 'Request an HMIS demo, software quote, brand consultation, or campaign strategy session — we respond within 24 hours.','tagline' => 'HMIS · Software · Branding · Public Engagement — one focused partner for Kenyan institutions.','badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['HMIS', 'Software', 'Branding', 'Campaigns']),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('contact_hero'))]); ?>
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
            ['value' => ($stats['response_time'] ?? '24') . 'h', 'label' => 'Average response time'],
            ['value' => '4', 'label' => 'Core service pillars'],
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
            ['value' => ($stats['response_time'] ?? '24') . 'h', 'label' => 'Average response time'],
            ['value' => '4', 'label' => 'Core service pillars'],
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

    <?php if($isChecklist): ?>
        <?php if (isset($component)) { $__componentOriginal2fae09bc3adb515c52827818ad659cf5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2fae09bc3adb515c52827818ad659cf5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.lead-magnet-band','data' => ['type' => 'hmis-checklist']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.lead-magnet-band'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'hmis-checklist']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2fae09bc3adb515c52827818ad659cf5)): ?>
<?php $attributes = $__attributesOriginal2fae09bc3adb515c52827818ad659cf5; ?>
<?php unset($__attributesOriginal2fae09bc3adb515c52827818ad659cf5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2fae09bc3adb515c52827818ad659cf5)): ?>
<?php $component = $__componentOriginal2fae09bc3adb515c52827818ad659cf5; ?>
<?php unset($__componentOriginal2fae09bc3adb515c52827818ad659cf5); ?>
<?php endif; ?>
    <?php endif; ?>

    
    <section id="contact-form" class="py-10 lg:py-14 bg-neutral-50 border-t border-neutral-200 scroll-mt-24" aria-labelledby="contact-form-heading">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <?php if($showPillarChooser && ! empty($pillarLinks)): ?>
                <div class="mb-8">
                    <p class="section-label mb-2">Which pillar fits your project?</p>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <?php $__currentLoopData = $pillarLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pillar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('contact', ['inquiry_type' => $pillar['inquiry_type']])); ?>#contact-form"
                               class="card-base p-4 border-t-4 <?php echo e($pillar['accent']); ?> group <?php echo e($inquiryType === $pillar['inquiry_type'] ? 'ring-2 ring-primary-500' : ''); ?>">
                                <p class="text-xs font-bold text-neutral-900 group-hover:text-primary-600 transition-colors"><?php echo e($pillar['label']); ?></p>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if(! empty($companyInfo['phone'])): ?>
                <div class="lg:hidden mb-6 grid grid-cols-2 gap-3">
                    <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $companyInfo['phone'])); ?>"
                       class="flex items-center justify-center gap-2 border border-neutral-200 bg-white py-3 text-sm font-semibold text-neutral-800 hover:border-primary-400 transition-colors">
                        Call us
                    </a>
                    <?php $waNum = preg_replace('/[^0-9]/', '', $companyInfo['phone']); ?>
                    <?php if($waNum): ?>
                        <a href="https://wa.me/<?php echo e($waNum); ?>?text=<?php echo e(rawurlencode($waMessage)); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="flex items-center justify-center gap-2 bg-[#25D366] text-white py-3 text-sm font-semibold hover:bg-[#1ebe5d] transition-colors">
                            WhatsApp
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="grid lg:grid-cols-5 gap-8 lg:gap-12">

                
                <div class="lg:col-span-3">
                    
                    <div class="flex flex-wrap gap-2 mb-5">
                        <?php $__currentLoopData = ['Reply within 24h', 'No spam, ever', 'Free consultation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'outline-light','size' => 'sm','class' => 'gap-1.5 tracking-wider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'outline-light','size' => 'sm','class' => 'gap-1.5 tracking-wider']); ?>
                                <svg class="w-3 h-3 text-primary-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($badge); ?>

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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php if($formSuccess ?? false): ?>
                        <?php
                            $successTitle = contact_form_ux($inquiryType)['success_message']
                                ?? session('success')
                                ?? "Thank you! We'll respond within 24 hours.";
                        ?>
                        <?php if (isset($component)) { $__componentOriginal746de018ded8594083eb43be3f1332e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal746de018ded8594083eb43be3f1332e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert','data' => ['type' => 'success','class' => 'mb-6 p-4','title' => $successTitle]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','class' => 'mb-6 p-4','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($successTitle)]); ?>
                            <?php if($isChecklist): ?>
                                Check your inbox — we send the checklist from <?php echo e($companyInfo['email'] ?? setting('email')); ?>.
                            <?php elseif($inquiryType === 'hmis-demo'): ?>
                                Our team will contact you to schedule a facility walkthrough.
                            <?php else: ?>
                                We typically respond within <?php echo e($stats['response_time'] ?? '24'); ?> hours on business days.
                            <?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $attributes = $__attributesOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__attributesOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $component = $__componentOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__componentOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <p class="section-label mb-2">Get in touch</p>
                    <h2 id="contact-form-heading" class="scroll-mt-24 text-2xl lg:text-3xl font-black text-neutral-900 mb-2 tracking-tight">
                        <?php echo e($headline); ?>

                    </h2>
                    <p class="text-sm text-neutral-600 mb-6 max-w-2xl leading-relaxed">
                        <?php echo e($formUx['description'] ?? ''); ?>

                    </p>

                    <?php if($contactPage?->excerpt ?? null): ?>
                        <p class="text-sm text-neutral-500 mb-6 border-l-2 border-neutral-300 pl-3 max-w-2xl">
                            <?php echo e($contactPage->excerpt); ?>

                        </p>
                    <?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal2a5af4cf6e97d87a0fb352c7311eb1fa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a5af4cf6e97d87a0fb352c7311eb1fa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.contact-form','data' => ['route' => route('contact.store'),'leadSource' => $contactPage,'inquiryType' => $inquiryType,'services' => $services,'preselectedServiceId' => $preselectedServiceId,'singleStep' => (bool) ($formUx['single_step'] ?? false),'requireService' => (bool) ($formUx['require_service'] ?? true),'showUpload' => (bool) ($formUx['show_upload'] ?? true),'hideServiceWhenPreselected' => ! ($formUx['require_service'] ?? true) && ! empty($preselectedServiceId),'hideHeader' => true,'messageLabel' => $formUx['message_label'] ?? 'Project Details','messagePlaceholder' => $formUx['message_placeholder'] ?? 'Tell us about your project...','submitLabel' => $formUx['submit_label'] ?? 'Send Message','class' => 'max-w-none border border-neutral-200 border-t-4 border-t-primary-500 bg-white p-6 lg:p-8','sourceContentId' => ''.e($contactPage->id ?? null).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.contact-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact.store')),'leadSource' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($contactPage),'inquiry-type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inquiryType),'services' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($services),'preselected-service-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($preselectedServiceId),'single-step' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((bool) ($formUx['single_step'] ?? false)),'require-service' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((bool) ($formUx['require_service'] ?? true)),'show-upload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((bool) ($formUx['show_upload'] ?? true)),'hide-service-when-preselected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! ($formUx['require_service'] ?? true) && ! empty($preselectedServiceId)),'hide-header' => true,'message-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formUx['message_label'] ?? 'Project Details'),'message-placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formUx['message_placeholder'] ?? 'Tell us about your project...'),'submit-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formUx['submit_label'] ?? 'Send Message'),'class' => 'max-w-none border border-neutral-200 border-t-4 border-t-primary-500 bg-white p-6 lg:p-8','source-content-id' => ''.e($contactPage->id ?? null).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2a5af4cf6e97d87a0fb352c7311eb1fa)): ?>
<?php $attributes = $__attributesOriginal2a5af4cf6e97d87a0fb352c7311eb1fa; ?>
<?php unset($__attributesOriginal2a5af4cf6e97d87a0fb352c7311eb1fa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2a5af4cf6e97d87a0fb352c7311eb1fa)): ?>
<?php $component = $__componentOriginal2a5af4cf6e97d87a0fb352c7311eb1fa; ?>
<?php unset($__componentOriginal2a5af4cf6e97d87a0fb352c7311eb1fa); ?>
<?php endif; ?>
                </div>

                
                <aside class="lg:col-span-2 space-y-6 lg:sticky lg:top-20 self-start">
                    <?php if (isset($component)) { $__componentOriginal1a4dbe493d57a6deef288c5c5c1c75f0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a4dbe493d57a6deef288c5c5c1c75f0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.contact-info-card','data' => ['address' => $companyInfo['address'] ?? setting('address', 'Nairobi, Kenya'),'email' => $companyInfo['email'] ?? setting('email'),'phone' => $companyInfo['phone'] ?? setting('phone'),'whatsappMessage' => $waMessage]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.contact-info-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['address' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($companyInfo['address'] ?? setting('address', 'Nairobi, Kenya')),'email' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($companyInfo['email'] ?? setting('email')),'phone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($companyInfo['phone'] ?? setting('phone')),'whatsapp-message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($waMessage)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1a4dbe493d57a6deef288c5c5c1c75f0)): ?>
<?php $attributes = $__attributesOriginal1a4dbe493d57a6deef288c5c5c1c75f0; ?>
<?php unset($__attributesOriginal1a4dbe493d57a6deef288c5c5c1c75f0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1a4dbe493d57a6deef288c5c5c1c75f0)): ?>
<?php $component = $__componentOriginal1a4dbe493d57a6deef288c5c5c1c75f0; ?>
<?php unset($__componentOriginal1a4dbe493d57a6deef288c5c5c1c75f0); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginalc11a15779fcbdadbd9313b66a637a9d3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc11a15779fcbdadbd9313b66a637a9d3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.stats-strip','data' => ['compact' => true,'columns' => 1,'standalone' => false,'class' => 'border border-neutral-200 bg-white p-4 text-left !grid-cols-1','items' => [
                            ['value' => ($stats['response_time'] ?? '24') . 'h', 'label' => 'Average response'],
                            ['value' => ($stats['satisfaction'] ?? '98') . '%', 'label' => 'Client satisfaction'],
                        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.stats-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['compact' => true,'columns' => 1,'standalone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'border border-neutral-200 bg-white p-4 text-left !grid-cols-1','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            ['value' => ($stats['response_time'] ?? '24') . 'h', 'label' => 'Average response'],
                            ['value' => ($stats['satisfaction'] ?? '98') . '%', 'label' => 'Client satisfaction'],
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

                    <?php if(! empty($companyInfo['maps_embed_url'] ?? null)): ?>
                        <div class="border border-neutral-200 overflow-hidden">
                            <iframe src="<?php echo e($companyInfo['maps_embed_url']); ?>"
                                    class="w-full h-56 border-0"
                                    loading="lazy" title="Our Location"
                                    allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </section>

    <?php if (isset($component)) { $__componentOriginal03b5006f52c5c5b41290402a897a208b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b5006f52c5c5b41290402a897a208b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.page-closer','data' => ['showMagnet' => ! $isChecklist,'title' => 'Prefer to Talk First?','subtitle' => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.','primaryUrl' => hub_cta_url('contact_footer_cta'),'primaryLabel' => 'Request HMIS Demo','secondaryUrl' => route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form','secondaryLabel' => 'Get HMIS Checklist','size' => 'default']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.page-closer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show-magnet' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $isChecklist),'title' => 'Prefer to Talk First?','subtitle' => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.','primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('contact_footer_cta')),'primary-label' => 'Request HMIS Demo','secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'),'secondary-label' => 'Get HMIS Checklist','size' => 'default']); ?>
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

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/frontend/contact.blade.php ENDPATH**/ ?>