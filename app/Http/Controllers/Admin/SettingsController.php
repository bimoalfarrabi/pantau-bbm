<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Settings\SettingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
                'ABOUT_MISSION_TITLE' => $settings->get('about.mission.title', 'The Mission'),
                'ABOUT_MISSION_BODY' => $settings->get('about.mission.body', 'PantauBBM dibangun dengan fokus tunggal: bikin pemantauan harga BBM di Indonesia terasa jelas, cepat, dan mudah dipakai. Di tengah perubahan harga yang bisa memengaruhi logistik, mobilitas, dan biaya harian, data yang rapi jadi kebutuhan utama.'),
                'ABOUT_CREATOR_NAME' => $settings->get('about.creator.name', 'Pantau Dev Team'),
                'ABOUT_CREATOR_SUBTITLE' => $settings->get('about.creator.subtitle', 'Systems Engineering'),
                'ABOUT_CREATOR_DESCRIPTION' => $settings->get('about.creator.description', 'Tim kecil yang fokus bangun alat data publik yang cepat, sederhana, dan enak dipakai. Kami percaya utility app tetap bisa terasa rapi, ringan, dan punya detail visual yang matang.'),
                'ABOUT_CREATOR_PHOTO_URL' => $settings->get('about.creator.photo_url', ''),
                'ABOUT_CREATOR_GITHUB_USERNAME' => $settings->get('about.creator.github_username', ''),
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
            'ABOUT_MISSION_TITLE' => ['required', 'string', 'max:255'],
            'ABOUT_MISSION_BODY' => ['required', 'string'],
            'ABOUT_CREATOR_NAME' => ['required', 'string', 'max:255'],
            'ABOUT_CREATOR_SUBTITLE' => ['required', 'string', 'max:255'],
            'ABOUT_CREATOR_DESCRIPTION' => ['required', 'string'],
            'ABOUT_CREATOR_PHOTO_URL' => ['nullable', 'url'],
            'ABOUT_CREATOR_GITHUB_USERNAME' => ['nullable', 'string', 'max:255'],
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
            'ABOUT_MISSION_TITLE' => 'about.mission.title',
            'ABOUT_MISSION_BODY' => 'about.mission.body',
            'ABOUT_CREATOR_NAME' => 'about.creator.name',
            'ABOUT_CREATOR_SUBTITLE' => 'about.creator.subtitle',
            'ABOUT_CREATOR_DESCRIPTION' => 'about.creator.description',
            'ABOUT_CREATOR_PHOTO_URL' => 'about.creator.photo_url',
            'ABOUT_CREATOR_GITHUB_USERNAME' => 'about.creator.github_username',
        ];

        foreach ($settingKeys as $inputKey => $settingKey) {
            $settings->set($settingKey, $validated[$inputKey]);
        }

        return back()->with('status', 'Pengaturan operasional tersimpan.');
    }

    public function fetchGithubProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
        ]);

        $response = Http::acceptJson()
            ->withHeaders([
                'User-Agent' => config('fuel.api.user_agent', 'PantauBBM/1.0'),
            ])
            ->timeout(10)
            ->get(sprintf('https://api.github.com/users/%s', rawurlencode($validated['username'])));

        if (! $response->successful()) {
            return response()->json([
                'message' => 'Gagal mengambil profil GitHub.',
            ], 422);
        }

        $profile = $response->json();

        return response()->json([
            'username' => $profile['login'] ?? $validated['username'],
            'name' => $profile['name'] ?? '',
            'bio' => $profile['bio'] ?? '',
            'avatar_url' => $profile['avatar_url'] ?? '',
            'html_url' => $profile['html_url'] ?? '',
        ]);
    }
}
