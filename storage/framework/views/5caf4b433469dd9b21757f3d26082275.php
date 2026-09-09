
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'hmis-checklist',
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
    'type' => 'hmis-checklist',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $magnet = lead_magnet($type, 'band');
?>

<section <?php echo e($attributes->merge(['class' => 'border-t border-primary-200 bg-primary-50'])); ?> aria-label="<?php echo e($magnet['title']); ?>">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 lg:py-12">
        <div class="grid lg:grid-cols-[1fr_auto] gap-6 lg:gap-10 items-center border-l-4 border-primary-500 pl-6 lg:pl-8">
            <div>
                <p class="section-label mb-2"><?php echo e($magnet['eyebrow']); ?></p>
                <h2 class="text-xl lg:text-2xl font-black text-neutral-900 tracking-tight mb-2">
                    <?php echo e($magnet['title']); ?>

                </h2>
                <p class="text-sm lg:text-base text-neutral-600 leading-relaxed max-w-2xl">
                    <?php echo e($magnet['text']); ?>

                </p>
            </div>
            <div class="shrink-0">
                <a href="<?php echo e($magnet['url']); ?>"
                   class="btn-primary text-sm uppercase tracking-wider"
                   data-cta="lead-magnet-band-<?php echo e($magnet['type']); ?>">
                    <?php echo e($magnet['button']); ?>

                    <?php if (isset($component)) { $__componentOriginal37a3f047daccd28b87517bd215a12923 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37a3f047daccd28b87517bd215a12923 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.arrow-right','data' => ['class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal37a3f047daccd28b87517bd215a12923)): ?>
<?php $attributes = $__attributesOriginal37a3f047daccd28b87517bd215a12923; ?>
<?php unset($__attributesOriginal37a3f047daccd28b87517bd215a12923); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal37a3f047daccd28b87517bd215a12923)): ?>
<?php $component = $__componentOriginal37a3f047daccd28b87517bd215a12923; ?>
<?php unset($__componentOriginal37a3f047daccd28b87517bd215a12923); ?>
<?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/lead-magnet-band.blade.php ENDPATH**/ ?>