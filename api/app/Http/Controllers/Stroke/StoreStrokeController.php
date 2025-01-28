<?php

namespace App\Http\Controllers\Stroke;

use App\Http\Controllers\Controller;
use App\Models\DrawingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

class StoreStrokeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(Request $request, $publicId)
    {
        $stroke = $request->validate([
            'points.*.x' => 'required|int',
            'points.*.y' => 'required|int',
            'color' => 'required|string',
        ]);

        $drawingSession = DrawingSession::where('public_id', $publicId)
            ->firstOrFail();

        $user = auth()->user();
        $isOwner = ($drawingSession->session_id === session()->getId())
            || ($user && $drawingSession->user_id === $user->id);

        if (!$drawingSession->is_public && !$isOwner) {
            abort(403);
        }

        $drawingSession->strokes()->create([
            'data' => json_encode($stroke),
        ]);

        // TODO: Extract to a class implementing ShouldBroadcast
        Broadcast::broadcast(
            ['drawing-session.' . $drawingSession->public_id], 'stroke-created', $stroke
        );
    }
}
