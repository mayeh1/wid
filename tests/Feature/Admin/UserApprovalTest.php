<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_user_is_redirected_to_pending_page_instead_of_dashboard(): void
    {
        $user = User::factory()->pending()->create();

        $this->actingAs($user)->get('/dashboard')->assertRedirect(route('account.pending'));
    }

    public function test_pending_user_can_view_the_pending_page(): void
    {
        $user = User::factory()->pending()->create();

        $this->actingAs($user)->get('/account/pending')->assertOk();
    }

    public function test_pending_user_is_blocked_from_volunteer_signup(): void
    {
        $user = User::factory()->pending()->create();

        $this->actingAs($user)->get('/volunteer')->assertRedirect(route('account.pending'));
    }

    public function test_approved_user_is_not_redirected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_pending_user_cannot_access_admin_panel_even_with_a_role(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->pending()->create();
        $user->assignRole('Admin');

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_approve_a_pending_user_via_the_table_action(): void
    {
        $admin = $this->superAdmin();
        $pending = User::factory()->pending()->create();

        Livewire::actingAs($admin)
            ->test(ListUsers::class)
            ->callTableAction('approve', $pending);

        $this->assertTrue($pending->fresh()->isApproved());
        $this->assertDatabaseHas('activity_log', [
            'subject_type' => User::class,
            'subject_id' => $pending->id,
        ]);
    }

    public function test_board_member_can_view_but_not_approve_users(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $boardMember = User::factory()->create();
        $boardMember->assignRole('Board Member');
        $pending = User::factory()->pending()->create();

        $this->actingAs($boardMember)->get('/admin/users')->assertOk();

        Livewire::actingAs($boardMember)
            ->test(ListUsers::class)
            ->assertTableActionHidden('approve', $pending);
    }

    public function test_non_super_admin_cannot_edit_a_super_admin_account(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('Super Admin');

        $this->actingAs($admin);

        $this->assertFalse(UserResource::canEdit($superAdmin));
    }

    private function superAdmin(): User
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        return $user;
    }
}
