<?php

namespace App\Policies;
use App\Models\{User,Invitation};

class InvitationPolicy
{
    public function manage(User $user, Invitation $invitation): bool
    {
        if ($user->isSuperAdmin()) {
            return $invitation->company_id === null;
        }

        return $user->isAdmin()
            && $user->company_id !== null
            && $user->company_id === $invitation->company_id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }
}