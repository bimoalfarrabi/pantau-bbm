<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegionCountTest extends TestCase
{
    public function test_region_count_returns_json_shape(): void
    {
        $response = $this->getJson('/wilayah/count');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'regions',
                'prices',
            ],
        ]);
    }
}
