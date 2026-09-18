<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\Inquiry;
use App\Models\News;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_the_public_homepage_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Technology Transfer');
        $response->assertSee('Login');

        $admin = Admin::first();
        $authResponse = $this->actingAs($admin, 'admin')->get('/');
        $authResponse->assertStatus(200);
        $authResponse->assertSee('Dashboard');
        $authResponse->assertSee('Logout');
    }

    public function test_api_search_endpoint_returns_results(): void
    {
        $response = $this->getJson('/api/search?q=UP');
        $response->assertStatus(200);
        $response->assertJsonStructure(['results']);
    }

    public function test_inquiry_submission_stores_record(): void
    {
        $payload = [
            'full_name' => 'John Innovator',
            'email' => 'john@example.com',
            'contact_number' => '09170001122',
            'affiliation' => 'student',
            'inquiry_type' => 'ip_protection',
            'subject' => 'IoT Patent Testing',
            'message' => 'We would like to consult on novelty search and drafting claims.',
        ];

        $response = $this->postJson('/inquiries', $payload);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('inquiries', [
            'email' => 'john@example.com',
            'subject' => 'IoT Patent Testing',
        ]);
    }

    public function test_admin_login_and_dashboard_access(): void
    {
        // 1. Visit login page
        $loginPage = $this->get('/admin/login');
        $loginPage->assertStatus(200);

        // 2. Login with seeded credentials
        $loginResponse = $this->post('/admin/login', [
            'identity' => 'superadmin@gmail.com',
            'password' => 'password',
        ]);
        $loginResponse->assertRedirect(route('admin.dashboard'));

        // 3. Access admin dashboard as authenticated admin
        $admin = Admin::where('email', 'superadmin@gmail.com')->first();
        $dashboardResponse = $this->actingAs($admin, 'admin')->get('/admin');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('SYSTEM OVERVIEW');
        $dashboardResponse->assertSee('Welcome back');
    }

    public function test_unauthenticated_user_cannot_access_admin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_manage_news_crud(): void
    {
        $admin = Admin::where('email', 'superadmin@gmail.com')->first();

        // 1. Create News
        $createResponse = $this->actingAs($admin, 'admin')->post('/admin/news', [
            'title' => 'Test Innovation News Headline',
            'category' => 'PARTNERSHIP',
            'badge_label' => 'NEW',
            'published_date' => '2026-09-18',
            'author' => 'TTBDO Media',
            'summary' => 'This is a test summary for the article.',
            'content' => '<p>Test body content.</p>',
            'is_featured' => 0,
            'is_published' => 1,
        ]);
        $createResponse->assertRedirect(route('admin.news.index'));

        $this->assertDatabaseHas('news', ['title' => 'Test Innovation News Headline']);
        $news = News::where('title', 'Test Innovation News Headline')->first();

        // 2. Toggle Featured
        $toggleResponse = $this->actingAs($admin, 'admin')->post("/admin/news/{$news->id}/toggle-featured");
        $toggleResponse->assertStatus(302);
        $this->assertTrue($news->fresh()->is_featured);

        // 3. Update News
        $updateResponse = $this->actingAs($admin, 'admin')->put("/admin/news/{$news->id}", [
            'title' => 'Updated Innovation News Headline',
            'category' => 'PARTNERSHIP',
            'badge_label' => 'UPDATED',
            'published_date' => '2026-09-18',
            'author' => 'TTBDO Media',
            'summary' => 'Updated summary text.',
            'content' => '<p>Updated content.</p>',
            'is_featured' => 1,
            'is_published' => 1,
        ]);
        $updateResponse->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', ['title' => 'Updated Innovation News Headline']);

        // 4. Delete News
        $deleteResponse = $this->actingAs($admin, 'admin')->delete("/admin/news/{$news->id}");
        $deleteResponse->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    public function test_admin_can_manage_events_crud(): void
    {
        $admin = Admin::where('email', 'superadmin@gmail.com')->first();

        // 1. Create Event
        $createResponse = $this->actingAs($admin, 'admin')->post('/admin/events', [
            'title' => 'Next-Gen Startup Hackathon',
            'category' => 'WORKSHOP',
            'badge_label' => 'HACKATHON',
            'event_date' => '2026-10-01',
            'start_time' => '08:00 AM',
            'end_time' => '06:00 PM',
            'venue' => 'UP Cebu Performing Arts Hall',
            'venue_type' => 'in-person',
            'summary' => '48-hour student innovation challenge.',
            'description' => '<p>Full hackathon rules and guidelines.</p>',
            'status' => 'upcoming',
        ]);
        $createResponse->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseHas('events', ['title' => 'Next-Gen Startup Hackathon']);

        $event = Event::where('title', 'Next-Gen Startup Hackathon')->first();

        // 2. Update Event
        $updateResponse = $this->actingAs($admin, 'admin')->put("/admin/events/{$event->id}", [
            'title' => 'Next-Gen Startup Hackathon - Revised',
            'category' => 'WORKSHOP',
            'badge_label' => 'HACKATHON',
            'event_date' => '2026-10-05',
            'start_time' => '08:30 AM',
            'end_time' => '05:30 PM',
            'venue' => 'UP Cebu SRP Campus',
            'venue_type' => 'hybrid',
            'summary' => 'Updated 48-hour challenge summary.',
            'status' => 'upcoming',
        ]);
        $updateResponse->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseHas('events', ['title' => 'Next-Gen Startup Hackathon - Revised']);

        // 3. Delete Event
        $deleteResponse = $this->actingAs($admin, 'admin')->delete("/admin/events/{$event->id}");
        $deleteResponse->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_admin_can_update_and_delete_inquiries(): void
    {
        $admin = Admin::where('email', 'superadmin@gmail.com')->first();
        $inquiry = Inquiry::first();

        // 1. Update Inquiry Status
        $updateResponse = $this->actingAs($admin, 'admin')->put("/admin/inquiries/{$inquiry->id}", [
            'status' => 'resolved',
            'admin_notes' => 'Consultation finished and license issued.',
        ]);
        $updateResponse->assertStatus(302);
        $this->assertEquals('resolved', $inquiry->fresh()->status);
        $this->assertEquals('Consultation finished and license issued.', $inquiry->fresh()->admin_notes);

        // 2. Delete Inquiry
        $deleteResponse = $this->actingAs($admin, 'admin')->delete("/admin/inquiries/{$inquiry->id}");
        $deleteResponse->assertStatus(302);
        $this->assertDatabaseMissing('inquiries', ['id' => $inquiry->id]);
    }
}

