<?php



    $gaId = trim((string) setting('google_analytics_id', ''));



?>



<?php if($gaId !== ''): ?>



    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e($gaId); ?>"></script>



    <script>



        window.dataLayer = window.dataLayer || [];



        function gtag(){dataLayer.push(arguments);}



        gtag('js', new Date());



        gtag('config', '<?php echo e($gaId); ?>', { anonymize_ip: true, send_page_view: true });



    </script>



    <?php echo app('Illuminate\Foundation\Vite')('resources/js/web-vitals.js'); ?>



<?php endif; ?>

<?php /**PATH C:\laragon\www\fslc\resources\views/components/seo/analytics.blade.php ENDPATH**/ ?>