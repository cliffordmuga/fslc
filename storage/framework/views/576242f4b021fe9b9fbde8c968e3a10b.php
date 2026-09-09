
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'breadcrumb' => [],
    'heroPreload' => null,
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
    'breadcrumb' => [],
    'heroPreload' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($heroPreload): ?>
    <?php $__env->startPush('head'); ?>
        <link rel="preload" href="<?php echo e($heroPreload); ?>" as="image">
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php if(isset($hero)): ?>
    <?php echo e($hero); ?>

<?php endif; ?>

<?php if(! empty($breadcrumb)): ?>
    <div class="bg-white border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <?php if (isset($component)) { $__componentOriginal845656e97179b8317ed324815f9732c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal845656e97179b8317ed324815f9732c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.breadcrumb','data' => ['items' => $breadcrumb]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($breadcrumb)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal845656e97179b8317ed324815f9732c3)): ?>
<?php $attributes = $__attributesOriginal845656e97179b8317ed324815f9732c3; ?>
<?php unset($__attributesOriginal845656e97179b8317ed324815f9732c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal845656e97179b8317ed324815f9732c3)): ?>
<?php $component = $__componentOriginal845656e97179b8317ed324815f9732c3; ?>
<?php unset($__componentOriginal845656e97179b8317ed324815f9732c3); ?>
<?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if(isset($stats)): ?>
    <?php echo e($stats); ?>

<?php endif; ?>

<?php echo e($slot); ?>

<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/hub-shell.blade.php ENDPATH**/ ?>