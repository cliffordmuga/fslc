{{-- resources/views/components/cms/responsive-image.blade.php --}}
{{--
Responsive image — requires $model->images eager-loaded when using the relation fallback.

Props:
- model: Eloquent model with images() relation
- collection: Image collection (default: 'featured')
- class: Additional CSS classes
- alt: Custom alt text
- fallback: Fallback image path
- sizes: Sizes attribute for responsive srcset (default: grid/card layout)
--}}

@props([
    'model',
    'collection' => 'featured',
    'class' => '',
    'alt' => '',
    'fallback' => 'images/placeholder.jpg',
    'loading' => 'lazy',
    'fetchpriority' => null,
    'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 400px',
])

@php
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
@endphp

@if($usePicture && $srcset)
<picture>
    <source type="image/webp" srcset="{{ $srcset }}" sizes="{{ $sizes }}">
    <img src="{{ $imgSrc }}"
        alt="{{ $imgAlt }}"
        class="{{ trim($imgClass) }}"
        @if($blurStyle) style="{{ $blurStyle }}" @endif
        loading="{{ $loading }}"
        decoding="async"
        width="{{ $imgWidth }}"
        height="{{ $imgHeight }}"
        @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif />
</picture>
@else
<img src="{{ $imgSrc }}"
    alt="{{ $imgAlt }}"
    class="{{ trim($imgClass) }}"
    @if($blurStyle) style="{{ $blurStyle }}" @endif
    loading="{{ $loading }}"
    decoding="async"
    width="{{ $imgWidth }}"
    height="{{ $imgHeight }}"
    @if($srcset) srcset="{{ $srcset }}" sizes="{{ $sizes }}" @endif
    @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif />
@endif
