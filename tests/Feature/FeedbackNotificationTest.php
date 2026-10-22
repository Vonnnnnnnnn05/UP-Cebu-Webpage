<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FeedbackNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $staff;
    protected Admin $director;
    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->staff = Admin::create([
            'username'  => 'staff_user',
            'full_name' => 'Staff Maria',
            'email'     => 'staff@ttbdo.ph',
            'password'  => Hash::make('secret123'),
            'role'      => 'staff',
        ]);

        $this->director = Admin::create([
            'username'  => 'director_user',
            'full_name' => 'Director Santos',
            'email'     => 'director@ttbdo.ph',
            'password'  => Hash::make('secret123'),
            'role'      => 'director',
        ]);

        $this->admin = Admin::create([
            'username'  => 'admin_user',
            'full_name' => 'Admin Manager',
            'email'     => 'admin@ttbdo.ph',
            'password'  => Hash::make('secret123'),
            'role'      => 'admin',
        ]);
    }

    public function test_director_leaving_event_feedback_creates_in_system_notification_for_staff_without_sending_email(): void
    {
        Mail::fake();

        $event = Event::create([
            'title'            => 'IP Commercialization Workshop 2026',
            'slug'             => 'ip-commercialization-workshop-2026',
            'category'         => 'Workshop',
            'status'           => 'upcoming',
            'approval_status'  => 'approved',
            'event_date'       => '2026-11-15',
            'venue'            => 'AVR 1',
            'enable_view_link' => true,
        ]);

        // Director submits feedback
        $this->actingAs($this->director, 'admin');

        $feedbackText = 'Please coordinate with guest speaker on patent portfolio slides.';
        $response = $this->post(route('admin.events.review', $event->id), [
            'director_comments' => $feedbackText,
        ]);

        $response->assertRedirect();

        // 1. In-system notification stored for staff
        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => Admin::class,
            'notifiable_id'   => $this->staff->id,
            'read_at'         => null,
        ]);

        $staffNotification = $this->staff->unreadNotifications()->first();
        $this->assertNotNull($staffNotification);
        $this->assertEquals('Director Santos', $staffNotification->data['reviewer_name']);
        $this->assertEquals($feedbackText, $staffNotification->data['message']);
        $this->assertEquals('event', $staffNotification->data['item_type']);
        $this->assertEquals($event->id, $staffNotification->data['item_id']);

        // 2. ABSOLUTELY NO EMAIL DISPATCHED (Strictly in-system requirement)
        Mail::assertNothingSent();

        // 3. Staff logs in and sees notification bell badge & dashboard notice
        $this->actingAs($this->staff, 'admin');
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('You have review feedback waiting in the system');

        // Dropdown unread count JSON API returns 1
        $apiResponse = $this->get(route('admin.notifications.unread-count'));
        $apiResponse->assertStatus(200);
        $apiResponse->assertJsonFragment(['count' => 1]);
    }

    public function test_director_leaving_inquiry_feedback_creates_in_system_notification_without_email(): void
    {
        Mail::fake();

        $inquiry = Inquiry::create([
            'full_name'      => 'Dr. Alice Tan',
            'affiliation'    => 'Biotech Lab UP Cebu',
            'email'          => 'alice@biotech.ph',
            'contact_number' => '09171234567',
            'inquiry_type'   => 'Patent Guidance',
            'subject'        => 'Biomedical Sensor Patenting',
            'message'        => 'Requesting initial patentability consultation.',
            'status'         => 'pending',
        ]);

        $this->actingAs($this->director, 'admin');

        $feedbackText = 'High commercial potential. Schedule pre-disclosure conference with IP officer.';
        $response = $this->post(route('admin.inquiries.review', $inquiry->id), [
            'director_comments' => $feedbackText,
            'review_decision'   => 'reviewed',
        ]);

        $response->assertRedirect();

        // Verify staff received notification
        $staffNotification = $this->staff->unreadNotifications()->first();
        $this->assertNotNull($staffNotification);
        $this->assertEquals($feedbackText, $staffNotification->data['message']);
        $this->assertEquals('inquiry', $staffNotification->data['item_type']);

        // No email sent
        Mail::assertNothingSent();
    }

    public function test_staff_updating_inquiry_notes_notifies_director_in_system_without_email(): void
    {
        Mail::fake();

        $inquiry = Inquiry::create([
            'full_name'   => 'Mark Joseph',
            'affiliation' => 'Robotics Club',
            'email'       => 'mark@robotics.ph',
            'subject'     => 'Lab space request',
            'message'     => 'Request for 2 weeks incubator access.',
            'status'      => 'pending',
        ]);

        // Staff updates consultation note
        $this->actingAs($this->staff, 'admin');

        $staffNote = 'Staff scheduled room inspection for next Monday.';
        $response = $this->put(route('admin.inquiries.update', $inquiry->id), [
            'status'      => 'pending',
            'admin_notes' => $staffNote,
        ]);

        $response->assertRedirect();

        // Director receives notification of staff note
        $directorNotification = $this->director->unreadNotifications()->first();
        $this->assertNotNull($directorNotification);
        $this->assertEquals($staffNote, $directorNotification->data['message']);
        $this->assertEquals('Staff Maria', $directorNotification->data['reviewer_name']);

        // No emails sent for status update staying pending
        Mail::assertNothingSent();
    }

    public function test_staff_can_view_notifications_page_and_mark_as_read(): void
    {
        // 1. Create a notification directly for staff
        $this->staff->notify(new \App\Notifications\FeedbackNotification([
            'title'         => 'Director Feedback on Event: Demo Day',
            'message'       => 'Check registration link and poster resolution.',
            'reviewer_name' => 'Director Santos',
            'reviewer_role' => 'director',
            'item_type'     => 'event',
            'item_id'       => 1,
            'item_title'    => 'Demo Day',
            'action_url'    => route('admin.events.index'),
            'type_label'    => 'Event Feedback',
        ]));

        $this->actingAs($this->staff, 'admin');

        // 2. Visit notifications page
        $pageResponse = $this->get(route('admin.notifications.index'));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Feedback &amp; Notifications Center', false);
        $pageResponse->assertSee('Check registration link and poster resolution.');
        $pageResponse->assertSee('Director Santos');

        $notification = $this->staff->unreadNotifications()->first();
        $this->assertNotNull($notification);

        // 3. Mark single notification as read via POST
        $readResponse = $this->post(route('admin.notifications.read', $notification->id));
        $readResponse->assertRedirect();

        $this->assertEquals(0, $this->staff->fresh()->unreadNotifications()->count());
        $this->assertEquals(1, $this->staff->fresh()->readNotifications()->count());

        // 4. Mark unread
        $unreadResponse = $this->post(route('admin.notifications.unread', $notification->id));
        $unreadResponse->assertRedirect();
        $this->assertEquals(1, $this->staff->fresh()->unreadNotifications()->count());

        // 5. Mark all as read via AJAX JSON
        $ajaxResponse = $this->postJson(route('admin.notifications.mark-all-read'));
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJson(['success' => true, 'count' => 0]);
        $this->assertEquals(0, $this->staff->fresh()->unreadNotifications()->count());

        // 6. Clear read history
        $clearResponse = $this->delete(route('admin.notifications.clear-read'));
        $clearResponse->assertRedirect();
        $this->assertEquals(0, $this->staff->fresh()->notifications()->count());
    }
}
