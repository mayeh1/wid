<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionBoundaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_manager_can_access_pages_but_not_donations(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Content Manager');

        $this->actingAs($user)->get('/admin/pages')->assertOk();
        $this->actingAs($user)->get('/admin/donations')->assertForbidden();
        $this->actingAs($user)->get('/admin/payment-methods')->assertForbidden();
    }

    public function test_finance_manager_can_access_donations_but_not_pages(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Finance Manager');

        $this->actingAs($user)->get('/admin/donations')->assertOk();
        $this->actingAs($user)->get('/admin/payment-methods')->assertOk();
        $this->actingAs($user)->get('/admin/pages')->assertForbidden();
    }

    public function test_volunteer_manager_can_access_volunteers_but_not_donations(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Volunteer Manager');

        $this->actingAs($user)->get('/admin/volunteers')->assertOk();
        $this->actingAs($user)->get('/admin/donations')->assertForbidden();
    }

    public function test_super_admin_bypasses_every_permission_check(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        foreach (['pages', 'donations', 'payment-methods', 'volunteers', 'memberships'] as $slug) {
            $this->actingAs($user)->get("/admin/{$slug}")->assertOk();
        }
    }

    public function test_settings_page_and_reports_page_are_permission_gated(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $contentManager = User::factory()->create();
        $contentManager->assignRole('Content Manager');

        $this->actingAs($contentManager)->get('/admin/manage-site-settings')->assertForbidden();
        $this->actingAs($contentManager)->get('/admin/reports')->assertForbidden();

        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $this->actingAs($admin)->get('/admin/manage-site-settings')->assertOk();
        $this->actingAs($admin)->get('/admin/reports')->assertOk();
    }

    public function test_accountant_has_read_only_financial_access(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Accountant');

        $this->actingAs($user)->get('/admin/donations')->assertOk();
        $this->actingAs($user)->get('/admin/payment-methods')->assertOk();
        $this->actingAs($user)->get('/admin/reports')->assertOk();
        // No create page: read-only role, no "manage" grant.
        $this->actingAs($user)->get('/admin/payment-methods/create')->assertForbidden();
        $this->actingAs($user)->get('/admin/pages')->assertForbidden();
    }

    public function test_event_manager_can_access_events_but_not_other_content(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Event Manager');

        $this->actingAs($user)->get('/admin/events')->assertOk();
        $this->actingAs($user)->get('/admin/events/create')->assertOk();
        $this->actingAs($user)->get('/admin/pages/create')->assertForbidden();
        $this->actingAs($user)->get('/admin/donations')->assertForbidden();
    }

    public function test_board_member_has_read_only_governance_access(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Board Member');

        $this->actingAs($user)->get('/admin/donations')->assertOk();
        $this->actingAs($user)->get('/admin/campaigns')->assertOk();
        $this->actingAs($user)->get('/admin/activities')->assertOk();
        $this->actingAs($user)->get('/admin/campaigns/create')->assertForbidden();
        $this->actingAs($user)->get('/admin/payment-methods')->assertForbidden();
    }
}
