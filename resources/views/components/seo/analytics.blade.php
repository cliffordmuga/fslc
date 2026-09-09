@php



    $gaId = trim((string) setting('google_analytics_id', ''));



@endphp



@if ($gaId !== '')



    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>



    <script>



        window.dataLayer = window.dataLayer || [];



        function gtag(){dataLayer.push(arguments);}



        gtag('js', new Date());



        gtag('config', '{{ $gaId }}', { anonymize_ip: true, send_page_view: true });



    </script>



    @vite('resources/js/web-vitals.js')



@endif

