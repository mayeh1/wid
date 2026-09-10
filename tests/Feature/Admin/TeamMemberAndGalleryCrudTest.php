<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\GalleryAlbumResource\Pages\CreateGalleryAlbum;
use App\Filament\Resources\GalleryAlbumResource\RelationManagers\PhotosRelationManager;
use App\Filament\Resources\TeamMemberResource\Pages\CreateTeamMember;
use App\Filament\Resources\TeamMemberResource\Pages\EditTeamMember;
use App\Models\GalleryAlbum;
use App\Models\TeamMember;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class TeamMemberAndGalleryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_member_can_be_created_with_social_links(): void
    {
        $admin = $this->superAdmin();

        Livewire::actingAs($admin)
            ->test(CreateTeamMember::class)
            ->fillForm([
                'name' => 'Jane Founder',
                'slug' => 'jane-founder',
                'role_title' => 'Board Chair',
                'category' => 'board_member',
                'bio' => 'A passionate advocate for women in development.',
                'website_url' => 'https://example.com',
                'portfolio_url' => 'https://example.com/portfolio',
                'linkedin_url' => 'https://linkedin.com/in/jane',
                'twitter_url' => 'https://twitter.com/jane',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('team_members', [
            'name' => 'Jane Founder',
            'website_url' => 'https://example.com',
            'portfolio_url' => 'https://example.com/portfolio',
            'linkedin_url' => 'https://linkedin.com/in/jane',
            'twitter_url' => 'https://twitter.com/jane',
        ]);

        $member = TeamMember::where('name', 'Jane Founder')->first();
        $this->assertCount(4, $member->links());
    }

    public function test_team_member_can_be_edited(): void
    {
        $admin = $this->superAdmin();
        $member = TeamMember::factory()->create(['name' => 'Old Name']);

        Livewire::actingAs($admin)
            ->test(EditTeamMember::class, ['record' => $member->getRouteKey()])
            ->fillForm(['name' => 'Updated Name'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('team_members', ['id' => $member->id, 'name' => 'Updated Name']);
    }

    public function test_gallery_album_can_be_created(): void
    {
        $admin = $this->superAdmin();

        Livewire::actingAs($admin)
            ->test(CreateGalleryAlbum::class)
            ->fillForm([
                'title' => 'Annual Gala 2026',
                'slug' => 'annual-gala-2026',
                'type' => 'photos',
                'description' => 'Highlights from the gala.',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('gallery_albums', ['slug' => 'annual-gala-2026']);
    }

    public function test_gallery_photo_can_be_added_with_caption_via_relation_manager(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = $this->superAdmin();
        $album = GalleryAlbum::factory()->create();

        Livewire::actingAs($admin)
            ->test(PhotosRelationManager::class, [
                'ownerRecord' => $album,
                'pageClass' => \App\Filament\Resources\GalleryAlbumResource\Pages\EditGalleryAlbum::class,
            ])
            ->mountTableAction('create')
            ->setTableActionData([
                'photo' => [UploadedFile::fake()->image('gala.jpg')],
                'caption' => 'Guests enjoying the evening',
            ])
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('gallery_photos', [
            'gallery_album_id' => $album->id,
            'caption' => 'Guests enjoying the evening',
        ]);
    }

    private function superAdmin(): User
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        return $user;
    }
}
