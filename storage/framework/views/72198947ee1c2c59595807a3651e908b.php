
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
    'size' => 'md',
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
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
    'size' => 'md',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $variantClass = match ($variant) {
        'secondary' => 'btn-secondary',
        'outline' => 'btn-outline',
        'dark' => 'btn-dark',
        'ghost-dark', 'ghost' => 'btn-ghost-dark',
        'danger' => 'btn-danger',
        default => 'btn-primary',
    };
    $sizeClass = match ($size) {
        'sm' => 'text-xs px-5 py-2.5 min-h-0',
        'lg' => 'text-sm px-8 py-4',
        default => '',
    };
    $classes = trim("{$variantClass} {$sizeClass}");
?>

<?php if($href): ?>
    <a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => $classes])); ?>>
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button type="<?php echo e($type); ?>" <?php echo e($attributes->merge(['class' => $classes])); ?>>
        <?php echo e($slot); ?>

    </button>
<?php endif; ?>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/ui/button.blade.php ENDPATH**/ ?>