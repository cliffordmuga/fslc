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
        $companyName = setting('company_name', config('app.name'));
        $pillarLinks = [
            ['slug' => 'hmis-digital-health-solutions-kenya', 'label' => 'HMIS & Digital Health', 'pillar' => 'hmis'],
            ['slug' => 'custom-software-web-development-kenya', 'label' => 'Software & Web', 'pillar' => 'software'],
            ['slug' => 'digital-strategy-branding-marketing-kenya', 'label' => 'Branding & Marketing', 'pillar' => 'branding'],
            ['slug' => 'political-public-engagement-kenya', 'label' => 'Public Engagement', 'pillar' => 'campaigns'],
        ];
        $defaultValues = [
            ['title' => 'Excellence', 'desc' => 'Rigorous HMIS delivery, clinical workflows, and MOH-aligned reporting.', 'border' => 'pillar-border-hmis', 'icon' => 'pillar-text-hmis', 'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
            ['title' => 'Partnership', 'desc' => 'Transparent timelines and co-design with your IT, clinical, and procurement teams.', 'border' => 'pillar-border-software', 'icon' => 'pillar-text-software', 'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
            ['title' => 'Innovation', 'desc' => 'Software and portals engineered for Kenyan infrastructure, connectivity, and regulations.', 'border' => 'pillar-border-branding', 'icon' => 'pillar-text-branding', 'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>'],
            ['title' => 'Integrity', 'desc' => 'Honest pricing, Kenya Data Protection Act compliance, and SHA-ready implementations.', 'border' => 'pillar-border-campaigns', 'icon' => 'pillar-text-campaigns', 'path' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'],
        ];
        $faqHalf = ! empty($faqItems) ? (int) ceil(count($faqItems) / 2) : 0;
        $faqLeft = ! empty($faqItems) ? array_slice($faqItems, 0, $faqHalf) : [];
        $faqRight = ! empty($faqItems) ? array_slice($faqItems, $faqHalf) : [];
    ?>

    <?php if (isset($component)) { $__componentOriginald85159ac95308cdeb145f8a1717e7ea9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald85159ac95308cdeb145f8a1717e7ea9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hub-shell','data' => ['breadcrumb' => [['label' => 'Home', 'url' => route('home')], ['label' => 'About']],'heroPreload' => hero_asset(2)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hub-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Home', 'url' => route('home')], ['label' => 'About']]),'hero-preload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(2))]); ?>
         <?php $__env->slot('hero', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'standard','imageUrl' => hero_asset(2),'imageSrcset' => hero_asset_srcset(2),'title' => 'Healthcare Technology Partner in Kenya','subtitle' => 'Since '.e($foundedYear).', we have specialized in HMIS & digital health, custom software, branding & marketing, and political & public engagement for Kenyan institutions.','tagline' => $companyName . ' · since ' . $foundedYear,'badges' => ['HMIS', 'Software', 'Branding', 'Campaigns'],'cta' => $aboutCta ?? null,'ctaUrl' => hub_cta_url('about_hero')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'standard','image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(2)),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset_srcset(2)),'title' => 'Healthcare Technology Partner in Kenya','subtitle' => 'Since '.e($foundedYear).', we have specialized in HMIS & digital health, custom software, branding & marketing, and political & public engagement for Kenyan institutions.','tagline' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($companyName . ' · since ' . $foundedYear),'badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['HMIS', 'Software', 'Branding', 'Campaigns']),'cta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($aboutCta ?? null),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('about_hero'))]); ?>
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
                    ['value' => '4', 'label' => 'Core Service Pillars'],
                    ['value' => 'HMIS', 'label' => 'Primary Practice'],
                    ['value' => $foundedYear, 'label' => 'Founded in Nairobi'],
                ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.stats-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['compact' => true,'columns' => 3,'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    ['value' => '4', 'label' => 'Core Service Pillars'],
                    ['value' => 'HMIS', 'label' => 'Primary Practice'],
                    ['value' => $foundedYear, 'label' => 'Founded in Nairobi'],
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

    
    <section id="about-story" class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="story-heading">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <?php if($intro?->excerpt ?? $intro?->content ?? null): ?>
                <div class="card-base border-l-4 border-primary-500 p-4 lg:p-5 mb-8 bg-primary-50/40 max-w-3xl">
                    <p class="text-sm lg:text-base text-neutral-700 leading-relaxed font-medium">
                        <?php echo e($intro->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($intro->content ?? ''), 220)); ?>

                    </p>
                </div>
            <?php endif; ?>

            <div class="grid lg:grid-cols-3 gap-8 lg:gap-12">
                <div class="lg:col-span-2">
                    <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'Our Story','title' => 'Who we are','headingId' => 'story-heading','bordered' => false,'class' => 'mb-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Our Story','title' => 'Who we are','heading-id' => 'story-heading','bordered' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'mb-5']); ?>
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
                    <div class="prose-custom prose-sm max-w-none">
                        <?php echo sanitize_rich_html($about?->content ?? '<p>Founded in ' . $foundedYear . ' in Nairobi, Forefront Solutions specializes in four focused disciplines: HMIS &amp; digital health, custom software &amp; web development, digital strategy &amp; branding, and political &amp; public engagement.</p><p>We partner with county hospitals, NGOs, government agencies, SACCOs, and campaign teams across Kenya and East Africa — delivering depth in each pillar rather than generic agency breadth.</p>'); ?>

                    </div>
                </div>
                <aside class="space-y-4">
                    <?php $__currentLoopData = [['label' => 'Mission', 'content' => $mission], ['label' => 'Vision', 'content' => $vision]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card-base p-4 lg:p-5 border-l-4 border-primary-500">
                            <p class="text-xs font-semibold uppercase tracking-widest text-primary-600 mb-2"><?php echo e($block['label']); ?></p>
                            <div class="prose-custom prose-sm text-neutral-600 leading-relaxed">
                                <?php echo sanitize_rich_html($block['content']?->content ?? ($block['label'] === 'Mission'
                                    ? '<p>Deliver dependable HMIS, software, and digital platforms that help Kenyan institutions serve people better — from discovery through training and support.</p>'
                                    : '<p>Kenya\'s most trusted specialist in healthcare information systems and the digital infrastructure hospitals, counties, and institutions depend on.</p>')); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </aside>
            </div>

            
            <div class="mt-10 lg:mt-12 pt-8 border-t border-neutral-200">
                <p class="section-label mb-2">Capabilities</p>
                <h3 class="text-lg lg:text-xl font-black text-neutral-900 mb-4 tracking-tight">Four core service pillars</h3>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <?php $__currentLoopData = $pillarLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pillar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('services.show', $pillar['slug'])); ?>"
                           class="card-base p-4 group border-t-4 pillar-border-<?php echo e($pillar['pillar']); ?>">
                            <span class="text-xs font-bold uppercase tracking-wider text-neutral-900 group-hover:text-primary-600 transition-colors">
                                <?php echo e($pillar['label']); ?>

                            </span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </section>

    
    <section class="py-10 lg:py-14 bg-neutral-50 border-t border-neutral-200" aria-labelledby="values-heading">
        <div class="page-container">
            <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'Values','title' => 'What we stand for','headingId' => 'values-heading']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Values','title' => 'What we stand for','heading-id' => 'values-heading']); ?>
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
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-200">
                <?php $__currentLoopData = $values ?? $defaultValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white p-5 lg:p-6 border-t-4 <?php echo e($v['border'] ?? 'border-primary-500'); ?> hover-lift">
                        <svg class="w-5 h-5 <?php echo e($v['icon'] ?? 'text-primary-500'); ?> mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <?php echo $v['path'] ?? $v['icon'] ?? ''; ?>

                        </svg>
                        <h3 class="text-sm font-bold text-neutral-900 mb-1.5"><?php echo e($v['title'] ?? $v['name'] ?? ''); ?></h3>
                        <p class="text-xs text-neutral-500 leading-relaxed"><?php echo e($v['description'] ?? $v['desc'] ?? ''); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    
    <?php if(!empty($timelineItems) && count($timelineItems) > 0): ?>
        <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="timeline-heading">
            <div class="page-container">
                <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'History','title' => 'Our journey','headingId' => 'timeline-heading']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'History','title' => 'Our journey','heading-id' => 'timeline-heading']); ?>
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
                <div class="relative">
                    <div class="hidden lg:block absolute top-5 left-0 right-0 h-px bg-neutral-200" aria-hidden="true"></div>
                    <div class="flex lg:grid lg:grid-cols-5 gap-px bg-neutral-200 overflow-x-auto snap-x snap-mandatory pb-2 lg:pb-0 -mx-6 px-6 lg:mx-0 lg:px-0">
                        <?php $__currentLoopData = $timelineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white p-5 lg:p-6 hover-lift relative min-w-[72vw] sm:min-w-[45vw] lg:min-w-0 snap-start shrink-0 lg:shrink">
                                <div class="hidden lg:block absolute -top-2.5 left-6 w-5 h-5 bg-primary-600 border-2 border-white" aria-hidden="true"></div>
                                <span class="text-xl lg:text-2xl font-black font-mono text-primary-600 block mb-2"><?php echo e($item['year'] ?? ''); ?></span>
                                <h3 class="text-sm font-bold text-neutral-900 mb-1"><?php echo e($item['title'] ?? ''); ?></h3>
                                <p class="text-xs text-neutral-500 leading-relaxed"><?php echo e($item['description'] ?? ''); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.trust-strip','data' => ['id' => 'sectors-trust','title' => 'Sectors we serve','compact' => true,'showEyebrow' => false,'sectors' => ['County Health', 'Hospitals & Clinics', 'NGO Programmes', 'Government ICT', 'SACCOs & SMEs', 'Political & Advocacy'],'class' => 'border-t border-neutral-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.trust-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'sectors-trust','title' => 'Sectors we serve','compact' => true,'show-eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'sectors' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['County Health', 'Hospitals & Clinics', 'NGO Programmes', 'Government ICT', 'SACCOs & SMEs', 'Political & Advocacy']),'class' => 'border-t border-neutral-200']); ?>
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

    <?php if (isset($component)) { $__componentOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a9d70f3c9eb7b3d478ad3d9622f2d90 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.trust-strip','data' => ['id' => 'certifications','title' => 'Standards & readiness','compact' => true,'showEyebrow' => false,'certifications' => ['MOH-Aligned HMIS', 'SHA Integration Ready', 'Kenya Data Protection Act', 'Laravel & Secure Hosting'],'class' => 'border-t border-neutral-200 bg-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.trust-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'certifications','title' => 'Standards & readiness','compact' => true,'show-eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'certifications' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['MOH-Aligned HMIS', 'SHA Integration Ready', 'Kenya Data Protection Act', 'Laravel & Secure Hosting']),'class' => 'border-t border-neutral-200 bg-white']); ?>
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

    
    <?php if (isset($component)) { $__componentOriginal3024b0a62f1e4de37b9d4f4d64bc33a4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3024b0a62f1e4de37b9d4f4d64bc33a4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.featured-testimonial','data' => ['testimonial' => $featuredTestimonial ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.featured-testimonial'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['testimonial' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($featuredTestimonial ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3024b0a62f1e4de37b9d4f4d64bc33a4)): ?>
<?php $attributes = $__attributesOriginal3024b0a62f1e4de37b9d4f4d64bc33a4; ?>
<?php unset($__attributesOriginal3024b0a62f1e4de37b9d4f4d64bc33a4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3024b0a62f1e4de37b9d4f4d64bc33a4)): ?>
<?php $component = $__componentOriginal3024b0a62f1e4de37b9d4f4d64bc33a4; ?>
<?php unset($__componentOriginal3024b0a62f1e4de37b9d4f4d64bc33a4); ?>
<?php endif; ?>

    
    <?php if(!empty($faqItems) && count($faqItems) > 0): ?>
        <section class="py-10 lg:py-14 bg-neutral-50 border-t border-neutral-200" aria-labelledby="faq-heading">
            <div class="page-container">
                <?php if (isset($component)) { $__componentOriginalb318ad2cd06daf89bda9bdf824e06c7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb318ad2cd06daf89bda9bdf824e06c7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.section-heading','data' => ['label' => 'FAQ','title' => 'Frequently asked questions','headingId' => 'faq-heading']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'FAQ','title' => 'Frequently asked questions','heading-id' => 'faq-heading']); ?>
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
                <div class="grid lg:grid-cols-2 gap-6 lg:gap-8" x-data="{ open: null }">
                    <?php $__currentLoopData = [$faqLeft, $faqRight]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $columnIndex => $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="divide-y divide-neutral-200 border border-neutral-200 bg-white">
                            <?php $__currentLoopData = $column; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $index = ($columnIndex * $faqHalf) + $i; ?>
                                <div>
                                    <button
                                        @click="open = (open === <?php echo e($index); ?>) ? null : <?php echo e($index); ?>"
                                        :aria-expanded="open === <?php echo e($index); ?>"
                                        class="w-full flex justify-between items-center gap-4 px-5 py-4 text-left text-sm font-semibold text-neutral-900 hover:bg-neutral-50 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-500">
                                        <?php echo e($faq['question']); ?>

                                        <svg class="w-4 h-4 text-neutral-400 flex-shrink-0 motion-safe:transition-transform motion-safe:duration-200"
                                             :class="{ 'rotate-180': open === <?php echo e($index); ?> }"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="open === <?php echo e($index); ?>"
                                         x-transition:enter="motion-safe:transition-all motion-safe:ease-out motion-safe:duration-200"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="motion-safe:transition-all motion-safe:ease-in motion-safe:duration-150"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="border-t border-neutral-100 bg-white">
                                        <p class="px-5 py-4 text-sm text-neutral-600 leading-relaxed"><?php echo e($faq['answer']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal03b5006f52c5c5b41290402a897a208b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b5006f52c5c5b41290402a897a208b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.page-closer','data' => ['title' => 'Ready to Digitize Your Hospital or Institution?','subtitle' => 'Request an HMIS demo or speak with our team about software, branding, or campaign platforms.','primaryUrl' => hub_cta_url('about_footer_primary', 'hmis-demo'),'primaryLabel' => 'Request HMIS Demo','secondaryUrl' => hub_cta_url('about_footer_secondary', 'hmis-checklist'),'secondaryLabel' => 'Get HMIS Checklist']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.page-closer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ready to Digitize Your Hospital or Institution?','subtitle' => 'Request an HMIS demo or speak with our team about software, branding, or campaign platforms.','primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('about_footer_primary', 'hmis-demo')),'primary-label' => 'Request HMIS Demo','secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('about_footer_secondary', 'hmis-checklist')),'secondary-label' => 'Get HMIS Checklist']); ?>
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

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/frontend/about.blade.php ENDPATH**/ ?>