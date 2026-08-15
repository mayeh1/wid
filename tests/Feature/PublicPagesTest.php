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
        '/campaigns', '/donate', '/success-stories',
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

    public function test_newsletter_signup_creates_a_subscriber(): void
    {
        $response = $this->post('/newsletter', ['email' => 'subscriber@example.com']);

        $response->assertRedirect();
        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'subscriber@example.com']);
    }

    public function test_newsletter_signup_is_idempotent_for_duplicate_emails(): void
    {
        $this->post('/newsletter', ['email' => 'dup@example.com']);
        $this->post('/newsletter', ['email' => 'dup@example.com']);

        $this->assertDatabaseCount('newsletter_subscribers', 1);
    }
}
