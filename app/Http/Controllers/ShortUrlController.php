<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortUrls\StoreShortUrlRequest;
use App\Models\ShortUrl;
use App\Services\ShortUrlService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ShortUrlController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = ShortUrl::with(['user', 'company']);

        if ($user->isSuperAdmin()) {
            // Super admin sees everything.right now no need for any kind of code in this
        } elseif ($user->isAdmin()) {
            $query->where('company_id', $user->company_id);
        } else {
            $query->where('user_id', $user->id);
        }

        if ($term = $request->string('search')->toString()) {
            $query->where(function ($q) use ($term) {
                $q->where('original_url', 'like', '%' . $term . '%')
                  ->orWhere('short_code', 'like', '%' . $term . '%');
            });
        }

        return view('short-urls.index', [
            'urls' => $query->latest()->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        abort_unless(auth()->user()->can('create', ShortUrl::class), 403);

        return view('short-urls.create');
    }

    public function store( StoreShortUrlRequest $request, ShortUrlService $shortUrlService ): RedirectResponse
    {
        $shortUrlService->create(
            $request->user(),
            $request->validated('original_url')
        );

        return redirect()
            ->route('short-urls.index')
            ->with('success', 'Short URL created successfully.');
    }
}