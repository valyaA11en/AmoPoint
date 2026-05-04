<?php

declare(strict_types=1);

namespace App\Services\Geo;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class GeoIpService
{
    public function lookup(?string $ip): array
    {
        if (!$ip || !$this->isPublicIp($ip)) {
            return [
                'city' => 'Unknown',
            ];
        }

        return Cache::remember(
            key: 'geo_ip:' . $ip,
            ttl: now()->addDay(),
            callback: fn() => $this->requestLocation($ip),
        );
    }

    private function requestLocation(string $ip): array
    {
        $baseUrl = rtrim((string) config('services.geo_ip.url'), '/');

        $response = Http::acceptJson()
            ->timeout(5)
            ->retry(2, 200, throw: false)
            ->get("{$baseUrl}/{$ip}/json/");

        if ($response->failed()) {
            return [
                'city' => 'Unknown',
            ];
        }

        return [
            'city' => $response->json('city') ?: 'Unknown',
        ];
    }

    private function isPublicIp(string $ip): bool
    {
        return (bool) filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}