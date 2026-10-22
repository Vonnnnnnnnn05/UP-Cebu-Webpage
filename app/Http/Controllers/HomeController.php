<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Featured News
        $featuredNews = News::published()
            ->featured()
            ->latest('published_date')
            ->first();

        // Recent News (excluding featured)
        $recentNews = News::published()
            ->where('is_featured', false)
            ->latest('published_date')
            ->take(3)
            ->get();

        // Fallback if no featured news marked
        if (!$featuredNews && $recentNews->isNotEmpty()) {
            $featuredNews = $recentNews->shift();
        }

        // All news for client-side modal reader
        $allNewsModalData = News::published()
            ->latest('published_date')
            ->get(['id', 'title', 'slug', 'category', 'badge_label', 'published_date', 'author', 'summary', 'content', 'startups_supported', 'meeting_focus', 'coverage_area', 'image_url'])
            ->map(function ($item) {
                $data = $item->toArray();
                $data['image_asset'] = $item->image_url ? asset($item->image_url) : null;
                return $data;
            });

        // All non-cancelled, approved events with eager-loaded relations
        $calendarEventsRaw = Event::where('status', '!=', 'cancelled')
            ->where(function ($q) {
                $q->where('approval_status', 'approved')
                  ->orWhereNull('approval_status');
            })
            ->with([
                'relatedNews' => fn ($q) => $q->select('id', 'slug', 'title', 'is_published'),
                'logs',
            ])
            ->orderBy('event_date', 'asc')
            ->get();

        // Pre-compute a plain array for the JS calendar (avoids Blade @json parser issues)
        $calendarEventsData = $calendarEventsRaw->map(function ($e) {
            $hasLogs  = $e->logs->isNotEmpty();
            $showView = (bool) $e->enable_view_link;
            $eventUrl = route('events.show', $e->slug);

            return [
                'id'                   => $e->id,
                'title'                => $e->title,
                'slug'                 => $e->slug,
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
                'show_view'            => $showView,
                'event_url'            => $eventUrl,
                'has_logs'             => $hasLogs,
            ];
        })->values()->toArray();

        // Site Settings
        $siteSettings = SiteSetting::getAllAsKeyValue();

        return view('home', compact(
            'featuredNews',
            'recentNews',
            'allNewsModalData',
            'calendarEventsData',
            'siteSettings'
        ));
    }

    /**
     * Public event detail page — shows the event info + daily activity log.
     */
    public function eventDetail(string $slug): View
    {
        $event = Event::where('slug', $slug)
            ->with([
                'logs',
                'relatedNews' => fn ($q) => $q->select('id', 'slug', 'title', 'is_published'),
            ])
            ->firstOrFail();

        // If not approved, only authenticated staff/admin/director can preview
        if ($event->approval_status && $event->approval_status !== 'approved' && !auth()->guard('admin')->check()) {
            abort(404);
        }

        $siteSettings = SiteSetting::getAllAsKeyValue();

        return view('events.show', compact('event', 'siteSettings'));
    }

    public function search(Request $request): JsonResponse
    {
        $q = trim($request->query('q', ''));

        $news = News::published()
            ->when($q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('summary', 'like', "%{$q}%")
                        ->orWhere('category', 'like', "%{$q}%");
                });
            })
            ->latest('published_date')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id'          => 'news-' . $item->id,
                    'title'       => $item->title,
                    'category'    => 'News',
                    'description' => $item->summary,
                    'url'         => '#news',
                    'keywords'    => [$item->category, 'news', 'article', $item->badge_label],
                    'raw_id'      => $item->id,
                ];
            });

        $events = Event::where('status', '!=', 'cancelled')
            ->where(function ($q) {
                $q->where('approval_status', 'approved')
                  ->orWhereNull('approval_status');
            })
            ->when($q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('summary', 'like', "%{$q}%")
                        ->orWhere('category', 'like', "%{$q}%");
                });
            })
            ->orderBy('event_date', 'asc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id'          => 'event-' . $item->id,
                    'title'       => $item->title,
                    'category'    => 'Events',
                    'description' => $item->summary,
                    'url'         => '#events',
                    'keywords'    => [$item->category, 'event', 'workshop', $item->venue_type],
                    'raw_id'      => $item->id,
                ];
            });

        return response()->json([
            'results' => $news->concat($events)->values(),
        ]);
    }

    public function article(string $slug): JsonResponse
    {
        $news = News::where('slug', $slug)->firstOrFail();
        return response()->json($news);
    }
}
