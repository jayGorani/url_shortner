<?php

namespace App\Services;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ShortUrlService
{
    public function create(User $user, string $originalUrl): ShortUrl
    {

        if ($user->isSuperAdmin()) {
            // cause superadmin has no rights to create a short URL
            throw new AuthorizationException(
                'You are not authorized to create short URLs.'
            );
        }

        // Retry only when a generated short code causes a duplicate key.
        $shortCode = $this->getUniqueCode();

        return ShortUrl::create(
            [
                'user_id'      => $user->id,
                'company_id'   => $user->company_id,
                'original_url' => $originalUrl,
                'short_code'   => $shortCode,
                'click_count'  => 0,
            ]
        );


    }


    public function getUniqueCode(): string {

        do {
            $shortCode = Str::random(8);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        return $shortCode;
    }

}