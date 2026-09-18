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
            ->get(['id', 'title', 'slug', 'category', 'badge_label', 'published_date', 'author', 'summary', 'content', 'startups_supported', 'meeting_focus', 'coverage_area']);

        // Featured Event
        $featuredEvent = Event::upcoming()
            ->featured()
            ->orderBy('event_date', 'asc')
            ->first();

        // Upcoming Events (excluding featured)
        $upcomingEvents = Event::upcoming()
            ->where('is_featured', false)
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        // Fallback if no featured event
        if (!$featuredEvent && $upcomingEvents->isNotEmpty()) {
            $featuredEvent = $upcomingEvents->shift();
        }

        // Site Settings
        $siteSettings = SiteSetting::getAllAsKeyValue();

        return view('home', compact(
            'featuredNews',
            'recentNews',
            'allNewsModalData',
            'featuredEvent',
            'upcomingEvents',
            'siteSettings'
        ));
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
                    'id' => 'news-' . $item->id,
                    'title' => $item->title,
                    'category' => 'News',
                    'description' => $item->summary,
                    'url' => '#news',
                    'keywords' => [$item->category, 'news', 'article', $item->badge_label],
                    'raw_id' => $item->id,
                ];
            });

        $events = Event::where('status', '!=', 'cancelled')
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
                    'id' => 'event-' . $item->id,
                    'title' => $item->title,
                    'category' => 'Events',
                    'description' => $item->summary,
                    'url' => '#events',
                    'keywords' => [$item->category, 'event', 'workshop', $item->venue_type],
                    'raw_id' => $item->id,
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
