<?php

namespace Database\Seeders;

use App\Models\FuelProduct;
use Illuminate\Database\Seeder;

class FuelCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Pertalite', 'slug' => 'pertalite', 'sort_order' => 10],
            ['name' => 'Pertamax', 'slug' => 'pertamax', 'sort_order' => 20],
            ['name' => 'Pertamax Turbo', 'slug' => 'pertamax-turbo', 'sort_order' => 30],
            ['name' => 'Dexlite', 'slug' => 'dexlite', 'sort_order' => 40],
        ];

        foreach ($products as $product) {
            FuelProduct::query()->updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}
