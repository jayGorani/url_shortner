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

        for ($attempt = 0; $attempt < 5; $attempt++) {

            $shortCode = Str::random(8);

            if (ShortUrl::where('short_code', $shortCode)->exists()) {
                continue;
            }

            try {

                return ShortUrl::create(
                    [
                        'user_id'      => $user->id,
                        'company_id'   => $user->company_id,
                        'original_url' => $originalUrl,
                        'short_code'   => $shortCode,
                        'click_count'  => 0,
                    ]
                );

            } catch (QueryException $exception) {

                // A concurrent request may have inserted the same code.
                if (ShortUrl::where('short_code', $shortCode)->exists()) {
                    continue;
                }

                throw $exception;

            }
        }

        throw new RuntimeException(
            'A short code could not be generated. Please try again.'
        );
    }

}