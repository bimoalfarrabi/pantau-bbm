@extends('layouts.public', [
    'title' => 'About - PantauBBM',
    'description' => 'Tentang PantauBBM, sumber data, dan tujuan platform.',
    'canonical' => route('about'),
])

@section('content')
<section class="border-b border-slate-200 bg-slate-50">
    <div class="mx-auto max-w-[1280px] px-5 py-16 md:py-20 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-slate-950 md:text-6xl">About PantauBBM</h1>
        <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">Platform informasi harga BBM regional yang fokus pada transparansi, kemudahan akses, dan tampilan data yang mudah dibaca.</p>
    </div>
</section>

<section class="mx-auto max-w-[1280px] px-5 py-16">
    <div class="grid gap-6 md:grid-cols-3">
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 text-3xl text-slate-400">▦</div>
            <h2 class="text-2xl font-semibold text-slate-950">Transparan</h2>
            <p class="mt-3 text-slate-600">Data harga ditampilkan apa adanya dari sumber publik pihak ketiga.</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 text-3xl text-slate-400">◎</div>
            <h2 class="text-2xl font-semibold text-slate-950">Regional</h2>
            <p class="mt-3 text-slate-600">Harga bisa dilihat berdasarkan provinsi atau wilayah yang tersedia.</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 text-3xl text-slate-400">↕</div>
            <h2 class="text-2xl font-semibold text-slate-950">Historis</h2>
            <p class="mt-3 text-slate-600">Riwayat dibuat ketika harga berubah dari data sebelumnya.</p>
        </article>
    </div>
</section>

<section id="data-source" class="border-y border-slate-200 bg-slate-50">
    <div class="mx-auto grid max-w-[1280px] gap-8 px-5 py-16 lg:grid-cols-[1fr_1.2fr]">
        <div>
            <h2 class="text-4xl font-bold tracking-tight text-slate-950">Data Source</h2>
            <p class="mt-4 text-lg leading-8 text-slate-600">PantauBBM memakai Bensin API sebagai sumber data dan menyimpan hasil sinkronisasi ke database lokal sebelum ditampilkan.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 py-4">
                <span class="text-slate-600">Provider</span>
                <span class="font-semibold text-slate-950">Bensin API</span>
            </div>
            <div class="flex items-center justify-between border-b border-slate-200 py-4">
                <span class="text-slate-600">Sync Flow</span>
                <span class="font-semibold text-slate-950">API → DB → Cache → UI</span>
            </div>
            <div class="flex items-center justify-between py-4">
                <span class="text-slate-600">Disclaimer</span>
                <span class="font-semibold text-slate-950">Bukan situs resmi Pertamina</span>
            </div>
        </div>
    </div>
</section>
@endsection
