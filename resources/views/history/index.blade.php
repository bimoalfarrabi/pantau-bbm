@extends('layouts.public', [
    'title' => 'Riwayat Harga BBM - PantauBBM',
    'description' => 'Lihat riwayat perubahan harga BBM Indonesia berdasarkan wilayah dan produk.',
    'canonical' => route('history.index'),
])

@section('content')
<section class="border-b border-slate-200 bg-slate-50">
    <div class="mx-auto max-w-[1280px] px-5 py-16 md:py-20 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-slate-950 md:text-6xl">Riwayat Harga BBM</h1>
        <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">Perubahan dicatat saat harga terbaru berbeda dari harga sebelumnya.</p>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-3 text-sm">
            <span class="mr-2 text-slate-400">☰</span>
            <a href="{{ route('history.index') }}" class="rounded-full bg-slate-950 px-5 py-2 font-semibold text-white">Semua</a>
            <span class="rounded-full border border-slate-300 px-5 py-2 text-slate-700">Pertalite</span>
            <span class="rounded-full border border-slate-300 px-5 py-2 text-slate-700">Pertamax</span>
            <span class="rounded-full border border-slate-300 px-5 py-2 text-slate-700">Dexlite</span>
            <span class="rounded-full border border-slate-300 px-5 py-2 text-slate-700">Solar</span>
        </div>
    </div>
</section>

<section class="mx-auto max-w-[1280px] px-5 py-16">
    <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <h2 class="text-4xl font-bold tracking-tight text-slate-950">Daftar Perubahan Harga</h2>
        <button class="flex items-center gap-2 text-sm font-medium text-slate-700">
            <span>☰</span>
            Urutkan
        </button>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @if ($histories->isEmpty())
            <div class="p-10 text-center">
                <div class="mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-full border border-slate-200 text-slate-400">↕</div>
                <p class="text-lg font-semibold text-slate-950">Belum ada riwayat perubahan harga.</p>
                <p class="mt-2 text-slate-600">Riwayat akan muncul setelah sync menemukan harga yang berubah.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
                        <tr>
                            <th class="p-5 font-semibold">Tanggal</th>
                            <th class="p-5 font-semibold">Wilayah</th>
                            <th class="p-5 font-semibold">Produk</th>
                            <th class="p-5 font-semibold">Harga Lama</th>
                            <th class="p-5 font-semibold">Harga Baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $history)
                            <tr class="border-b border-slate-200 last:border-b-0">
                                <td class="p-5 text-slate-600">{{ $history->changed_at?->timezone('Asia/Jakarta')?->format('d M Y') }}</td>
                                <td class="p-5 font-semibold text-slate-950">{{ $history->region->name }}</td>
                                <td class="p-5 text-slate-700">{{ $history->fuelProduct->name }}</td>
                                <td class="p-5 text-slate-700">{{ $history->old_price === null ? '-' : 'Rp '.number_format($history->old_price, 0, ',', '.') }}</td>
                                <td class="p-5 font-semibold text-slate-950">{{ $history->new_price === null ? '-' : 'Rp '.number_format($history->new_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection
