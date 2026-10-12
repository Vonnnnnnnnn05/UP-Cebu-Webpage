<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventLog;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Standard category options for TTBDO activities & events.
     */
    protected array $categories = [
        'Activity',
        'Meeting',
        'Incubation',
        'University Visit',
        'Training',
        'Seminar',
        'Ceremony',
        'Partnership',
        'Workshop',
        'Demo Day',
        'Annual Summit',
    ];

    public function index(Request $request): View
    {
        $status         = trim($request->query('status', ''));
        $category       = trim($request->query('category', ''));
        $approvalStatus = trim($request->query('approval_status', ''));
        $search         = trim($request->query('search', ''));

        // Paginated table list with logs count and reviewer/approver relations
        $eventsList = Event::query()
            ->withCount('logs')
            ->with(['reviewer', 'approver'])
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->when($approvalStatus, function ($query, $approvalStatus) {
                $query->where('approval_status', $approvalStatus);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('summary', 'like', "%{$search}%")
                        ->orWhere('venue', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->orderBy('event_date', 'asc')
            ->paginate(15)
            ->withQueryString();

        // Flat array for the admin calendar JS
        $calendarData = Event::withCount('logs')
            ->with(['reviewer', 'approver'])
            ->orderBy('event_date', 'asc')
            ->get()
            ->map(function ($e) {
                return [
                    'id'                   => $e->id,
                    'title'                => $e->title,
                    'event_date'           => $e->event_date ? $e->event_date->format('Y-m-d') : null,
                    'end_date'             => $e->end_date ? $e->end_date->format('Y-m-d') : null,
                    'formatted_date_range' => $e->formatted_date_range,
                    'start_time'           => $e->start_time,
                    'end_time'             => $e->end_time,
                    'venue'                => $e->venue,
                    'venue_type'           => $e->venue_type,
                    'category'             => $e->category,
                    'badge_label'          => $e->badge_label,
                    'summary'              => $e->summary,
                    'image_url'            => $e->image_url ? asset($e->image_url) : null,
                    'status'               => $e->status,
                    'approval_status'      => $e->approval_status ?? 'approved',
                    'director_comments'    => $e->director_comments,
                    'reviewed_by_name'     => $e->reviewer?->full_name,
                    'approved_by_name'     => $e->approver?->full_name,
                    'is_featured'          => (bool) $e->is_featured,
                    'enable_view_link'     => (bool) $e->enable_view_link,
                    'logs_count'           => (int) $e->logs_count,
                    'public_url'           => route('events.show', $e->slug),
                    'edit_url'             => route('admin.events.edit', $e->id),
                    'delete_url'           => route('admin.events.destroy', $e->id),
                    'toggle_url'           => route('admin.events.toggle-featured', $e->id),
                    'approve_url'          => route('admin.events.approve', $e->id),
                    'review_url'           => route('admin.events.review', $e->id),
                ];
            })->values()->toArray();

        $categories = $this->categories;

        return view('admin.events.index', compact('eventsList', 'status', 'category', 'approvalStatus', 'search', 'calendarData', 'categories'));
    }

    public function create(): View
    {
        $newsList = News::orderBy('published_date', 'desc')->get(['id', 'title']);

        return view('admin.events.form', [
            'event' => new Event([
                'category'         => 'Activity',
                'badge_label'      => 'UPCOMING',
                'event_date'       => now()->format('Y-m-d'),
                'end_date'         => null,
                'start_time'       => '09:00 AM',
                'end_time'         => '05:00 PM',
                'venue'            => 'UP Cebu Campus',
                'venue_type'       => 'in-person',
                'summary'          => '',
                'registration_url' => '#contact',
                'status'           => 'upcoming',
                'is_featured'      => false,
                'news_id'          => null,
                'enable_view_link' => false,
            ]),
            'isEdit'     => false,
            'newsList'   => $newsList,
            'categories' => $this->categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'category'         => 'required|string|max:50',
            'badge_label'      => 'nullable|string|max:50',
            'event_date'       => 'required|date',
            'end_date'         => 'nullable|date|after_or_equal:event_date',
            'start_time'       => 'nullable|string|max:20',
            'end_time'         => 'nullable|string|max:20',
            'venue'            => 'nullable|string|max:150',
            'venue_type'       => 'nullable|string|in:in-person,virtual,hybrid',
            'summary'          => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url'        => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'registration_url' => 'nullable|string|max:255',
            'status'           => 'required|string|in:upcoming,completed,cancelled',
            'news_id'          => 'nullable|integer|exists:news,id',
        ], [
            'event_date.required'     => 'The start date is required.',
            'end_date.after_or_equal' => 'The end date cannot be earlier than the start date.',
            'image.image'             => 'The uploaded file must be a valid image.',
            'image.max'               => 'The image size may not exceed 5MB.',
        ]);

        $validated['is_featured']      = $request->boolean('is_featured');
        $validated['enable_view_link'] = $request->boolean('enable_view_link');
        $validated['news_id']          = $validated['news_id'] ?? null;
        $validated['start_time']       = !empty($validated['start_time']) ? $validated['start_time'] : '09:00 AM';
        $validated['end_time']         = !empty($validated['end_time']) ? $validated['end_time'] : '05:00 PM';
        $validated['venue']            = !empty($validated['venue']) ? $validated['venue'] : 'UP Cebu Campus';
        $validated['venue_type']       = !empty($validated['venue_type']) ? $validated['venue_type'] : 'in-person';
        $validated['summary']          = $validated['summary'] ?? '';
        $validated['slug']             = Event::generateUniqueSlug($validated['title']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events/covers', 'public');
            $validated['image_url'] = 'storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $validated['image_url'] = $request->input('image_url');
        }

        $currentUser = Auth::guard('admin')->user();
        // Immediately visible on public calendar - no approval waiting stage
        $validated['approval_status'] = 'approved';
        $validated['approved_by']     = $currentUser?->id;
        $validated['approved_at']     = now();

        if ($validated['is_featured']) {
            Event::where('is_featured', true)->update(['is_featured' => false]);
        }

        $event = Event::create($validated);

        if ($request->has('save_and_add_details')) {
            return redirect()->route('admin.events.edit', $event->id)
                ->with('success_message', 'Event saved! You can now add daily activity details below.');
        }

        return redirect()->route('admin.events.index')->with('success_message', 'Activity/Event scheduled successfully.');
    }

    public function edit(Event $event): View|RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();
        if ($currentUser && !$currentUser->canEdit()) {
            return redirect()->route('admin.events.index')
                ->with('info_message', 'As Director, please use the Feedback modal to submit review notes and recommendations.');
        }

        $event->load([
            'logs' => fn($q) => $q->orderBy('log_date', 'asc')->orderBy('sort_order', 'asc')->orderBy('id', 'asc'),
            'reviewer',
            'approver',
        ]);
        $newsList = News::orderBy('published_date', 'desc')->get(['id', 'title']);

        return view('admin.events.form', [
            'event'      => $event,
            'isEdit'     => true,
            'newsList'   => $newsList,
            'categories' => $this->categories,
        ]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();
        if ($currentUser && !$currentUser->canEdit()) {
            return redirect()->route('admin.events.index')
                ->with('error_message', 'The Director provides feedback via the Review modal rather than editing content directly.');
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'category'         => 'required|string|max:50',
            'badge_label'      => 'nullable|string|max:50',
            'event_date'       => 'required|date',
            'end_date'         => 'nullable|date|after_or_equal:event_date',
            'start_time'       => 'nullable|string|max:20',
            'end_time'         => 'nullable|string|max:20',
            'venue'            => 'nullable|string|max:150',
            'venue_type'       => 'nullable|string|in:in-person,virtual,hybrid',
            'summary'          => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url'        => 'nullable|string|max:255',
            'remove_image'     => 'nullable|boolean',
            'description'      => 'nullable|string',
            'registration_url' => 'nullable|string|max:255',
            'status'           => 'required|string|in:upcoming,completed,cancelled',
            'news_id'          => 'nullable|integer|exists:news,id',
            'director_comments'=> 'nullable|string|max:5000',
        ], [
            'event_date.required'     => 'The start date is required.',
            'end_date.after_or_equal' => 'The end date cannot be earlier than the start date.',
            'image.image'             => 'The uploaded file must be a valid image.',
            'image.max'               => 'The image size may not exceed 5MB.',
        ]);

        $validated['is_featured']      = $request->boolean('is_featured');
        $validated['enable_view_link'] = $request->boolean('enable_view_link');
        $validated['news_id']          = $validated['news_id'] ?? null;
        $validated['start_time']       = !empty($validated['start_time']) ? $validated['start_time'] : '09:00 AM';
        $validated['end_time']         = !empty($validated['end_time']) ? $validated['end_time'] : '05:00 PM';
        $validated['venue']            = !empty($validated['venue']) ? $validated['venue'] : 'UP Cebu Campus';
        $validated['venue_type']       = !empty($validated['venue_type']) ? $validated['venue_type'] : 'in-person';
        $validated['summary']          = $validated['summary'] ?? '';

        if ($request->boolean('remove_image')) {
            $validated['image_url'] = null;
        } elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('events/covers', 'public');
            $validated['image_url'] = 'storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $validated['image_url'] = $request->input('image_url');
        }

        // Keep event published and live
        $validated['approval_status'] = 'approved';

        // Admin or Staff can clear or update director comments once addressed
        if ($request->has('director_comments')) {
            $validated['director_comments'] = $request->input('director_comments');
        }

        if ($event->title !== $validated['title']) {
            $validated['slug'] = Event::generateUniqueSlug($validated['title'], $event->id);
        }

        if ($validated['is_featured']) {
            Event::where('id', '!=', $event->id)->where('is_featured', true)->update(['is_featured' => false]);
        }

        $event->update($validated);

        if ($request->has('stay_on_page')) {
            return redirect()->route('admin.events.edit', $event->id)->with('success_message', 'Event updated successfully.');
        }

        return redirect()->route('admin.events.index')->with('success_message', 'Event updated successfully.');
    }

    /**
     * Director observes the published activity and submits feedback/comments on what needs improvement.
     * The event remains published and live on the public calendar.
     */
    public function review(Request $request, Event $event): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();
        if ($currentUser && !$currentUser->canReview()) {
            return redirect()->back()->with('error_message', 'Only the Director or Admin can provide quality feedback.');
        }

        $request->validate([
            'director_comments' => 'nullable|string|max:5000',
        ]);

        $event->update([
            'director_comments' => $request->input('director_comments'),
            'reviewed_by'       => $currentUser->id,
            'reviewed_at'       => now(),
        ]);

        return redirect()->back()->with('success_message', 'Director quality feedback and suggestions recorded successfully.');
    }

    /**
     * Admin approves or ensures the activity is published.
     */
    public function approve(Request $request, Event $event): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();
        if ($currentUser && !$currentUser->canApprove()) {
            return redirect()->back()->with('error_message', 'Only Administrators have permission to approve activities.');
        }

        $event->update([
            'approval_status' => 'approved',
            'approved_by'     => $currentUser->id,
            'approved_at'     => now(),
        ]);

        return redirect()->back()->with('success_message', 'Activity officially marked as approved.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();
        if ($currentUser && !$currentUser->canDelete()) {
            return redirect()->back()->with('error_message', 'Only Administrators have permission to delete activities.');
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success_message', 'Activity/Event removed successfully.');
    }

    public function toggleFeatured(Event $event): RedirectResponse
    {
        $newState = !$event->is_featured;

        if ($newState) {
            Event::where('is_featured', true)->update(['is_featured' => false]);
        }

        $event->update(['is_featured' => $newState]);

        return redirect()->back()->with('success_message', 'Featured status updated.');
    }

    /**
     * Store a new daily detail entry under this event.
     */
    public function storeDetail(Request $request, Event $event): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();
        if ($currentUser && !$currentUser->canEdit()) {
            return redirect()->back()->with('error_message', 'The Director provides feedback rather than modifying activity records.');
        }

        $validated = $request->validate([
            'log_date'    => 'required|date',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'log_date.required'    => 'Please select the date for this daily detail.',
            'title.required'       => 'Please enter a title or heading for this day.',
            'description.required' => 'Please enter the description of activities conducted on this day.',
            'image.image'          => 'The uploaded file must be a valid image.',
            'image.max'            => 'The image size may not exceed 5MB.',
        ]);

        // Validate that detail date is within parent event start and end dates
        $startDate = $event->event_date->format('Y-m-d');
        $endDate   = $event->end_date ? $event->end_date->format('Y-m-d') : $startDate;

        if ($validated['log_date'] < $startDate || $validated['log_date'] > $endDate) {
            return redirect()->back()
                ->withErrors(['log_date' => "The daily detail date must be between the event's start date ({$startDate}) and end date ({$endDate})."])
                ->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events/daily', 'public');
            $imagePath = 'storage/' . $path;
        }

        $count = $event->logs()->count();

        $event->logs()->create([
            'log_date'    => $validated['log_date'],
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'image_path'  => $imagePath,
            'sort_order'  => $count + 1,
        ]);

        // If View Details wasn't turned on yet, enable it so visitors can view this documentation
        if (!$event->enable_view_link) {
            $event->update(['enable_view_link' => true]);
        }

        return redirect()->back()->with('success_message', 'Daily detail added successfully.');
    }

    /**
     * Update an existing daily detail entry.
     */
    public function updateDetail(Request $request, Event $event, EventLog $detail): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();
        if ($currentUser && !$currentUser->canEdit()) {
            return redirect()->back()->with('error_message', 'The Director provides feedback rather than modifying activity records.');
        }

        if ($detail->event_id !== $event->id) {
            abort(404);
        }

        $validated = $request->validate([
            'log_date'     => 'required|date',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'remove_image' => 'nullable|boolean',
        ], [
            'log_date.required'    => 'Please select the date for this daily detail.',
            'title.required'       => 'Please enter a title or heading for this day.',
            'description.required' => 'Please enter the description of activities conducted on this day.',
            'image.image'          => 'The uploaded file must be a valid image.',
            'image.max'            => 'The image size may not exceed 5MB.',
        ]);

        $startDate = $event->event_date->format('Y-m-d');
        $endDate   = $event->end_date ? $event->end_date->format('Y-m-d') : $startDate;

        if ($validated['log_date'] < $startDate || $validated['log_date'] > $endDate) {
            return redirect()->back()
                ->withErrors(['log_date' => "The daily detail date must be between the event's start date ({$startDate}) and end date ({$endDate})."])
                ->withInput();
        }

        $updateData = [
            'log_date'    => $validated['log_date'],
            'title'       => $validated['title'],
            'description' => $validated['description'],
        ];

        if ($request->boolean('remove_image')) {
            $updateData['image_path'] = null;
        } elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('events/daily', 'public');
            $updateData['image_path'] = 'storage/' . $path;
        }

        $detail->update($updateData);

        return redirect()->back()->with('success_message', 'Daily detail updated successfully.');
    }

    /**
     * Delete a daily detail entry.
     */
    public function destroyDetail(Event $event, EventLog $detail): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();
        if ($currentUser && !$currentUser->canEdit()) {
            return redirect()->back()->with('error_message', 'The Director provides feedback rather than modifying activity records.');
        }

        if ($detail->event_id !== $event->id) {
            abort(404);
        }

        $detail->delete();

        return redirect()->back()->with('success_message', 'Daily detail removed successfully.');
    }
}
