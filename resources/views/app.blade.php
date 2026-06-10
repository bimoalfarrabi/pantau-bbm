<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

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
