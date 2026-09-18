@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="mb-8 bg-white border border-border-card rounded-2xl p-6 sm:p-7 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
    <div>
        <div class="flex items-center gap-2 text-xs font-bold tracking-[1.5px] uppercase text-green-base mb-1.5">
            <span class="inline-block w-2.5 h-2.5 bg-green-base rounded-full"></span>
            SYSTEM OVERVIEW
        </div>
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-tight">
            Welcome back, {{ $currentAdmin->full_name }}
        </h1>
        <p class="text-sm text-ink-muted mt-1.5 leading-relaxed max-w-2xl">
            Logged in as <strong class="text-ink-base">{{ $currentAdmin->email }}</strong> (Role: {{ ucfirst($currentAdmin->role) }}). Manage your public announcements, innovation calendar, and incoming consultation requests below.
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
        <a href="{{ route('admin.news.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-sm font-bold transition-all shadow-xs">
            <svg class="w-4 h-4 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Post News</span>
        </a>
        <a href="{{ route('admin.events.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-green-base hover:bg-green-hover text-white text-sm font-bold transition-all shadow-xs">
            <svg class="w-4 h-4 text-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Schedule Event</span>
        </a>
    </div>
</div>

<!-- Metrics Overview Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Card 1: News -->
    <div class="bg-white border border-border-card rounded-xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider block">News &amp; Articles</span>
            <span class="text-3xl sm:text-4xl font-bold font-serif text-maroon-base mt-1 block">{{ $totalNews }}</span>
            <a href="{{ route('admin.news.index') }}" class="text-xs text-maroon-base hover:text-green-base font-semibold transition-colors mt-2 inline-block">Manage articles &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-maroon-base/10 text-maroon-base flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
            </svg>
        </div>
    </div>

    <!-- Card 2: Events -->
    <div class="bg-white border border-border-card rounded-xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider block">Events &amp; Summits</span>
            <span class="text-3xl sm:text-4xl font-bold font-serif text-maroon-base mt-1 block">{{ $totalEvents }}</span>
            <a href="{{ route('admin.events.index') }}" class="text-xs text-maroon-base hover:text-green-base font-semibold transition-colors mt-2 inline-block">View calendar &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-green-base/10 text-green-base flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
        </div>
    </div>

    <!-- Card 3: Inquiries -->
    <div class="bg-white border border-border-card rounded-xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider block">Total Inquiries</span>
            <span class="text-3xl sm:text-4xl font-bold font-serif text-maroon-base mt-1 block">{{ $totalInquiries }}</span>
            <a href="{{ route('admin.inquiries.index') }}" class="text-xs text-maroon-base hover:text-green-base font-semibold transition-colors mt-2 inline-block">Review inbox &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-gold-base/15 text-gold-deep flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
        </div>
    </div>

    <!-- Card 4: Pending Inquiries -->
    <div class="bg-white border border-border-card rounded-xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider block">Pending Review</span>
            <span class="text-3xl sm:text-4xl font-bold font-serif text-maroon-base mt-1 block">{{ $pendingInquiries }}</span>
            <a href="{{ route('admin.inquiries.index', ['status' => 'pending']) }}" class="text-xs text-maroon-base hover:text-green-base font-semibold transition-colors mt-2 inline-block">Filter pending &rarr;</a>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-100 text-maroon-base flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
        </div>
    </div>
</div>

<!-- Two-Column Content Grid: Inquiries Table & Quick Lists -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    <!-- Recent Inquiries Inbox (Span 7) -->
    <div class="lg:col-span-7 bg-white border border-border-card rounded-2xl p-5 sm:p-6 shadow-xs">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-border-card">
            <div>
                <h3 class="font-serif text-lg font-bold text-maroon-base">Recent Consultations &amp; Inquiries</h3>
                <p class="text-xs text-ink-muted mt-0.5">Latest contact requests received from visitors.</p>
            </div>
            <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-bold text-maroon-base hover:text-green-base transition-colors">View All &rarr;</a>
        </div>

        <div class="space-y-3">
            @forelse ($recentInquiries as $inq)
            <div class="p-4 rounded-xl border border-border-card bg-cream-soft hover:bg-white hover:border-gold-base transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-bold text-sm text-ink-base">{{ $inq->full_name }}</span>
                        <span class="text-[10px] uppercase font-semibold px-2 py-0.5 rounded-full {{ $inq->status === 'pending' ? 'bg-amber-100 text-amber-800' : ($inq->status === 'in_review' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ str_replace('_', ' ', $inq->status) }}
                        </span>
                    </div>
                    <p class="text-xs text-ink-base font-medium line-clamp-1">{{ $inq->subject }}</p>
                    <span class="text-[11px] text-ink-muted">{{ $inq->created_at ? $inq->created_at->diffForHumans() : '' }} &bull; {{ $inq->email }}</span>
                </div>
                <a href="{{ route('admin.inquiries.index', ['id' => $inq->id]) }}"
                    class="shrink-0 px-3 py-1.5 rounded-lg bg-white border border-border-card hover:bg-cream-bg text-xs font-bold text-ink-base transition-colors text-center">
                    Review
                </a>
            </div>
            @empty
            <div class="p-8 text-center text-xs text-ink-muted">
                No inquiries received yet.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Recent News & Events (Span 5) -->
    <div class="lg:col-span-5 space-y-6">
        <!-- Recent News -->
        <div class="bg-white border border-border-card rounded-2xl p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-border-card">
                <h3 class="font-serif text-base font-bold text-maroon-base">Recent News &amp; Grants</h3>
                <a href="{{ route('admin.news.index') }}" class="text-xs font-bold text-maroon-base hover:text-green-base transition-colors">Manage &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentNewsList as $item)
                <div class="text-xs border-b border-border-card/60 pb-2.5 last:border-b-0 last:pb-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[9.5px] font-bold uppercase text-green-base">{{ $item->category }}</span>
                        @if ($item->is_featured)
                        <span class="bg-maroon-base text-white text-[9px] font-bold px-1.5 py-0.2 rounded">FEATURED</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.news.edit', $item->id) }}" class="font-semibold text-ink-base hover:text-maroon-base block line-clamp-1">
                        {{ $item->title }}
                    </a>
                </div>
                @empty
                <p class="text-xs text-ink-muted">No news recorded.</p>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white border border-border-card rounded-2xl p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-border-card">
                <h3 class="font-serif text-base font-bold text-maroon-base">Upcoming Events</h3>
                <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-maroon-base hover:text-green-base transition-colors">Manage &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse ($upcomingEventsList as $ev)
                <div class="text-xs border-b border-border-card/60 pb-2.5 last:border-b-0 last:pb-0">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="font-bold text-maroon-base">{{ $ev->event_date ? $ev->event_date->format('M d, Y') : '' }}</span>
                        <span class="text-[9.5px] text-ink-muted">{{ $ev->venue }}</span>
                    </div>
                    <a href="{{ route('admin.events.edit', $ev->id) }}" class="font-semibold text-ink-base hover:text-maroon-base block line-clamp-1">
                        {{ $ev->title }}
                    </a>
                </div>
                @empty
                <p class="text-xs text-ink-muted">No upcoming events.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
