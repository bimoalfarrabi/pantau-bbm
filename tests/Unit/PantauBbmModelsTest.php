<?php

namespace Tests\Unit;

use App\Models\FuelPrice;
use App\Models\FuelPriceHistory;
use App\Models\FuelProduct;
use App\Models\Region;
use App\Models\SyncLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class PantauBbmModelsTest extends TestCase
{
    public function test_models_define_expected_fillable_casts_and_relationships(): void
    {
        $region = new Region();
        $product = new FuelProduct();
        $price = new FuelPrice();
        $history = new FuelPriceHistory();
        $syncLog = new SyncLog();

        $this->assertSame(['name', 'slug', 'source_key'], $region->getFillable());
        $this->assertSame(['name', 'slug', 'sort_order'], $product->getFillable());
        $this->assertSame(['region_id', 'fuel_product_id', 'price', 'last_synced_at'], $price->getFillable());
        $this->assertSame(['region_id', 'fuel_product_id', 'old_price', 'new_price', 'changed_at'], $history->getFillable());
        $this->assertSame(['source', 'status', 'message', 'started_at', 'finished_at'], $syncLog->getFillable());

        $this->assertSame('integer', $price->getCasts()['price']);
        $this->assertSame('datetime', $price->getCasts()['last_synced_at']);
        $this->assertSame('integer', $history->getCasts()['old_price']);
        $this->assertSame('integer', $history->getCasts()['new_price']);
        $this->assertSame('datetime', $history->getCasts()['changed_at']);
        $this->assertSame('datetime', $syncLog->getCasts()['started_at']);
        $this->assertSame('datetime', $syncLog->getCasts()['finished_at']);

        $this->assertRelation(Region::class, 'fuelPrices', HasMany::class);
        $this->assertRelation(Region::class, 'fuelPriceHistories', HasMany::class);
        $this->assertRelation(FuelProduct::class, 'fuelPrices', HasMany::class);
        $this->assertRelation(FuelProduct::class, 'fuelPriceHistories', HasMany::class);
        $this->assertRelation(FuelPrice::class, 'region', BelongsTo::class);
        $this->assertRelation(FuelPrice::class, 'fuelProduct', BelongsTo::class);
        $this->assertRelation(FuelPriceHistory::class, 'region', BelongsTo::class);
        $this->assertRelation(FuelPriceHistory::class, 'fuelProduct', BelongsTo::class);
    }

    private function assertRelation(string $modelClass, string $method, string $relationClass): void
    {
        $reflection = new ReflectionMethod($modelClass, $method);
        $this->assertTrue($reflection->hasReturnType());
        $this->assertSame($relationClass, $reflection->getReturnType()->getName());
    }
}
