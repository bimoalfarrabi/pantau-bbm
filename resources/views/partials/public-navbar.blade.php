@php
    $isAbout = request()->routeIs('about');
@endphp

<header class="border-b border-slate-200 bg-slate-50/95 backdrop-blur">
    <nav class="mx-auto flex max-w-[1280px] flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold tracking-tight text-slate-950">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-300 text-slate-600">▣</span>
            PantauBBM
        </a>

        <div class="flex flex-wrap items-center gap-2 text-sm text-slate-700 md:gap-6">
            <a href="{{ route('about') }}" class="px-4 py-2 transition hover:text-slate-950 {{ $isAbout ? 'text-slate-950' : '' }}">About</a>
        </div>
    </nav>
</header>
