<!DOCTYPE html>
<script>
    window.onpageshow = function(event) {
        // 只有在結帳頁面，且是從 BFCache (按上一頁) 回來時才重整
        if (event.persisted && window.location.pathname.includes('/checkout')) {
            // 加上隨機參數確保擊穿 Varnish 快取
            window.location.replace(window.location.pathname + '?ref=' + Date.now());
        }
    };
</script>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700|noto-sans-tc:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
