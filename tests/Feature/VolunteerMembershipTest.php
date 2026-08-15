<?php

namespace Tests\Feature;

use App\Models\MembershipLevel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VolunteerMembershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_volunteer_signup(): void
    {
        $this->get('/volunteer')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_apply_to_volunteer(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/volunteer', [
            'phone' => '555-1234',
            'skills' => 'Mentoring, Event Planning',
            'availability' => 'Weekends',
        ])->assertRedirect(route('volunteer.dashboard'));

        $this->assertDatabaseHas('volunteers', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    public function test_volunteer_dashboard_redirects_to_application_when_none_exists(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/volunteer/dashboard')->assertRedirect(route('volunteer.create'));
    }

    public function test_authenticated_user_can_join_a_membership_level(): void
    {
        $user = User::factory()->create();
        $level = MembershipLevel::create([
            'name' => 'Friend', 'slug' => 'friend', 'annual_price' => 25, 'is_active' => true,
        ]);

        $this->actingAs($user)->post('/membership', [
            'membership_level_id' => $level->id,
        ])->assertRedirect(route('membership.dashboard'));

        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id,
            'membership_level_id' => $level->id,
            'status' => 'active',
        ]);
    }

    public function test_member_can_renew_membership(): void
    {
        $user = User::factory()->create();
        $level = MembershipLevel::create([
            'name' => 'Friend', 'slug' => 'friend', 'annual_price' => 25, 'is_active' => true,
        ]);
        $user->memberships()->create([
            'membership_level_id' => $level->id,
            'status' => 'expired',
            'started_at' => now()->subYears(2),
            'expires_at' => now()->subYear(),
        ]);

        $this->actingAs($user)->post('/membership/renew')->assertRedirect();

        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id,
            'status' => 'active',
        ]);
    }
}
