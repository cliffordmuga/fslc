
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => 'Filters',
    'items' => [],
    'resultSummary' => null,
    'sticky' => false,
    'wrap' => false,
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
    'label' => 'Filters',
    'items' => [],
    'resultSummary' => null,
    'sticky' => false,
    'wrap' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $activeClass = 'bg-neutral-900 text-white border-neutral-900';
    $inactiveClass = 'bg-white text-neutral-600 border-neutral-200 hover:border-neutral-400 hover:text-neutral-900';
    $navClass = $wrap
        ? 'flex flex-wrap gap-2'
        : 'flex gap-2 overflow-x-auto snap-x snap-mandatory pb-1 -mx-6 px-6 lg:mx-0 lg:px-0 lg:flex-wrap lg:overflow-visible lg:snap-none';
    $barClass = $sticky
        ? 'sticky top-16 z-40 bg-white/95 backdrop-blur-sm border-b border-neutral-200'
        : 'border-b border-neutral-200 bg-white';
?>

<div <?php echo e($attributes->merge(['class' => $barClass])); ?>>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
        <div class="<?php if(isset($prefix)): ?> flex flex-col lg:flex-row lg:items-start gap-4 <?php endif; ?>">
        <?php if(isset($prefix)): ?>
            <?php echo e($prefix); ?>

        <?php endif; ?>

        <div class="<?php if(isset($prefix)): ?> flex-1 min-w-0 <?php endif; ?>">
        <nav aria-label="<?php echo e($label); ?>" class="<?php echo e($navClass); ?>">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isActive = (bool) ($chip['active'] ?? false);
                    $chipClass = $isActive ? $activeClass : ($chip['class'] ?? $inactiveClass);
                ?>
                <a href="<?php echo e($chip['url']); ?>"
                   <?php if($isActive): ?> aria-current="page" <?php endif; ?>
                   <?php if(! empty($chip['title'])): ?> title="<?php echo e($chip['title']); ?>" <?php endif; ?>
                   class="inline-flex items-center px-4 py-2.5 text-xs font-semibold uppercase tracking-wider border transition-colors min-h-[48px] snap-start shrink-0 <?php echo e($chipClass); ?>">
                    <?php echo e($chip['label']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

        <?php if($resultSummary): ?>
            <p class="mt-3 text-sm text-neutral-500"><?php echo $resultSummary; ?></p>
        <?php endif; ?>
        </div>
        </div>

        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/filter-chip-nav.blade.php ENDPATH**/ ?>