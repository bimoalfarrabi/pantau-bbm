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
                'ABOUT_SOURCES_TITLE' => $settings->get('about.sources.title', 'Sources & Credits'),
                'ABOUT_SOURCE_ONE_TITLE' => $settings->get('about.source_one.title', 'Bensin API'),
                'ABOUT_SOURCE_ONE_DESCRIPTION' => $settings->get('about.source_one.description', 'Sumber utama harga BBM yang dipakai untuk sinkronisasi data dan pembaruan tampilan.'),
                'ABOUT_SOURCE_ONE_LINK_LABEL' => $settings->get('about.source_one.link_label', 'Buka link'),
                'ABOUT_SOURCE_ONE_URL' => $settings->get('about.source_one.url', ''),
                'ABOUT_SOURCE_TWO_TITLE' => $settings->get('about.source_two.title', 'Open Source Stack'),
                'ABOUT_SOURCE_TWO_DESCRIPTION' => $settings->get('about.source_two.description', 'Dibangun dengan Laravel, Tailwind CSS, dan komponen frontend yang ringan supaya pengalaman tetap konsisten.'),
                'ABOUT_SOURCE_TWO_LINK_LABEL' => $settings->get('about.source_two.link_label', 'Buka link'),
                'ABOUT_SOURCE_TWO_URL' => $settings->get('about.source_two.url', ''),
                'ABOUT_SOURCE_THREE_TITLE' => $settings->get('about.source_three.title', 'Disclaimer'),
                'ABOUT_SOURCE_THREE_DESCRIPTION' => $settings->get('about.source_three.description', 'PantauBBM adalah platform independen dan bukan situs resmi Pertamina atau pemerintah Indonesia.'),
                'ABOUT_SOURCE_THREE_LINK_LABEL' => $settings->get('about.source_three.link_label', 'Buka link'),
                'ABOUT_SOURCE_THREE_URL' => $settings->get('about.source_three.url', ''),
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
            'ABOUT_SOURCES_TITLE' => ['required', 'string', 'max:255'],
            'ABOUT_SOURCE_ONE_TITLE' => ['required', 'string', 'max:255'],
            'ABOUT_SOURCE_ONE_DESCRIPTION' => ['required', 'string'],
            'ABOUT_SOURCE_ONE_LINK_LABEL' => ['nullable', 'string', 'max:255'],
            'ABOUT_SOURCE_ONE_URL' => ['nullable', 'url'],
            'ABOUT_SOURCE_TWO_TITLE' => ['required', 'string', 'max:255'],
            'ABOUT_SOURCE_TWO_DESCRIPTION' => ['required', 'string'],
            'ABOUT_SOURCE_TWO_LINK_LABEL' => ['nullable', 'string', 'max:255'],
            'ABOUT_SOURCE_TWO_URL' => ['nullable', 'url'],
            'ABOUT_SOURCE_THREE_TITLE' => ['required', 'string', 'max:255'],
            'ABOUT_SOURCE_THREE_DESCRIPTION' => ['required', 'string'],
            'ABOUT_SOURCE_THREE_LINK_LABEL' => ['nullable', 'string', 'max:255'],
            'ABOUT_SOURCE_THREE_URL' => ['nullable', 'url'],
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
            'ABOUT_SOURCES_TITLE' => 'about.sources.title',
            'ABOUT_SOURCE_ONE_TITLE' => 'about.source_one.title',
            'ABOUT_SOURCE_ONE_DESCRIPTION' => 'about.source_one.description',
            'ABOUT_SOURCE_ONE_LINK_LABEL' => 'about.source_one.link_label',
            'ABOUT_SOURCE_ONE_URL' => 'about.source_one.url',
            'ABOUT_SOURCE_TWO_TITLE' => 'about.source_two.title',
            'ABOUT_SOURCE_TWO_DESCRIPTION' => 'about.source_two.description',
            'ABOUT_SOURCE_TWO_LINK_LABEL' => 'about.source_two.link_label',
            'ABOUT_SOURCE_TWO_URL' => 'about.source_two.url',
            'ABOUT_SOURCE_THREE_TITLE' => 'about.source_three.title',
            'ABOUT_SOURCE_THREE_DESCRIPTION' => 'about.source_three.description',
            'ABOUT_SOURCE_THREE_LINK_LABEL' => 'about.source_three.link_label',
            'ABOUT_SOURCE_THREE_URL' => 'about.source_three.url',
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
