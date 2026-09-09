
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="robots" content="noindex, nofollow">

    <?php echo $__env->yieldPushContent('meta'); ?>
    <?php if (! empty(trim($__env->yieldContent('title')))): ?>
        <?php echo $__env->yieldContent('title'); ?>
    <?php else: ?>
        <title><?php echo e(meta_title('Account')); ?></title>
    <?php endif; ?>

    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('images/favicon.svg')); ?>">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap"
          as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"></noscript>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>:root { <?php echo e(brand_theme_css_vars()); ?> }</style>
</head>

<body class="min-h-screen bg-neutral-50 text-neutral-900 antialiased flex flex-col">

    <header class="border-b border-neutral-200 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2.5 group" aria-label="<?php echo e(config('app.name')); ?> — home">
                <?php if (isset($component)) { $__componentOriginalc23ab0491158a0ac212e66d64f9d2c3b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc23ab0491158a0ac212e66d64f9d2c3b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.brand-mark','data' => ['size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.brand-mark'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc23ab0491158a0ac212e66d64f9d2c3b)): ?>
<?php $attributes = $__attributesOriginalc23ab0491158a0ac212e66d64f9d2c3b; ?>
<?php unset($__attributesOriginalc23ab0491158a0ac212e66d64f9d2c3b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc23ab0491158a0ac212e66d64f9d2c3b)): ?>
<?php $component = $__componentOriginalc23ab0491158a0ac212e66d64f9d2c3b; ?>
<?php unset($__componentOriginalc23ab0491158a0ac212e66d64f9d2c3b); ?>
<?php endif; ?>
            </a>
            <a href="<?php echo e(route('home')); ?>" class="text-sm text-neutral-600 hover:text-neutral-900 transition-colors">
                &larr; Back to site
            </a>
        </div>
    </header>

    <main id="main-content" class="flex-1 py-10 lg:py-14 px-6 lg:px-8" role="main">
        <?php echo e($slot); ?>

    </main>

    <footer class="border-t border-neutral-200 bg-white py-6 text-center text-xs text-neutral-500">
        <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. Secure account access.</p>
    </footer>

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
<?php /**PATH C:\laragon\www\fslc\resources\views/layouts/auth.blade.php ENDPATH**/ ?>