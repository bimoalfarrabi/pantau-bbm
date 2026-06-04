<?php

namespace App\Http\Controllers;

use App\Models\FuelPriceHistory;
use App\Models\Region;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;

class RegionController extends Controller
{
    public function show(string $slug): View
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

        return view('regions.show', [
            'region' => $region,
            'slug' => $slug,
            'historyProducts' => $historyProducts,
            'historyEntries' => $historyEntries,
        ]);
    }
}
