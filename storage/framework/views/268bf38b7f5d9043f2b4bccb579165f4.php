
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'items' => [], // [ ['label' => 'Home', 'url' => '/'], ['label' => 'About'] ] — last item without url = current page
    'separator' => 'chevron', // 'chevron' | 'slash' | 'dot'
    'variant' => 'light', // 'light' | 'dark'
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
    'items' => [], // [ ['label' => 'Home', 'url' => '/'], ['label' => 'About'] ] — last item without url = current page
    'separator' => 'chevron', // 'chevron' | 'slash' | 'dot'
    'variant' => 'light', // 'light' | 'dark'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $separatorChar = match ($separator) {
        'slash' => '/',
        'dot' => '·',
        default => null, // chevron uses SVG
    };
    $isDark = $variant === 'dark';
    $linkClass = $isDark
        ? 'truncate py-2 px-2 -mx-2 text-neutral-400 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/50 transition-colors'
        : 'truncate py-2 px-2 -mx-2 rounded text-neutral-600 hover:text-primary-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400/50 focus-visible:ring-offset-1 transition-colors';
    $currentClass = $isDark
        ? 'truncate font-medium text-white py-2 px-2 -mx-2 line-clamp-2 md:truncate md:line-clamp-none'
        : 'truncate font-medium text-neutral-900 py-2 px-2 -mx-2 rounded';
    $sepClass = $isDark ? 'text-neutral-600' : 'text-neutral-400';
?>

<nav <?php echo e($attributes->merge(['class' => 'pb-4', 'aria-label' => 'Breadcrumb'])); ?>>
    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($index > 0): ?>
                <li class="flex items-center shrink-0 <?php echo e($sepClass); ?> select-none" aria-hidden="true">
                    <?php if($separatorChar): ?>
                        <span><?php echo e($separatorChar); ?></span>
                    <?php else: ?>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
            <li class="flex items-center min-w-0 <?php echo e($index > 0 ? 'shrink-0 max-w-[12rem] sm:max-w-none' : ''); ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <?php if(!empty($item['url'])): ?>
                    <a href="<?php echo e($item['url']); ?>"
                        class="<?php echo e($linkClass); ?>"
                        itemprop="item">
                        <span itemprop="name"><?php echo e($item['label']); ?></span>
                    </a>
                    <meta itemprop="position" content="<?php echo e($index + 1); ?>" />
                <?php else: ?>
                    <span class="<?php echo e($currentClass); ?>" aria-current="page" itemprop="name">
                        <?php echo e($item['label']); ?>

                    </span>
                    <meta itemprop="item" content="<?php echo e(url()->current()); ?>" />
                    <meta itemprop="position" content="<?php echo e($index + 1); ?>" />
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/breadcrumb.blade.php ENDPATH**/ ?>