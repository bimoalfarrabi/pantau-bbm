<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'fuel.api.base_url' => 'https://api.bensin.example',
            'fuel.api.timeout' => 30,
            'fuel.api.retry_attempts' => 3,
            'fuel.api.retry_sleep_ms' => 500,
            'fuel.api.user_agent' => 'PantauBBM/1.0',
            'fuel.sync.schedule_cron' => '*/30 * * * *',
            'fuel.sync.lock_store' => 'file',
            'fuel.sync.lock_seconds' => 1800,
            'fuel.sync.cache_store' => 'file',
            'about.mission.title' => 'The Mission',
            'about.mission.body' => "PantauBBM dibangun dengan fokus tunggal: bikin pemantauan harga BBM di Indonesia terasa jelas, cepat, dan mudah dipakai. Di tengah perubahan harga yang bisa memengaruhi logistik, mobilitas, dan biaya harian, data yang rapi jadi kebutuhan utama.\n\nKami memangkas noise. Tanpa gangguan visual yang tidak perlu, tanpa lapisan informasi yang membingungkan. Hanya data yang bersih, tren yang mudah dibaca, dan akses yang cepat supaya keputusan bisa diambil tanpa ribet.",
            'about.creator.name' => 'Pantau Dev Team',
            'about.creator.subtitle' => 'Systems Engineering',
            'about.creator.description' => 'Tim kecil yang fokus bangun alat data publik yang cepat, sederhana, dan enak dipakai. Kami percaya utility app tetap bisa terasa rapi, ringan, dan punya detail visual yang matang.',
            'about.creator.photo_url' => '',
            'about.sources.title' => 'Sources & Credits',
            'about.source_one.title' => 'Bensin API',
            'about.source_one.description' => 'Sumber utama harga BBM yang dipakai untuk sinkronisasi data dan pembaruan tampilan.',
            'about.source_one.link_label' => 'Buka link',
            'about.source_one.url' => '',
            'about.source_two.title' => 'Open Source Stack',
            'about.source_two.description' => 'Dibangun dengan Laravel, Tailwind CSS, dan komponen frontend yang ringan supaya pengalaman tetap konsisten.',
            'about.source_two.link_label' => 'Buka link',
            'about.source_two.url' => '',
            'about.source_three.title' => 'Disclaimer',
            'about.source_three.description' => 'PantauBBM adalah platform independen dan bukan situs resmi Pertamina atau pemerintah Indonesia.',
            'about.source_three.link_label' => 'Buka link',
            'about.source_three.url' => '',
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->firstOrCreate([
                'key' => $key,
            ], [
                'value' => (string) $value,
            ]);
        }
    }
}
