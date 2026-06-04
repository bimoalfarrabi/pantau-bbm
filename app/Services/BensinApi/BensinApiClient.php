<?php

namespace App\Services\BensinApi;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class BensinApiClient
{
    public function fetchPrices(): array
    {
        $index = $this->request()
            ->get('/v1/index.json')
            ->throw()
            ->json('provinsi', []);

        return collect($index)
            ->map(function (array $province): array {
                return $this->request()
                    ->get($province['path'])
                    ->throw()
                    ->json() ?? [];
            })
            ->filter(fn (array $payload): bool => ! empty($payload))
            ->values()
            ->all();
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl(config('fuel.api.base_url'))
            ->timeout(config('fuel.api.timeout'))
            ->retry(config('fuel.api.retry_attempts'), config('fuel.api.retry_sleep_ms'))
            ->withUserAgent(config('fuel.api.user_agent'))
            ->acceptJson();
    }
}
