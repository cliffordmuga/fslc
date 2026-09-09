
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title'          => 'Ready to get started?',
    'subtitle'       => null,
    'primaryUrl'     => null,
    'primaryLabel'   => 'Book a Free Consultation',
    'primaryClass'   => null,
    'secondaryUrl'   => null,
    'secondaryLabel' => null,
    'size'           => 'default',
    'showWhatsapp'   => false,
    'whatsappSource' => null,
    'whatsappLabel'  => 'Chat on WhatsApp',
    'maxWidth'       => 'max-w-5xl',
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
    'title'          => 'Ready to get started?',
    'subtitle'       => null,
    'primaryUrl'     => null,
    'primaryLabel'   => 'Book a Free Consultation',
    'primaryClass'   => null,
    'secondaryUrl'   => null,
    'secondaryLabel' => null,
    'size'           => 'default',
    'showWhatsapp'   => false,
    'whatsappSource' => null,
    'whatsappLabel'  => 'Chat on WhatsApp',
    'maxWidth'       => 'max-w-5xl',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $btnClass = $primaryClass ?? 'inline-flex items-center gap-2 bg-white text-primary-800 hover:bg-neutral-100 font-semibold px-8 py-4 text-sm min-h-[48px] transition-colors duration-200';
    $secondaryBtnClass = 'inline-flex items-center gap-2 border border-neutral-500 text-white hover:bg-neutral-800 font-semibold px-8 py-4 text-sm min-h-[48px] transition-colors duration-200';
?>

<section id="contact"
    <?php echo e($attributes->merge(['class' => 'py-10 lg:py-14 bg-neutral-900 border-t-4 border-primary-500'])); ?>

    aria-labelledby="footer-cta-heading">

    <div class="<?php echo e($maxWidth); ?> mx-auto px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

            
            <div>
                <h2 id="footer-cta-heading"
                    class="text-2xl lg:text-3xl font-black text-white tracking-tight leading-tight">
                    <?php echo e($title); ?>

                </h2>
                <?php if($subtitle): ?>
                    <p class="text-sm text-neutral-400 mt-2 max-w-xl leading-relaxed">
                        <?php echo e($subtitle); ?>

                    </p>
                <?php endif; ?>
            </div>

            
            <?php if(isset($actions) && trim((string) $actions) !== ''): ?>
                <?php echo e($actions); ?>

            <?php else: ?>
                <div class="flex flex-wrap gap-3 lg:flex-shrink-0">
                    <?php if($primaryUrl): ?>
                        <a href="<?php echo e($primaryUrl); ?>" class="<?php echo e($btnClass); ?>">
                            <?php echo e($primaryLabel); ?>

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
                    <?php endif; ?>
                    <?php if($secondaryUrl): ?>
                        <a href="<?php echo e($secondaryUrl); ?>" class="<?php echo e($secondaryBtnClass); ?>">
                            <?php echo e($secondaryLabel ?? 'Learn More'); ?>

                        </a>
                    <?php endif; ?>
                    <?php if($showWhatsapp): ?>
                        <?php if (isset($component)) { $__componentOriginal19de87f30393c669728662186f56b104 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal19de87f30393c669728662186f56b104 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.whatsapp-cta','data' => ['variant' => 'block','source' => $whatsappSource,'label' => $whatsappLabel,'class' => '!w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.whatsapp-cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'block','source' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($whatsappSource),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($whatsappLabel),'class' => '!w-auto']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal19de87f30393c669728662186f56b104)): ?>
<?php $attributes = $__attributesOriginal19de87f30393c669728662186f56b104; ?>
<?php unset($__attributesOriginal19de87f30393c669728662186f56b104); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal19de87f30393c669728662186f56b104)): ?>
<?php $component = $__componentOriginal19de87f30393c669728662186f56b104; ?>
<?php unset($__componentOriginal19de87f30393c669728662186f56b104); ?>
<?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/footer-cta.blade.php ENDPATH**/ ?>