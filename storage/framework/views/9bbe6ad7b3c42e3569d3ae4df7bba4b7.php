
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'magnetType' => 'hmis-checklist',
    'showMagnet' => true,
    'title' => 'Ready to Digitize Your Hospital or Institution?',
    'subtitle' => 'Request an HMIS demo or speak with our team about software, branding, or campaign platforms.',
    'primaryUrl' => null,
    'primaryLabel' => 'Request HMIS Demo',
    'secondaryUrl' => null,
    'secondaryLabel' => 'Get HMIS Checklist',
    'showWhatsapp' => false,
    'size' => 'large',
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
    'magnetType' => 'hmis-checklist',
    'showMagnet' => true,
    'title' => 'Ready to Digitize Your Hospital or Institution?',
    'subtitle' => 'Request an HMIS demo or speak with our team about software, branding, or campaign platforms.',
    'primaryUrl' => null,
    'primaryLabel' => 'Request HMIS Demo',
    'secondaryUrl' => null,
    'secondaryLabel' => 'Get HMIS Checklist',
    'showWhatsapp' => false,
    'size' => 'large',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($showMagnet): ?>
    <?php if (isset($component)) { $__componentOriginal2fae09bc3adb515c52827818ad659cf5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2fae09bc3adb515c52827818ad659cf5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.lead-magnet-band','data' => ['type' => $magnetType]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.lead-magnet-band'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($magnetType)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2fae09bc3adb515c52827818ad659cf5)): ?>
<?php $attributes = $__attributesOriginal2fae09bc3adb515c52827818ad659cf5; ?>
<?php unset($__attributesOriginal2fae09bc3adb515c52827818ad659cf5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2fae09bc3adb515c52827818ad659cf5)): ?>
<?php $component = $__componentOriginal2fae09bc3adb515c52827818ad659cf5; ?>
<?php unset($__componentOriginal2fae09bc3adb515c52827818ad659cf5); ?>
<?php endif; ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.footer-cta','data' => ['title' => $title,'subtitle' => $subtitle,'primaryUrl' => $primaryUrl ?? hub_cta_url('page_footer_primary', 'hmis-demo'),'primaryLabel' => $primaryLabel,'secondaryUrl' => $secondaryUrl ?? hub_cta_url('page_footer_secondary', 'hmis-checklist'),'secondaryLabel' => $secondaryLabel,'showWhatsapp' => $showWhatsapp,'size' => $size,'attributes' => $attributes]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.footer-cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subtitle),'primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($primaryUrl ?? hub_cta_url('page_footer_primary', 'hmis-demo')),'primary-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($primaryLabel),'secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($secondaryUrl ?? hub_cta_url('page_footer_secondary', 'hmis-checklist')),'secondary-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($secondaryLabel),'show-whatsapp' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showWhatsapp),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($size),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8)): ?>
<?php $attributes = $__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8; ?>
<?php unset($__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8)): ?>
<?php $component = $__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8; ?>
<?php unset($__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/page-closer.blade.php ENDPATH**/ ?>