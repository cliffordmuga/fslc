
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title'             => setting('company_name', config('app.name', env('APP_NAME', 'App'))),
    'subtitle'          => 'Explore our portfolio, services, and insights.',
    'tagline'           => null,
    'cta'               => null,
    'image'             => null,
    'imageUrl'          => null,
    'imageSrcset'       => null,
    'imageSizes'        => '100vw',
    'size'              => 'standard',   // 'home' (homepage only) | 'standard' (all other pages)
    'gradient'          => 'to-br',
    'urgency'           => false,
    'metrics'           => null,
    'badges'            => [],
    'backgroundType'    => 'grid',
    'ctaUrl'            => null,
    'ctaLabel'          => 'Request HMIS Demo',
    'overlay'           => null,
    'theme'             => 'dark',
    'showScrollIndicator' => false,
    'showSecondaryCta'  => true,
    'suppressCta'       => false,
]));

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

foreach (array_filter(([
    'title'             => setting('company_name', config('app.name', env('APP_NAME', 'App'))),
    'subtitle'          => 'Explore our portfolio, services, and insights.',
    'tagline'           => null,
    'cta'               => null,
    'image'             => null,
    'imageUrl'          => null,
    'imageSrcset'       => null,
    'imageSizes'        => '100vw',
    'size'              => 'standard',   // 'home' (homepage only) | 'standard' (all other pages)
    'gradient'          => 'to-br',
    'urgency'           => false,
    'metrics'           => null,
    'badges'            => [],
    'backgroundType'    => 'grid',
    'ctaUrl'            => null,
    'ctaLabel'          => 'Request HMIS Demo',
    'overlay'           => null,
    'theme'             => 'dark',
    'showScrollIndicator' => false,
    'showSecondaryCta'  => true,
    'suppressCta'       => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $headingId   = 'hero-' . uniqid();
    $subtitleId  = 'hero-sub-' . uniqid();
    $isLight     = $theme === 'light';
    $isHome      = $size === 'home';

    $heightClass = $isHome ? 'hero-h-home' : 'hero-h-standard';

    $bgClass = $isLight ? 'bg-neutral-50' : 'bg-neutral-900';

    $headingClass = $isLight ? 'text-neutral-900' : 'text-white';
    $subtitleClass = $isLight ? 'text-neutral-600' : 'text-neutral-300';
    $taglineClass  = $isLight ? 'text-neutral-500' : 'text-neutral-400';

    $headingSizeClass = $isHome
        ? 'text-3xl sm:text-4xl lg:text-5xl xl:text-6xl'
        : 'text-3xl sm:text-4xl lg:text-5xl';

    $hasImage  = $image || $imageUrl;
    $imageSrc  = $image ? $image->url('main') : $imageUrl;
    $isDecorativeHero = $hasImage && ! $image?->alt_text;
    $imageAlt  = $isDecorativeHero ? '' : ($image?->alt_text ?? '');
    $srcset    = $imageSrcset ?? null;
    $demoUrl   = hub_cta_url('hero_cta');
    $hasActions = ! $suppressCta && ($cta || $ctaUrl) && ! $isLight;
?>

<section
    <?php echo e($attributes->merge(['class' => "relative {$heightClass} flex items-center overflow-hidden {$bgClass}"])); ?>

    role="region"
    aria-labelledby="<?php echo e($headingId); ?>"
>
    <?php if($hasImage && $imageSrc): ?>
        <div class="absolute inset-0">
            <img src="<?php echo e($imageSrc); ?>"
                 <?php if($srcset): ?> srcset="<?php echo e($srcset); ?>" sizes="<?php echo e($imageSizes); ?>" <?php endif; ?>
                 alt="<?php echo e($imageAlt); ?>"
                 <?php if($isDecorativeHero): ?> aria-hidden="true" <?php endif; ?>
                 width="1920" height="1080"
                 class="w-full h-full object-cover <?php echo e($isLight ? 'opacity-20' : 'opacity-[0.38]'); ?>"
                 loading="eager" fetchpriority="high" decoding="async">
            <div class="absolute inset-0 <?php echo e($isLight ? 'bg-gradient-to-b from-white/70 via-white/30 to-neutral-50/80' : 'bg-gradient-to-b from-neutral-900/45 via-neutral-900/25 to-neutral-900/65'); ?>"></div>
        </div>
    <?php endif; ?>

    <?php if(!$isLight): ?>
        <div class="absolute inset-0 bg-hero-grid opacity-[0.05] pointer-events-none" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500" aria-hidden="true"></div>

    <div class="relative z-10 page-container hero-inner">

        <?php if(!empty($badges)): ?>
            <div class="flex flex-wrap gap-2 mb-4" role="list">
                <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => $isLight ? 'outline-light' : 'outline','size' => 'sm','class' => 'font-mono tracking-widest','role' => 'listitem']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isLight ? 'outline-light' : 'outline'),'size' => 'sm','class' => 'font-mono tracking-widest','role' => 'listitem']); ?>
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
        <?php endif; ?>

        <h1 id="<?php echo e($headingId); ?>"
            class="<?php echo e($headingSizeClass); ?> font-black leading-[1.05] tracking-tight mb-3 <?php echo e($headingClass); ?>"
            itemprop="name">
            <?php echo e($title); ?>

        </h1>

        <?php if($subtitle): ?>
            <p id="<?php echo e($subtitleId); ?>"
                class="text-base sm:text-lg lg:text-xl max-w-2xl leading-relaxed mb-1 <?php echo e($subtitleClass); ?>"
                itemprop="description">
                <?php echo e($subtitle); ?>

            </p>
        <?php endif; ?>

        <?php if($tagline): ?>
            <p class="text-sm mt-2 max-w-xl <?php echo e($taglineClass); ?> font-medium">
                <?php echo e($tagline); ?>

            </p>
        <?php endif; ?>

        <?php if($slot->isNotEmpty()): ?>
            <div class="mt-5"><?php echo e($slot); ?></div>
        <?php elseif($hasActions): ?>
            <div class="flex flex-wrap gap-3 mt-6 items-center">
                <?php if($cta): ?>
                    <?php if (isset($component)) { $__componentOriginal9e673ab3e9cc0949baa4b253778f7c5a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9e673ab3e9cc0949baa4b253778f7c5a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.cta-button','data' => ['cta' => $cta,'variant' => 'primary','url' => $ctaUrl ?? $demoUrl,'class' => 'text-sm font-semibold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.cta-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['cta' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cta),'variant' => 'primary','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ctaUrl ?? $demoUrl),'class' => 'text-sm font-semibold']); ?>
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
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['href' => $ctaUrl ?? $demoUrl,'variant' => 'primary','class' => 'text-sm font-semibold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ctaUrl ?? $demoUrl),'variant' => 'primary','class' => 'text-sm font-semibold']); ?>
                        <?php echo e($ctaLabel); ?>

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
                <?php endif; ?>

                <?php if($showSecondaryCta): ?>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['href' => hub_cta_url('hero_secondary', '', route('portfolio.index')),'variant' => $isLight ? 'secondary' : 'ghost-dark','class' => 'text-sm px-6','ariaLabel' => 'View our work']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('hero_secondary', '', route('portfolio.index'))),'variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isLight ? 'secondary' : 'ghost-dark'),'class' => 'text-sm px-6','aria-label' => 'View our work']); ?>
                        View Our Work
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
                <?php endif; ?>

                <?php if($urgency): ?>
                    <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => 'danger','size' => 'md','uppercase' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'danger','size' => 'md','uppercase' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
                        Limited availability
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

        <?php if($isHome && $metrics): ?>
            <div class="mt-7 pt-5 border-t <?php echo e($isLight ? 'border-neutral-200' : 'border-neutral-700'); ?>">
                <div class="flex flex-wrap gap-8">
                    <?php $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <div class="text-2xl lg:text-3xl font-black font-mono <?php echo e($isLight ? 'text-neutral-900' : 'text-white'); ?>">
                                <?php echo e($metric['value']); ?>

                            </div>
                            <div class="text-xs uppercase tracking-wider font-medium mt-0.5 <?php echo e($isLight ? 'text-neutral-500' : 'text-neutral-400'); ?>">
                                <?php echo e($metric['label']); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/hero-section.blade.php ENDPATH**/ ?>