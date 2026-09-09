
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type', // email|tel|whatsapp
    'value',
    'label' => null,
    'class' => 'flex items-center hover:text-white transition-colors',
    'message' => null, // optional for whatsapp
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
    'type', // email|tel|whatsapp
    'value',
    'label' => null,
    'class' => 'flex items-center hover:text-white transition-colors',
    'message' => null, // optional for whatsapp
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $encoded = obfuscate_contact((string) $value);

    $label =
        $label ??
        match ($type) {
            'email' => 'Send us an email',
            'tel' => 'Call us',
            'whatsapp' => 'Chat on WhatsApp',
            default => 'Contact',
        };

    $encodedMessage = filled($message) ? obfuscate_contact($message) : null;

    // Build optional attributes safely (no Blade directives)
    $extraAttrs = [];
    if ($type === 'whatsapp' && $encodedMessage) {
        $extraAttrs['data-message'] = $encodedMessage;
    }
?>

<span class="protected-contact <?php echo e($class); ?>" data-type="<?php echo e($type); ?>" data-value="<?php echo e($encoded); ?>"
    aria-label="<?php echo e($label); ?>" role="link" tabindex="0"
    <?php $__currentLoopData = $extraAttrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e($k); ?>="<?php echo e($v); ?>" <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
    <?php echo e($slot); ?>

</span>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/contact-link.blade.php ENDPATH**/ ?>