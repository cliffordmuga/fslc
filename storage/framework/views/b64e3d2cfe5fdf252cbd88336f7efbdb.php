<?php
    $type = $content->type;
    $hubLabel = content_hub_label($type);
    $leadConfig = $leadConfig ?? [];
    $isHmis = content_is_hmis_related($content);
    $inquiryType = $detailInquiryType ?? content_inquiry_type_for_detail($content, $type, $leadConfig);
    $heroVariant = content_detail_hero_variant($type);
    $heroUrl = hero_asset($heroVariant);
    $heroSrcset = hero_asset_srcset($heroVariant);
    $checklistUrl = route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form';

    $schemaType = match ($type) {
        'portfolio' => 'CreativeWork',
        'blog'      => 'BlogPosting',
        'services'  => 'Service',
        default     => 'Article',
    };

    $indexRoute = match ($type) {
        'portfolio' => 'portfolio.index',
        'services'  => 'services.index',
        'blog'      => 'insights.index',
        default     => null,
    };

    $sidebarCtaLabel = $leadConfig['cta_label'] ?? match ($type) {
        'portfolio' => $isHmis ? 'Request HMIS Demo' : 'Discuss a Similar Project',
        'blog'      => 'Request HMIS Demo',
        'services'  => 'Get Project Quote',
        default     => 'Request HMIS Demo',
    };
    $sidebarTitle = $leadConfig['sidebar_title'] ?? match ($type) {
        'portfolio' => $isHmis ? 'Request HMIS Demo' : 'Discuss a Similar Project',
        'blog'      => 'Request HMIS Guidance',
        'services'  => 'Request a Quote',
        default     => 'Request a Quote',
    };
    $sidebarText = $leadConfig['sidebar_text'] ?? match ($type) {
        'portfolio' => $isHmis
            ? 'Book a facility walkthrough and see HMIS modules, SHA workflows, and reporting in action.'
            : 'Tell us your scope and timeline — we\'ll outline a clear path forward for a similar engagement.',
        'blog'      => 'HMIS demos, procurement guidance, and software quotes — we respond within 24 hours.',
        'services'  => 'Free consultation — tell us your project goals and we\'ll give you a clear path forward.',
        default     => 'Free consultation — tell us your project goals and we\'ll give you a clear path forward.',
    };

    $contactParams = array_filter([
        'inquiry_type' => $inquiryType,
        'service' => $type === 'services' ? $content->slug : null,
    ]);
    $contactUrl = route('contact', $contactParams) . '#contact-form';

    $readingTime = $type === 'blog' ? content_reading_time_minutes($content->content) : null;
    $articleHeadings = $type === 'blog' ? content_article_headings($content->content) : [];
    $showArticleToc = count($articleHeadings) >= 3;
    $hasLeadMagnetShortcode = str_contains($content->content ?? '', '[lead-magnet');
    $showAutoLeadMagnet = $isHmis && in_array($type, ['services', 'blog'], true) && ! $hasLeadMagnetShortcode;

    $featuredTestimonial = ! empty($testimonials) && $testimonials->count() > 0
        ? $testimonials->first()
        : null;

    $crumbItems = [['label' => 'Home', 'url' => route('home')]];
    if ($indexRoute) {
        $crumbItems[] = ['label' => $hubLabel, 'url' => route($indexRoute)];
    }
    $crumbItems[] = ['label' => $content->title];

    $heroSubtitle = null;
    if ($type === 'blog' && ($content->published_at || $readingTime)) {
        $parts = [];
        if ($content->published_at) {
            $parts[] = $content->published_at->format('d M Y');
        }
        if ($readingTime) {
            $parts[] = "{$readingTime} min read";
        }
        $heroSubtitle = implode(' · ', $parts);
    } elseif ($content->published_at) {
        $heroSubtitle = $content->published_at->format('d M Y');
    }

    $heroTagline = filled($content->excerpt)
        ? \Illuminate\Support\Str::limit(strip_tags($content->excerpt), 120)
        : "Explore this {$hubLabel} from Forefront Solutions — HMIS, software, and digital transformation in Kenya.";

    $detailBadges = match ($type) {
        'portfolio' => $isHmis ? ['HMIS', 'Case Study'] : ['Portfolio', 'Case Study'],
        'services'  => ['Services'],
        'blog'      => ['Insights'],
        default     => [ucfirst($type)],
    };

    $relatedSectionLabel = match ($type) {
        'blog' => 'Related articles',
        default => 'More ' . $hubLabel,
    };
?>

<?php $__env->startPush('head'); ?>
    <?php if($heroUrl): ?>
        <link rel="preload" href="<?php echo e($heroUrl); ?>" as="image">
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

    
    <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'standard','title' => $content->title,'subtitle' => $heroSubtitle,'tagline' => $heroTagline,'badges' => $detailBadges,'imageUrl' => $heroUrl,'imageSrcset' => $heroSrcset,'ctaUrl' => hub_cta_url('detail_hero_' . $type, $inquiryType, $contactUrl),'ctaLabel' => $sidebarCtaLabel,'suppressCta' => $type === 'blog']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'standard','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($content->title),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($heroSubtitle),'tagline' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($heroTagline),'badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($detailBadges),'image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($heroUrl),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($heroSrcset),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('detail_hero_' . $type, $inquiryType, $contactUrl)),'cta-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sidebarCtaLabel),'suppress-cta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($type === 'blog')]); ?>
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

    <div class="bg-white border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <?php if (isset($component)) { $__componentOriginal845656e97179b8317ed324815f9732c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal845656e97179b8317ed324815f9732c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.breadcrumb','data' => ['items' => $crumbItems]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($crumbItems)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal845656e97179b8317ed324815f9732c3)): ?>
<?php $attributes = $__attributesOriginal845656e97179b8317ed324815f9732c3; ?>
<?php unset($__attributesOriginal845656e97179b8317ed324815f9732c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal845656e97179b8317ed324815f9732c3)): ?>
<?php $component = $__componentOriginal845656e97179b8317ed324815f9732c3; ?>
<?php unset($__componentOriginal845656e97179b8317ed324815f9732c3); ?>
<?php endif; ?>
        </div>
    </div>

    
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 lg:py-14">
        <div class="grid lg:grid-cols-4 gap-8 lg:gap-12">

            
            <article id="detail-article" class="lg:col-span-3">

                    
                    <div class="lg:hidden mb-6 bg-neutral-900 border-t-4 border-primary-500 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-400 mb-1">Next Step</p>
                        <p class="text-sm text-neutral-300 mb-3 leading-snug"><?php echo e($sidebarTitle); ?></p>
                        <a href="<?php echo e(generate_utm_url($contactUrl, 'details_' . $type . '_mobile_' . $content->id)); ?>"
                           class="block w-full text-center bg-white text-neutral-900 hover:bg-primary-50 py-3 text-sm font-bold uppercase tracking-wider transition-colors">
                            <?php echo e($sidebarCtaLabel); ?> &rarr;
                        </a>
                    </div>

                    
                    <?php
                        $subtitleFallback = match ($type) {
                            'portfolio' => 'A case study delivering measurable outcomes for Kenyan institutions.',
                            'services'  => 'A focused service pillar for hospitals, government, NGOs, and campaigns.',
                            'blog'      => 'Expert insights on HMIS, elections, software, and digital strategy in Kenya.',
                            default     => 'In-depth content from Forefront Solutions.',
                        };
                        $subtitle = $content->excerpt ?? $subtitleFallback;
                    ?>
                    <p class="text-base text-neutral-600 leading-relaxed mb-6 border-l-4 border-primary-500 pl-4 py-1">
                        <?php echo e($subtitle); ?>

                    </p>

                    
                    <?php if($content->images && $content->images->where('collection', 'featured')->isNotEmpty()): ?>
                        <figure class="mb-8">
                            <?php if (isset($component)) { $__componentOriginal709b67cb8379df6b5da8f3c1045984a7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal709b67cb8379df6b5da8f3c1045984a7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.responsive-image','data' => ['model' => $content,'collection' => 'featured','class' => 'w-full aspect-[16/9] object-cover border border-neutral-200','alt' => $content->title . ' – Featured Image','loading' => 'lazy']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.responsive-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($content),'collection' => 'featured','class' => 'w-full aspect-[16/9] object-cover border border-neutral-200','alt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($content->title . ' – Featured Image'),'loading' => 'lazy']); ?>
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
                            <?php $featuredImage = $content->images->where('collection', 'featured')->first(); ?>
                            <?php if($featuredImage?->alt_text): ?>
                                <figcaption class="mt-2 text-xs text-neutral-500 border-l-2 border-neutral-300 pl-3">
                                    <?php echo e($featuredImage->alt_text); ?>

                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>

                    
                    <?php if($showArticleToc): ?>
                        <nav class="mb-8 p-4 border border-neutral-200 bg-neutral-50" aria-label="Table of contents">
                            <p class="text-xs font-semibold uppercase tracking-[0.15em] text-neutral-500 mb-3">In this article</p>
                            <ol class="space-y-2 text-sm">
                                <?php $__currentLoopData = $articleHeadings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $heading): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <a href="#<?php echo e($heading['id']); ?>"
                                           class="text-primary-600 hover:text-primary-800 font-medium transition-colors">
                                            <?php echo e($heading['text']); ?>

                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ol>
                        </nav>
                    <?php endif; ?>

                    
                    <div class="prose-custom prose-sm lg:prose-base max-w-none text-neutral-700 leading-relaxed">
                        <?php if($type === 'blog'): ?>
                            <?php echo render_cms_article($content->content); ?>

                        <?php else: ?>
                            <?php echo render_cms_content($content->content); ?>

                        <?php endif; ?>
                    </div>

                    
                    <?php if($showAutoLeadMagnet): ?>
                        <div class="mt-8">
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
                        </div>
                    <?php endif; ?>

                    
                    <?php if(in_array($type, ['portfolio', 'services', 'blog']) && $content->gallery_for_view->isNotEmpty()): ?>
                        <?php
                            $galleryTitle = match ($type) {
                                'portfolio' => 'Project Gallery',
                                'services'  => 'Service Gallery',
                                default     => 'Gallery',
                            };
                        ?>
                        <?php if (isset($component)) { $__componentOriginal541cce823c097157920f01c1f51c0b47 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal541cce823c097157920f01c1f51c0b47 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.content-gallery','data' => ['content' => $content,'title' => $galleryTitle]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.content-gallery'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($content),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($galleryTitle)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal541cce823c097157920f01c1f51c0b47)): ?>
<?php $attributes = $__attributesOriginal541cce823c097157920f01c1f51c0b47; ?>
<?php unset($__attributesOriginal541cce823c097157920f01c1f51c0b47); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal541cce823c097157920f01c1f51c0b47)): ?>
<?php $component = $__componentOriginal541cce823c097157920f01c1f51c0b47; ?>
<?php unset($__componentOriginal541cce823c097157920f01c1f51c0b47); ?>
<?php endif; ?>
                    <?php endif; ?>

                    
                    <?php if(!empty($metrics) && in_array($type, ['portfolio', 'services'])): ?>
                        <div class="mt-8 border border-neutral-200 border-t-4 border-t-primary-500 grid grid-cols-2 md:grid-cols-4">
                            <?php $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-5 border-r border-neutral-200 last:border-r-0 text-center">
                                    <div class="text-3xl font-black font-mono text-primary-600 mb-1"><?php echo e($metric['value']); ?></div>
                                    <p class="text-xs text-neutral-500 uppercase tracking-wider"><?php echo e($metric['label']); ?></p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($content->tags && $content->tags->count() > 0): ?>
                        <section class="mt-8 pt-6 border-t border-neutral-200" aria-labelledby="tags-heading">
                            <h3 id="tags-heading" class="text-xs font-semibold uppercase tracking-[0.15em] text-neutral-500 mb-3">
                                Related Topics
                            </h3>
                            <nav class="flex flex-wrap gap-2" aria-label="Related topics">
                                <?php $__currentLoopData = $content->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $tagUrl = route('tags.show', $tag->slug) .
                                            (in_array($type, ['blog', 'portfolio', 'services']) ? '?type=' . $type : '');
                                    ?>
                                    <a href="<?php echo e($tagUrl); ?>"
                                       class="inline-block px-3 py-1 text-xs font-semibold bg-neutral-100 border border-neutral-200 text-neutral-600 hover:border-neutral-400 hover:text-neutral-900 transition-colors uppercase tracking-wider">
                                        #<?php echo e($tag->name); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </nav>
                        </section>
                    <?php endif; ?>

                    
                    <?php if(in_array($type, ['portfolio', 'services', 'blog'])): ?>
                        <div class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-neutral-600 border-t border-neutral-200 pt-6">
                            <span>
                                <?php if($type === 'portfolio'): ?>
                                    Explore related capabilities
                                <?php elseif($type === 'services'): ?>
                                    See this work in practice
                                <?php else: ?>
                                    Ready to implement what you read?
                                <?php endif; ?>
                            </span>
                            <?php if($type === 'portfolio' || $type === 'blog'): ?>
                                <a href="<?php echo e(route('services.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                                    Explore our services
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if($type === 'services' || $type === 'blog'): ?>
                                <a href="<?php echo e(route('portfolio.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                                    See case studies
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if($type === 'portfolio' || $type === 'services'): ?>
                                <a href="<?php echo e(route('insights.index')); ?>" class="font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                                    Read insights
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($featuredTestimonial): ?>
                        <?php if (isset($component)) { $__componentOriginal3024b0a62f1e4de37b9d4f4d64bc33a4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3024b0a62f1e4de37b9d4f4d64bc33a4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.featured-testimonial','data' => ['testimonial' => $featuredTestimonial,'title' => 'What Clients Say','inline' => true,'class' => 'mt-8 pt-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.featured-testimonial'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['testimonial' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($featuredTestimonial),'title' => 'What Clients Say','inline' => true,'class' => 'mt-8 pt-6']); ?>
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
                    <?php endif; ?>

            </article>

            
            <aside class="lg:col-span-1 lg:sticky lg:top-20 self-start space-y-6" role="complementary" aria-label="Sidebar">

                
                <div class="bg-neutral-900 border-t-4 border-primary-500 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-400 mb-2">Next Step</p>
                    <h4 class="text-lg font-black text-white mb-2 tracking-tight"><?php echo e($sidebarTitle); ?></h4>
                    <p class="text-sm text-neutral-400 mb-5 leading-relaxed">
                        <?php echo e($sidebarText); ?>

                    </p>
                    <a href="<?php echo e(generate_utm_url($contactUrl, 'details_' . $type . '_' . $content->id)); ?>"
                       class="block w-full text-center bg-white text-neutral-900 hover:bg-primary-50 py-3 text-sm font-bold uppercase tracking-wider transition-colors">
                        <?php echo e($sidebarCtaLabel); ?> &rarr;
                    </a>
                    <?php if($isHmis || $type === 'blog'): ?>
                        <a href="<?php echo e(generate_utm_url($checklistUrl, 'details_' . $type . '_checklist_' . $content->id)); ?>"
                           class="block w-full text-center mt-3 border border-neutral-600 text-neutral-300 hover:text-white hover:border-neutral-400 py-2.5 text-xs font-bold uppercase tracking-wider transition-colors">
                            Download HMIS Checklist
                        </a>
                    <?php endif; ?>
                </div>

                
                <?php if($content->ctas && $content->ctas->count() > 0): ?>
                    <div class="border border-neutral-200 p-5">
                        <h4 class="text-xs font-semibold uppercase tracking-[0.15em] text-neutral-500 mb-4">Quick Actions</h4>
                        <?php $__currentLoopData = $content->ctas->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal9e673ab3e9cc0949baa4b253778f7c5a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9e673ab3e9cc0949baa4b253778f7c5a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.cta-button','data' => ['cta' => $cta,'variant' => 'outline','size' => 'md','class' => 'w-full mb-3 btn-secondary text-sm py-2.5','url' => generate_utm_url($cta->action, 'details_cta_' . $content->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.cta-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['cta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cta),'variant' => 'outline','size' => 'md','class' => 'w-full mb-3 btn-secondary text-sm py-2.5','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(generate_utm_url($cta->action, 'details_cta_' . $content->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9e673ab3e9cc0949baa4b253778f7c5a)): ?>
<?php $attributes = $__attributesOriginal9e673ab3e9cc0949baa4b253778f7c5a; ?>
<?php unset($__attributesOriginal9e673ab3e9cc0949baa4b253778f7c5a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9e673ab3e9cc0949baa4b253778f7c5a)): ?>
<?php $component = $__componentOriginal9e673ab3e9cc0949baa4b253778f7c5a; ?>
<?php unset($__componentOriginal9e673ab3e9cc0949baa4b253778f7c5a); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                
                <?php if(!empty($relatedItems) && $relatedItems->count() > 0): ?>
                    <div class="border border-neutral-200 p-5">
                        <h4 class="text-xs font-semibold uppercase tracking-[0.15em] text-neutral-500 mb-4">
                            <?php echo e($relatedSectionLabel); ?>

                        </h4>
                        <ul class="space-y-4">
                            <?php $__currentLoopData = $relatedItems->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <?php $thumb = $related->images?->where('collection', 'featured')->first(); ?>
                                    <a href="<?php echo e(generate_utm_url($related->url, 'details_related_' . $type . '_' . $content->id)); ?>"
                                       class="group flex items-start gap-3 hover:text-primary-600 transition-colors">
                                        <?php if($thumb): ?>
                                            <img src="<?php echo e($thumb->image_url); ?>" alt="<?php echo e($related->title); ?>"
                                                 class="w-12 h-12 object-cover flex-shrink-0 border border-neutral-200"
                                                 loading="lazy">
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-neutral-100 border border-neutral-200 flex-shrink-0 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <span class="text-sm text-neutral-700 group-hover:text-primary-600 leading-snug line-clamp-2 transition-colors">
                                            <?php echo e($related->title); ?>

                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </aside>
        </div>
    </div>

    
    <?php
        $breadcrumbData = [
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => array_values(array_filter([
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                $indexRoute ? [
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => $hubLabel,
                    'item'     => route($indexRoute),
                ] : null,
                [
                    '@type'    => 'ListItem',
                    'position' => $indexRoute ? 3 : 2,
                    'name'     => $content->title,
                    'item'     => $content->url ?? request()->url(),
                ],
            ])),
        ];
    ?>
    <?php if (isset($component)) { $__componentOriginal1ff444a6761d54b4237649bd3eed67fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.json-ld','data' => ['data' => $breadcrumbData]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.json-ld'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($breadcrumbData)]); ?>
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

    
    <?php if($type === 'services'): ?>
        <?php if (isset($component)) { $__componentOriginal47a6b974428a50244b0b412cf1f5050a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal47a6b974428a50244b0b412cf1f5050a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.process-timeline','data' => ['compact' => true,'sectionLabel' => 'Process','title' => 'How we deliver','headingId' => 'details-process-heading','class' => 'bg-neutral-50 border-t border-neutral-200','ctaUrl' => route('contact', ['inquiry_type' => $inquiryType]) . '#contact-form','ctaLabel' => $sidebarCtaLabel]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.process-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['compact' => true,'section-label' => 'Process','title' => 'How we deliver','heading-id' => 'details-process-heading','class' => 'bg-neutral-50 border-t border-neutral-200','cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => $inquiryType]) . '#contact-form'),'cta-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sidebarCtaLabel)]); ?>
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
    <?php endif; ?>

    
    <?php
        $useHmisFooter = $isHmis || $type === 'blog' || ($type === 'services' && ! empty($leadConfig));
        $footerCtaTitle = match ($type) {
            'portfolio' => $isHmis ? 'Ready to Deploy HMIS at Your Facility?' : 'Ready to Start a Similar Project?',
            'services'  => $leadConfig['sidebar_title'] ?? config('forefront.inquiry_ux.default.sticky_label', 'Request HMIS Demo'),
            'blog'      => 'Ready to Act on These Insights?',
            default     => 'Let\'s Work Together',
        };
        $footerCtaSubtitle = match ($type) {
            'portfolio' => $isHmis
                ? 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.'
                : 'HMIS, software, or campaign platform — discuss scope and timeline with our team.',
            'services'  => $leadConfig['sidebar_text'] ?? 'Free consultation — no obligation.',
            'blog'      => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.',
            default     => 'We respond within 24 hours.',
        };
        $footerCtaLabel = $useHmisFooter
            ? ($leadConfig['cta_label'] ?? 'Request HMIS Demo')
            : match ($type) {
                'portfolio' => 'Discuss a Similar Project',
                default     => 'Get a Free Consultation',
            };
        $footerSecondaryUrl = $useHmisFooter ? $checklistUrl : null;
        $footerSecondaryLabel = $useHmisFooter ? 'Get HMIS Checklist' : null;
    ?>
    <?php if (isset($component)) { $__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.footer-cta','data' => ['title' => $footerCtaTitle,'subtitle' => $footerCtaSubtitle,'primaryUrl' => generate_utm_url($contactUrl, 'details_' . $type . '_footer'),'primaryLabel' => $footerCtaLabel,'secondaryUrl' => $footerSecondaryUrl,'secondaryLabel' => $footerSecondaryLabel,'showWhatsapp' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.footer-cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerCtaTitle),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerCtaSubtitle),'primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(generate_utm_url($contactUrl, 'details_' . $type . '_footer')),'primary-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerCtaLabel),'secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerSecondaryUrl),'secondary-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerSecondaryLabel),'show-whatsapp' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8)): ?>
<?php $attributes = $__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8; ?>
<?php unset($__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8)): ?>
<?php $component = $__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8; ?>
<?php unset($__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/frontend/details.blade.php ENDPATH**/ ?>