
<?php
    $seoData    = $seoData ?? ($defaultSeoData ?? defaultSeoData());
    $title      = $seoData['title'] ?? null;
    $description = $seoData['description'] ?? null;
    $canonical  = $seoData['canonical_url'] ?? canonical_url();
    $ogTitle    = $seoData['og_title'] ?? $title;
    $ogDesc     = $seoData['og_description'] ?? $description;
    $ogImage    = $seoData['og_image'] ?? cdn_asset('images/default-og-image.png');
    $noindex    = (bool) ($seoData['noindex'] ?? false);
    $nofollow   = (bool) ($seoData['nofollow'] ?? false);
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<title><?php echo e(meta_title($title)); ?></title>
<meta name="description" content="<?php echo e(meta_description($description)); ?>">
<?php $keywordsMeta = meta_keywords($seoData['keywords'] ?? null); ?>
<?php if($keywordsMeta): ?>
    <meta name="keywords" content="<?php echo e($keywordsMeta); ?>">
<?php endif; ?>
<?php if($noindex || $nofollow): ?>
    <meta name="robots" content="<?php echo e($noindex ? 'noindex' : 'index'); ?>, <?php echo e($nofollow ? 'nofollow' : 'follow'); ?>">
<?php endif; ?>
<link rel="canonical" href="<?php echo e($canonical); ?>">

<meta property="og:title" content="<?php echo e($ogTitle); ?>">
<meta property="og:description" content="<?php echo e($ogDesc); ?>">
<meta property="og:image" content="<?php echo e($ogImage); ?>">
<meta property="og:url" content="<?php echo e($canonical); ?>">
<meta property="og:type" content="<?php echo e($seoData['og_type'] ?? 'website'); ?>">
<meta property="og:locale" content="en_KE">
<meta property="og:site_name" content="<?php echo e(setting('company_name', config('app.name'))); ?>">

<meta name="twitter:card" content="<?php echo e($seoData['twitter_card'] ?? 'summary_large_image'); ?>">
<meta name="twitter:title" content="<?php echo e($ogTitle); ?>">
<meta name="twitter:description" content="<?php echo e($ogDesc); ?>">
<meta name="twitter:image" content="<?php echo e($ogImage); ?>">

<link rel="icon" type="image/svg+xml" href="<?php echo e(asset('images/favicon.svg')); ?>">
<link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>" sizes="32x32">
<link rel="apple-touch-icon" href="<?php echo e(asset('images/favicon.svg')); ?>">
<meta name="theme-color" content="<?php echo e(setting('primary_color', '#0ea5e9')); ?>">

<?php if(isset($seoData['preload_image'])): ?>
    <link rel="preload" as="image" href="<?php echo e($seoData['preload_image']); ?>">
<?php endif; ?>

<?php echo $__env->yieldPushContent('head'); ?>

<?php if(! empty($seoData['structured_data'])): ?>
    <?php if (isset($component)) { $__componentOriginal1ff444a6761d54b4237649bd3eed67fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.json-ld','data' => ['data' => $seoData['structured_data']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.json-ld'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seoData['structured_data'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $attributes = $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $component = $__componentOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>
<?php endif; ?>

<?php echo $__env->yieldContent('seo'); ?>

<?php if(isset($content) && ($content->type ?? null) !== 'intro'): ?>
    <?php
        $crumbs = [['name' => 'Home', 'url' => url('/')]];
        $type = $content->type ?? null;
        if ($type === 'portfolio') {
            $crumbs[] = ['name' => 'Portfolio', 'url' => route('portfolio.index')];
        } elseif ($type === 'services') {
            $crumbs[] = ['name' => 'Services', 'url' => route('services.index')];
        } elseif ($type === 'blog') {
            $crumbs[] = ['name' => 'Insights', 'url' => route('insights.index')];
        } elseif (in_array($type, ['mission', 'vision', 'about'], true)) {
            $crumbs[] = ['name' => 'About', 'url' => route('about')];
        }
        $crumbs[] = ['name' => $content->title ?? 'Page', 'url' => url()->current()];
    ?>
    <?php if (isset($component)) { $__componentOriginal1ff444a6761d54b4237649bd3eed67fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.json-ld','data' => ['data' => breadcrumb_schema($crumbs)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.json-ld'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(breadcrumb_schema($crumbs))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $attributes = $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $component = $__componentOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginal1ff444a6761d54b4237649bd3eed67fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.json-ld','data' => ['data' => organization_schema()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.json-ld'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(organization_schema())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $attributes = $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $component = $__componentOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>

<?php if(request()->routeIs('home')): ?>
    <?php
        $localBiz = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => setting('company_name', config('app.name', env('APP_NAME', 'App'))),
            'url' => url('/'),
            'logo' => cdn_asset('images/logo.png'),
            'image' => cdn_asset('images/default-og-image.png'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => setting('address', 'Nairobi, Kenya'),
                'addressLocality' => setting('address_city', 'Nairobi'),
                'addressCountry' => 'KE',
            ],
            'areaServed' => ['KE', 'East Africa'],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'url' => route('contact'),
                'availableLanguage' => ['English', 'Swahili'],
            ],
            'sameAs' => collect([
                setting('facebook_url'),
                setting('twitter_url'),
                setting('linkedin_url'),
                setting('instagram_url'),
            ])->filter()->values()->toArray(),
        ];
    ?>
    <?php if (isset($component)) { $__componentOriginal1ff444a6761d54b4237649bd3eed67fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.json-ld','data' => ['data' => $localBiz]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.json-ld'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($localBiz)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $attributes = $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $component = $__componentOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>
<?php endif; ?>

<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
<link rel="dns-prefetch" href="https://fonts.bunny.net">
<link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap"
      as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"></noscript>

<?php if (isset($component)) { $__componentOriginal97ea767458488943a1e3c830863ae308 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal97ea767458488943a1e3c830863ae308 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.critical-css','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.critical-css'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal97ea767458488943a1e3c830863ae308)): ?>
<?php $attributes = $__attributesOriginal97ea767458488943a1e3c830863ae308; ?>
<?php unset($__attributesOriginal97ea767458488943a1e3c830863ae308); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal97ea767458488943a1e3c830863ae308)): ?>
<?php $component = $__componentOriginal97ea767458488943a1e3c830863ae308; ?>
<?php unset($__componentOriginal97ea767458488943a1e3c830863ae308); ?>
<?php endif; ?>
<?php
    $appStylesheet = \Illuminate\Support\Facades\Vite::asset('resources/css/app.css');
?>
<link rel="preload" href="<?php echo e($appStylesheet); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="<?php echo e($appStylesheet); ?>"></noscript>
<?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
<?php if (isset($component)) { $__componentOriginal8082eee56d07c0c2a921577661a31c3c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8082eee56d07c0c2a921577661a31c3c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.analytics','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.analytics'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8082eee56d07c0c2a921577661a31c3c)): ?>
<?php $attributes = $__attributesOriginal8082eee56d07c0c2a921577661a31c3c; ?>
<?php unset($__attributesOriginal8082eee56d07c0c2a921577661a31c3c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8082eee56d07c0c2a921577661a31c3c)): ?>
<?php $component = $__componentOriginal8082eee56d07c0c2a921577661a31c3c; ?>
<?php unset($__componentOriginal8082eee56d07c0c2a921577661a31c3c); ?>
<?php endif; ?>
<style>
    :root { <?php echo e(brand_theme_css_vars()); ?> }
</style>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/layout/site-head.blade.php ENDPATH**/ ?>