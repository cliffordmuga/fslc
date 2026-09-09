<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'How We Bring Your Vision to Life',
    'headingId' => null,
    'ctaUrl' => null,
    'ctaLabel' => null,
    'compact' => false,
    'sectionLabel' => null,
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
    'title' => 'How We Bring Your Vision to Life',
    'headingId' => null,
    'ctaUrl' => null,
    'ctaLabel' => null,
    'compact' => false,
    'sectionLabel' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sectionClass = $compact ? 'py-10 lg:py-14 bg-white border-t border-neutral-200' : 'py-14 lg:py-20 bg-white';
    $headingClass = $compact
        ? 'text-2xl lg:text-3xl font-black text-neutral-900 tracking-tight'
        : 'text-3xl md:text-4xl lg:text-5xl font-extrabold text-center mb-12 lg:mb-14 text-neutral-900 tracking-tight';
    $stepsContainerClass = $compact
        ? 'flex md:grid md:grid-cols-4 gap-4 lg:gap-6 overflow-x-auto snap-x snap-mandatory pb-2 md:pb-0 -mx-6 px-6 md:mx-0 md:px-0 scrollbar-thin'
        : 'grid md:grid-cols-4 gap-6 lg:gap-8';
    $stepCardClass = $compact
        ? 'card-base p-5 lg:p-6 text-center hover-lift group min-w-[78vw] md:min-w-0 snap-start shrink-0 md:shrink'
        : 'card-base p-6 lg:p-8 text-center hover-lift group';
    $stepNumberClass = $compact
        ? 'w-12 h-12 mx-auto mb-4 bg-primary-50 border-2 border-primary-500 flex items-center justify-center text-primary-600 text-xl font-black group-hover:bg-primary-100 transition-colors duration-300'
        : 'w-14 h-14 mx-auto mb-5 bg-gradient-to-br from-primary-50 to-primary-100 rounded-full flex items-center justify-center text-primary-600 text-2xl font-black group-hover:scale-105 transition-transform duration-300';
?>

<section <?php echo e($attributes->merge(['class' => $sectionClass])); ?>>
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <?php if($compact): ?>
            <div class="mb-8 border-b border-neutral-200 pb-4">
                <?php if($sectionLabel): ?>
                    <p class="section-label mb-1"><?php echo e($sectionLabel); ?></p>
                <?php endif; ?>
                <h2 <?php if($headingId): ?> id="<?php echo e($headingId); ?>" <?php endif; ?> class="<?php echo e($headingClass); ?>">
                    <?php echo e($title); ?>

                </h2>
            </div>
        <?php else: ?>
            <h2 <?php if($headingId): ?> id="<?php echo e($headingId); ?>" <?php endif; ?> class="<?php echo e($headingClass); ?>">
                <?php echo e($title); ?>

            </h2>
        <?php endif; ?>

        <div class="<?php echo e($stepsContainerClass); ?>">
            <?php $__currentLoopData = [['number' => '1', 'title' => 'Discovery', 'description' => 'Free consultation to understand your facility, institution, audience, budget, and timeline.'], ['number' => '2', 'title' => 'Design & Strategy', 'description' => 'We map HMIS modules, portal workflows, brand positioning, or campaign architecture tailored to your goals.'], ['number' => '3', 'title' => 'Development', 'description' => 'Secure delivery of HMIS modules, institutional portals, campaign platforms, and integrations built for Kenyan operations.'], ['number' => '4', 'title' => 'Launch & Growth', 'description' => 'We launch, train your team, optimize performance and SEO, and support long-term adoption and growth.']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="<?php echo e($stepCardClass); ?>">
                    <div class="<?php echo e($stepNumberClass); ?>">
                        <?php echo e($step['number']); ?>

                    </div>

                    <h3 class="text-lg lg:text-xl font-bold mb-3 text-neutral-900 tracking-tight group-hover:text-primary-600 transition-colors">
                        <?php echo e($step['title']); ?>

                    </h3>

                    <p class="text-sm lg:text-base text-neutral-600 leading-relaxed">
                        <?php echo e($step['description']); ?>

                    </p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if($ctaUrl): ?>
            <div class="mt-10 lg:mt-12 text-center">
                <a href="<?php echo e($ctaUrl); ?>"
                    class="btn-primary inline-flex items-center gap-2 px-8 py-4 text-base lg:text-lg font-semibold shadow-medium hover:shadow-elevated hover-lift transition-all duration-500">
                    <?php echo e($ctaLabel ?? 'Start with a Free Consultation'); ?>

                    <?php if (isset($component)) { $__componentOriginal37a3f047daccd28b87517bd215a12923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37a3f047daccd28b87517bd215a12923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.arrow-right','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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
            </div>
        <?php endif; ?>
    </div>
</section>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/process-timeline.blade.php ENDPATH**/ ?>