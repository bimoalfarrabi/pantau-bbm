<?php

namespace App\Services\BensinApi;

use Illuminate\Support\Str;

class BensinApiPriceParser
{
    public function parsePrice(int|string|null $value): ?int
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim((string) $value);

        if ($normalized === '' || in_array(strtoupper($normalized), ['-', 'N/A'], true)) {
            return null;
        }

        $normalized = str_replace(['Rp', 'rp', '.', ' '], '', $normalized);

        if ($normalized === '' || ! is_numeric($normalized)) {
            return null;
        }

        return (int) $normalized;
    }

    public function parseRegions(array $payloads): array
    {
        return collect($payloads)
            ->map(fn (array $payload): array => $this->normalizeProvince($payload))
            ->values()
            ->all();
    }

    private function normalizeProvince(array $payload): array
    {
        $regionName = $payload['province'] ?? $payload['region_name'] ?? $payload['name'] ?? '';
        $regionSlug = $payload['province_slug'] ?? $payload['region_slug'] ?? $payload['slug'] ?? Str::slug($regionName);

        return [
            'region_name' => $regionName,
            'region_slug' => $regionSlug,
            'products' => collect($payload['products'] ?? [])
                ->map(fn (array $product): array => [
                    'name' => $this->normalizeProductName($product['product'] ?? $product['name'] ?? ''),
                    'slug' => Str::slug($this->normalizeProductName($product['product'] ?? $product['name'] ?? '')),
                    'price' => $this->parsePrice($product['price_rupiah'] ?? $product['price'] ?? null),
                    'effective_date' => $payload['pertamina_updated_at'] ?? $product['effective_date'] ?? null,
                ])
                ->all(),
        ];
    }

    private function normalizeProductName(string $name): string
    {
        return Str::of($name)->lower()->replace(['_', '-'], ' ')->title()->toString();
    }
}
