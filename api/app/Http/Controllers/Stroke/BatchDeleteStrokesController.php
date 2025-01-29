<?php

namespace App\Http\Controllers\Stroke;

use App\Http\Controllers\Controller;
use App\Http\Requests\BatchDeleteStrokesRequest;
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
    public function __invoke(BatchDeleteStrokesRequest $request, $publicId)
    {
        $validated = $request->validated();

        $drawingSession = DrawingSession::where('public_id', $publicId)
            ->firstOrFail();

        $user = auth()->user();
        $belongsToSession = $drawingSession->session_id === session()->getId();
        $belongsToUser = $user && ($drawingSession->user_id === $user->id);
        $isOwner = $belongsToSession || $belongsToUser;

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
