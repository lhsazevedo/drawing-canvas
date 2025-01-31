<?php

use App\Http\Controllers\DrawingSession\IndexDrawingSessionController;
use App\Http\Controllers\DrawingSession\ShowDrawingSessionController;
use App\Http\Controllers\DrawingSession\StoreDrawingSessionController;
use App\Http\Controllers\Stroke\BatchDeleteStrokesController;
use App\Http\Controllers\Stroke\IndexStrokeController;
use App\Http\Controllers\Stroke\StoreStrokeController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/me', function () {
    return response()->json([
        'data' => auth()->user()
    ]);
});

Route::get('/drawing-sessions', IndexDrawingSessionController::class);
Route::post('/drawing-sessions', StoreDrawingSessionController::class);
Route::get('/drawing-sessions/{ìd}', ShowDrawingSessionController::class);
Route::get('/drawing-sessions/{id}/strokes', IndexStrokeController::class);
Route::post('/drawing-sessions/{id}/strokes', StoreStrokeController::class);
Route::post('/drawing-sessions/{id}/strokes/batch-delete', BatchDeleteStrokesController::class);

require __DIR__.'/auth.php';
