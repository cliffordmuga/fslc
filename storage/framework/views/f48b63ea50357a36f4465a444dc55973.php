
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'title',
    'intro' => null,
    'headingId' => null,
    'bordered' => true,
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
    'label' => null,
    'title',
    'intro' => null,
    'headingId' => null,
    'bordered' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $id = $headingId ?? 'section-' . \Illuminate\Support\Str::slug(strip_tags($title));
    $wrapperClass = $bordered ? 'mb-8 border-b border-neutral-200 pb-4' : 'mb-8';
    $hasAction = isset($action) && trim((string) $action) !== '';
?>

<div <?php echo e($attributes->merge(['class' => $wrapperClass])); ?>>
    <div class="<?php echo e($hasAction ? 'flex items-end justify-between gap-4' : ''); ?>">
        <div class="min-w-0">
            <?php if($label): ?>
                <p class="section-label mb-1"><?php echo e($label); ?></p>
            <?php endif; ?>
            <h2 id="<?php echo e($id); ?>" class="text-2xl lg:text-3xl font-black text-neutral-900 tracking-tight <?php echo e($intro ? 'mb-3' : ''); ?>">
                <?php echo e($title); ?>

            </h2>
            <?php if($intro): ?>
                <p class="text-sm text-neutral-600 max-w-3xl leading-relaxed">
                    <?php echo e($intro); ?>

                </p>
            <?php endif; ?>
        </div>
        <?php if($hasAction): ?>
            <div class="shrink-0 hidden sm:block">
                <?php echo e($action); ?>

            </div>
        <?php endif; ?>
    </div>
    <?php if($hasAction): ?>
        <div class="sm:hidden mt-3">
            <?php echo e($action); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/section-heading.blade.php ENDPATH**/ ?>