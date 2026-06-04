<?php

namespace App\Http\Controllers;

use App\Models\FuelPriceHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;

class HistoryController extends Controller
{
    public function __invoke(): View
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

        return view('history.index', [
            'histories' => $histories,
        ]);
    }
}
