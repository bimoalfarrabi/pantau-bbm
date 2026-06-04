<?php

namespace App\Http\Controllers;

use App\Models\FuelPrice;
use App\Models\Region;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class RegionCountController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $counts = Cache::store('file')->remember('regions.counts', now()->addMinutes(30), function (): array {
            try {
                return [
                    'regions' => Region::query()->count(),
                    'prices' => FuelPrice::query()->count(),
                ];
            } catch (QueryException) {
                return [
                    'regions' => 0,
                    'prices' => 0,
                ];
            }
        });

        return response()->json([
            'data' => $counts,
        ]);
    }
}
