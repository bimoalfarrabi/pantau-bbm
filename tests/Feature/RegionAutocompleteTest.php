<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegionAutocompleteTest extends TestCase
{
    public function test_region_autocomplete_returns_json_shape(): void
    {
        $response = $this->getJson('/wilayah/autocomplete?q=jawa');

        $response->assertOk();
        $response->assertJsonStructure([
            'data',
        ]);
    }
}
