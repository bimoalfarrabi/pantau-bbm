<?php

namespace Tests\Unit;

use App\Models\FuelPrice;
use App\Models\FuelPriceHistory;
use App\Models\FuelProduct;
use App\Models\Region;
use App\Services\FuelPrices\FuelPriceSynchronizer;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FuelPriceSynchronizerTest extends TestCase
{
    #[Test]
    public function first_sync_creates_prices_without_history(): void
    {
        $synchronizer = new FakeFuelPriceSynchronizer();

        $synchronizer->sync($this->payload(12100));

        $this->assertSame(1, $synchronizer->regionsCount());
        $this->assertSame(1, $synchronizer->productsCount());
        $this->assertSame(1, $synchronizer->pricesCount());
        $this->assertSame(0, $synchronizer->historiesCount());
        $this->assertSame(12100, $synchronizer->priceValue());
    }

    #[Test]
    public function unchanged_sync_does_not_create_history(): void
    {
        $synchronizer = new FakeFuelPriceSynchronizer();

        $synchronizer->sync($this->payload(12100));
        $synchronizer->sync($this->payload(12100));

        $this->assertSame(0, $synchronizer->historiesCount());
        $this->assertSame(1, $synchronizer->pricesCount());
    }

    #[Test]
    public function changed_sync_creates_one_history(): void
    {
        $synchronizer = new FakeFuelPriceSynchronizer();

        $synchronizer->sync($this->payload(12100));
        $synchronizer->sync($this->payload(12500));

        $this->assertSame(1, $synchronizer->historiesCount());
        $this->assertSame(12100, $synchronizer->historyOldPrice());
        $this->assertSame(12500, $synchronizer->historyNewPrice());
        $this->assertSame(12500, $synchronizer->priceValue());
    }

    private function payload(int $price): array
    {
        return [[
            'region_name' => 'Jawa Timur',
            'region_slug' => 'jawa-timur',
            'products' => [[
                'name' => 'Pertamax',
                'slug' => 'pertamax',
                'price' => $price,
                'effective_date' => '2026-06-01',
            ]],
        ]];
    }
}

class FakeFuelPriceSynchronizer extends FuelPriceSynchronizer
{
    /** @var array<string, Region> */
    private array $regions = [];

    /** @var array<string, FuelProduct> */
    private array $products = [];

    /** @var array<string, FuelPrice> */
    private array $prices = [];

    /** @var list<array{old_price:?int,new_price:?int}> */
    private array $histories = [];

    public function sync(array $normalizedRegions): void
    {
        foreach ($normalizedRegions as $normalizedRegion) {
            $region = $this->upsertRegion($normalizedRegion);

            foreach ($normalizedRegion['products'] ?? [] as $normalizedProduct) {
                $product = $this->upsertProduct($normalizedProduct);
                $this->syncPrice($region, $product, $normalizedProduct);
            }
        }
    }

    protected function upsertRegion(array $normalizedRegion): Region
    {
        $slug = $normalizedRegion['region_slug'];
        $region = $this->regions[$slug] ?? new Region();
        $region->id ??= count($this->regions) + 1;
        $region->name = $normalizedRegion['region_name'];
        $region->slug = $slug;
        $region->source_key = $slug;
        $this->regions[$slug] = $region;

        return $region;
    }

    protected function upsertProduct(array $normalizedProduct): FuelProduct
    {
        $slug = $normalizedProduct['slug'];
        $product = $this->products[$slug] ?? new FuelProduct();
        $product->id ??= count($this->products) + 1;
        $product->name = $normalizedProduct['name'];
        $product->slug = $slug;
        $product->sort_order = 0;
        $this->products[$slug] = $product;

        return $product;
    }

    protected function existingPrice(Region $region, FuelProduct $product): FuelPrice
    {
        $key = $region->id.'|'.$product->id;

        if (! isset($this->prices[$key])) {
            $price = new FuelPrice();
            $price->exists = false;
            $price->region_id = $region->id;
            $price->fuel_product_id = $product->id;
            return $price;
        }

        return $this->prices[$key];
    }

    protected function createHistory(Region $region, FuelProduct $product, ?int $oldPrice, ?int $newPrice): FuelPriceHistory
    {
        $history = new FuelPriceHistory();
        $history->old_price = $oldPrice;
        $history->new_price = $newPrice;
        $this->histories[] = [
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
        ];

        return $history;
    }

    protected function savePrice(FuelPrice $price, ?int $newPrice): void
    {
        $price->exists = true;
        $price->price = $newPrice;
        $key = $price->region_id.'|'.$price->fuel_product_id;
        $this->prices[$key] = $price;
    }

    public function regionsCount(): int
    {
        return count($this->regions);
    }

    public function productsCount(): int
    {
        return count($this->products);
    }

    public function pricesCount(): int
    {
        return count($this->prices);
    }

    public function historiesCount(): int
    {
        return count($this->histories);
    }

    public function priceValue(): ?int
    {
        return array_values($this->prices)[0]->price ?? null;
    }

    public function historyOldPrice(): ?int
    {
        return $this->histories[0]['old_price'] ?? null;
    }

    public function historyNewPrice(): ?int
    {
        return $this->histories[0]['new_price'] ?? null;
    }
}
