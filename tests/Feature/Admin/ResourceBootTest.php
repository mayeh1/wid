<?php

namespace Tests\Feature\Admin;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Download;
use App\Models\Event;
use App\Models\Faq;
use App\Models\GalleryAlbum;
use App\Models\HeroSlide;
use App\Models\Membership;
use App\Models\MembershipLevel;
use App\Models\Menu;
use App\Models\NewsletterSubscriber;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\Partner;
use App\Models\PaymentMethod;
use App\Models\Program;
use App\Models\Project;
use App\Models\SuccessStory;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Volunteer;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceBootTest extends TestCase
{
    use RefreshDatabase;

    private const RESOURCE_INDEX_ROUTES = [
        'pages', 'team-members', 'programs', 'projects', 'events',
        'blog-categories', 'blog-posts', 'news-posts', 'gallery-albums',
        'testimonials', 'partners', 'faqs', 'downloads', 'hero-slides', 'menus',
        'contact-messages', 'payment-methods', 'campaigns', 'donations',
        'volunteers', 'membership-levels', 'memberships', 'success-stories',
        'newsletter-subscribers', 'activities', 'users',
    ];

    /**
     * slug => [has create page, record factory]. Resources whose records only
     * originate from the public site (donations, volunteers, contact messages)
     * intentionally have no Create page in the admin.
     */
    private function resourceRecordMap(): array
    {
        return [
            'pages' => [true, fn () => Page::factory()->create()],
            'team-members' => [true, fn () => TeamMember::factory()->create()],
            'programs' => [true, fn () => Program::factory()->create()],
            'projects' => [true, fn () => Project::factory()->create()],
            'events' => [true, fn () => Event::factory()->create()],
            'blog-categories' => [true, fn () => BlogCategory::create(['name' => 'General', 'slug' => 'general'])],
            'blog-posts' => [true, fn () => BlogPost::factory()->create()],
            'news-posts' => [true, fn () => NewsPost::factory()->create()],
            'gallery-albums' => [true, fn () => GalleryAlbum::factory()->create()],
            'testimonials' => [true, fn () => Testimonial::factory()->create()],
            'partners' => [true, fn () => Partner::factory()->create()],
            'faqs' => [true, fn () => Faq::factory()->create()],
            'downloads' => [true, fn () => Download::factory()->create()],
            'hero-slides' => [true, fn () => HeroSlide::factory()->create()],
            'menus' => [true, fn () => Menu::create(['name' => 'Main Menu', 'slug' => 'main-menu'])],
            'contact-messages' => [false, fn () => ContactMessage::create([
                'name' => 'Jane Doe', 'email' => 'jane@example.com', 'message' => 'Hello there.',
            ])],
            'payment-methods' => [true, fn () => PaymentMethod::factory()->create()],
            'campaigns' => [true, fn () => Campaign::factory()->create()],
            'donations' => [false, fn () => \App\Models\Donation::factory()->create()],
            'volunteers' => [false, fn () => Volunteer::factory()->create()],
            'membership-levels' => [true, fn () => MembershipLevel::factory()->create()],
            'memberships' => [true, fn () => Membership::factory()->create()],
            'success-stories' => [true, fn () => SuccessStory::factory()->create()],
            'newsletter-subscribers' => [true, fn () => NewsletterSubscriber::create(['email' => 'sub@example.com'])],
            'users' => [true, fn () => User::factory()->create()],
            // 'activities' intentionally excluded: read-only, no create/edit pages.
        ];
    }

    public function test_admin_dashboard_boots_for_super_admin(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_every_resource_index_page_boots(): void
    {
        $admin = $this->superAdmin();

        foreach (self::RESOURCE_INDEX_ROUTES as $slug) {
            $this->actingAs($admin)
                ->get("/admin/{$slug}")
                ->assertOk();
        }
    }

    public function test_every_resource_create_page_boots(): void
    {
        $admin = $this->superAdmin();

        foreach ($this->resourceRecordMap() as $slug => [$hasCreate, $makeRecord]) {
            if (! $hasCreate) {
                continue;
            }

            $this->actingAs($admin)
                ->get("/admin/{$slug}/create")
                ->assertOk();
        }
    }

    public function test_every_resource_edit_page_boots(): void
    {
        $admin = $this->superAdmin();

        foreach ($this->resourceRecordMap() as $slug => [$hasCreate, $makeRecord]) {
            $record = $makeRecord();

            $this->actingAs($admin)
                ->get("/admin/{$slug}/{$record->id}/edit")
                ->assertOk();
        }
    }

    public function test_site_settings_page_boots(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->get('/admin/manage-site-settings')->assertOk();
    }

    public function test_reports_page_boots(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->get('/admin/reports')->assertOk();
    }

    public function test_admin_can_download_pdf_summary_report(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)
            ->get(route('admin.reports.summary-pdf'))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_non_admin_cannot_download_pdf_summary_report(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user)->get(route('admin.reports.summary-pdf'))->assertForbidden();
    }

    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    private function superAdmin(): User
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        return $user;
    }
}
