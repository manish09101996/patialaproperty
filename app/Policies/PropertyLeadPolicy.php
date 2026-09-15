<?php

namespace App\Policies;

use App\Models\PropertyLead;
use App\Models\User;

class PropertyLeadPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PropertyLead $lead): bool
    {
        return $user->isAdmin() || $user->id === $lead->user_id || $user->id === $lead->owner_id;
    }

    /**
     * Determine whether the user can update the model status/details.
     */
    public function update(User $user, PropertyLead $lead): bool
    {
        return $user->isAdmin() || $user->id === $lead->owner_id;
    }
}
