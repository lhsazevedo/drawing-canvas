<?php

namespace App\Policies;

use App\Models\DrawingSession;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DrawingSessionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DrawingSession $session): bool
    {
        return $session->is_public ?: $user->id === $session->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DrawingSession $session): bool
    {
        return $user->id === $session->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DrawingSession $session): bool
    {
        return $user->id === $session->user_id;
    }
}
