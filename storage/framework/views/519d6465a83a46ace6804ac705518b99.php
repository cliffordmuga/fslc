

<?php $__env->startSection('title', 'CTA Details'); ?>

<?php $__env->startSection('header', 'Call-to-Action Details'); ?>

<?php $__env->startSection('content'); ?>

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-6">

            <a href="<?php echo e(route('admin.ctas.index')); ?>" class="text-sm text-primary-600 hover:text-primary-800">&larr; Back to CTAs</a>

        </div>



        <div class="card-base p-6 space-y-4">

            <div>

                <h1 class="text-xl font-bold text-neutral-900"><?php echo e($cta->text); ?></h1>

                <p class="text-sm text-neutral-500 mt-1">Type: <?php echo e(ucfirst($cta->type)); ?> · Priority: <?php echo e($cta->priority); ?></p>

            </div>



            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                <div>

                    <dt class="font-medium text-neutral-500">Linked content</dt>

                    <dd class="text-neutral-900"><?php echo e($cta->content?->title ?? 'Global (all pages)'); ?></dd>

                </div>

                <div>

                    <dt class="font-medium text-neutral-500">URL</dt>

                    <dd class="text-neutral-900 break-all"><?php echo e($cta->action ?: '—'); ?></dd>

                </div>

                <div>

                    <dt class="font-medium text-neutral-500">Clicks</dt>

                    <dd class="text-neutral-900"><?php echo e(number_format($cta->clicks)); ?></dd>

                </div>

                <div>
                    <dt class="font-medium text-neutral-500">Impressions</dt>
                    <dd class="text-neutral-900"><?php echo e(number_format($cta->impressions)); ?></dd>
                </div>

            </dl>



            <div class="pt-4 flex gap-3">

                <a href="<?php echo e(route('admin.ctas.edit', $cta)); ?>" class="btn-primary px-4 py-2 text-sm">Edit</a>

            </div>

        </div>

    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\fslc\resources\views/admin/ctas/show.blade.php ENDPATH**/ ?>