
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'route' => route('contact.store'),
    'leadSource' => null,
    'inquiryType' => 'general',
    'services' => collect(),
    'preselectedServiceId' => null,
    'enableMini' => false,
    'singleStep' => false,
    'hideHeader' => false,
    'requireService' => true,
    'hideServiceWhenPreselected' => false,
    'title' => 'Start Your Project',
    'description' => 'Get in touch for a free consultation',
    'messageLabel' => 'Project Details',
    'messagePlaceholder' => 'Tell us about your project goals, timeline, and budget...',
    'submitLabel' => 'Send Message',
    'enableUpload' => false,
    'showUpload' => null,
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
    'route' => route('contact.store'),
    'leadSource' => null,
    'inquiryType' => 'general',
    'services' => collect(),
    'preselectedServiceId' => null,
    'enableMini' => false,
    'singleStep' => false,
    'hideHeader' => false,
    'requireService' => true,
    'hideServiceWhenPreselected' => false,
    'title' => 'Start Your Project',
    'description' => 'Get in touch for a free consultation',
    'messageLabel' => 'Project Details',
    'messagePlaceholder' => 'Tell us about your project goals, timeline, and budget...',
    'submitLabel' => 'Send Message',
    'enableUpload' => false,
    'showUpload' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $formAction = filled($route) ? $route : route('contact.store');
    $useUpload = $showUpload ?? $enableUpload;
    $useSingleStep = $singleStep || $enableMini;
    $showServiceField = $requireService || ! $hideServiceWhenPreselected;
    $hideServiceSelect = $hideServiceWhenPreselected && $preselectedServiceId;
?>

<form method="POST" action="<?php echo e($formAction); ?>"
    <?php echo e($attributes->merge(['class' => 'space-y-5 w-full'])); ?>

    x-data="contactFormApp(<?php echo \Illuminate\Support\Js::from([
        'singleStep' => $useSingleStep,
        'requireService' => $requireService,
        'inquiryType' => $inquiryType,
        'formAction' => $formAction,
        'leadEventsUrl' => route('lead-events.store'),
        'successRedirect' => route('contact') . '?utm_source=form_success&utm_medium=lead&inquiry_type=' . $inquiryType,
        'preselectedServiceId' => $preselectedServiceId,
    ])->toHtml() ?>)"
    x-trap="step === 2 && !singleStep"
    @submit.prevent="submitForm($event)"
    enctype="multipart/form-data"
    aria-labelledby="<?php echo e($hideHeader ? 'contact-form-heading' : 'form-title'); ?>">

    <?php echo csrf_field(); ?>
    <input type="hidden" name="source_content_id" value="<?php echo e($leadSource?->id ?? ''); ?>">
    <input type="hidden" name="inquiry_type" value="<?php echo e($inquiryType); ?>">
    <input type="hidden" name="utm_source" value="<?php echo e(request()->query('utm_source', 'direct')); ?>">
    <input type="hidden" name="utm_medium" value="<?php echo e(request()->query('utm_medium', 'organic')); ?>">
    <input type="hidden" name="_form_rendered_at" value="<?php echo e(time()); ?>">

    <?php if (! ($hideHeader)): ?>
        <div class="mb-6">
            <h3 id="form-title" class="text-xl font-black text-neutral-900 mb-2 tracking-tight"><?php echo e($title); ?></h3>
            <p class="text-sm text-neutral-600 leading-relaxed"><?php echo e($description); ?></p>
        </div>
    <?php endif; ?>

    <?php if(! $useSingleStep): ?>
        <div class="flex items-center justify-between mb-6 motion-reduce:transition-none" x-show="step === 1 || step === 2">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 flex items-center justify-center text-sm font-bold border-2 min-h-[36px]"
                    :class="step >= 1 ? 'bg-primary-600 text-white border-primary-600' : 'bg-neutral-100 text-neutral-500 border-neutral-200'">1</div>
                <span class="text-sm font-medium" :class="step >= 1 ? 'text-primary-600' : 'text-neutral-500'">Contact</span>
            </div>
            <div class="flex-1 h-px mx-4 bg-neutral-200">
                <div class="h-full bg-primary-500 transition-all duration-300 motion-reduce:transition-none" :style="`width: ${step >= 2 ? '100%' : '0%'}`"></div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 flex items-center justify-center text-sm font-bold border-2 min-h-[36px]"
                    :class="step >= 2 ? 'bg-primary-600 text-white border-primary-600' : 'bg-neutral-100 text-neutral-500 border-neutral-200'">2</div>
                <span class="text-sm font-medium" :class="step >= 2 ? 'text-primary-600' : 'text-neutral-500'">Details</span>
            </div>
        </div>
    <?php endif; ?>

    
    <div x-show="Object.values(errors).some(v => v)" role="alert" aria-live="assertive"
        class="border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
        <p class="font-semibold mb-1">Please correct the following:</p>
        <ul class="list-disc list-inside space-y-0.5">
            <template x-for="(msg, field) in errors" :key="field">
                <li x-show="msg" x-text="msg"></li>
            </template>
        </ul>
    </div>

    
    <div x-show="singleStep || step === 1" x-transition.opacity.duration.200ms class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-neutral-700 mb-1.5">Email Address *</label>
            <input type="email" id="email" name="email" x-model="formData.email" required
                class="input-base w-full px-4 py-3 text-sm border border-neutral-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/30 transition-colors"
                placeholder="your.email@hospital.or.ke" @input="errors.email = ''" aria-describedby="email-error" />
            <p id="email-error" x-show="errors.email" x-text="errors.email" class="text-red-600 text-xs mt-1" role="alert"></p>
        </div>

        <?php if(! $useSingleStep): ?>
            <button type="button" @click="step = 2; trackLeadEvent('lead_step_reached', 'details')" :disabled="!formData.email"
                class="w-full btn-primary disabled:opacity-50 disabled:cursor-not-allowed min-h-[48px] text-sm font-bold uppercase tracking-wider">
                Continue &rarr;
            </button>
        <?php endif; ?>
    </div>

    
    <div x-show="singleStep || step === 2" x-transition.opacity.duration.200ms class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-neutral-700 mb-1.5">Full Name *</label>
            <input type="text" id="name" name="name" x-model="formData.name" required
                class="input-base w-full px-4 py-3 text-sm border border-neutral-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/30"
                placeholder="Your full name" />
            <p x-show="errors.name" x-text="errors.name" class="text-red-600 text-xs mt-1" role="alert"></p>
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-neutral-700 mb-1.5">Phone Number</label>
            <input type="tel" id="phone" name="phone" x-model="formData.phone"
                class="input-base w-full px-4 py-3 text-sm border border-neutral-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/30"
                placeholder="+254 700 000 000" />
        </div>

        <?php if($useUpload): ?>
            <div>
                <label for="file" class="block text-sm font-medium text-neutral-700 mb-1.5">Attach File (Optional)</label>
                <input type="file" id="file" name="file" x-ref="file" @change="formData.file = $event.target.files[0]"
                    class="input-base w-full px-4 py-3 text-sm border border-neutral-200 file:mr-3 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700"
                    accept=".pdf,.doc,.docx,.jpg,.png" />
            </div>
        <?php endif; ?>

        <?php if($hideServiceSelect && $preselectedServiceId): ?>
            <input type="hidden" name="service_content_id" value="<?php echo e($preselectedServiceId); ?>">
        <?php elseif($showServiceField && $services->isNotEmpty()): ?>
            <div>
                <label for="service_content_id" class="block text-sm font-medium text-neutral-700 mb-1.5">
                    Service<?php echo e($requireService ? ' *' : ''); ?>

                </label>
                <select id="service_content_id" name="service_content_id" x-model="formData.service_content_id"
                    <?php if($requireService): ?> required <?php endif; ?>
                    class="input-base w-full px-4 py-3 text-sm border border-neutral-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/30 appearance-none bg-white">
                    <option value="">Select a service</option>
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($service->id); ?>"><?php echo e($service->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p x-show="errors.service_content_id" x-text="errors.service_content_id" class="text-red-600 text-xs mt-1" role="alert"></p>
            </div>
        <?php endif; ?>

        <div>
            <label for="message" class="block text-sm font-medium text-neutral-700 mb-1.5"><?php echo e($messageLabel); ?><?php echo e($inquiryType === 'hmis-checklist' ? '' : ' *'); ?></label>
            <textarea id="message" name="message" x-model="formData.message"
                <?php if (! ($inquiryType === 'hmis-checklist')): ?> required <?php endif; ?>
                rows="<?php echo e($inquiryType === 'hmis-checklist' ? 3 : 4); ?>"
                class="input-base w-full px-4 py-3 text-sm border border-neutral-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/30 resize-none"
                placeholder="<?php echo e($messagePlaceholder); ?>"></textarea>
            <p x-show="errors.message" x-text="errors.message" class="text-red-600 text-xs mt-1" role="alert"></p>
        </div>

        <div class="hidden" aria-hidden="true">
            <label for="website_url">Leave empty</label>
            <input type="text" id="website_url" name="website_url" value="" tabindex="-1" autocomplete="off" />
        </div>
        <div class="sr-only" aria-hidden="true">
            <label for="company_website">Company website (leave blank)</label>
            <input type="url" id="company_website" name="company_website" value="" tabindex="-1" autocomplete="off" />
        </div>

        <div x-show="errors.general" class="border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm" role="alert" x-text="errors.general"></div>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <?php if(! $useSingleStep): ?>
                <button type="button" @click="step = 1"
                    class="flex-1 py-3 px-5 border border-neutral-300 text-neutral-700 text-sm font-semibold hover:bg-neutral-50 min-h-[48px]">
                    &larr; Back
                </button>
            <?php endif; ?>
            <button type="submit" :disabled="loading"
                class="flex-1 btn-primary disabled:opacity-50 disabled:cursor-not-allowed min-h-[48px] text-sm font-bold uppercase tracking-wider">
                <span x-show="!loading"><?php echo e($submitLabel); ?></span>
                <span x-show="loading">Sending...</span>
            </button>
        </div>
    </div>

    <div class="pt-4 border-t border-neutral-100 flex flex-wrap gap-4 text-xs text-neutral-500 uppercase tracking-wider">
        <span class="inline-flex items-center gap-1.5"><span class="text-primary-600">&#10003;</span> No spam</span>
        <span class="inline-flex items-center gap-1.5"><span class="text-primary-600">&#10003;</span> Reply within 24h</span>
    </div>
</form>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/contact-form.blade.php ENDPATH**/ ?>