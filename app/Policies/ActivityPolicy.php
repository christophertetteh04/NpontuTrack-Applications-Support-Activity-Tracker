<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Activity;

class ActivityPolicy
{
    /**
     * Determine whether the user can view any activities.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view activities
    }

    /**
     * Determine whether the user can view the activity.
     */
    public function view(User $user, Activity $activity): bool
    {
        return true; // All authenticated users can view any activity
    }

    /**
     * Determine whether the user can create activities.
     */
    public function create(User $user): bool
    {
        return $user->isTeamLead(); // team_lead and admin
    }

    /**
     * Determine whether the user can update the activity.
     */
    public function update(User $user, Activity $activity): bool
    {
        return $user->isTeamLead(); // team_lead and admin
    }

    /**
     * Determine whether the user can delete the activity.
     */
    public function delete(User $user, Activity $activity): bool
    {
        return $user->isAdmin(); // admin only
    }

    /**
     * Determine whether the user can restore the activity.
     */
    public function restore(User $user, Activity $activity): bool
    {
        return $user->isAdmin(); // admin only, for soft deletes
    }

    /**
     * Determine whether the user can permanently delete the activity.
     */
    public function forceDelete(User $user, Activity $activity): bool
    {
        return $user->isAdmin(); // admin only
    }
}
