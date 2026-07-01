<?php

namespace App\Http\Controllers;

use App\Models\FuelPrice;
use App\Models\FuelProduct;
use App\Models\Region;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);
        $activeProductSlug = (string) $request->query('product', 'all');
        $sortMode = (string) $request->query('sort', 'lowest');
        $perPage = $this->perPage((int) $request->query('perPage', 6));
        $page = max(1, (int) $request->query('page', 1));

        $regionalPrices = $this->regionalPrices($activeProductSlug, $sortMode, $page, $perPage);

        return Inertia::render('Home', [
            'regions' => $this->regions($search),
            'filters' => [
                'search' => $search,
                'product' => $activeProductSlug,
                'sort' => $sortMode,
                'page' => $page,
                'perPage' => $perPage,
            ],
            'products' => $this->products(),
            'regionalPrices' => $regionalPrices['data'],
            'regionalMeta' => [
                'currentPage' => $regionalPrices['currentPage'],
                'lastPage' => $regionalPrices['lastPage'],
                'perPage' => $perPage,
                'total' => $regionalPrices['total'],
            ],
            'seo' => [
                'title' => 'PantauBBM - Pantau Harga BBM Indonesia',
                'description' => 'Pantau harga Pertalite, Pertamax, Dexlite, dan BBM lainnya berdasarkan daerah di Indonesia.',
                'canonical' => route('home'),
            ],
        ]);
    }

    private function regions(string $search): array
    {
        try {
            return Region::query()
                ->when($search !== '', function ($query) use ($search): void {
                    $slugSearch = str($search)->slug();
                    $query->where(function ($query) use ($search, $slugSearch): void {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$slugSearch}%");
                    })->orderByRaw('case when name like ? then 0 when slug like ? then 1 else 2 end', ["{$search}%", "{$slugSearch}%"]);
                })
                ->orderBy('name')
                ->limit(8)
                ->get(['id', 'name', 'slug'])
                ->toArray();
        } catch (QueryException) {
            return [];
        }
    }

    private function products(): array
    {
        try {
            return FuelProduct::query()->orderBy('sort_order')->get(['id', 'name', 'slug', 'sort_order'])->toArray();
        } catch (QueryException) {
            return [];
        }
    }

    private function regionalPrices(string $activeProductSlug, string $sortMode, int $page, int $perPage): array
    {
        try {
            $regions = Region::query()
                ->with(['fuelPrices.fuelProduct'])
                ->orderBy('name')
                ->get()
                ->map(function (Region $region): array {
                    return [
                        'name' => $region->name,
                        'slug' => $region->slug,
                        'prices' => $region->fuelPrices
                            ->sortBy(fn ($price) => $price->fuelProduct?->sort_order ?? 0)
                            ->map(fn (FuelPrice $price): array => [
                                'name' => $price->fuelProduct?->name,
                                'slug' => $price->fuelProduct?->slug,
                                'price' => $price->price,
                            ])
                            ->values()
                            ->all(),
                    ];
                });

            if ($activeProductSlug !== 'all') {
                $regions = $regions
                    ->map(function (array $region) use ($activeProductSlug): array {
                        $region['prices'] = array_values(array_filter($region['prices'], fn ($price) => $price['slug'] === $activeProductSlug));

                        return $region;
                    })
                    ->filter(fn (array $region): bool => count($region['prices']) > 0)
                    ->values();
            }

            $regions = $regions->sort(function (array $firstRegion, array $secondRegion) use ($sortMode): int {
                if ($sortMode === 'highest') {
                    return $this->maxRegionPrice($secondRegion) <=> $this->maxRegionPrice($firstRegion);
                }

                if ($sortMode === 'lowest') {
                    return $this->minRegionPrice($firstRegion) <=> $this->minRegionPrice($secondRegion);
                }

                return strcasecmp($firstRegion['name'], $secondRegion['name']);
            })->values();

            $total = $regions->count();
            $lastPage = max(1, (int) ceil($total / $perPage));
            $page = min($page, $lastPage);
            $offset = ($page - 1) * $perPage;
            $pageItems = $regions->slice($offset, $perPage)->values()->all();

            return [
                'data' => $pageItems,
                'currentPage' => $page,
                'lastPage' => $lastPage,
                'total' => $total,
            ];
        } catch (QueryException) {
            return [
                'data' => [],
                'currentPage' => 1,
                'lastPage' => 1,
                'total' => 0,
            ];
        }
    }

    private function maxRegionPrice(array $region): int
    {
        return (int) max(array_map(fn (array $price): int => (int) ($price['price'] ?? 0), $region['prices']));
    }

    private function minRegionPrice(array $region): int
    {
        $availablePrices = array_values(array_filter(array_map(fn (array $price) => $price['price'], $region['prices']), fn ($price) => $price !== null));

        return count($availablePrices) > 0 ? (int) min($availablePrices) : PHP_INT_MAX;
    }

    private function perPage(int $perPage): int
    {
        return in_array($perPage, [6, 9, 12], true) ? $perPage : 6;
    }
}
