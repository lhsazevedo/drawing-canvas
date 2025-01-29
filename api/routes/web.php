<?php

use App\Http\Controllers\DrawingSession\IndexDrawingSessionController;
use App\Http\Controllers\DrawingSession\StoreDrawingSessionController;
use App\Http\Controllers\Stroke\BatchDeleteStrokesController;
use App\Http\Controllers\Stroke\IndexStrokeController;
use App\Http\Controllers\Stroke\StoreStrokeController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
    ]);

    $validated['password'] = Hash::make($validated['password']);

    $user = User::create($validated + [Hash::make($validated['password'])]);

    return new JsonResource($user);
});

Route::get('/auth/me', function () {
    return response()->json([
        'foo' => 'bar',
        'user' => auth()->user()
    ]);
});

Route::get('/drawing-sessions', IndexDrawingSessionController::class);
Route::post('/drawing-sessions', StoreDrawingSessionController::class);
Route::get('/drawing-sessions/{id}/strokes', IndexStrokeController::class);
Route::post('/drawing-sessions/{id}/strokes', StoreStrokeController::class);
Route::post('/drawing-sessions/{id}/strokes/batch-delete', BatchDeleteStrokesController::class);
