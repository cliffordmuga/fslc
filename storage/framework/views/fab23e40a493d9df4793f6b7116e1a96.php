

<?php $__env->startSection('seo'); ?>
    <?php $__currentLoopData = $pageSchemas ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if (isset($component)) { $__componentOriginal1ff444a6761d54b4237649bd3eed67fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.json-ld','data' => ['data' => $schema]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.json-ld'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($schema)]); ?>
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
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if (isset($component)) { $__componentOriginald85159ac95308cdeb145f8a1717e7ea9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald85159ac95308cdeb145f8a1717e7ea9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hub-shell','data' => ['breadcrumb' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $pageKey === 'privacy' ? 'Privacy Policy' : 'Terms & Conditions'],
        ],'heroPreload' => hero_asset(2)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hub-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $pageKey === 'privacy' ? 'Privacy Policy' : 'Terms & Conditions'],
        ]),'hero-preload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(2))]); ?>
         <?php $__env->slot('hero', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal8d24c5a1535570606fe4612a7be01ba8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d24c5a1535570606fe4612a7be01ba8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.hero-section','data' => ['theme' => 'dark','size' => 'standard','title' => $title,'subtitle' => $pageKey === 'privacy'
                    ? 'How we collect, use, and protect personal information submitted through this website.'
                    : 'Terms governing use of this website and services provided by ' . setting('company_name', config('app.name')) . '.','imageUrl' => hero_asset(2),'imageSrcset' => hero_asset_srcset(2),'tagline' => 'HMIS · Software · Branding · Public Engagement — Forefront Solutions Kenya.','badges' => ['Legal'],'ctaUrl' => hub_cta_url('legal_hero')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['theme' => 'dark','size' => 'standard','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pageKey === 'privacy'
                    ? 'How we collect, use, and protect personal information submitted through this website.'
                    : 'Terms governing use of this website and services provided by ' . setting('company_name', config('app.name')) . '.'),'image-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset(2)),'image-srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hero_asset_srcset(2)),'tagline' => 'HMIS · Software · Branding · Public Engagement — Forefront Solutions Kenya.','badges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Legal']),'cta-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('legal_hero'))]); ?>
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
         <?php $__env->endSlot(); ?>

    <section class="py-10 lg:py-14 bg-white border-t border-neutral-200" aria-label="<?php echo e($title); ?>">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="prose-custom prose-sm max-w-none">
                <?php if($content): ?>
                    <?php echo render_cms_content($content->content); ?>

                <?php else: ?>
                    <?php if($pageKey === 'privacy'): ?>
                        <h2>Information we collect</h2>
                        <p>
                            We may collect information you submit through forms (such as your name, email address, phone number, and message).
                            We may also collect basic technical information (such as browser type and pages visited) for analytics and site improvement.
                        </p>
                        <h2>How we use information</h2>
                        <ul>
                            <li>To respond to inquiries and provide requested services</li>
                            <li>To improve website performance, content, and user experience</li>
                            <li>To monitor spam and protect site integrity</li>
                        </ul>
                        <h2>Sharing</h2>
                        <p>
                            We do not sell your personal information. We may share information only when necessary to provide services,
                            comply with legal obligations, or protect our rights.
                        </p>
                        <h2>Data retention</h2>
                        <p>
                            We retain inquiries and submissions for as long as necessary for business, security, and compliance purposes.
                        </p>
                    <?php else: ?>
                        <h2>Use of the website</h2>
                        <p>You agree to use this website lawfully and not to misuse forms, content, or features.</p>
                        <h2>Intellectual property</h2>
                        <p>
                            Unless otherwise stated, website content is owned by
                            <?php echo e(setting('company_name', config('app.name'))); ?> and may not be copied without permission.
                        </p>
                        <h2>Service inquiries</h2>
                        <p>
                            Submitting an inquiry does not guarantee availability or acceptance of a project.
                            Project terms, pricing, and timelines are agreed in writing.
                        </p>
                        <h2>Third-party links</h2>
                        <p>This site may link to third-party websites. We are not responsible for their content or privacy practices.</p>
                        <h2>Limitation of liability</h2>
                        <p>We are not liable for damages arising from use of this website, except where prohibited by law.</p>
                    <?php endif; ?>

                    <h2>Contact</h2>
                    <p>
                        Questions? <a href="<?php echo e(hub_cta_url('legal_contact')); ?>">Request an HMIS demo</a>
                        or <a href="<?php echo e(route('contact', ['inquiry_type' => 'hmis-checklist'])); ?>#contact-form">download the HMIS checklist</a>.
                    </p>
                <?php endif; ?>

                <p class="text-sm text-neutral-500 mt-10 not-prose">
                    Last updated: <?php echo e(($content?->updated_at ?? now())->format('F j, Y')); ?>

                </p>
            </div>
        </div>
    </section>

    <?php if (isset($component)) { $__componentOriginal03b5006f52c5c5b41290402a897a208b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03b5006f52c5c5b41290402a897a208b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cms.page-closer','data' => ['title' => 'Questions about our policies?','subtitle' => 'Request an HMIS demo or speak with our team — we respond within 24 hours.','primaryUrl' => hub_cta_url('legal_footer'),'primaryLabel' => 'Request HMIS Demo','secondaryUrl' => route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form','secondaryLabel' => 'Get HMIS Checklist','size' => 'default']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cms.page-closer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Questions about our policies?','subtitle' => 'Request an HMIS demo or speak with our team — we respond within 24 hours.','primary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(hub_cta_url('legal_footer')),'primary-label' => 'Request HMIS Demo','secondary-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('contact', ['inquiry_type' => 'hmis-checklist']) . '#contact-form'),'secondary-label' => 'Get HMIS Checklist','size' => 'default']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03b5006f52c5c5b41290402a897a208b)): ?>
<?php $attributes = $__attributesOriginal03b5006f52c5c5b41290402a897a208b; ?>
<?php unset($__attributesOriginal03b5006f52c5c5b41290402a897a208b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03b5006f52c5c5b41290402a897a208b)): ?>
<?php $component = $__componentOriginal03b5006f52c5c5b41290402a897a208b; ?>
<?php unset($__componentOriginal03b5006f52c5c5b41290402a897a208b); ?>
<?php endif; ?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald85159ac95308cdeb145f8a1717e7ea9)): ?>
<?php $attributes = $__attributesOriginald85159ac95308cdeb145f8a1717e7ea9; ?>
<?php unset($__attributesOriginald85159ac95308cdeb145f8a1717e7ea9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald85159ac95308cdeb145f8a1717e7ea9)): ?>
<?php $component = $__componentOriginald85159ac95308cdeb145f8a1717e7ea9; ?>
<?php unset($__componentOriginald85159ac95308cdeb145f8a1717e7ea9); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/frontend/legal.blade.php ENDPATH**/ ?>