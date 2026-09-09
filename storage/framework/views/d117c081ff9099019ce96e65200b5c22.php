
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'subtitle' => null,
    'maxWidth' => 'max-w-md',
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
    'title',
    'subtitle' => null,
    'maxWidth' => 'max-w-md',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="<?php echo e($maxWidth); ?> mx-auto w-full">
    <div class="mb-8">
        <p class="section-label mb-2">Account</p>
        <h1 class="text-2xl lg:text-3xl font-black text-neutral-900 tracking-tight"><?php echo e($title); ?></h1>
        <?php if($subtitle): ?>
            <p class="text-sm text-neutral-600 mt-2 leading-relaxed"><?php echo e($subtitle); ?></p>
        <?php endif; ?>
    </div>

    <div class="card-base bg-white p-6 lg:p-8">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/auth/form-shell.blade.php ENDPATH**/ ?>