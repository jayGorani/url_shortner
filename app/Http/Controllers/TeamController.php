<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->isAdmin(), 403);

        return view('team.index', [
            'users' => User::where('company_id', $request->user()->company_id)
                ->latest()
                ->paginate(15),
        ]);
    }
}