
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'cta' => null,
    'variant' => 'primary',
    'size' => 'md',
    'url' => null,
    'fullWidth' => false,
    'icon' => null,
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
    'cta' => null,
    'variant' => 'primary',
    'size' => 'md',
    'url' => null,
    'fullWidth' => false,
    'icon' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $effectiveCta =
        $cta ?:
        (object) [
            'text' => 'Request HMIS Demo',
            'action' => $url ?? route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form',
            'type' => 'link',
            'impressions' => 0,
            'clicks' => 0,
            'conversions' => 0,
            'priority' => 1,
        ];

    $variantClass = match ($variant) {
        'secondary', 'dark' => 'btn-dark',
        'outline' => 'btn-outline',
        'ghost', 'ghost-dark' => 'btn-ghost-dark',
        default => 'btn-primary',
    };

    $sizeClass = match ($size) {
        'sm' => 'text-sm px-5 py-2.5 min-h-[42px]',
        'lg' => 'text-lg px-9 py-4 min-h-[56px]',
        default => 'text-base px-7 py-3.5 min-h-[48px]',
    };

    $buttonClasses = trim(implode(' ', [
        $variantClass,
        $sizeClass,
        $fullWidth ? 'w-full' : '',
        'group',
    ]));

    $actionUrl = $url ?? ($effectiveCta->action ?? route('contact'));
    if (!filled($actionUrl)) {
        $actionUrl = route('contact');
    }
    $finalUrl = generate_utm_url($actionUrl, 'cta_button_' . date('Y'), 'button', 'lead_gen');
    $isExternal = filter_var($finalUrl, FILTER_VALIDATE_URL) && !str_starts_with($finalUrl, url('/'));
?>

<?php if(filter_var($effectiveCta->action ?? $actionUrl, FILTER_VALIDATE_URL) || $effectiveCta->type === 'link'): ?>
    <a href="<?php echo e($finalUrl); ?>" <?php echo e($attributes->merge(['class' => $buttonClasses])); ?>

        data-ga-event="<?php echo e($effectiveCta->text ?? 'cta_click'); ?>" itemprop="url"
        aria-label="<?php echo e($effectiveCta->text ?? 'Click to learn more'); ?>" <?php if($isExternal): ?> rel="noopener noreferrer" <?php endif; ?>>
        <?php if($icon): ?>
            <x-heroicon-<?php echo e($icon); ?> class="w-4 h-4 mr-2 flex-shrink-0" />
        <?php endif; ?>
        <span><?php echo e($effectiveCta->text); ?></span>
    </a>
<?php else: ?>
    <button type="<?php echo e($effectiveCta->type ?? 'button'); ?>" <?php echo e($attributes->merge(['class' => $buttonClasses])); ?>

        data-ga-event="<?php echo e($effectiveCta->text ?? 'cta_click'); ?>"
        aria-label="<?php echo e($effectiveCta->text ?? 'Click to learn more'); ?>">
        <?php if($icon): ?>
            <x-heroicon-<?php echo e($icon); ?> class="w-4 h-4 mr-2 flex-shrink-0" />
        <?php endif; ?>
        <span><?php echo e($effectiveCta->text); ?></span>
    </button>
<?php endif; ?>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/cta-button.blade.php ENDPATH**/ ?>