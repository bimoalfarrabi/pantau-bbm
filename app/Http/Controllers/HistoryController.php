<?php

namespace App\Http\Controllers;

use App\Models\FuelPriceHistory;
use Illuminate\Database\QueryException;
use Inertia\Inertia;
use Inertia\Response;

class HistoryController extends Controller
{
    public function __invoke(): Response
    {
        try {
            $histories = FuelPriceHistory::query()
                ->with(['region', 'fuelProduct'])
                ->latest('changed_at')
                ->limit(50)
                ->get();
        } catch (QueryException) {
            $histories = collect();
        }

        return Inertia::render('History/Index', [
            'histories' => $histories->map(fn (FuelPriceHistory $history): array => [
                'changedAt' => $history->changed_at?->timezone('Asia/Jakarta')?->toDateTimeString(),
                'regionName' => $history->region?->name,
                'productName' => $history->fuelProduct?->name,
                'oldPrice' => $history->old_price,
                'newPrice' => $history->new_price,
            ]),
        ]);
    }
}
