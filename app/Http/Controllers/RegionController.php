<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;

class RegionController extends Controller
{
    public function show(string $slug): View
    {
        try {
            $region = Region::query()
                ->where('slug', $slug)
                ->with(['fuelPrices.fuelProduct'])
                ->first();
        } catch (QueryException) {
            $region = null;
        }

        return view('regions.show', [
            'region' => $region,
            'slug' => $slug,
        ]);
    }
}
