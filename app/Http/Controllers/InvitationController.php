<?php
namespace App\Http\Controllers;

use App\Http\Requests\Invitations\AcceptInvitationRequest;
use App\Http\Requests\Invitations\StoreInvitationRequest;
use App\Models\Invitation;
use App\Services\InvitationService;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvitationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();

        $query = Invitation::with('inviter')->latest();

        if ($user->isSuperAdmin()) {
            $query->whereNull('company_id');
        } else {
            $query->where('company_id', $user->company_id);
        }

        return view('invitations.index', [
            'invitations' => $query->paginate(15),
        ]);
    }

    public function store( StoreInvitationRequest $request, InvitationService $invitationService ): RedirectResponse
    {
        $invitationService->invite($request->user(), $request->validated());

        return back()->with('success', 'Invitation sent.');
    }

    public function show(string $token): View
    {
        $invitation = Invitation::where('token', hash('sha256', $token))
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        return view('invitations.accept', [
            'token'      => $token,
            'invitation' => $invitation,
        ]);
    }

    public function accept( AcceptInvitationRequest $request, InvitationService $invitationService ): RedirectResponse
    {
        $user = $invitationService->accept(
            $request->validated('token'),
            $request->validated('password')
        );

        auth()->login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Your account is ready.');
    }

    public function revoke( Invitation $invitation, InvitationService $invitationService ): RedirectResponse
    {
        $this->authorize('manage', $invitation);

        $invitationService->revoke($invitation);

        return back()->with('success', 'Invitation revoked.');
    }

    public function resend( Invitation $invitation, InvitationService $invitationService ): RedirectResponse
    {
        $this->authorize('manage', $invitation);

        $invitationService->resend($invitation);

        return back()->with('success', 'Invitation resent.');
    }
}