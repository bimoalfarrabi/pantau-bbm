<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_homepage_renders_inertia_page_with_seo_props(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Home', false);
        $response->assertSee('PantauBBM - Pantau Harga BBM Indonesia', false);
        $response->assertSee('Pantau harga Pertalite, Pertamax, Dexlite, dan BBM lainnya berdasarkan daerah di Indonesia.', false);
    }

    public function test_homepage_accepts_regional_pagination_query_params(): void
    {
        $response = $this->get('/?page=2&perPage=9&product=all&sort=name');

        $response->assertOk();
        $response->assertSee('regionalMeta', false);
        $response->assertSee('perPage', false);
    }
}
