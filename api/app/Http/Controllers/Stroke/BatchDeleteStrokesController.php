<?php

namespace App\Http\Controllers\Stroke;

use App\Http\Controllers\Controller;
use App\Models\DrawingSession;
use App\Models\Stroke;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Broadcast;

class BatchDeleteStrokesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(Request $request, $publicId)
    {
        $validated = $request->validate([
            'uuids' => 'required|array',
            'uuids.*' => 'required|string',
        ]);

        $drawingSession = DrawingSession::where('public_id', $publicId)
            ->firstOrFail();

        $user = auth()->user();
        $isOwner = ($drawingSession->session_id === session()->getId())
            || ($user && $drawingSession->user_id === $user->id);

        if (!$drawingSession->is_public && !$isOwner) {
            abort(403);
        }

        $strokes = $drawingSession
            ->strokes()
            ->whereIn('uuid', $validated['uuids'])
            ->get();

        $uuids = $strokes->pluck('uuid')->toArray();
        Stroke::whereIn('uuid', $uuids)->delete();

        // TODO: Extract to a class implementing ShouldBroadcast
        Broadcast::broadcast(
            channels: ['drawing-session.' . $drawingSession->public_id],
            event: 'strokes-deleted',
            payload: ['uuids' => $uuids],
        );
    }
}
