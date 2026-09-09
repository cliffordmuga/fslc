
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">

<head>
    <?php echo $__env->make('components.layout.site-head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<body class="flex flex-col min-h-screen bg-white text-neutral-900 antialiased pb-[calc(var(--sticky-bar-h)+env(safe-area-inset-bottom,0px))] lg:pb-0">

    <div id="reading-progress"
         class="fixed top-0 inset-x-0 z-[300] h-[2px] bg-transparent pointer-events-none"
         aria-hidden="true">
        <div id="reading-progress-bar" class="h-full bg-primary-500 w-0"></div>
    </div>

    <?php if(config('forefront.show_preloader')): ?>
    <div id="preloader" aria-hidden="true"
         class="fixed inset-0 z-[200] flex items-center justify-center bg-white transition-opacity duration-300 motion-reduce:hidden"
         role="presentation">
        <div class="flex flex-col items-center gap-3">
            <div class="w-10 h-10 bg-primary-600 flex items-center justify-center text-white font-black text-xl">
                <?php echo e(substr(config('app.name', env('APP_NAME', 'App')), 0, 1)); ?>

            </div>
            <div class="w-6 h-6 border-2 border-neutral-200 border-t-primary-600 rounded-full animate-spin"></div>
        </div>
    </div>
    <?php endif; ?>

    <a href="#main-content"
       class="sr-only focus:not-sr-only fixed top-2 left-2 z-[100] bg-primary-600 text-white px-4 py-2 text-sm font-semibold">
        Skip to main content
    </a>

    <?php if (isset($component)) { $__componentOriginalc507408747e62fee25db52f7b60baa49 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc507408747e62fee25db52f7b60baa49 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.site-nav','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.site-nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc507408747e62fee25db52f7b60baa49)): ?>
<?php $attributes = $__attributesOriginalc507408747e62fee25db52f7b60baa49; ?>
<?php unset($__attributesOriginalc507408747e62fee25db52f7b60baa49); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc507408747e62fee25db52f7b60baa49)): ?>
<?php $component = $__componentOriginalc507408747e62fee25db52f7b60baa49; ?>
<?php unset($__componentOriginalc507408747e62fee25db52f7b60baa49); ?>
<?php endif; ?>

    <main id="main-content" class="flex-1 pt-14 lg:pt-16" role="main">
        <?php echo $__env->yieldContent('content'); ?>
        <?php echo e($slot ?? ''); ?>

    </main>

    <?php if (isset($component)) { $__componentOriginal5e8ea8fda17cd0f61d55aa678718e05e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5e8ea8fda17cd0f61d55aa678718e05e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.site-footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.site-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5e8ea8fda17cd0f61d55aa678718e05e)): ?>
<?php $attributes = $__attributesOriginal5e8ea8fda17cd0f61d55aa678718e05e; ?>
<?php unset($__attributesOriginal5e8ea8fda17cd0f61d55aa678718e05e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5e8ea8fda17cd0f61d55aa678718e05e)): ?>
<?php $component = $__componentOriginal5e8ea8fda17cd0f61d55aa678718e05e; ?>
<?php unset($__componentOriginal5e8ea8fda17cd0f61d55aa678718e05e); ?>
<?php endif; ?>

    <div class="hidden lg:block fixed bottom-6 right-6 z-[100]">
        <?php if (isset($component)) { $__componentOriginal19de87f30393c669728662186f56b104 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal19de87f30393c669728662186f56b104 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.whatsapp-cta','data' => ['variant' => 'fab','label' => 'Chat on WhatsApp']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.whatsapp-cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'fab','label' => 'Chat on WhatsApp']); ?>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
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
    </div>

    <?php if (isset($component)) { $__componentOriginal50b48c960d0dc9606e39fe7c6435d259 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50b48c960d0dc9606e39fe7c6435d259 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.sticky-contact-bar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.sticky-contact-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal50b48c960d0dc9606e39fe7c6435d259)): ?>
<?php $attributes = $__attributesOriginal50b48c960d0dc9606e39fe7c6435d259; ?>
<?php unset($__attributesOriginal50b48c960d0dc9606e39fe7c6435d259); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal50b48c960d0dc9606e39fe7c6435d259)): ?>
<?php $component = $__componentOriginal50b48c960d0dc9606e39fe7c6435d259; ?>
<?php unset($__componentOriginal50b48c960d0dc9606e39fe7c6435d259); ?>
<?php endif; ?>

    <button id="back-to-top"
            type="button"
            aria-label="Back to top"
            class="fixed bottom-20 right-3 lg:bottom-6 lg:left-6 lg:right-auto z-[90]
                   w-11 h-11 min-w-[44px] min-h-[44px] bg-neutral-900 text-white flex items-center justify-center
                   opacity-0 pointer-events-none transition-opacity duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
        </svg>
    </button>

    <?php if (isset($component)) { $__componentOriginalc8e4644d31509019c822192705efbd38 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8e4644d31509019c822192705efbd38 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.flash-messages','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8e4644d31509019c822192705efbd38)): ?>
<?php $attributes = $__attributesOriginalc8e4644d31509019c822192705efbd38; ?>
<?php unset($__attributesOriginalc8e4644d31509019c822192705efbd38); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8e4644d31509019c822192705efbd38)): ?>
<?php $component = $__componentOriginalc8e4644d31509019c822192705efbd38; ?>
<?php unset($__componentOriginalc8e4644d31509019c822192705efbd38); ?>
<?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\fslc\resources\views/layouts/guest.blade.php ENDPATH**/ ?>