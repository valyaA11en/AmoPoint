<?php

declare(strict_types=1);

namespace App\Services\Visits;

use App\Models\Visit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class VisitStatisticsService
{
    public function getDashboardData(): array
    {
        $from = now()->subDay();

        return [
            'total_visits' => $this->getTotalVisits($from),
            'unique_visitors' => $this->getUniqueVisitors($from),
            'visits_by_hour' => $this->getUniqueVisitsByHour($from),
            'visits_by_city' => $this->getVisitsByCity($from),
        ];
    }

    private function getTotalVisits($from): int
    {
        return Visit::query()
            ->where('visited_at', '>=', $from)
            ->count();
    }

    private function getUniqueVisitors($from): int
    {
        return Visit::query()
            ->where('visited_at', '>=', $from)
            ->distinct('visitor_id')
            ->count('visitor_id');
    }

    private function getUniqueVisitsByHour($from): Collection
    {
        $hourExpression = $this->getHourExpression();

        return Visit::query()
            ->selectRaw("{$hourExpression} as hour")
            ->selectRaw('COUNT(DISTINCT visitor_id) as unique_visits')
            ->where('visited_at', '>=', $from)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->map(fn(Visit $visit) => [
                'hour' => $visit->hour,
                'unique_visits' => (int) $visit->unique_visits,
            ]);
    }

    private function getVisitsByCity($from): Collection
    {
        return Visit::query()
            ->selectRaw("COALESCE(city, 'Unknown') as city")
            ->selectRaw('COUNT(*) as total')
            ->where('visited_at', '>=', $from)
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn(Visit $visit) => [
                'city' => $visit->city ?: 'Unknown',
                'total' => (int) $visit->total,
            ]);
    }

    private function getHourExpression(): string
    {
        return match (DB::getDriverName()) {
            'sqlite' => "strftime('%Y-%m-%d %H:00', visited_at)",
            'mysql', 'mariadb' => "DATE_FORMAT(visited_at, '%Y-%m-%d %H:00')",
            'pgsql' => "to_char(date_trunc('hour', visited_at), 'YYYY-MM-DD HH24:00')",
            default => "strftime('%Y-%m-%d %H:00', visited_at)",
        };
    }
}