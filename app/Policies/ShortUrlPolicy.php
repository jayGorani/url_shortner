<?php

namespace App\Policies;
use App\Models\{User,ShortUrl};

class ShortUrlPolicy
{
    public function view(User $user, ShortUrl $shortUrl): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->company_id === null || $user->company_id !== $shortUrl->company_id) {
            return false;
        }

        return $user->isAdmin()
            || ($user->isMember() && $shortUrl->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMember();
    }
}