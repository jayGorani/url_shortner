<?php

namespace App\Http\Controllers;

use App\Models\{Company,User,ShortUrl};
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return view('dashboards.super-admin', [
                'companies' => Company::count(),
                'users'     => User::count(),
                'admins'    => User::where('role', 'admin')->count(),
                'members'   => User::where('role', 'member')->count(),
                'urls'      => ShortUrl::count(),
                'clicks'    => ShortUrl::sum('click_count'),
            ]);
        }

        $shortUrls = ShortUrl::where('company_id', $user->company_id);

        if ($user->isMember()) {
            $shortUrls->where('user_id', $user->id);
        }

        return view('dashboards.company', [
            'user'       => $user,
            'company'    => $user->company,
            'usersCount' => $user->company->users()->count(),
            'urlsCount'  => (clone $shortUrls)->count(),
            'clicks'     => (clone $shortUrls)->sum('click_count'),
        ]);
    }
}