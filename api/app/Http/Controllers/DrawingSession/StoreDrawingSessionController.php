<?php

namespace App\Http\Controllers\DrawingSession;

use App\Http\Controllers\Controller;
use App\Models\DrawingSession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class StoreDrawingSessionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'is_public' => 'required|boolean',
        ]);

        $session = DrawingSession::create($validated + [
            'public_id' => Str::random(6),
            'session_id' => session()->getId(),
            'user_id' => auth()->user()?->id,
        ]);

        return new JsonResource($session);
    }
}
