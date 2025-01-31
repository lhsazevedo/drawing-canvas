<?php

namespace App\Http\Controllers\Stroke;

use App\Http\Controllers\Controller;
use App\Models\DrawingSession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexStrokeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(Request $request, $publicId)
    {
        $drawingSession = DrawingSession::where('public_id', $publicId)
            ->firstOrFail();

        $user = auth()->user();
        $isOwner = ($drawingSession->session_id === session()->getId())
            || ($user && $drawingSession->user_id === $user->id);

        if (! $drawingSession->is_public && ! $isOwner) {
            abort(403);
        }

        return JsonResource::collection($drawingSession->strokes);
    }
}
