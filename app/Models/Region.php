<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'source_key',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function fuelPrices(): HasMany
    {
        return $this->hasMany(FuelPrice::class);
    }

    public function fuelPriceHistories(): HasMany
    {
        return $this->hasMany(FuelPriceHistory::class);
    }
}
