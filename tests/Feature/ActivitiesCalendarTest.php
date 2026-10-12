<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventLog;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ActivitiesCalendarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test public homepage calendar renders activities.
     */
    public function test_public_homepage_displays_activities_calendar(): void
    {
        // Simple Activity (no details page)
        $simpleEvent = Event::create([
            'title'            => 'SKSU Visit',
            'slug'             => 'sksu-visit-test',
            'category'         => 'Activity',
            'event_date'       => '2026-09-25',
            'status'           => 'completed',
            'enable_view_link' => false,
            'summary'          => 'Simple visit record',
            'venue'            => 'SKSU Campus',
        ]);

        // Multi-day Event with View Details enabled
        $multiDayEvent = Event::create([
            'title'            => 'BARMM Visit for Incubation',
            'slug'             => 'barmm-visit-test',
            'category'         => 'Meeting',
            'event_date'       => '2026-09-28',
            'end_date'         => '2026-09-30',
            'status'           => 'upcoming',
            'enable_view_link' => true,
            'summary'          => '3-day incubation meeting',
            'venue'            => 'UP Cebu Campus',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('TTBDO Activities Calendar');
        $response->assertSee('SKSU Visit');
        $response->assertSee('BARMM Visit for Incubation');
    }

    /**
     * Test public event details page displays chronological daily details.
     */
    public function test_public_event_details_page_renders_chronological_daily_breakdown(): void
    {
        $event = Event::create([
            'title'            => 'BARMM Visit for Incubation',
            'slug'             => 'barmm-visit-details-test',
            'category'         => 'Meeting',
            'event_date'       => '2026-09-28',
            'end_date'         => '2026-09-30',
            'status'           => 'upcoming',
            'enable_view_link' => true,
            'summary'          => 'BARMM incubation engagement overview',
            'venue'            => 'UP Cebu & Partners',
        ]);

        $event->logs()->createMany([
            [
                'log_date'    => '2026-09-28',
                'title'       => 'Arrival of BARMM Officials',
                'description' => 'Arrival and welcome ceremony.',
                'sort_order'  => 1,
            ],
            [
                'log_date'    => '2026-09-29',
                'title'       => 'Continuation of the Meeting',
                'description' => 'Incubation strategy sessions.',
                'sort_order'  => 2,
            ],
            [
                'log_date'    => '2026-09-30',
                'title'       => 'Closing Ceremony',
                'description' => 'Synthesis and final agreements.',
                'sort_order'  => 3,
            ],
        ]);

        $response = $this->get('/events/' . $event->slug);
        $response->assertStatus(200);
        $response->assertSee('BARMM Visit for Incubation');
        $response->assertSee('Meeting');
        $response->assertSee('September 28–30, 2026');
        $response->assertSee('Arrival of BARMM Officials');
        $response->assertSee('Continuation of the Meeting');
        $response->assertSee('Closing Ceremony');
        $response->assertSee('Back to Activities Calendar');
    }

    /**
     * Test admin can create a simple activity without required detailed article or summary.
     */
    public function test_admin_can_create_simple_activity(): void
    {
        $admin = Admin::where('email', 'admin@gmail.com')->first();

        $response = $this->actingAs($admin, 'admin')->post('/admin/events', [
            'title'            => 'SKSU Simple Visit',
            'category'         => 'Activity',
            'event_date'       => '2026-09-25',
            'end_date'         => null,
            'status'           => 'completed',
            'enable_view_link' => 0,
        ]);

        $response->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseHas('events', [
            'title'            => 'SKSU Simple Visit',
            'category'         => 'Activity',
            'enable_view_link' => 0,
        ]);
    }

    /**
     * Test admin can add, update, and delete daily details under an event.
     */
    public function test_admin_can_manage_daily_details(): void
    {
        $admin = Admin::where('email', 'admin@gmail.com')->first();

        $event = Event::create([
            'title'            => 'Multi-day Summit',
            'slug'             => 'multi-day-summit',
            'category'         => 'Meeting',
            'event_date'       => '2026-10-10',
            'end_date'         => '2026-10-12',
            'status'           => 'upcoming',
            'enable_view_link' => true,
        ]);

        // 1. Add Daily Detail (valid date within range)
        $addResponse = $this->actingAs($admin, 'admin')->post("/admin/events/{$event->id}/details", [
            'log_date'    => '2026-10-10',
            'title'       => 'Day 1 Opening',
            'description' => 'Welcome and introductory keynotes.',
        ]);
        $addResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('event_logs', [
            'event_id' => $event->id,
            'title'    => 'Day 1 Opening',
        ]);

        $detail = EventLog::where('event_id', $event->id)->first();

        // 2. Update Daily Detail
        $updateResponse = $this->actingAs($admin, 'admin')->put("/admin/events/{$event->id}/details/{$detail->id}", [
            'log_date'    => '2026-10-11',
            'title'       => 'Day 2 Technical Consultations',
            'description' => 'Updated day 2 consultations description.',
        ]);
        $updateResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('event_logs', [
            'id'    => $detail->id,
            'title' => 'Day 2 Technical Consultations',
        ]);

        // 3. Validation: Prevent date outside event start and end dates
        $invalidResponse = $this->actingAs($admin, 'admin')->post("/admin/events/{$event->id}/details", [
            'log_date'    => '2026-10-25', // Outside 2026-10-10 to 2026-10-12
            'title'       => 'Invalid Date Entry',
            'description' => 'This should fail validation.',
        ]);
        $invalidResponse->assertSessionHasErrors(['log_date']);

        // 4. Delete Daily Detail
        $deleteResponse = $this->actingAs($admin, 'admin')->delete("/admin/events/{$event->id}/details/{$detail->id}");
        $deleteResponse->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('event_logs', [
            'id' => $detail->id,
        ]);
    }

    /**
     * Test /login redirects to /admin/login and public nav does not display login button.
     */
    public function test_login_route_redirects_and_public_login_button_is_hidden(): void
    {
        // 1. /login redirects to /admin/login
        $redirectResponse = $this->get('/login');
        $redirectResponse->assertRedirect('/admin/login');

        // 2. /admin/login is directly accessible
        $adminLoginResponse = $this->get('/admin/login');
        $adminLoginResponse->assertStatus(200);

        // 3. Public homepage does not show visible Staff Login button
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertDontSee('Staff Login');
    }

    /**
     * Test admin can upload images for both the main event and daily activity details ("everyday images").
     */
    public function test_admin_can_upload_event_cover_image_and_daily_detail_image(): void
    {
        Storage::fake('public');

        $admin = Admin::where('email', 'admin@gmail.com')->first();
        $coverFile = UploadedFile::fake()->image('event_banner.jpg', 800, 600);

        // 1. Create event with uploaded cover image
        $createResponse = $this->actingAs($admin, 'admin')->post('/admin/events', [
            'title'            => 'Robotics Bootcamp 2026',
            'category'         => 'Workshop',
            'event_date'       => '2026-11-01',
            'end_date'         => '2026-11-03',
            'status'           => 'upcoming',
            'image'            => $coverFile,
            'enable_view_link' => 1,
        ]);
        $createResponse->assertRedirect();

        $event = Event::where('title', 'Robotics Bootcamp 2026')->first();
        $this->assertNotNull($event->image_url);
        $this->assertStringContainsString('storage/events/covers/', $event->image_url);

        // 2. Add daily detail with image ("everyday images")
        $dailyFile = UploadedFile::fake()->image('day1_photo.jpg', 600, 400);
        $detailResponse = $this->actingAs($admin, 'admin')->post("/admin/events/{$event->id}/details", [
            'log_date'    => '2026-11-01',
            'title'       => 'Day 1 Hardware Assembly',
            'description' => 'Students assembled microcontrollers.',
            'image'       => $dailyFile,
        ]);
        $detailResponse->assertSessionHasNoErrors();

        $detail = EventLog::where('event_id', $event->id)->first();
        $this->assertNotNull($detail->image_path);
        $this->assertStringContainsString('storage/events/daily/', $detail->image_path);

        // 3. Update daily detail to remove image
        $updateResponse = $this->actingAs($admin, 'admin')->put("/admin/events/{$event->id}/details/{$detail->id}", [
            'log_date'     => '2026-11-01',
            'title'        => 'Day 1 Hardware Assembly (Updated)',
            'description'  => 'Updated description without photo.',
            'remove_image' => 1,
        ]);
        $updateResponse->assertSessionHasNoErrors();
        $detail->refresh();
        $this->assertNull($detail->image_path);
    }

    /**
     * Test public page displays both event cover and daily detail photos.
     */
    public function test_public_page_displays_event_and_daily_detail_images(): void
    {
        $event = Event::create([
            'title'            => 'Innovation Showcase',
            'slug'             => 'innovation-showcase-photo-test',
            'category'         => 'Demo Day',
            'event_date'       => '2026-11-15',
            'status'           => 'upcoming',
            'image_url'        => 'storage/events/covers/sample_cover.jpg',
            'enable_view_link' => true,
        ]);

        $event->logs()->create([
            'log_date'    => '2026-11-15',
            'title'       => 'Day 1 Pitching Sessions',
            'description' => 'Startups pitched to investors.',
            'image_path'  => 'storage/events/daily/sample_pitch_photo.jpg',
            'sort_order'  => 1,
        ]);

        $response = $this->get('/events/' . $event->slug);
        $response->assertStatus(200);
        $response->assertSee('storage/events/covers/sample_cover.jpg');
        $response->assertSee('storage/events/daily/sample_pitch_photo.jpg');
    }
}
