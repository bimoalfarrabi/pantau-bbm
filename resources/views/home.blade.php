@extends('layouts.public', [
    'title' => 'PantauBBM - Pantau Harga BBM Indonesia',
    'description' => 'Pantau harga Pertalite, Pertamax, Dexlite, dan BBM lainnya berdasarkan daerah di Indonesia.',
    'canonical' => route('home'),
])

@section('content')
<section class="mx-auto max-w-6xl px-4 py-12 md:py-16">
    <div class="max-w-3xl">
        <p class="text-sm font-semibold uppercase tracking-wide text-brand-secondary">Harga BBM Indonesia</p>
        <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">Pantau Perubahan Harga BBM Indonesia.</h1>
        <p class="mt-5 text-lg text-slate-600">Cari harga BBM berdasarkan provinsi, lihat data terbaru, dan pantau riwayat perubahan harga.</p>
        <form class="mt-8 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
            <label for="search" class="sr-only">Cari daerah</label>
            <input id="search" type="search" placeholder="Cari provinsi, contoh: Jawa Timur" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 outline-none focus:border-brand-primary" disabled>
        </form>
    </div>
</section>

<section class="mx-auto grid max-w-6xl gap-4 px-4 pb-12 md:grid-cols-3">
    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Harga terbaru</h2>
        <p class="mt-2 text-slate-600">Daftar harga BBM terbaru dari database lokal setelah sinkronisasi.</p>
    </article>
    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Riwayat perubahan</h2>
        <p class="mt-2 text-slate-600">History dibuat hanya saat harga berubah dari data sebelumnya.</p>
    </article>
    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Detail wilayah</h2>
        <p class="mt-2 text-slate-600">Setiap provinsi punya halaman detail untuk semua produk BBM.</p>
    </article>
</section>

<section class="mx-auto max-w-6xl px-4 pb-16">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-semibold">Data harga tersedia</h2>
        @if (count($latestPrices) === 0)
            <p class="mt-3 text-slate-600">Data belum tersedia. Jalankan sinkronisasi awal dengan <code>php artisan fuel:sync</code>.</p>
        @else
            <div class="mt-4 grid gap-3 md:grid-cols-2">
                @foreach ($latestPrices as $price)
                    <a href="{{ route('regions.show', $price->region->slug) }}" class="rounded-xl border border-slate-200 p-4">
                        <p class="font-semibold">{{ $price->fuelProduct->name }}</p>
                        <p class="text-sm text-slate-600">{{ $price->region->name }}</p>
                        <p class="mt-2 text-lg font-bold">{{ $price->price === null ? 'Tidak tersedia' : 'Rp '.number_format($price->price, 0, ',', '.') }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
