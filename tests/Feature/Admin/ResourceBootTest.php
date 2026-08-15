<?php

namespace Tests\Feature\Admin;

use App\Models\User;
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
        'newsletter-subscribers', 'activities',
    ];

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
