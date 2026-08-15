<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private const INDEX_ROUTES = [
        '/', '/about', '/programs', '/projects', '/events', '/blog',
        '/news', '/gallery', '/faqs', '/resources', '/contact',
        '/campaigns', '/donate',
    ];

    public function test_every_public_index_page_boots_with_no_data(): void
    {
        foreach (self::INDEX_ROUTES as $route) {
            $this->get($route)->assertOk();
        }
    }

    public function test_contact_form_creates_a_message(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Hello, I would like to learn more.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contact_messages', ['email' => 'jane@example.com']);
    }
}
