

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => 'Chat on WhatsApp',
    'message' => null,
    'phone' => setting('phone', '254700000000'),
    'variant' => 'inline', // inline | block | fab
    'protected' => true,
    'class' => '',

    'withIcon' => true,
    'theme' => 'auto', // auto | light | dark
    'source' => null,
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
    'label' => 'Chat on WhatsApp',
    'message' => null,
    'phone' => setting('phone', '254700000000'),
    'variant' => 'inline', // inline | block | fab
    'protected' => true,
    'class' => '',

    'withIcon' => true,
    'theme' => 'auto', // auto | light | dark
    'source' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $cleanPhone = preg_replace('/\D+/', '', (string) $phone);

    // --- Source detection (route-based fallback) ---
    $routeName = request()->route()?->getName();
    $autoSource = match (true) {
        str_starts_with((string) $routeName, 'about') => 'about',
        str_starts_with((string) $routeName, 'services') => 'services',
        str_starts_with((string) $routeName, 'portfolio') => 'portfolio',
        str_starts_with((string) $routeName, 'blog') => 'blog',
        str_starts_with((string) $routeName, 'contact') => 'contact',
        str_starts_with((string) $routeName, 'search') => 'search',
        default => 'general',
    };

    $resolvedSource = $source ?: $autoSource;

    // --- Page-aware default message ---
    $defaultMessage = match ($resolvedSource) {
        'about'    => "Hi! I'm on your About page and I'd like to book a free 30-min consultation about my project.",
        'services' => "Hi! I'm viewing your Services and I'd like a quote + recommended package for my project.",
        'portfolio'=> "Hi! I saw your Portfolio and I'd like something similar - can we discuss scope and budget?",
        'blog'     => "Hi! I'm reading your Insights and I'd like help applying these ideas to my business.",
        'contact'  => "Hi! I'd like to book a free 30-min consultation for my digital project.",
        'search'   => "Hi! I used your search and I'd like help finding the right solution for my business.",
        default    => "Hi! I'd like to book a free 30-min consultation for my digital project.",
    };

    $resolvedMessage = filled($message) ? $message : $defaultMessage;

    // --- Build href safely (NO Blade directives inside attributes) ---
    $href = 'https://wa.me/' . $cleanPhone;
    if (filled($resolvedMessage)) {
        $href .= '?text=' . urlencode($resolvedMessage);
    }

    $encodedPhone = obfuscate_contact($cleanPhone);
    $encodedMessage = filled($resolvedMessage) ? obfuscate_contact($resolvedMessage) : null;

    // --- Theme classes ---
    $isDarkForced = $theme === 'dark';
    $isLightForced = $theme === 'light';

    $bg = $isDarkForced
        ? 'bg-emerald-500 hover:bg-emerald-600 text-white'
        : ($isLightForced
            ? 'bg-green-600 hover:bg-green-700 text-white'
            : 'bg-green-600 hover:bg-green-700 text-white dark:bg-emerald-500 dark:hover:bg-emerald-600');

    $baseInline = "inline-flex items-center gap-3 {$bg} px-6 py-3.5
        transition-colors min-h-[48px] text-sm font-semibold";

    $baseBlock = "inline-flex items-center justify-center gap-3 {$bg} px-6 py-3.5
        transition-colors min-h-[48px] text-sm font-semibold w-full";

    $baseFab = "floating-cta inline-flex items-center justify-center {$bg}";

    $base = match ($variant) {
        'fab' => $baseFab,
        'block' => $baseBlock,
        default => $baseInline,
    };

    $classes = trim("$base $class");

    $whatsAppSvg = <<<'SVG'
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.767 5.766 0 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.767-5.766-.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.852-.299.071-.677.107-1.082.079-.405-.025-.773-.167-1.467-.725-.695-.558-1.134-.811-1.846-1.589-.729-.788-.961-1.259-1.145-1.667-.184-.408-.194-.783-.078-1.025.116-.242.506-.368.959-.514.452-.146.957-.246 1.429-.369.472-.123.88-.071 1.273.016.393.087.836.457 1.237.883.401.426.723.927.892 1.378.169.451.237.927.162 1.379z"/>
    </svg>
    SVG;

    $fallbackInner = $withIcon ? $whatsAppSvg . '<span>' . e($label) . '</span>' : '<span>' . e($label) . '</span>';
?>

<?php if($protected): ?>
    <span class="protected-contact <?php echo e($classes); ?> cursor-pointer" data-type="whatsapp"
        data-label="<?php echo e($label); ?>" data-value="<?php echo e($encodedPhone); ?>"
        <?php if($encodedMessage): ?> data-message="<?php echo e($encodedMessage); ?>" <?php endif; ?> aria-label="<?php echo e($label); ?>"
        role="link" tabindex="0">
        <?php if($slot->isEmpty()): ?>
            <?php echo $fallbackInner; ?>

        <?php else: ?>
            <?php echo e($slot); ?>

        <?php endif; ?>
    </span>
<?php else: ?>
    <a class="<?php echo e($classes); ?>" href="<?php echo e($href); ?>" target="_blank" rel="noopener noreferrer nofollow"
        aria-label="<?php echo e($label); ?>">
        <?php if($slot->isEmpty()): ?>
            <?php echo $fallbackInner; ?>

        <?php else: ?>
            <?php echo e($slot); ?>

        <?php endif; ?>
    </a>
<?php endif; ?>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/whatsapp-cta.blade.php ENDPATH**/ ?>