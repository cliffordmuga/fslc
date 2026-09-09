


<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'model',
    'collection' => 'featured',
    'class' => '',
    'alt' => '',
    'fallback' => 'images/placeholder.jpg',
    'loading' => 'lazy',
    'fetchpriority' => null,
    'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 400px',
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
    'model',
    'collection' => 'featured',
    'class' => '',
    'alt' => '',
    'fallback' => 'images/placeholder.jpg',
    'loading' => 'lazy',
    'fetchpriority' => null,
    'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 400px',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $imgSrc = asset($fallback);
    $imgAlt = $alt ?: 'Image';
    $imgWidth = 400;
    $imgHeight = 250;
    $srcset = null;
    $usePicture = false;
    $blurPlaceholder = null;

    if ($model && method_exists($model, 'images')) {
        $imageService = app(\App\Services\ImageService::class);

        if ($model->relationLoaded('images')) {
            $image = $model->images->where('collection', $collection)->where('variant', 'main')->first()
                ?? $model->images->where('collection', $collection)->first();
            if ($image) {
                $imgSrc = $image->url ?? upload_asset($image->image_url);
                $imgAlt = $alt ?: ($image->alt_text ?? $model->title ?? 'Image');
                $blurPlaceholder = $image->blur_placeholder;
            }
        } elseif ($model->id ?? null) {
            $variants = $imageService->getImageVariants($model::class, $model->id, $collection);

            if ($variants && ! empty($variants)) {
                $main = $variants['main'] ?? reset($variants);
                $imgSrc = $main['url'];
                $imgWidth = $main['width'] ?? 800;
                $imgHeight = $main['height'] ?? 400;
                $imgAlt = $alt ?: ($main['alt'] ?? $model->title ?? 'Image');
                $blurPlaceholder = $main['blur_placeholder'] ?? null;
                $byWidth = collect($variants)->sortBy('width')->values();
                $srcset = $byWidth->map(fn ($v) => ($v['url'] ?? '') . ' ' . ($v['width'] ?? 0) . 'w')->implode(', ');
                $usePicture = collect($variants)->contains(fn ($v) => isset($v['url']) && str_ends_with($v['url'], '.webp'));
            }
        }
    }

    $imgClass = 'w-full h-full object-cover ' . $class;
    $blurStyle = $blurPlaceholder
        ? 'background-image:url(' . json_encode($blurPlaceholder) . ');background-size:cover;background-position:center;'
        : '';
?>

<?php if($usePicture && $srcset): ?>
<picture>
    <source type="image/webp" srcset="<?php echo e($srcset); ?>" sizes="<?php echo e($sizes); ?>">
    <img src="<?php echo e($imgSrc); ?>"
        alt="<?php echo e($imgAlt); ?>"
        class="<?php echo e(trim($imgClass)); ?>"
        <?php if($blurStyle): ?> style="<?php echo e($blurStyle); ?>" <?php endif; ?>
        loading="<?php echo e($loading); ?>"
        decoding="async"
        width="<?php echo e($imgWidth); ?>"
        height="<?php echo e($imgHeight); ?>"
        <?php if($fetchpriority): ?> fetchpriority="<?php echo e($fetchpriority); ?>" <?php endif; ?> />
</picture>
<?php else: ?>
<img src="<?php echo e($imgSrc); ?>"
    alt="<?php echo e($imgAlt); ?>"
    class="<?php echo e(trim($imgClass)); ?>"
    <?php if($blurStyle): ?> style="<?php echo e($blurStyle); ?>" <?php endif; ?>
    loading="<?php echo e($loading); ?>"
    decoding="async"
    width="<?php echo e($imgWidth); ?>"
    height="<?php echo e($imgHeight); ?>"
    <?php if($srcset): ?> srcset="<?php echo e($srcset); ?>" sizes="<?php echo e($sizes); ?>" <?php endif; ?>
    <?php if($fetchpriority): ?> fetchpriority="<?php echo e($fetchpriority); ?>" <?php endif; ?> />
<?php endif; ?>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/responsive-image.blade.php ENDPATH**/ ?>