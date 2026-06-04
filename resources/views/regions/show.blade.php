@php($regionName = $region?->name ?? str($slug)->replace('-', ' ')->title())
@extends('layouts.public', [
    'title' => 'Harga BBM '.$regionName.' Terbaru - PantauBBM',
    'description' => 'Lihat harga BBM terbaru untuk wilayah '.$regionName.', termasuk Pertalite, Pertamax, Dexlite, dan produk BBM lainnya.',
    'canonical' => route('regions.show', $slug),
])

@section('content')
<section class="border-b border-slate-200 bg-slate-50">
    <div class="mx-auto max-w-[1280px] px-5 py-16 md:py-20 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-slate-950 md:text-6xl">{{ $regionName }}</h1>
        <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">Lihat harga BBM terbaru untuk wilayah ini berdasarkan data sinkronisasi terakhir.</p>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-3 text-sm">
            <span class="mr-2 text-slate-400">☰</span>
            <a href="{{ route('home') }}" class="rounded-full border border-slate-300 px-5 py-2 text-slate-700">Semua Wilayah</a>
            <a href="{{ route('history.index') }}" class="rounded-full border border-slate-300 px-5 py-2 text-slate-700">Riwayat</a>
            <span class="rounded-full bg-slate-950 px-5 py-2 font-semibold text-white">Location</span>
        </div>
    </div>
</section>

<section class="mx-auto max-w-[1280px] px-5 py-16">
    <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <h2 class="text-4xl font-bold tracking-tight text-slate-950">Detail Harga Regional</h2>
        <span class="text-sm font-medium text-slate-700">{{ $region?->fuelPrices?->count() ?? 0 }} produk tersedia</span>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.25fr_0.75fr]">
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center gap-3 text-2xl font-semibold text-slate-950">
                <span class="text-slate-400">▦</span>
                {{ $regionName }}
            </div>

            @if (! $region || $region->fuelPrices->isEmpty())
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-center">
                    <p class="font-semibold text-slate-950">Data wilayah ini belum tersedia.</p>
                    <p class="mt-2 text-sm text-slate-600">Jalankan sinkronisasi untuk memuat data terbaru.</p>
                </div>
            @else
                <div class="space-y-1">
                    @foreach ($region->fuelPrices->sortBy(fn ($price) => $price->fuelProduct?->sort_order ?? 0) as $price)
                        <div class="flex items-center justify-between border-b border-slate-200 py-4 last:border-b-0">
                            <div>
                                <p class="text-slate-700">{{ $price->fuelProduct->name }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $price->price === null ? 'Tidak tersedia dari sumber' : 'Harga per liter' }}</p>
                            </div>
                            <p class="text-xl font-semibold text-slate-950">{{ $price->price === null ? '-' : 'Rp '.number_format($price->price, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </article>

        <aside class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-slate-950">Informasi Data</h3>
                <div class="mt-5 space-y-4 text-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                        <span class="text-slate-600">Wilayah</span>
                        <span class="font-semibold text-slate-950">{{ $regionName }}</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                        <span class="text-slate-600">Slug</span>
                        <span class="font-semibold text-slate-950">{{ $slug }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Update terakhir</span>
                        <span class="font-semibold text-slate-950">{{ $region?->fuelPrices?->max('last_synced_at')?->timezone('Asia/Jakarta')?->format('d M Y H:i') ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-slate-950">Sumber</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">Data powered by Bensin API. PantauBBM bukan situs resmi Pertamina atau MyPertamina.</p>
            </div>
        </aside>
    </div>
</section>
@endsection
