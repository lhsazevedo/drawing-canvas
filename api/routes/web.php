<?php

use App\Http\Controllers\DrawingSessionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('drawing-sessions', DrawingSessionController::class);
