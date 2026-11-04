<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;

use Illuminate\Http\RedirectResponse;


class RedirectController extends Controller
{
    public function __invoke(string $short_code): RedirectResponse
    {
        $shortUrl = ShortUrl::query()
            ->where('short_code', $short_code)
            ->firstOrFail();

        ShortUrl::query()
            ->whereKey($shortUrl->getKey())
            ->increment('click_count');

        return redirect()->away($shortUrl->original_url, 302);
    }
}