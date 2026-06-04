<?php

namespace App\Console\Commands;

use App\Models\SyncLog;
use App\Services\BensinApi\BensinApiClient;
use App\Services\BensinApi\BensinApiPriceParser;
use App\Services\FuelPrices\FuelPriceSynchronizer;
use App\Services\Settings\SettingRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Throwable;

class SyncFuelPricesCommand extends Command
{
    protected $signature = 'fuel:sync {--force : Force release an existing sync lock before running.}';

    protected $description = 'Sync Indonesian fuel prices from Bensin API.';

    public function handle(
        BensinApiClient $client,
        BensinApiPriceParser $parser,
        FuelPriceSynchronizer $synchronizer,
        SettingRepository $settings
    ): int {
        Config::set('fuel.api.base_url', $settings->get('fuel.api.base_url', config('fuel.api.base_url')));
        Config::set('fuel.api.timeout', (int) $settings->get('fuel.api.timeout', config('fuel.api.timeout')));
        Config::set('fuel.api.retry_attempts', (int) $settings->get('fuel.api.retry_attempts', config('fuel.api.retry_attempts')));
        Config::set('fuel.api.retry_sleep_ms', (int) $settings->get('fuel.api.retry_sleep_ms', config('fuel.api.retry_sleep_ms')));
        Config::set('fuel.api.user_agent', $settings->get('fuel.api.user_agent', config('fuel.api.user_agent')));
        Config::set('fuel.sync.lock_store', $settings->get('fuel.sync.lock_store', config('fuel.sync.lock_store')));
        Config::set('fuel.sync.lock_seconds', (int) $settings->get('fuel.sync.lock_seconds', config('fuel.sync.lock_seconds')));
        Config::set('fuel.sync.cache_store', $settings->get('fuel.sync.cache_store', config('fuel.sync.cache_store')));

        $lock = Cache::store(config('fuel.sync.lock_store'))
            ->lock(config('fuel.sync.lock_key'), config('fuel.sync.lock_seconds'));

        if ($this->option('force')) {
            $lock->forceRelease();
            $this->warn('Existing fuel sync lock released.');
        }

        if (! $lock->get()) {
            $this->warn('Fuel price sync is already running. Run `php artisan fuel:sync --force` only if you are sure no sync process is active.');

            return self::SUCCESS;
        }

        $syncLog = SyncLog::query()->create([
            'source' => 'bensin-api',
            'status' => 'running',
            'started_at' => Carbon::now(),
        ]);

        try {
            $payload = $client->fetchPrices();
            $regions = $parser->parseRegions($payload);

            $synchronizer->sync($regions);

            Cache::forget('fuel_prices.latest');
            Cache::forget('regions.all');
            Cache::store(config('fuel.sync.cache_store'))->forget('regions.counts');
            Cache::forget('homepage.summary');

            $syncLog->update([
                'status' => 'success',
                'message' => sprintf('Synced %d regions.', count($regions)),
                'finished_at' => Carbon::now(),
            ]);

            $this->info($syncLog->message);

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);

            $syncLog->update([
                'status' => 'failed',
                'message' => $exception->getMessage(),
                'finished_at' => Carbon::now(),
            ]);

            $this->error('Fuel price sync failed: '.$exception->getMessage());

            return self::FAILURE;
        } finally {
            $lock->release();
        }
    }
}
