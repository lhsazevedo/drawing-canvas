<?php

namespace App\Http\Controllers\Stroke;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStrokeRequest;
use App\Models\DrawingSession;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Broadcast;

class StoreStrokeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(StoreStrokeRequest $request, $publicId)
    {
        $stroke = $request->validated();

        $drawingSession = DrawingSession::query()
            ->where('public_id', $publicId)
            ->firstOrFail();

        $user = auth()->user();
        $belongsToSession = $drawingSession->session_id === session()->getId();
        $belongsToUser = $user && ($drawingSession->user_id === $user->id);
        $isOwner = $belongsToSession || $belongsToUser;

        if (! $drawingSession->is_public && ! $isOwner) {
            abort(403);
        }

        $drawingSession->strokes()->create($stroke);

        Broadcast::on('drawing-session.'.$drawingSession->public_id)
            ->as('stroke-created')
            ->with($stroke)
            ->sendNow();

        return new JsonResource($stroke);
    }
}
