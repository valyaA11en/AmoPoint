<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JokeController;
use App\Http\Controllers\Api\VisitController;

Route::get('/jokes', [JokeController::class, 'index']);
Route::post('/visits', [VisitController::class, 'store'])
    ->middleware('throttle:120,1');