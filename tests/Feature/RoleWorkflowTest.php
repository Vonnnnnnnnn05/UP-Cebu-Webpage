<?php

namespace Tests\Feature;

use App\Mail\InquiryApprovedMail;
use App\Mail\InquiryDeclinedMail;
use App\Models\Admin;
use App\Models\Event;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RoleWorkflowTest extends TestCase
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
            'full_name' => 'Staff Member',
            'email'     => 'staff@gmail.com',
            'password'  => Hash::make('password123'),
            'role'      => 'staff',
            'is_active' => true,
        ]);

        $this->director = Admin::create([
            'username'  => 'director_user',
            'full_name' => 'Director Person',
            'email'     => 'director@gmail.com',
            'password'  => Hash::make('password123'),
            'role'      => 'director',
            'is_active' => true,
        ]);

        $this->admin = Admin::create([
            'username'  => 'admin_user',
            'full_name' => 'Admin Manager',
            'email'     => 'admin@gmail.com',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_staff_posts_event_and_it_is_immediately_visible_on_public_calendar(): void
    {
        $this->actingAs($this->staff, 'admin');

        $payload = [
            'title'            => 'Workshop on IP Protection',
            'summary'          => 'Workshop covering patent basics.',
            'category'         => 'Workshop',
            'status'           => 'upcoming',
            'event_date'       => '2026-10-15',
            'venue'            => 'Conference Hall 1',
            'venue_type'       => 'in-person',
            'enable_view_link' => 1,
        ];

        // 1. Staff creates event
        $response = $this->post(route('admin.events.store'), $payload);
        $response->assertRedirect(route('admin.events.index'));

        // Event is immediately approved & published
        $this->assertDatabaseHas('events', [
            'title'           => 'Workshop on IP Protection',
            'approval_status' => 'approved',
        ]);

        // 2. Immediately visible to public visitors without waiting for approval
        $publicResponse = $this->get('/');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Workshop on IP Protection');
    }

    public function test_director_observes_published_event_and_leaves_quality_feedback(): void
    {
        // 1. Published event posted by staff
        $event = Event::create([
            'title'            => 'Incubation Orientation',
            'slug'             => 'incubation-orientation',
            'category'         => 'Incubation',
            'status'           => 'upcoming',
            'approval_status'  => 'approved',
            'event_date'       => '2026-10-20',
            'venue'            => 'Room 204',
            'enable_view_link' => true,
        ]);

        // 2. Director reviews the live event and leaves quality feedback / suggestions
        $this->actingAs($this->director, 'admin');

        $response = $this->post(route('admin.events.review', $event->id), [
            'director_comments' => 'Title is clear. Please expand description with speaker bio before next week.',
        ]);

        $response->assertRedirect();

        // 3. Comments are saved, and the event remains published on public calendar
        $this->assertDatabaseHas('events', [
            'id'                => $event->id,
            'approval_status'   => 'approved',
            'director_comments' => 'Title is clear. Please expand description with speaker bio before next week.',
            'reviewed_by'       => $this->director->id,
        ]);

        // Public visitor can still see the live event
        $publicResponse = $this->get('/');
        $publicResponse->assertSee('Incubation Orientation');
    }

    public function test_admin_makes_corrections_to_published_event(): void
    {
        $event = Event::create([
            'title'            => 'Startup Demo Day 2026 - Typpo in Title',
            'slug'             => 'startup-demo-day-2026',
            'category'         => 'Demo Day',
            'status'           => 'upcoming',
            'approval_status'  => 'approved',
            'event_date'       => '2026-11-05',
            'venue'            => 'Innovation Center',
            'enable_view_link' => true,
        ]);

        // Administrator corrects typo and updates event
        $this->actingAs($this->admin, 'admin');

        $response = $this->put(route('admin.events.update', $event->id), [
            'title'            => 'Startup Demo Day 2026 - Corrected Title',
            'category'         => 'Demo Day',
            'status'           => 'upcoming',
            'event_date'       => '2026-11-05',
            'venue'            => 'Innovation Center',
        ]);
        $response->assertRedirect(route('admin.events.index'));

        $this->assertDatabaseHas('events', [
            'id'              => $event->id,
            'title'           => 'Startup Demo Day 2026 - Corrected Title',
            'approval_status' => 'approved',
        ]);
    }

    public function test_director_cannot_delete_event(): void
    {
        $event = Event::create([
            'title'            => 'Important Protected Summit',
            'slug'             => 'important-protected-summit',
            'category'         => 'Summit',
            'status'           => 'upcoming',
            'approval_status'  => 'approved',
            'event_date'       => '2026-11-15',
            'venue'            => 'Auditorium',
            'enable_view_link' => true,
        ]);

        // Director cannot delete event
        $this->actingAs($this->director, 'admin');
        $response = $this->delete(route('admin.events.destroy', $event->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('events', ['id' => $event->id]);

        // Admin has governance authority to delete
        $this->actingAs($this->admin, 'admin');
        $adminDeleteResponse = $this->delete(route('admin.events.destroy', $event->id));
        $adminDeleteResponse->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_director_reviews_inquiry_and_admin_approves_it(): void
    {
        $inquiry = Inquiry::create([
            'full_name'    => 'Juan Dela Cruz',
            'organization' => 'BioTech Ventures',
            'email'        => 'juan@biotech.ph',
            'phone'        => '09171234567',
            'category'     => 'Patent & IP Filing',
            'subject'      => 'Request for Patent Guidance',
            'message'      => 'We are looking for patent assessment on a novel biomedical device.',
            'status'       => 'pending',
        ]);

        // Director reviews inquiry
        $this->actingAs($this->director, 'admin');
        $response = $this->post(route('admin.inquiries.review', $inquiry->id), [
            'director_comments' => 'High commercialization value. Endorse to IP consultant.',
            'review_decision'   => 'reviewed',
        ]);
        $response->assertRedirect();

        $this->assertDatabaseHas('inquiries', [
            'id'                => $inquiry->id,
            'status'            => 'reviewed',
            'director_comments' => 'High commercialization value. Endorse to IP consultant.',
            'reviewed_by'       => $this->director->id,
        ]);

        // Admin approves inquiry
        $this->actingAs($this->admin, 'admin');
        $response = $this->post(route('admin.inquiries.approve', $inquiry->id));
        $response->assertRedirect();

        $this->assertDatabaseHas('inquiries', [
            'id'          => $inquiry->id,
            'status'      => 'approved',
            'approved_by' => $this->admin->id,
        ]);
    }

    public function test_staff_can_update_inquiry_status_and_notes(): void
    {
        $inquiry = Inquiry::create([
            'full_name'      => 'Original Visitor',
            'affiliation'    => 'Old Startup Co',
            'email'          => 'original@startup.ph',
            'contact_number' => '09170000000',
            'inquiry_type'   => 'General Inquiry',
            'subject'        => 'Original Subject',
            'message'        => 'Original message inquiry.',
            'status'         => 'pending',
        ]);

        $this->actingAs($this->staff, 'admin');

        $response = $this->put(route('admin.inquiries.update', $inquiry->id), [
            'status'      => 'approved',
            'admin_notes' => 'Staff assigned coordinator and booked meeting room.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('inquiries', [
            'id'             => $inquiry->id,
            'full_name'      => 'Original Visitor',
            'email'          => 'original@startup.ph',
            'status'         => 'approved',
            'admin_notes'    => 'Staff assigned coordinator and booked meeting room.',
        ]);
    }

    public function test_staff_can_approve_decline_and_set_pending_status(): void
    {
        Mail::fake();

        $inquiry = Inquiry::create([
            'full_name'      => 'Test Applicant',
            'affiliation'    => 'Applicant Org',
            'email'          => 'applicant@test.ph',
            'subject'        => 'Applicant Request',
            'message'        => 'Applicant content message.',
            'status'         => 'pending',
        ]);

        $this->actingAs($this->staff, 'admin');

        // 1. Staff approves inquiry
        $approveRes = $this->post(route('admin.inquiries.approve', $inquiry->id));
        $approveRes->assertRedirect();
        $this->assertDatabaseHas('inquiries', [
            'id'     => $inquiry->id,
            'status' => 'approved',
        ]);
        Mail::assertSent(InquiryApprovedMail::class, function ($mail) {
            return $mail->hasTo('applicant@test.ph') && $mail->organizationName === 'Applicant Org';
        });

        // 2. Staff declines inquiry
        $declineRes = $this->post(route('admin.inquiries.decline', $inquiry->id));
        $declineRes->assertRedirect();
        $this->assertDatabaseHas('inquiries', [
            'id'     => $inquiry->id,
            'status' => 'declined',
        ]);
        Mail::assertSent(InquiryDeclinedMail::class, function ($mail) {
            return $mail->hasTo('applicant@test.ph') && $mail->organizationName === 'Applicant Org';
        });

        // 3. Staff resets status to pending via update
        $pendingRes = $this->put(route('admin.inquiries.update', $inquiry->id), [
            'status'      => 'pending',
            'admin_notes' => 'Returned to pending queue.',
        ]);
        $pendingRes->assertRedirect();
        $this->assertDatabaseHas('inquiries', [
            'id'          => $inquiry->id,
            'status'      => 'pending',
            'admin_notes' => 'Returned to pending queue.',
        ]);
    }

    public function test_approved_inquiry_sends_email_with_organization_name(): void
    {
        Mail::fake();

        $inquiry = Inquiry::create([
            'full_name'   => 'Elena Ramos',
            'affiliation' => 'Cebu Robotics Hub',
            'email'       => 'elena@robotics.ph',
            'subject'     => 'Lab Facility Booking Request',
            'message'     => 'We would like to book the maker space for 3 days.',
            'status'      => 'pending',
        ]);

        $this->actingAs($this->staff, 'admin');

        $response = $this->put(route('admin.inquiries.update', $inquiry->id), [
            'status'      => 'approved',
            'admin_notes' => 'Approved booking schedule.',
        ]);

        $response->assertRedirect();

        Mail::assertSent(InquiryApprovedMail::class, function ($mail) use ($inquiry) {
            return $mail->hasTo('elena@robotics.ph') &&
                   $mail->organizationName === 'Cebu Robotics Hub' &&
                   $mail->inquiry->id === $inquiry->id;
        });

        Mail::assertNotSent(InquiryDeclinedMail::class);

        // Render and check content
        $mailable = new InquiryApprovedMail($inquiry);
        $mailable->assertSeeInHtml('Hello! Cebu Robotics Hub');
        $mailable->assertSeeInHtml('You are approved');
    }

    public function test_declined_inquiry_sends_empathetic_email_with_organization_name(): void
    {
        Mail::fake();

        $inquiry = Inquiry::create([
            'full_name'   => 'Mark Santos',
            'affiliation' => 'GreenTech Startups',
            'email'       => 'mark@greentech.ph',
            'subject'     => 'Solar Dryer Grant Partnership',
            'message'     => 'Seeking joint IP development grant.',
            'status'      => 'pending',
        ]);

        $this->actingAs($this->staff, 'admin');

        $response = $this->put(route('admin.inquiries.update', $inquiry->id), [
            'status'      => 'declined',
            'admin_notes' => 'Not aligned with current thrust.',
        ]);

        $response->assertRedirect();

        Mail::assertSent(InquiryDeclinedMail::class, function ($mail) use ($inquiry) {
            return $mail->hasTo('mark@greentech.ph') &&
                   $mail->organizationName === 'GreenTech Startups' &&
                   $mail->inquiry->id === $inquiry->id;
        });

        Mail::assertNotSent(InquiryApprovedMail::class);

        // Render and check empathetic content
        $mailable = new InquiryDeclinedMail($inquiry);
        $mailable->assertSeeInHtml('Hello! GreenTech Startups');
        $mailable->assertSeeInHtml('We understand the time, passion, and meticulous thought');
        $mailable->assertSeeInHtml('Please be assured that this outcome is by no means a reflection of the merit');
    }

    public function test_updating_notes_without_status_change_does_not_retrigger_email(): void
    {
        Mail::fake();

        $inquiry = Inquiry::create([
            'full_name'   => 'Already Approved Org Person',
            'affiliation' => 'Verified Innovations',
            'email'       => 'verified@innovations.ph',
            'subject'     => 'Incubation Query',
            'message'     => 'Details here',
            'status'      => 'approved',
        ]);

        $this->actingAs($this->staff, 'admin');

        $response = $this->put(route('admin.inquiries.update', $inquiry->id), [
            'status'      => 'approved',
            'admin_notes' => 'Just updating coordinator notes.',
        ]);

        $response->assertRedirect();
        Mail::assertNothingSent();
    }

    public function test_staff_can_view_director_feedback_and_notes_on_events_and_inquiries(): void
    {
        // 1. Event with director comments
        $event = Event::create([
            'title'             => 'IP Commercialization Summit',
            'slug'              => 'ip-commercialization-summit',
            'category'          => 'Summit',
            'status'            => 'upcoming',
            'approval_status'   => 'approved',
            'event_date'        => '2026-11-20',
            'venue'             => 'Auditorium Hall',
            'director_comments' => 'Please coordinate audio-visual crew ahead of time.',
            'reviewed_by'       => $this->director->id,
            'reviewed_at'       => now(),
        ]);

        $this->actingAs($this->staff, 'admin');

        $eventPage = $this->get(route('admin.events.index'));
        $eventPage->assertStatus(200);
        $eventPage->assertSee('Please coordinate audio-visual crew ahead of time.');
        $eventPage->assertSee('View Feedback');

        // 2. Inquiry with director comments and staff/admin notes
        $inquiry = Inquiry::create([
            'full_name'         => 'Test Inquirer',
            'affiliation'       => 'Test Org',
            'email'             => 'inquirer@test.ph',
            'subject'           => 'Technology Transfer Inquiry',
            'message'           => 'Proposal message.',
            'status'            => 'reviewed',
            'director_comments' => 'Director recommends faculty meeting.',
            'admin_notes'       => 'Staff has drafted response.',
            'reviewed_by'       => $this->director->id,
            'reviewed_at'       => now(),
        ]);

        $inquiryPage = $this->get(route('admin.inquiries.index'));
        $inquiryPage->assertStatus(200);
        $inquiryPage->assertSee('Director recommends faculty meeting.');
        $inquiryPage->assertSee('Staff has drafted response.');
        $inquiryPage->assertSee($this->director->full_name);
    }
}

