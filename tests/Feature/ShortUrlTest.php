<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    private function account(string $role = 'member', ?int $companyId = null): User
    {
        if ($companyId === null) {
            $suffix = Str::lower(Str::random(10));
            $companyId = Company::create([
                'name' => 'Company '.$suffix,
                'slug' => 'company-'.$suffix,
            ])->id;
        }

        return User::factory()->create([
            'company_id' => $companyId,
            'role' => $role,
        ]);
    }

    public function test_member_can_create_and_only_see_own_urls(): void
    {
        $member = $this->account();
        $otherMember = $this->account('member', $member->company_id);

        ShortUrl::create([
            'user_id' => $otherMember->id,
            'company_id' => $otherMember->company_id,
            'original_url' => 'https://private.test',
            'short_code' => 'Priv1234',
        ]);

        $this->actingAs($member)->post(route('short-urls.store'), [
            'original_url' => 'https://example.com',
            'company_id' => 99999,
            'user_id' => $otherMember->id,
        ])->assertRedirect(route('short-urls.index'));

        $this->assertDatabaseHas('short_urls', [
            'user_id' => $member->id,
            'company_id' => $member->company_id,
        ]);
        $this->assertDatabaseCount('short_urls', 2);

        $this->get(route('short-urls.index'))
            ->assertDontSee('private.test')
            ->assertSee('example.com');
    }

    public function test_admin_can_create_url_and_view_all_urls_in_own_company_only(): void
    {
        $admin = $this->account('admin');
        $sameCompanyMember = $this->account('member', $admin->company_id);
        $otherCompanyMember = $this->account('member');

        ShortUrl::create([
            'user_id' => $sameCompanyMember->id,
            'company_id' => $sameCompanyMember->company_id,
            'original_url' => 'https://same-company.test',
            'short_code' => 'Same1234',
        ]);
        ShortUrl::create([
            'user_id' => $otherCompanyMember->id,
            'company_id' => $otherCompanyMember->company_id,
            'original_url' => 'https://other-company.test',
            'short_code' => 'Other123',
        ]);

        $this->actingAs($admin)->post(route('short-urls.store'), [
            'original_url' => 'https://admin-created.test',
        ])->assertRedirect(route('short-urls.index'));

        $this->get(route('short-urls.index'))
            ->assertSee('same-company.test')
            ->assertSee('admin-created.test')
            ->assertDontSee('other-company.test');
    }

    public function test_super_admin_cannot_create_short_urls(): void
    {
        $superAdmin = User::factory()->create([
            'company_id' => null,
            'role' => 'super_admin',
        ]);

        $this->actingAs($superAdmin)
            ->post(route('short-urls.store'), ['original_url' => 'https://example.com'])
            ->assertForbidden();
    }

    public function test_public_redirect_increments_click_count_without_authentication(): void
    {
        $member = $this->account();
        $url = ShortUrl::create([
            'user_id' => $member->id,
            'company_id' => $member->company_id,
            'original_url' => 'https://example.com/path',
            'short_code' => 'Abc12345',
        ]);

        $this->assertGuest();
        $this->get('/Abc12345')->assertRedirect('https://example.com/path');
        $this->assertDatabaseHas('short_urls', ['id' => $url->id, 'click_count' => 1]);
    }

    public function test_invalid_short_code_returns_404(): void
    {
        $this->get('/Nope9999')->assertNotFound();
    }
}
