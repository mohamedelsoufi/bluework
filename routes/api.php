<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ClockInController;
use App\Http\Controllers\ApiDocController;

Route::post('/worker/clock-in', [ClockInController::class, 'clockIn']);
Route::get('/worker/clock-ins', [ClockInController::class, 'getClockIns']);

Route::get('/api/docs', [ApiDocController::class, 'api']);
