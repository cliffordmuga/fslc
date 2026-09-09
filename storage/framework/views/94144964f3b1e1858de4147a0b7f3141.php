
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'info',
    'title' => null,
    'dismissible' => false,
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
    'type' => 'info',
    'title' => null,
    'dismissible' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $styles = match ($type) {
        'success' => [
            'wrap' => 'border-l-4 border-green-500 bg-green-50 text-green-900',
            'title' => 'text-green-900',
            'body' => 'text-green-800',
        ],
        'error', 'danger' => [
            'wrap' => 'border-l-4 border-red-500 bg-red-50 text-red-900',
            'title' => 'text-red-900',
            'body' => 'text-red-800',
        ],
        'warning' => [
            'wrap' => 'border-l-4 border-amber-500 bg-amber-50 text-amber-900',
            'title' => 'text-amber-900',
            'body' => 'text-amber-800',
        ],
        default => [
            'wrap' => 'border-l-4 border-primary-500 bg-primary-50 text-neutral-900',
            'title' => 'text-neutral-900',
            'body' => 'text-neutral-700',
        ],
    };
?>

<div
    <?php echo e($attributes->merge([
        'class' => "px-4 py-3 text-sm {$styles['wrap']}",
        'role' => $type === 'error' || $type === 'danger' ? 'alert' : 'status',
    ])); ?>

    <?php if($type === 'error' || $type === 'danger'): ?> aria-live="assertive" <?php else: ?> aria-live="polite" <?php endif; ?>
>
    <?php if($title): ?>
        <p class="font-semibold mb-1 <?php echo e($styles['title']); ?>"><?php echo e($title); ?></p>
    <?php endif; ?>
    <div class="<?php echo e($styles['body']); ?>">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/ui/alert.blade.php ENDPATH**/ ?>