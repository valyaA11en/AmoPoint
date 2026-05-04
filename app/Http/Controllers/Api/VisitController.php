<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Visits\StoreVisitAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVisitRequest;
use Illuminate\Http\JsonResponse;

final class VisitController extends Controller
{
    public function store(StoreVisitRequest $request, StoreVisitAction $action): JsonResponse
    {
        $visit = $action->execute(
            data: $request->validated(),
            request: $request,
        );

        return response()->json([
            'status' => 'ok',
            'data' => [
                'id' => $visit->id,
            ],
        ], 201);
    }
}