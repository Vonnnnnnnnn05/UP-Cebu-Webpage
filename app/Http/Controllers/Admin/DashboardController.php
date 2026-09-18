<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Inquiry;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $currentAdmin = Auth::guard('admin')->user();

        $totalNews = News::count();
        $totalEvents = Event::where('status', '!=', 'cancelled')->count();
        $totalInquiries = Inquiry::count();
        $pendingInquiries = Inquiry::pending()->count();

        $recentInquiries = Inquiry::latest()->take(5)->get();
        $recentNewsList = News::latest('published_date')->take(3)->get(['id', 'title', 'category', 'is_featured', 'published_date']);
        $upcomingEventsList = Event::where('status', '!=', 'cancelled')->orderBy('event_date', 'asc')->take(3)->get(['id', 'title', 'event_date', 'venue', 'status']);

        return view('admin.dashboard', compact(
            'currentAdmin',
            'totalNews',
            'totalEvents',
            'totalInquiries',
            'pendingInquiries',
            'recentInquiries',
            'recentNewsList',
            'upcomingEventsList'
        ));
    }
}
