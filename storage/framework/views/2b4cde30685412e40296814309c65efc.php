

<?php $__env->startSection('content'); ?>

    <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'standard','title' => 'Sitemap','subtitle' => 'Quick links to major sections — case studies, services, insights, and contact.','imageUrl' => hero_asset(5),'imageSrcset' => hero_asset_srcset(5),'tagline' => 'Navigate Forefront Solutions — HMIS, software, branding, and public engagement.','badges' => ['Navigation'],'ctaUrl' => ''.e(generate_utm_url(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form', 'sitemap_hero')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'standard','title' => 'Sitemap','subtitle' => 'Quick links to major sections — case studies, services, insights, and contact.','image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(5)),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset_srcset(5)),'tagline' => 'Navigate Forefront Solutions — HMIS, software, branding, and public engagement.','badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Navigation']),'cta-url' => ''.e(generate_utm_url(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form', 'sitemap_hero')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d24c5a1535570606fe4612a7be01ba8)): ?>
<?php $attributes = $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8; ?>
<?php unset($__attributesOriginal8d24c5a1535570606fe4612a7be01ba8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d24c5a1535570606fe4612a7be01ba8)): ?>
<?php $component = $__componentOriginal8d24c5a1535570606fe4612a7be01ba8; ?>
<?php unset($__componentOriginal8d24c5a1535570606fe4612a7be01ba8); ?>
<?php endif; ?>

    <div class="bg-white border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <?php if (isset($component)) { $__componentOriginal845656e97179b8317ed324815f9732c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal845656e97179b8317ed324815f9732c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.breadcrumb','data' => ['items' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Sitemap']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Home', 'url' => route('home')], ['label' => 'Sitemap']])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal845656e97179b8317ed324815f9732c3)): ?>
<?php $attributes = $__attributesOriginal845656e97179b8317ed324815f9732c3; ?>
<?php unset($__attributesOriginal845656e97179b8317ed324815f9732c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal845656e97179b8317ed324815f9732c3)): ?>
<?php $component = $__componentOriginal845656e97179b8317ed324815f9732c3; ?>
<?php unset($__componentOriginal845656e97179b8317ed324815f9732c3); ?>
<?php endif; ?>
        </div>
    </div>

    <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-labelledby="sitemap-heading">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="mb-8 border-b border-neutral-200 pb-4">
                <p class="section-label mb-1">Navigation</p>
                <h2 id="sitemap-heading" class="text-2xl lg:text-3xl font-black text-neutral-900 tracking-tight">Site sections</h2>
                <?php if(!empty($lastGenerated)): ?>
                    <p class="mt-2 text-sm text-neutral-500">Last generated: <?php echo e(\Carbon\Carbon::parse($lastGenerated)->diffForHumans()); ?></p>
                <?php endif; ?>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <?php $__currentLoopData = [
                    ['route' => 'home', 'label' => 'Home'],
                    ['route' => 'about', 'label' => 'About'],
                    ['route' => 'services.index', 'label' => 'Services'],
                    ['route' => 'portfolio.index', 'label' => 'Portfolio'],
                    ['route' => 'insights.index', 'label' => 'Insights'],
                    ['route' => 'contact', 'label' => 'Contact', 'params' => ['inquiry_type' => 'hmis-demo']],
                    ['route' => 'privacy', 'label' => 'Privacy'],
                    ['route' => 'terms', 'label' => 'Terms'],
                    ['route' => 'sitemap.xml', 'label' => 'XML Sitemap', 'external' => true],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $href = ($link['external'] ?? false)
                            ? route($link['route'])
                            : (isset($link['params'])
                                ? route($link['route'], $link['params']) . (str_contains($link['route'], 'contact') ? '#contact-form' : '')
                                : route($link['route']));
                    ?>
                    <a href="<?php echo e($href); ?>"
                       class="card-base p-4 text-sm font-semibold text-neutral-800 hover:text-primary-600 hover-lift transition-colors">
                        <?php echo e($link['label']); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <?php if (isset($component)) { $__componentOriginal2fae09bc3adb515c52827818ad659cf5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2fae09bc3adb515c52827818ad659cf5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.lead-magnet-band','data' => ['type' => 'hmis-checklist']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.lead-magnet-band'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'hmis-checklist']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2fae09bc3adb515c52827818ad659cf5)): ?>
<?php $attributes = $__attributesOriginal2fae09bc3adb515c52827818ad659cf5; ?>
<?php unset($__attributesOriginal2fae09bc3adb515c52827818ad659cf5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2fae09bc3adb515c52827818ad659cf5)): ?>
<?php $component = $__componentOriginal2fae09bc3adb515c52827818ad659cf5; ?>
<?php unset($__componentOriginal2fae09bc3adb515c52827818ad659cf5); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.footer-cta','data' => ['title' => 'Ready to get started?','subtitle' => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.','primaryUrl' => generate_utm_url(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form', 'sitemap_footer'),'primaryLabel' => 'Request HMIS Demo','secondaryUrl' => route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form','secondaryLabel' => 'Get HMIS Checklist','showWhatsapp' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.footer-cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ready to get started?','subtitle' => 'Request an HMIS demo or download the procurement checklist — we respond within 24 hours.','primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(generate_utm_url(route('contact', ['inquiry_type' => 'hmis-demo']) . '#contact-form', 'sitemap_footer')),'primary-label' => 'Request HMIS Demo','secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'),'secondary-label' => 'Get HMIS Checklist','show-whatsapp' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8)): ?>
<?php $attributes = $__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8; ?>
<?php unset($__attributesOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8)): ?>
<?php $component = $__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8; ?>
<?php unset($__componentOriginal2f421a4b3e0feeaca9f0e5b00d83bdb8); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/sitemap.blade.php ENDPATH**/ ?>