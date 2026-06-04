<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'PantauBBM - Pantau Harga BBM Indonesia' }}</title>
    <meta name="description" content="{{ $description ?? 'Pantau harga Pertalite, Pertamax, Dexlite, dan BBM lainnya berdasarkan daerah di Indonesia.' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'PantauBBM - Pantau Harga BBM Indonesia' }}">
    <meta property="og:description" content="{{ $description ?? 'Pantau harga BBM Indonesia berdasarkan daerah dan riwayat perubahan.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    <meta property="og:image" content="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @unless(app()->environment('testing'))
        @vite(['resources/js/app.js'])
    @endunless
</head>
<body class="font-sans bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-slate-50">
        <nav class="mx-auto flex max-w-[1280px] items-center justify-between px-5 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold tracking-tight text-slate-950">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-300 text-slate-600">▣</span>
                PantauBBM
            </a>
            <div class="flex items-center gap-6 text-sm text-slate-700">
                <a href="{{ route('home') }}" class="hover:text-slate-950">Map View</a>
                <a href="{{ route('about') }}" class="hover:text-slate-950">About</a>
                <a href="{{ route('home') }}#daftar-regional" class="rounded-full bg-slate-950 px-5 py-2.5 font-semibold text-white">Location</a>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer id="tentang-data" class="border-t border-slate-200 bg-slate-50">
        <div class="mx-auto flex max-w-[1280px] flex-col gap-6 px-5 py-12 text-sm text-slate-700 md:flex-row md:items-center md:justify-between">
            <div class="flex flex-wrap items-center gap-5">
                <p class="text-xl font-bold text-slate-950">PantauBBM</p>
                <p>© 2026 PantauBBM Data</p>
            </div>
            <div class="flex flex-wrap gap-8 text-base">
                <a href="{{ route('about') }}" class="hover:text-slate-950">About</a>
                <a href="{{ route('about') }}#data-source" class="hover:text-slate-950">Data Source</a>
                <a href="mailto:hello@pantaubbm.local" class="hover:text-slate-950">Contact</a>
            </div>
            <div class="w-full text-xs text-slate-500 md:w-auto md:text-right">
                <p>Data powered by Bensin API.</p>
                <p>PantauBBM bukan situs resmi Pertamina atau MyPertamina.</p>
            </div>
        </div>
    </footer>
</body>
</html>
