<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Open Graph / Social Media Preview -->
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="PantauBBM">
        <meta property="og:title" content="PantauBBM - Pantau Harga BBM Indonesia">
        <meta property="og:description" content="Pantau harga Pertalite, Pertamax, Dexlite, dan BBM lainnya berdasarkan daerah di Indonesia.">
        <meta property="og:image" content="{{ asset('og-image.png') }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:locale" content="id_ID">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="PantauBBM - Pantau Harga BBM Indonesia">
        <meta name="twitter:description" content="Pantau harga Pertalite, Pertamax, Dexlite, dan BBM lainnya berdasarkan daerah di Indonesia.">
        <meta name="twitter:image" content="{{ asset('og-image.png') }}">

        <!-- SEO -->
        <meta name="description" content="Pantau harga Pertalite, Pertamax, Dexlite, dan BBM lainnya berdasarkan daerah di Indonesia.">

        <link rel="preload" href="/fonts/figtree/Figtree.woff2" as="font" type="font/woff2" crossorigin>

        <!-- Scripts -->
        @routes
        @unless(app()->environment('testing'))
            @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @endunless
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
