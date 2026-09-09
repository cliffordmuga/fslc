<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Trusted partners',
    'logos' => [],
    'sectors' => [],
    'certifications' => [],
    'compact' => false,
    'eyebrow' => null,
    'showEyebrow' => null,
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
    'title' => 'Trusted partners',
    'logos' => [],
    'sectors' => [],
    'certifications' => [],
    'compact' => false,
    'eyebrow' => null,
    'showEyebrow' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sectionClass = $compact
        ? 'py-8 lg:py-10 bg-neutral-50 border-t border-neutral-200'
        : 'py-14 lg:py-20 bg-white border-b border-neutral-100';
    $titleClass = $compact
        ? 'text-2xl lg:text-3xl font-black tracking-tight text-neutral-900 mb-6'
        : 'text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight text-neutral-900 mb-10';
    $sectorGridClass = $compact
        ? 'grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-3 max-w-3xl mx-auto'
        : 'flex flex-wrap justify-center gap-3 md:gap-4';
    $displayEyebrow = ($showEyebrow ?? ! empty($sectors)) && $compact
        ? ($eyebrow ?: (! empty($sectors) ? 'Clients' : null))
        : null;
?>

<section <?php echo e($attributes->merge(['class' => $sectionClass])); ?> aria-labelledby="<?php echo e(Str::slug($title)); ?>-heading">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <?php if($displayEyebrow): ?>
            <p class="section-label mb-1"><?php echo e($displayEyebrow); ?></p>
        <?php endif; ?>
        <h2 id="<?php echo e(Str::slug($title)); ?>-heading" class="<?php echo e($titleClass); ?>">
            <?php echo e($title); ?>

        </h2>
        <?php if(isset($description)): ?>
            <?php echo e($description); ?>

        <?php endif; ?>
        <?php if(!empty($sectors)): ?>
            <div class="<?php echo e($sectorGridClass); ?>">
                <?php $__currentLoopData = $sectors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="inline-flex items-center justify-center px-3 py-2 text-[11px] md:text-xs font-semibold uppercase tracking-wider border border-neutral-200 bg-white text-neutral-700">
                        <?php echo e($sector); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php elseif(!empty($certifications)): ?>
            <div class="flex flex-wrap justify-center gap-2 md:gap-3 max-w-3xl mx-auto">
                <?php $__currentLoopData = $certifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="inline-flex items-center justify-center px-3 py-2 text-[11px] md:text-xs font-semibold uppercase tracking-wider border border-primary-200 bg-primary-50 text-primary-800">
                        <?php echo e($cert); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-16 lg:gap-20">
                <?php $__currentLoopData = $logos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="grayscale hover:grayscale-0 opacity-70 hover:opacity-100 transition-all duration-300">
                        <img src="<?php echo e($logo['src'] ?? $logo['url'] ?? ''); ?>" alt="<?php echo e($logo['alt'] ?? ''); ?>"
                            class="h-10 md:h-12 object-contain max-w-[120px]" loading="lazy" decoding="async">
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/trust-strip.blade.php ENDPATH**/ ?>