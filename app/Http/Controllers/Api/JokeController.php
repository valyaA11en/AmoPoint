<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JokeResource;
use App\Models\Joke;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class JokeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $limit = min((int) $request->integer('limit', 50), 100);

        $jokes = Joke::query()
            ->latest()
            ->limit($limit)
            ->get();

        return JokeResource::collection($jokes);
    }
}