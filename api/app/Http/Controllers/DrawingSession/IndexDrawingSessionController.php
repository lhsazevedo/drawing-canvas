<?php

namespace App\Http\Controllers\DrawingSession;

use App\Http\Controllers\Controller;
use App\Models\DrawingSession;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexDrawingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        $query = DrawingSession::query();

        if ($user = auth()->user()) {
            $query->where('user_id', $user->id);
        } else {
            $query->where('session_id', session()->getId());
        }

        return new JsonResource($query->get());
    }
}
