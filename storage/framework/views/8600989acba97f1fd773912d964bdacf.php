
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'size' => 'md', // sm | md | lg
    'showName' => true,
    'nameClass' => 'text-base font-bold text-neutral-900 hidden sm:block tracking-tight',
    'dark' => false,
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
    'size' => 'md', // sm | md | lg
    'showName' => true,
    'nameClass' => 'text-base font-bold text-neutral-900 hidden sm:block tracking-tight',
    'dark' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $logoUrl = site_brand_logo_url();
    $sizes = match ($size) {
        'sm' => 'w-7 h-7',
        'lg' => 'w-10 h-10',
        default => 'w-8 h-8',
    };
    $nameClass = $dark
        ? str_replace('text-neutral-900', 'text-white', $nameClass)
        : $nameClass;
?>

<?php if($logoUrl): ?>
    <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e(config('app.name')); ?>" class="<?php echo e($sizes); ?> object-contain flex-shrink-0" <?php echo e($attributes); ?>>
<?php else: ?>
    <div <?php echo e($attributes->merge(['class' => "{$sizes} bg-primary-600 flex items-center justify-center text-white font-black text-base flex-shrink-0"])); ?>>
        <?php echo e(site_brand_initial()); ?>

    </div>
<?php endif; ?>

<?php if($showName): ?>
    <span class="<?php echo e($nameClass); ?>"><?php echo e(config('app.name')); ?></span>
<?php endif; ?>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/brand-mark.blade.php ENDPATH**/ ?>