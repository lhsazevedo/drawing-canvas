<?php

namespace App\Http\Controllers\DrawingSession;

use App\Http\Controllers\Controller;
use App\Models\DrawingSession;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowDrawingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke($publicId)
    {
        $drawingSession = DrawingSession::where('public_id', $publicId)->firstOrFail();

        $user = auth()->user();
        $belongsToSession = $drawingSession->session_id === session()->getId();
        $belongsToUser = $user && ($drawingSession->user_id === $user->id);
        $isOwner = $belongsToSession || $belongsToUser;

        if (! $drawingSession->is_public && ! $isOwner) {
            abort(403);
        }

        return new JsonResource($drawingSession);
    }
}
