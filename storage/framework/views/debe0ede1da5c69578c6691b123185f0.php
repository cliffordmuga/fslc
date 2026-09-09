
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'address' => null,
    'email' => null,
    'phone' => null,
    'whatsappMessage' => "Hi! I'd like to get in touch about a project.",
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
    'address' => null,
    'email' => null,
    'phone' => null,
    'whatsappMessage' => "Hi! I'd like to get in touch about a project.",
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="card-base border-t-4 border-primary-500 p-6 lg:p-8 bg-white" itemscope itemtype="https://schema.org/PostalAddress">
    <p class="section-label mb-2">Our Studio</p>
    <h3 class="text-xl font-black mb-4 text-neutral-900 tracking-tight">Contact Details</h3>
    <?php if($address): ?>
        <p class="text-sm text-neutral-600 mb-5 leading-relaxed" itemprop="streetAddress"><?php echo e($address); ?></p>
    <?php endif; ?>

    <div class="space-y-3 text-sm">
        <?php if($email): ?>
            <?php if (isset($component)) { $__componentOriginala62357e812019a65fd8af6b87ca239b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala62357e812019a65fd8af6b87ca239b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.contact-link','data' => ['type' => 'email','value' => $email,'class' => 'flex items-center gap-3 text-primary-600 hover:text-primary-700 transition-colors min-h-[44px]','label' => 'Send us an email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.contact-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($email),'class' => 'flex items-center gap-3 text-primary-600 hover:text-primary-700 transition-colors min-h-[44px]','label' => 'Send us an email']); ?>
                <div class="w-9 h-9 bg-primary-50 border border-primary-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                Email
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala62357e812019a65fd8af6b87ca239b2)): ?>
<?php $attributes = $__attributesOriginala62357e812019a65fd8af6b87ca239b2; ?>
<?php unset($__attributesOriginala62357e812019a65fd8af6b87ca239b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala62357e812019a65fd8af6b87ca239b2)): ?>
<?php $component = $__componentOriginala62357e812019a65fd8af6b87ca239b2; ?>
<?php unset($__componentOriginala62357e812019a65fd8af6b87ca239b2); ?>
<?php endif; ?>
        <?php endif; ?>

        <?php if($phone): ?>
            <?php if (isset($component)) { $__componentOriginala62357e812019a65fd8af6b87ca239b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala62357e812019a65fd8af6b87ca239b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.contact-link','data' => ['type' => 'tel','value' => $phone,'class' => 'flex items-center gap-3 text-primary-600 hover:text-primary-700 transition-colors min-h-[44px]','label' => 'Call us']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.contact-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'tel','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($phone),'class' => 'flex items-center gap-3 text-primary-600 hover:text-primary-700 transition-colors min-h-[44px]','label' => 'Call us']); ?>
                <div class="w-9 h-9 bg-primary-50 border border-primary-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                Phone
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala62357e812019a65fd8af6b87ca239b2)): ?>
<?php $attributes = $__attributesOriginala62357e812019a65fd8af6b87ca239b2; ?>
<?php unset($__attributesOriginala62357e812019a65fd8af6b87ca239b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala62357e812019a65fd8af6b87ca239b2)): ?>
<?php $component = $__componentOriginala62357e812019a65fd8af6b87ca239b2; ?>
<?php unset($__componentOriginala62357e812019a65fd8af6b87ca239b2); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala62357e812019a65fd8af6b87ca239b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala62357e812019a65fd8af6b87ca239b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.contact-link','data' => ['type' => 'whatsapp','value' => $phone,'message' => $whatsappMessage,'class' => 'flex items-center gap-3 text-green-700 hover:text-green-800 transition-colors min-h-[44px] font-medium','label' => 'WhatsApp']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.contact-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'whatsapp','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($phone),'message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($whatsappMessage),'class' => 'flex items-center gap-3 text-green-700 hover:text-green-800 transition-colors min-h-[44px] font-medium','label' => 'WhatsApp']); ?>
                <div class="w-9 h-9 bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0012.05 0z" />
                    </svg>
                </div>
                WhatsApp
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala62357e812019a65fd8af6b87ca239b2)): ?>
<?php $attributes = $__attributesOriginala62357e812019a65fd8af6b87ca239b2; ?>
<?php unset($__attributesOriginala62357e812019a65fd8af6b87ca239b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala62357e812019a65fd8af6b87ca239b2)): ?>
<?php $component = $__componentOriginala62357e812019a65fd8af6b87ca239b2; ?>
<?php unset($__componentOriginala62357e812019a65fd8af6b87ca239b2); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/contact-info-card.blade.php ENDPATH**/ ?>