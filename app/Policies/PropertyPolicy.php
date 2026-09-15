<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Property $property): bool
    {
        if ($property->status === 'published') {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->isAdmin() || $user->id === $property->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner', 'agent']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Property $property): bool
    {
        return $user->isAdmin() || $user->id === $property->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): bool
    {
        return $user->isAdmin() || $user->id === $property->user_id;
    }

    /**
     * Determine whether the user can approve properties.
     */
    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can reject/request changes for properties.
     */
    public function review(User $user): bool
    {
        return $user->isAdmin();
    }
}
