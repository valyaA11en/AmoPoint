<?php

declare(strict_types=1);

namespace App\Actions\Visits;

use App\Models\Visit;
use App\Services\Geo\GeoIpService;
use Illuminate\Http\Request;

final class StoreVisitAction
{
    public function __construct(
        private readonly GeoIpService $geoIpService,
    ) {
    }

    public function execute(array $data, Request $request): Visit
    {
        $ip = $request->ip();
        $location = $this->geoIpService->lookup($ip);

        return Visit::query()->create([
            'visitor_id' => $data['visitor_id'],
            'ip_address' => $ip,
            'city' => $location['city'],
            'device_type' => $data['device_type'],
            'user_agent' => $data['user_agent'] ?? null,
            'page_url' => $data['page_url'],
            'referrer' => $data['referrer'] ?? null,
            'language' => $data['language'] ?? null,
            'timezone' => $data['timezone'] ?? null,
            'screen' => $data['screen'] ?? null,
            'visited_at' => now(),
        ]);
    }
}