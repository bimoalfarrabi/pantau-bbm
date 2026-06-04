@php
    $regionName = $region?->name ?? str($slug)->replace('-', ' ')->title();
    $prices = $region?->fuelPrices?->sortBy(fn ($price) => $price->fuelProduct?->sort_order ?? 0)->values() ?? collect();
    $lastSyncedAt = $prices->max('last_synced_at');
    $latestChanges = collect($historyEntries)->flatten(1)->sortByDesc('changedAt')->values();
    $defaultProductId = $historyProducts->first()['id'] ?? $prices->first()?->fuel_product_id;

    $ronLabels = [
        'pertalite' => 'RON 90',
        'pertamax' => 'RON 92',
        'pertamax turbo' => 'RON 98',
        'dexlite' => 'CN 51',
        'pertamina dex' => 'CN 53',
    ];

    $trendPayload = $prices->mapWithKeys(function ($price) use ($historyEntries) {
        $entries = collect($historyEntries[$price->fuel_product_id] ?? [])
            ->sortBy('changedAt')
            ->values()
            ->map(fn ($entry) => [
                'label' => \Illuminate\Support\Carbon::parse($entry['changedAt'])->timezone('Asia/Jakarta')->format('d M'),
                'price' => $entry['newPrice'],
            ]);

        if ($entries->isEmpty() && $price->price !== null) {
            $entries = collect([[
                'label' => $price->last_synced_at?->timezone('Asia/Jakarta')?->format('d M') ?? 'Saat ini',
                'price' => $price->price,
            ]]);
        }

        return [$price->fuel_product_id => $entries->values()];
    });
@endphp

@extends('layouts.public', [
    'title' => 'Harga BBM '.$regionName.' Terbaru - PantauBBM',
    'description' => 'Lihat harga BBM terbaru untuk wilayah '.$regionName.', tren harga, dan histori perubahan terakhir.',
    'canonical' => route('regions.show', $slug),
])

@section('content')
<section class="border-b border-slate-200 bg-slate-50">
    <div class="mx-auto max-w-[1280px] px-5 py-12 md:py-16">
        <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
            <a href="{{ route('home') }}" class="hover:text-slate-950">Beranda</a>
            <span>›</span>
            <a href="{{ route('home') }}#daftar-regional" class="hover:text-slate-950">Regions</a>
            <span>›</span>
            <span class="text-slate-950">{{ $regionName }}</span>
        </div>

        <div class="mt-8 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-5xl font-bold tracking-tight text-slate-950 md:text-7xl">{{ $regionName }}</h1>
                <p class="mt-4 flex items-center gap-2 text-lg text-slate-600">
                    <span>◷</span>
                    Pembaruan terakhir: {{ $lastSyncedAt?->timezone('Asia/Jakarta')?->format('d M Y, H:i').' WIB' ?? '-' }}
                </p>
            </div>
            <div class="inline-flex w-fit items-center gap-3 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-950 shadow-sm">
                <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                {{ $prices->isNotEmpty() ? 'Data tersedia' : 'Data belum tersedia' }}
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-[1280px] px-5 py-10 md:py-14">
    @if (! $region || $prices->isEmpty())
        <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
            <p class="text-xl font-semibold text-slate-950">Data wilayah ini belum tersedia.</p>
            <p class="mt-2 text-slate-600">Jalankan sinkronisasi untuk memuat data terbaru.</p>
        </div>
    @else
        <div class="grid gap-6 lg:grid-cols-[0.95fr_1.95fr]">
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-8 flex items-center gap-3">
                    <span class="text-2xl">⛽</span>
                    <h2 class="text-2xl font-bold text-slate-950">Harga Saat Ini</h2>
                </div>

                <div class="divide-y divide-slate-200">
                    @foreach ($prices as $price)
                        @php
                            $productName = $price->fuelProduct?->name ?? 'Produk';
                            $label = $ronLabels[str($productName)->lower()->toString()] ?? 'BBM';
                            $latestProductChange = collect($historyEntries[$price->fuel_product_id] ?? [])->sortByDesc('changedAt')->first();
                            $delta = $latestProductChange ? ($latestProductChange['newPrice'] - $latestProductChange['oldPrice']) : 0;
                        @endphp
                        <div class="py-5" data-price-row data-product-id="{{ $price->fuel_product_id }}">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <span class="inline-flex rounded-md px-3 py-1 text-sm font-semibold {{ str_contains($label, '92') ? 'bg-blue-100 text-blue-800' : (str_contains($label, '98') ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700') }}">{{ $label }}</span>
                                    <p class="mt-2 text-lg font-semibold text-slate-950">{{ $productName }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-slate-950">{{ $price->price === null ? '-' : 'Rp '.number_format($price->price, 0, ',', '.') }}</p>
                                    @if ($delta > 0)
                                        <p class="mt-1 text-sm font-semibold text-rose-600">↗ +Rp{{ number_format($delta, 0, ',', '.') }}</p>
                                    @elseif ($delta < 0)
                                        <p class="mt-1 text-sm font-semibold text-emerald-600">↘ -Rp{{ number_format(abs($delta), 0, ',', '.') }}</p>
                                    @else
                                        <p class="mt-1 text-sm text-slate-500">— Tetap</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <div class="space-y-6">
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-950">Tren Harga</h2>
                            <p class="mt-1 text-sm text-slate-600">Pilih produk untuk melihat pola perubahan harga.</p>
                        </div>
                        <select id="trend-product" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700">
                            @foreach ($prices as $price)
                                <option value="{{ $price->fuel_product_id }}" @selected($price->fuel_product_id === $defaultProductId)>{{ $price->fuelProduct?->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-8 rounded-2xl bg-slate-100 p-5">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Ringkasan Tren</p>
                                <p id="trend-summary" class="text-xs text-slate-500">Pilih produk untuk melihat ringkasan.</p>
                            </div>
                            <div id="trend-badge" class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200">—</div>
                        </div>
                        <div id="trend-chart" class="flex h-72 items-end gap-3 overflow-hidden rounded-xl"></div>
                        <p id="trend-empty" class="hidden py-24 text-center text-sm text-slate-500">Histori produk ini belum tersedia.</p>
                    </div>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-950">Perubahan Terakhir</h2>
                            <p class="mt-1 text-sm text-slate-600">Riwayat untuk produk yang dipilih.</p>
                        </div>
                        <span id="last-change-product" class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">{{ $historyProducts->first()['name'] ?? 'Produk' }}</span>
                    </div>
                    <div class="mt-6 divide-y divide-slate-200" id="last-change-list">
        @foreach ($historyProducts as $product)
                            @foreach (($historyEntries[$product['id']] ?? []) as $change)
                            @php($delta = $change['newPrice'] - $change['oldPrice'])
                            <div class="flex gap-4 py-4 first:pt-0 last:pb-0" data-change-item data-product-id="{{ $product['id'] }}">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $delta > 0 ? 'bg-rose-100 text-rose-700' : ($delta < 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $delta > 0 ? '↗' : ($delta < 0 ? '↘' : '—') }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-950">{{ $change['productName'] }} {{ $delta > 0 ? 'naik' : ($delta < 0 ? 'turun' : 'tetap') }} {{ $delta === 0 ? '' : 'Rp '.number_format(abs($delta), 0, ',', '.') }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ \Illuminate\Support\Carbon::parse($change['changedAt'])->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</p>
                                </div>
                            </div>
                            @endforeach
                        @endforeach
                    </div>
                    <div id="last-change-empty" class="hidden rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">
                        Belum ada histori perubahan harga untuk produk ini.
                    </div>
                </article>
            </div>
        </div>

        <article class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-2xl font-bold text-slate-950">Histori Lengkap</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @forelse ($latestChanges->take(24) as $change)
                    @php($delta = $change['newPrice'] - $change['oldPrice'])
                    <div class="rounded-2xl border border-slate-200 p-4" data-full-history-item data-product-id="{{ $change['productId'] }}">
                        <p class="font-semibold text-slate-950">{{ $change['productName'] }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $change['oldPrice'] === null ? '-' : 'Rp '.number_format($change['oldPrice'], 0, ',', '.') }} → {{ $change['newPrice'] === null ? '-' : 'Rp '.number_format($change['newPrice'], 0, ',', '.') }}</p>
                        <p class="mt-2 text-xs font-semibold {{ $delta > 0 ? 'text-rose-600' : ($delta < 0 ? 'text-emerald-600' : 'text-slate-500') }}">{{ $delta > 0 ? '+Rp'.number_format($delta, 0, ',', '.') : ($delta < 0 ? '-Rp'.number_format(abs($delta), 0, ',', '.') : 'Tetap') }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada data histori.</p>
                @endforelse
            </div>
            <p id="full-history-empty" class="mt-6 hidden rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">Belum ada histori lengkap untuk produk ini.</p>
        </article>
    @endif
</section>

<script>
    window.PANTAU_BBM_TRENDS = @json($trendPayload);

    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('trend-product');
        const chart = document.getElementById('trend-chart');
        const empty = document.getElementById('trend-empty');
        const summary = document.getElementById('trend-summary');
        const badge = document.getElementById('trend-badge');
        const changeItems = document.querySelectorAll('[data-change-item]');
        const fullHistoryItems = document.querySelectorAll('[data-full-history-item]');
        const changeEmpty = document.getElementById('last-change-empty');
        const fullHistoryEmpty = document.getElementById('full-history-empty');
        const changeTitle = document.getElementById('last-change-product');

        if (!select || !chart || !empty) return;

        const render = () => {
            const data = window.PANTAU_BBM_TRENDS[select.value] || [];
            const selectedProduct = select.options[select.selectedIndex]?.textContent || 'Produk';
            chart.innerHTML = '';

            changeTitle.textContent = selectedProduct;
            badge.textContent = selectedProduct;

            let visibleChanges = 0;
            changeItems.forEach((item) => {
                const isVisible = item.dataset.productId === select.value;
                item.classList.toggle('hidden', !isVisible);
                visibleChanges += isVisible ? 1 : 0;
            });

            changeEmpty.classList.toggle('hidden', visibleChanges > 0);

            let visibleFullHistory = 0;
            fullHistoryItems.forEach((item) => {
                const isVisible = item.dataset.productId === select.value;
                item.classList.toggle('hidden', !isVisible);
                visibleFullHistory += isVisible ? 1 : 0;
            });

            fullHistoryEmpty.classList.toggle('hidden', visibleFullHistory > 0);

            if (data.length === 0) {
                chart.classList.add('hidden');
                empty.classList.remove('hidden');
                summary.textContent = 'Histori belum tersedia untuk produk ini.';
                return;
            }

            chart.classList.remove('hidden');
            empty.classList.add('hidden');

            const prices = data.map((item) => Number(item.price || 0));
            const max = Math.max(...prices, 1);
            const min = Math.min(...prices, max);
            const range = Math.max(max - min, 1);
            const first = prices[0] || 0;
            const last = prices[prices.length - 1] || 0;
            const delta = last - first;

            summary.textContent = delta > 0
                ? `Naik Rp${new Intl.NumberFormat('id-ID').format(delta)} dari awal histori.`
                : delta < 0
                    ? `Turun Rp${new Intl.NumberFormat('id-ID').format(Math.abs(delta))} dari awal histori.`
                    : 'Harga stabil sepanjang histori yang tersedia.';

            data.forEach((item, index) => {
                const height = 35 + ((Number(item.price || 0) - min) / range) * 55;
                const wrapper = document.createElement('div');
                wrapper.className = 'group flex min-w-12 flex-1 flex-col items-center gap-2';
                const tone = index === data.length - 1 ? 'bg-slate-950' : 'bg-slate-300';
                wrapper.innerHTML = `
                    <div class="relative flex h-full w-full flex-col justify-end">
                        <div class="w-full ${tone} rounded-t-xl transition-all group-hover:opacity-80" style="height: ${height}%"></div>
                        <div class="pointer-events-none absolute -top-12 left-1/2 hidden -translate-x-1/2 rounded-lg bg-slate-950 px-3 py-2 text-center text-xs font-semibold text-white shadow-lg group-hover:block">
                            Rp${new Intl.NumberFormat('id-ID').format(Number(item.price || 0))}
                            <div class="mt-1 text-[10px] font-normal text-slate-200">${item.label}</div>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500">${item.label}</span>`;
                chart.appendChild(wrapper);
            });

            const axis = document.createElement('div');
            axis.className = 'mt-4 flex items-center justify-between text-xs text-slate-400';
            axis.innerHTML = '<span>Rendah</span><span>Tinggi</span>';
            chart.appendChild(axis);
        };

        select.addEventListener('change', render);
        render();
    });
</script>
@endsection
