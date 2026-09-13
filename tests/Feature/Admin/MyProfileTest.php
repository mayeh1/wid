<?php

namespace Tests\Feature\Admin;

use App\Filament\Pages\MyProfile;
use App\Models\TeamMember;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MyProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_a_linked_team_member_cannot_access_my_profile(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Team Member');

        $this->actingAs($user)->get('/admin/my-profile')->assertForbidden();
    }

    public function test_linked_team_member_can_edit_their_own_bio_and_links(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Team Member');

        $teamMember = TeamMember::factory()->create([
            'user_id' => $user->id,
            'name' => 'Jane Staff',
            'category' => 'staff',
            'bio' => 'Old bio',
        ]);

        $this->actingAs($user)->get('/admin/my-profile')->assertOk();

        Livewire::actingAs($user)
            ->test(MyProfile::class)
            ->fillForm([
                'bio' => 'Updated bio about my work at WID.',
                'linkedin_url' => 'https://linkedin.com/in/janestaff',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $teamMember->refresh();
        $this->assertSame('Updated bio about my work at WID.', $teamMember->bio);
        $this->assertSame('https://linkedin.com/in/janestaff', $teamMember->linkedin_url);
        // Name/category are not editable from this page - must stay untouched.
        $this->assertSame('Jane Staff', $teamMember->name);
        $this->assertSame('staff', $teamMember->category);
    }

    public function test_linked_team_member_cannot_edit_someone_elses_profile(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $userA = User::factory()->create();
        $userA->assignRole('Team Member');
        TeamMember::factory()->create(['user_id' => $userA->id, 'bio' => 'A bio']);

        $userB = User::factory()->create();
        $memberB = TeamMember::factory()->create(['user_id' => null, 'bio' => 'B bio']);

        Livewire::actingAs($userA)
            ->test(MyProfile::class)
            ->fillForm(['bio' => 'Hijacked bio'])
            ->call('save');

        $this->assertSame('B bio', $memberB->fresh()->bio);
    }

    public function test_team_member_role_has_no_oversight_or_content_access(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Team Member');
        TeamMember::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get('/admin')->assertOk();
        $this->actingAs($user)->get('/admin/donations')->assertForbidden();
        $this->actingAs($user)->get('/admin/pages')->assertForbidden();
        $this->actingAs($user)->get('/admin/activities')->assertForbidden();
    }

    public function test_founder_has_oversight_access_plus_blog_and_news_but_not_pages(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Founder');

        $this->actingAs($user)->get('/admin/donations')->assertOk();
        $this->actingAs($user)->get('/admin/blog-posts')->assertOk();
        $this->actingAs($user)->get('/admin/blog-posts/create')->assertOk();
        $this->actingAs($user)->get('/admin/news-posts')->assertOk();
        $this->actingAs($user)->get('/admin/pages')->assertForbidden();
    }

    public function test_board_member_can_also_use_my_profile_when_linked(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Board Member');
        TeamMember::factory()->create(['user_id' => $user->id, 'category' => 'board_member']);

        $this->actingAs($user)->get('/admin/my-profile')->assertOk();
    }
}
