<?php

namespace App\Policies;

use App\Models\PropertyVisit;
use App\Models\User;

class PropertyVisitPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PropertyVisit $visit): bool
    {
        return $user->isAdmin() || $user->id === $visit->user_id || $user->id === $visit->property->user_id;
    }

    /**
     * Determine whether the user can update the model status/details.
     */
    public function update(User $user, PropertyVisit $visit): bool
    {
        return $user->isAdmin() || $user->id === $visit->user_id || $user->id === $visit->property->user_id;
    }
}
