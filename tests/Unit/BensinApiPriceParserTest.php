<?php

namespace Tests\Unit;

use App\Services\BensinApi\BensinApiPriceParser;
use PHPUnit\Framework\TestCase;

class BensinApiPriceParserTest extends TestCase
{
    public function test_parse_price_normalizes_currency_string_to_integer(): void
    {
        $parser = new BensinApiPriceParser();

        $this->assertSame(12100, $parser->parsePrice('Rp 12.100'));
    }

    public function test_parse_price_returns_null_for_unavailable_values(): void
    {
        $parser = new BensinApiPriceParser();

        $this->assertNull($parser->parsePrice('-'));
        $this->assertNull($parser->parsePrice('N/A'));
        $this->assertNull($parser->parsePrice('   '));
        $this->assertNull($parser->parsePrice(null));
    }

    public function test_parse_regions_normalizes_real_api_payload_into_rows(): void
    {
        $parser = new BensinApiPriceParser();

        $payloads = [[
            'province' => 'Prov. DKI Jakarta',
            'province_slug' => 'dki-jakarta',
            'pertamina_updated_at' => '2026-06-01T15:59:37.000Z',
            'products' => [
                ['product' => 'DEXLITE', 'price_rupiah' => 23000],
                ['product' => 'PERTALITE', 'price_rupiah' => 10000],
            ],
        ]];

        $this->assertSame([
            [
                'region_name' => 'Prov. DKI Jakarta',
                'region_slug' => 'dki-jakarta',
                'products' => [
                    [
                        'name' => 'Dexlite',
                        'slug' => 'dexlite',
                        'price' => 23000,
                        'effective_date' => '2026-06-01T15:59:37.000Z',
                    ],
                    [
                        'name' => 'Pertalite',
                        'slug' => 'pertalite',
                        'price' => 10000,
                        'effective_date' => '2026-06-01T15:59:37.000Z',
                    ],
                ],
            ],
        ], $parser->parseRegions($payloads));
    }
}
