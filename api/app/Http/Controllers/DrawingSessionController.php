<?php

namespace App\Http\Controllers;

use App\Models\DrawingSession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class DrawingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO...
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'public_id' => 'required|alpha_dash:ascii',
            'description' => 'nullable|string',
            'is_public' => 'required|boolean',
        ]);

        $session = DrawingSession::create([
            'public_id' => Str::random(6),
            'user_id' => auth()->guard()->id(),
            ...$validated
        ]);

        return new JsonResource($session);
    }

    /**
     * Display the specified resource.
     */
    public function show(DrawingSession $session)
    {
        Gate::authorize('view', $session);

        return new JsonResource(DrawingSession::findOrFail($session->id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DrawingSession $session)
    {
        // TODO...
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DrawingSession $session)
    {
        // TODO...
    }
}
