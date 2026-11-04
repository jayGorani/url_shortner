<?php

namespace Tests\Feature;

use App\Mail\InvitationMail;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_invite_admin_and_company_is_created_on_acceptance(): void
    {
        Mail::fake();

        $superAdmin = User::factory()->create([
            'company_id' => null,
            'role' => 'super_admin',
        ]);

        $this->actingAs($superAdmin)->post(route('invitations.store'), [
            'name' => 'New Admin',
            'email' => 'admin@newco.test',
            'role' => 'admin',
            'company_name' => 'New Co',
        ])->assertRedirect();

        $invitation = Invitation::query()->firstOrFail();
        Mail::assertSent(InvitationMail::class);
        $this->assertNull($invitation->company_id, 'A new company must not exist before invitation acceptance.');

        // Use a real 64-character plain token to satisfy AcceptInvitationRequest.
        $plainToken = str_repeat('a', 64);
        $invitation->update(['token' => hash('sha256', $plainToken)]);

        // Acceptance is a guest workflow; the inviter must not remain authenticated.
        $this->post(route('logout'));

        $this->post(route('invitations.accept'), [
            'token' => $plainToken,
            'password' => 'very-secure-password',
            'password_confirmation' => 'very-secure-password',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('companies', ['name' => 'New Co']);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@newco.test',
            'role' => 'admin',
        ]);
        $this->assertNotNull($invitation->fresh()->accepted_at);
        $this->assertNull($invitation->fresh()->token, 'Accepted invitation tokens must be invalidated.');
    }

    public function test_expired_invitation_cannot_be_accepted(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $inviter = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
        ]);

        $plainToken = str_repeat('b', 64);
        Invitation::create([
            'company_id' => $company->id,
            'name' => 'Invitee',
            'invited_by' => $inviter->id,
            'email' => 'i@acme.test',
            'role' => 'member',
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->subDay(),
        ]);

        $this->post(route('invitations.accept'), [
            'token' => $plainToken,
            'password' => 'very-secure-password',
            'password_confirmation' => 'very-secure-password',
        ])->assertSessionHasErrors();

        $this->assertDatabaseMissing('users', ['email' => 'i@acme.test']);
    }

    public function test_invitation_token_cannot_be_used_twice(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $inviter = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);
        $plainToken = str_repeat('c', 64);

        Invitation::create([
            'company_id' => $company->id,
            'name' => 'Invitee',
            'invited_by' => $inviter->id,
            'email' => 'once@acme.test',
            'role' => 'member',
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->addDay(),
        ]);

        $payload = [
            'token' => $plainToken,
            'password' => 'very-secure-password',
            'password_confirmation' => 'very-secure-password',
        ];

        $this->post(route('invitations.accept'), $payload)->assertRedirect(route('dashboard'));
        $this->post('/logout');
        $this->post(route('invitations.accept'), $payload)->assertSessionHasErrors();

        $this->assertDatabaseCount('users', 2); // inviter + accepted invitee
    }
}
