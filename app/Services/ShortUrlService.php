<?php

namespace App\Services;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ShortUrlService
{
    public function create(User $user, string $originalUrl): ShortUrl
    {
        abort_unless($user->isAdmin() || $user->isMember(), 403);

        // A unique database index on short_code is still required. The retry
        // handles the unlikely event that two requests generate the same code.

        for ($attempt = 0; $attempt < 5; $attempt++) {
            try {
                return ShortUrl::create(
                    [
                        'user_id'      => $user->id,
                        'company_id'   => $user->company_id,
                        'original_url' => $originalUrl,
                        'short_code'   => Str::random(8),
                        'click_count'  => 0,
                    ]
                );
            } catch (QueryException $exception) {
                $sqlState = $exception->errorInfo[0] ?? null;
                $driverCode = (int) ($exception->errorInfo[1] ?? 0);

                $isDuplicateKey = $sqlState === '23505'
                    || ($sqlState === '23000' && $driverCode === 1062);

                if (! $isDuplicateKey) {
                    throw $exception;
                }
            }
        }

        throw ValidationException::withMessages([
            'original_url' => 'A short code could not be generated. Please try again.',
        ]);
    }
}

