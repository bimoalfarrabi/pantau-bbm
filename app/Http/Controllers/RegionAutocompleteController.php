<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RegionAutocompleteController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $search = Str::of((string) $request->query('q', ''))
            ->trim()
            ->lower()
            ->squish()
            ->toString();

        $cacheKey = 'regions.autocomplete.v3.'.sha1($search);
        $cachedRegions = Cache::store('file')->get($cacheKey);

        if ($cachedRegions !== null) {
            return response()->json([
                'data' => $cachedRegions,
            ]);
        }

        $regions = $this->searchRegions($search);

        if ($search === '' || count($regions) > 0) {
            Cache::store('file')->put($cacheKey, $regions, now()->addMinutes(30));
        }

        return response()->json([
            'data' => $regions,
        ]);
    }

    private function searchRegions(string $search): array
    {
        try {
            return Region::query()
                ->when($search !== '', function ($query) use ($search): void {
                    $slugSearch = str($search)->slug()->toString();

                    $query->where(function ($query) use ($search, $slugSearch): void {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$slugSearch}%");
                    })
                        ->orderByRaw('case when lower(name) like ? then 0 when slug like ? then 1 else 2 end', ["{$search}%", "{$slugSearch}%"]);
                })
                ->orderBy('name')
                ->limit(8)
                ->get(['id', 'name', 'slug'])
                ->map(fn (Region $region): array => [
                    'id' => $region->id,
                    'name' => $region->name,
                    'slug' => $region->slug,
                    'url' => route('regions.show', $region->slug),
                ])
                ->values()
                ->all();
        } catch (QueryException) {
            return [];
        }
    }
}
