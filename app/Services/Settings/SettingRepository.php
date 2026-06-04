<?php

namespace App\Services\Settings;

use App\Models\Setting;

class SettingRepository
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Setting::query()->where('key', $key)->value('value') ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        Setting::query()->updateOrCreate([
            'key' => $key,
        ], [
            'value' => $value === null ? null : (string) $value,
        ]);
    }
}
