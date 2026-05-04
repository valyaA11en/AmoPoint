<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Visits\VisitStatisticsService;
use Illuminate\View\View;

final class VisitStatisticsController extends Controller
{
    public function __invoke(VisitStatisticsService $statisticsService): View
    {
        return view('admin.visits.index', [
            'statistics' => $statisticsService->getDashboardData(),
        ]);
    }
}