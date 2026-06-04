<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Settings\SettingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function index(SettingRepository $settings): Response
    {
        return Inertia::render('Admin/Settings', [
            'settings' => [
                'FUEL_API_BASE_URL' => $settings->get('fuel.api.base_url', config('fuel.api.base_url')),
                'FUEL_API_TIMEOUT' => $settings->get('fuel.api.timeout', config('fuel.api.timeout')),
                'FUEL_API_RETRY_ATTEMPTS' => $settings->get('fuel.api.retry_attempts', config('fuel.api.retry_attempts')),
                'FUEL_API_RETRY_SLEEP_MS' => $settings->get('fuel.api.retry_sleep_ms', config('fuel.api.retry_sleep_ms')),
                'FUEL_API_USER_AGENT' => $settings->get('fuel.api.user_agent', config('fuel.api.user_agent')),
                'FUEL_SYNC_SCHEDULE_CRON' => $settings->get('fuel.sync.schedule_cron', config('fuel.sync.schedule_cron')),
                'FUEL_SYNC_LOCK_STORE' => $settings->get('fuel.sync.lock_store', config('fuel.sync.lock_store')),
                'FUEL_SYNC_LOCK_SECONDS' => $settings->get('fuel.sync.lock_seconds', config('fuel.sync.lock_seconds')),
                'FUEL_SYNC_CACHE_STORE' => $settings->get('fuel.sync.cache_store', config('fuel.sync.cache_store')),
            ],
        ]);
    }

    public function update(Request $request, SettingRepository $settings): RedirectResponse
    {
        $validated = $request->validate([
            'FUEL_API_BASE_URL' => ['required', 'url'],
            'FUEL_API_TIMEOUT' => ['required', 'integer', 'min:1'],
            'FUEL_API_RETRY_ATTEMPTS' => ['required', 'integer', 'min:0'],
            'FUEL_API_RETRY_SLEEP_MS' => ['required', 'integer', 'min:0'],
            'FUEL_API_USER_AGENT' => ['required', 'string', 'max:255'],
            'FUEL_SYNC_SCHEDULE_CRON' => ['required', 'string', 'max:255'],
            'FUEL_SYNC_LOCK_STORE' => ['required', 'string', 'max:255'],
            'FUEL_SYNC_LOCK_SECONDS' => ['required', 'integer', 'min:1'],
            'FUEL_SYNC_CACHE_STORE' => ['required', 'string', 'max:255'],
        ]);

        $settingKeys = [
            'FUEL_API_BASE_URL' => 'fuel.api.base_url',
            'FUEL_API_TIMEOUT' => 'fuel.api.timeout',
            'FUEL_API_RETRY_ATTEMPTS' => 'fuel.api.retry_attempts',
            'FUEL_API_RETRY_SLEEP_MS' => 'fuel.api.retry_sleep_ms',
            'FUEL_API_USER_AGENT' => 'fuel.api.user_agent',
            'FUEL_SYNC_SCHEDULE_CRON' => 'fuel.sync.schedule_cron',
            'FUEL_SYNC_LOCK_STORE' => 'fuel.sync.lock_store',
            'FUEL_SYNC_LOCK_SECONDS' => 'fuel.sync.lock_seconds',
            'FUEL_SYNC_CACHE_STORE' => 'fuel.sync.cache_store',
        ];

        foreach ($settingKeys as $inputKey => $settingKey) {
            $settings->set($settingKey, $validated[$inputKey]);
        }

        return back()->with('status', 'Pengaturan operasional tersimpan.');
    }
}
