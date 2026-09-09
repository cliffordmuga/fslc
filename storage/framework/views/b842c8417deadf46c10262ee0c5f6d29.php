<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'items' => [],
    'columns' => 4,
    'standalone' => true,
    'compact' => false,
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
    'items' => [],
    'columns' => 4,
    'standalone' => true,
    'compact' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $gridClass = $columns === 3 ? 'md:grid-cols-3' : 'md:grid-cols-4';
    $sectionClass = $compact
        ? 'py-8 lg:py-10 bg-white border-b border-neutral-200'
        : 'py-14 lg:py-20 bg-white border-b border-neutral-100';
    $valueClass = $compact
        ? 'text-2xl sm:text-3xl font-black text-primary-600 mb-1 tracking-tight group-hover:text-primary-700 transition-colors'
        : 'text-3xl sm:text-4xl lg:text-5xl font-black text-primary-600 mb-2 tracking-tight group-hover:text-primary-700 transition-colors';
?>
<?php if($standalone): ?>
<section class="<?php echo e($sectionClass); ?>">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
<?php endif; ?>
        <div <?php echo e($attributes->merge(['class' => 'grid ' . $gridClass . ' gap-6 md:gap-8 text-center'])); ?>>
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group">
                    <div class="<?php echo e($valueClass); ?>"><?php echo e($item['value'] ?? ''); ?></div>
                    <p class="text-xs sm:text-sm font-medium text-neutral-600 mx-auto"><?php echo e($item['label'] ?? ''); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
<?php if($standalone): ?>
    </div>
</section>
<?php endif; ?>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/stats-strip.blade.php ENDPATH**/ ?>