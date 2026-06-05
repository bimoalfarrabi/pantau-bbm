<?php

namespace App\Http\Controllers;

use App\Models\FuelPriceHistory;
use App\Models\Region;
use Illuminate\Database\QueryException;
use Inertia\Inertia;
use Inertia\Response;

class RegionController extends Controller
{
    public function show(string $slug): Response
    {
        try {
            $region = Region::query()
                ->where('slug', $slug)
                ->with(['fuelPrices.fuelProduct'])
                ->first();

            $historyQuery = FuelPriceHistory::query()->with(['fuelProduct', 'region'])->whereHas('region', fn ($query) => $query->where('slug', $slug));

            $historyProducts = (clone $historyQuery)
                ->select('fuel_product_id')
                ->distinct()
                ->orderBy('fuel_product_id')
                ->get()
                ->map(fn ($history): array => [
                    'id' => $history->fuel_product_id,
                    'name' => $history->fuelProduct?->name ?? 'Produk',
                ])
                ->values();

            $currentProducts = $region
                ? $region->fuelPrices
                    ->sortBy(fn ($price) => $price->fuelProduct?->sort_order ?? 0)
                    ->values()
                    ->map(fn ($price): array => [
                        'id' => $price->fuel_product_id,
                        'name' => $price->fuelProduct?->name ?? 'Produk',
                    ])
                : collect();

            $historyProducts = $historyProducts
                ->concat($currentProducts)
                ->unique('id')
                ->values();

            $historyEntries = $historyQuery
                ->latest('changed_at')
                ->limit(80)
                ->get()
                ->groupBy('fuel_product_id')
                ->map(fn ($entries): array => $entries->map(fn (FuelPriceHistory $entry): array => [
                    'productId' => $entry->fuel_product_id,
                    'changedAt' => $entry->changed_at?->timezone('Asia/Jakarta')?->toDateTimeString(),
                    'oldPrice' => $entry->old_price,
                    'newPrice' => $entry->new_price,
                    'productName' => $entry->fuelProduct?->name,
                ])->values()->all())
                ->all();
        } catch (QueryException) {
            $region = null;
            $historyProducts = collect();
            $historyEntries = [];
        }

        return Inertia::render('Regions/Show', [
            'region' => $region ? [
                'name' => $region->name,
                'slug' => $region->slug,
                'prices' => $region->fuelPrices
                    ->sortBy(fn ($price) => $price->fuelProduct?->sort_order ?? 0)
                    ->values()
                    ->map(fn ($price): array => [
                        'fuel_product_id' => $price->fuel_product_id,
                        'price' => $price->price,
                        'last_synced_at' => $price->last_synced_at?->timezone('Asia/Jakarta')?->toDateTimeString(),
                        'product' => [
                            'name' => $price->fuelProduct?->name,
                            'slug' => $price->fuelProduct?->slug,
                            'sort_order' => $price->fuelProduct?->sort_order,
                        ],
                    ])
                    ->values()
                    ->all(),
            ] : null,
            'slug' => $slug,
            'historyProducts' => $historyProducts,
            'historyEntries' => $historyEntries,
            'seo' => [
                'title' => 'Harga BBM '.($region?->name ?? str($slug)->replace('-', ' ')->title()).' Terbaru - PantauBBM',
                'description' => 'Lihat harga BBM terbaru untuk wilayah '.($region?->name ?? str($slug)->replace('-', ' ')->title()).', tren harga, dan histori perubahan terakhir.',
                'canonical' => route('regions.show', $slug),
            ],
        ]);
    }
}
