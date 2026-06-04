<?php

namespace App\Services\FuelPrices;

use App\Models\FuelPrice;
use App\Models\FuelPriceHistory;
use App\Models\FuelProduct;
use App\Models\Region;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FuelPriceSynchronizer
{
    public function sync(array $normalizedRegions): void
    {
        DB::transaction(function () use ($normalizedRegions): void {
            foreach ($normalizedRegions as $normalizedRegion) {
                $region = $this->upsertRegion($normalizedRegion);

                foreach ($normalizedRegion['products'] ?? [] as $normalizedProduct) {
                    $product = $this->upsertProduct($normalizedProduct);
                    $this->syncPrice($region, $product, $normalizedProduct);
                }
            }
        });
    }

    protected function upsertRegion(array $normalizedRegion): Region
    {
        return Region::query()->updateOrCreate(
            ['slug' => $normalizedRegion['region_slug']],
            [
                'name' => $normalizedRegion['region_name'],
                'source_key' => $normalizedRegion['region_slug'],
            ]
        );
    }

    protected function upsertProduct(array $normalizedProduct): FuelProduct
    {
        return FuelProduct::query()->updateOrCreate(
            ['slug' => $normalizedProduct['slug']],
            [
                'name' => $normalizedProduct['name'],
                'sort_order' => Arr::get($normalizedProduct, 'sort_order', 0),
            ]
        );
    }

    protected function existingPrice(Region $region, FuelProduct $product): ?FuelPrice
    {
        return FuelPrice::query()->firstOrNew([
            'region_id' => $region->id,
            'fuel_product_id' => $product->id,
        ]);
    }

    protected function createHistory(Region $region, FuelProduct $product, ?int $oldPrice, ?int $newPrice): FuelPriceHistory
    {
        return FuelPriceHistory::query()->create([
            'region_id' => $region->id,
            'fuel_product_id' => $product->id,
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
            'changed_at' => Carbon::now(),
        ]);
    }

    protected function savePrice(FuelPrice $price, ?int $newPrice): void
    {
        $price->price = $newPrice;
        $price->last_synced_at = Carbon::now();
        $price->save();
    }

    protected function syncPrice(Region $region, FuelProduct $product, array $normalizedProduct): void
    {
        $currentPrice = $this->existingPrice($region, $product);
        $newPrice = Arr::get($normalizedProduct, 'price');
        $oldPrice = $currentPrice->exists ? $currentPrice->price : null;

        if (! $currentPrice->exists) {
            $this->savePrice($currentPrice, $newPrice);

            return;
        }

        if ($oldPrice !== $newPrice) {
            $this->createHistory($region, $product, $oldPrice, $newPrice);
        }

        $this->savePrice($currentPrice, $newPrice);
    }
}
