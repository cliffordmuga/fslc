
<?php
    $navItems = [
        ['route' => 'home', 'active' => ['home'], 'label' => 'Home'],
        ['route' => 'about', 'active' => ['about', 'mission', 'vision', 'intro'], 'label' => 'About'],
        ['route' => 'portfolio.index', 'active' => ['portfolio.index', 'portfolio.show'], 'label' => 'Portfolio'],
        ['route' => 'services.index', 'active' => ['services.index', 'services.show'], 'label' => 'Services'],
        ['route' => 'insights.index', 'active' => ['insights.index', 'insights.show', 'blog.index', 'blog.show'], 'label' => 'Insights'],
        ['route' => 'contact', 'active' => ['contact', 'contact.store', 'leads.store'], 'label' => 'Contact'],
    ];
?>

<nav x-data="{ open: false, search: false, scrolled: false }"
     @scroll.window.debounce.10="scrolled = window.scrollY > 30"
     :class="scrolled ? 'nav-scrolled' : 'nav-default'"
     class="fixed top-0 inset-x-0 z-50"
     aria-label="Primary">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14 lg:h-16">
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2.5 group" aria-label="<?php echo e(config('app.name', env('APP_NAME', 'App'))); ?>">
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
                <span class="hidden lg:block text-xs text-neutral-500 font-medium border-l border-neutral-200 pl-2.5 ml-0.5">
                    HMIS · Software · Kenya
                </span>
            </a>

            <div class="hidden lg:flex items-center">
                <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $active = request()->routeIs($item['active']); ?>
                    <a href="<?php echo e(route($item['route'])); ?>"
                       class="px-4 py-5 text-sm font-medium transition-colors duration-200 border-b-2
                           <?php echo e($active
                               ? 'text-primary-600 border-primary-600'
                               : 'text-neutral-600 hover:text-neutral-900 border-transparent hover:border-neutral-300'); ?>">
                        <?php echo e($item['label']); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="hidden lg:flex items-center gap-2">
                <button @click="search = !search" aria-label="Search"
                        class="inline-flex items-center justify-center min-h-[44px] min-w-[44px] p-2 text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100 transition-colors"
                        :class="{ 'bg-neutral-100 text-neutral-900': search }">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="text-sm text-neutral-600 hover:text-neutral-900 px-3 py-2 transition-colors">Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-sm text-neutral-600 hover:text-neutral-900 px-3 py-2 transition-colors">Sign In</a>
                    <a href="<?php echo e(nav_primary_cta_url()); ?>" class="btn-primary text-xs px-5 py-2.5"><?php echo e(config('forefront.nav_primary_cta.label', 'Request HMIS Demo')); ?></a>
                <?php endif; ?>
            </div>

            <div class="lg:hidden flex items-center gap-1.5">
                <button @click="search = !search" aria-label="Search"
                        class="inline-flex items-center justify-center min-h-[44px] min-w-[44px] p-2 text-neutral-500 hover:text-neutral-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
                <button @click="open = !open" :aria-expanded="open" aria-label="Menu"
                        class="inline-flex items-center justify-center min-h-[44px] min-w-[44px] p-2 text-neutral-500 hover:text-neutral-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="search" x-transition
         class="border-t border-neutral-200 bg-white"
         @click.away="search = false" @keydown.escape.window="search = false">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <form action="<?php echo e(route('search')); ?>" method="GET" class="flex gap-2 max-w-2xl">
                <input type="search" name="q" placeholder="Search projects, services, insights…"
                       autocomplete="off" required autofocus
                       class="flex-1 input-base text-sm">
                <button type="submit" class="btn-primary text-sm px-5 py-2.5 min-h-0">Search</button>
            </form>
        </div>
    </div>

    <div x-show="open" x-transition
         class="lg:hidden border-t border-neutral-200 bg-white shadow-md"
         role="menu">
        <div class="divide-y divide-neutral-100">
            <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $active = request()->routeIs($item['active']); ?>
                <a href="<?php echo e(route($item['route'])); ?>"
                   class="flex items-center px-5 py-3.5 text-sm font-medium transition-colors border-l-2
                       <?php echo e($active
                           ? 'text-primary-600 bg-primary-50 border-primary-600'
                           : 'text-neutral-700 hover:bg-neutral-50 border-transparent'); ?>"
                   @click="open = false" role="menuitem">
                    <?php echo e($item['label']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="px-5 py-4 flex flex-col gap-2">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn-secondary text-sm py-2.5" @click="open = false">Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(nav_primary_cta_url()); ?>" class="btn-primary text-sm text-center py-2.5" @click="open = false"><?php echo e(config('forefront.nav_primary_cta.label', 'Request HMIS Demo')); ?></a>
                    <a href="<?php echo e(route('login')); ?>" class="btn-secondary text-sm text-center py-2.5" @click="open = false">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/layout/site-nav.blade.php ENDPATH**/ ?>