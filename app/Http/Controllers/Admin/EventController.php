<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $status = trim($request->query('status', ''));
        $search = trim($request->query('search', ''));

        $eventsList = Event::query()
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('summary', 'like', "%{$search}%")
                        ->orWhere('venue', 'like', "%{$search}%");
                });
            })
            ->orderBy('event_date', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.events.index', compact('eventsList', 'status', 'search'));
    }

    public function create(): View
    {
        return view('admin.events.form', [
            'event' => new Event([
                'category' => 'WORKSHOP',
                'badge_label' => 'UPCOMING',
                'event_date' => now()->addDays(7)->format('Y-m-d'),
                'start_time' => '09:00 AM',
                'end_time' => '05:00 PM',
                'venue' => 'UP Cebu Lahug Campus',
                'venue_type' => 'in-person',
                'registration_url' => '#contact',
                'status' => 'upcoming',
                'is_featured' => false,
            ]),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'badge_label' => 'nullable|string|max:50',
            'event_date' => 'required|date',
            'start_time' => 'required|string|max:20',
            'end_time' => 'required|string|max:20',
            'venue' => 'required|string|max:150',
            'venue_type' => 'required|string|in:in-person,virtual,hybrid',
            'summary' => 'required|string',
            'description' => 'nullable|string',
            'registration_url' => 'nullable|string|max:255',
            'status' => 'required|string|in:upcoming,completed,cancelled',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['slug'] = Event::generateUniqueSlug($validated['title']);

        if ($validated['is_featured']) {
            Event::where('is_featured', true)->update(['is_featured' => false]);
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success_message', 'Event scheduled successfully.');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.form', [
            'event' => $event,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'badge_label' => 'nullable|string|max:50',
            'event_date' => 'required|date',
            'start_time' => 'required|string|max:20',
            'end_time' => 'required|string|max:20',
            'venue' => 'required|string|max:150',
            'venue_type' => 'required|string|in:in-person,virtual,hybrid',
            'summary' => 'required|string',
            'description' => 'nullable|string',
            'registration_url' => 'nullable|string|max:255',
            'status' => 'required|string|in:upcoming,completed,cancelled',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($event->title !== $validated['title']) {
            $validated['slug'] = Event::generateUniqueSlug($validated['title'], $event->id);
        }

        if ($validated['is_featured']) {
            Event::where('id', '!=', $event->id)->where('is_featured', true)->update(['is_featured' => false]);
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success_message', 'Event updated successfully.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success_message', 'Event removed successfully.');
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
}
