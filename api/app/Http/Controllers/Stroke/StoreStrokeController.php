<?php

namespace App\Http\Controllers\Stroke;

use App\Http\Controllers\Controller;
use App\Models\DrawingSession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Broadcast;

class StoreStrokeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(Request $request, $publicId)
    {
        $validated = $request->validate([
            'uuid' => 'required|uuid',
            'color' => 'required|string',
            'size' => 'required|int',
            'points.*' => 'required|array',
            'points.*.*' => 'required|int',
            'min_x' => 'required|int',
            'min_y' => 'required|int',
            'max_x' => 'required|int',
            'max_y' => 'required|int',
        ]);

        $drawingSession = DrawingSession::where('public_id', $publicId)
            ->firstOrFail();

        $user = auth()->user();
        $isOwner = ($drawingSession->session_id === session()->getId())
            || ($user && $drawingSession->user_id === $user->id);

        if (!$drawingSession->is_public && !$isOwner) {
            abort(403);
        }

        $drawingSession->strokes()->create($validated);

        $resource = new JsonResource($validated);

        // TODO: Extract to a class implementing ShouldBroadcast
        Broadcast::broadcast(
            channels: ['drawing-session.' . $drawingSession->public_id],
            event: 'stroke-created',
            payload: $resource->toArray($request),
        );

        return $resource;
    }
}
