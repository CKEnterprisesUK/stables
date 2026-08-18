<?php

namespace App\Policies;

use App\Models\Sponsorship;
use App\Models\User;

class SponsorshipPolicy
{
    /**
     * Determine if the user can view the sponsorship.
     * A sponsor can only view their own sponsorships.
     */
    public function view(User $user, Sponsorship $sponsorship): bool
    {
        return $user->id === $sponsorship->user_id;
    }

    /**
     * Determine if the user can cancel the sponsorship.
     * A sponsor can only cancel their own sponsorships.
     */
    public function cancel(User $user, Sponsorship $sponsorship): bool
    {
        return $user->id === $sponsorship->user_id;
    }
}
