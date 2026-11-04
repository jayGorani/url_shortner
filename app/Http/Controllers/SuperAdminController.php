<?php
namespace App\Http\Controllers;
use App\Models\{Company,User};
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    public function companies(): View
    {
        return view('super-admin.companies', [
            'companies' => Company::withCount(['users', 'shortUrls'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function users(Request $request): View
    {
        $query = User::with('company');

        if ($term = $request->string('search')->toString()) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                  ->orWhere('email', 'like', '%' . $term . '%');
            });
        }

        return view('super-admin.users', [
            'users' => $query
                ->whereNotNull('company_id')
                ->latest()
                ->paginate(20)
                ->withQueryString(),
        ]);
    }
}
