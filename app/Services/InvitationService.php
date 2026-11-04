<?php

namespace App\Services;

use App\Mail\InvitationMail;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InvitationService
{
    public function invite(User $inviter, array $data): Invitation
    {
        $isSuperAdmin = $inviter->isSuperAdmin();

        abort_unless($isSuperAdmin || $inviter->isAdmin(), 403);

        $role = $data['role'];
        $email = Str::lower($data['email']);

        if ($isSuperAdmin) {
            abort_unless(
                $role === 'admin' && ! empty($data['company_name']),
                403
            );

            $companyId = null;
            $companyName = $data['company_name'];
        } else {
            abort_unless(in_array($role, ['admin', 'member'], true), 403);

            $companyId = $inviter->company_id;
            $companyName = null;

            abort_unless($companyId, 403);
        }

        $pendingInvitations = Invitation::query()
            ->where('email', $email)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now());

        if ($isSuperAdmin) {
            $pendingInvitations
                ->whereNull('company_id')
                ->where('company_name', $companyName);
        } else {
            $pendingInvitations->where('company_id', $companyId);
        }

        if ($pendingInvitations->exists()) {
            throw ValidationException::withMessages([
                'email' => 'A pending invitation already exists for this email.',
            ]);
        }

        if (User::query()->where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'This email already has an account.',
            ]);
        }

        $plainToken = Str::random(64);

        $invitation = Invitation::create([
            'company_id' => $companyId,
            'company_name' => $companyName,
            'name' => $data['name'],
            'invited_by' => $inviter->id,
            'email' => $email,
            'role' => $role,
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($invitation->email)->send(
            new InvitationMail($invitation, $plainToken)
        );

        return $invitation;
    }

    public function accept(string $plainToken, string $password): User
    {
        return DB::transaction(function () use ($plainToken, $password) {
            $invitation = Invitation::query()
                ->where('token', hash('sha256', $plainToken))
                ->lockForUpdate()
                ->first();

            if (
                ! $invitation
                || $invitation->accepted_at !== null
                || $invitation->expires_at === null
                || $invitation->expires_at->isPast()
            ) {
                throw ValidationException::withMessages([
                    'token' => 'This invitation is invalid, expired, or already used.',
                ]);
            }

            if (User::query()->where('email', $invitation->email)->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'An account already exists for this email.',
                ]);
            }

            $companyId = $invitation->company_id;

            if ($invitation->role === 'admin' && $companyId === null) {
                $company = Company::create([
                    'name' => $invitation->company_name,
                    'slug' => $this->makeUniqueCompanySlug($invitation->company_name),
                    'status' => 'active',
                ]);

                $companyId = $company->id;
            }

            if ($companyId === null) {
                throw ValidationException::withMessages([
                    'invitation' => 'The company associated with this invitation is unavailable.',
                ]);
            }

            $user = User::create([
                'company_id' => $companyId,
                'name' => $invitation->name,
                'email' => $invitation->email,
                'password' => $password,
                'role' => $invitation->role,
            ]);

            $invitation->update([
                'company_id' => $companyId,
                'accepted_at' => now(),
                'token' => null,
            ]);

            return $user;
        });
    }

    public function revoke(Invitation $invitation): void
    {
        $invitation->delete();
    }

    public function resend(Invitation $invitation): Invitation
    {
        if ($invitation->accepted_at !== null) {
            throw ValidationException::withMessages([
                'invitation' => 'Accepted invitations cannot be resent.',
            ]);
        }

        $plainToken = Str::random(64);

        $invitation->update([
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($invitation->email)->send(
            new InvitationMail($invitation, $plainToken)
        );

        return $invitation;
    }

    private function makeUniqueCompanySlug(string $companyName): string
    {
        $baseSlug = Str::slug($companyName) ?: 'company';
        $slug = $baseSlug;
        $suffix = 1;

        while (Company::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$suffix++;
        }

        return $slug;
    }
}
