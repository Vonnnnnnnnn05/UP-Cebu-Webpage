@extends('admin.layouts.admin')

@section('title', 'Manage Events & Summits')

@section('content')

{{-- ══════════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════════ --}}
<div class="mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-tight">
            Innovation Events &amp; Workshops
        </h1>
        <p class="text-xs sm:text-sm text-ink-muted mt-1">
            Schedule summits, IP clinics, pitch demos, and mentorship masterclasses.
        </p>
    </div>
    <a href="{{ route('admin.events.create') }}"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-green-base hover:bg-green-hover text-white text-xs sm:text-sm font-bold shadow-xs transition-all self-start sm:self-auto">
        <svg class="w-4 h-4 text-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>Schedule New Event</span>
    </a>
</div>

{{-- ══════════════════════════════════════════════════════════
     VIEW TOGGLE  (Calendar | List)
══════════════════════════════════════════════════════════ --}}
<div class="mb-4 flex items-center gap-2">
    <button id="view-calendar" type="button"
        class="view-tab-btn inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold border transition-all cursor-pointer
               bg-maroon-base text-white border-maroon-base">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
        </svg>
        Calendar
    </button>
    <button id="view-list" type="button"
        class="view-tab-btn inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold border transition-all cursor-pointer
               bg-white text-ink-muted border-border-card hover:bg-cream-soft">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
        </svg>
        List
    </button>
</div>

{{-- ══════════════════════════════════════════════════════════
     CALENDAR VIEW
══════════════════════════════════════════════════════════ --}}
<div id="admin-calendar-view">

    {{-- Calendar toolbar --}}
    <div class="flex items-center justify-between mb-3 gap-2">
        {{-- Prev --}}
        <button id="acal-prev" type="button" aria-label="Previous month"
            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white border border-border-card text-xs font-bold text-ink-base
                   hover:bg-maroon-base hover:text-white hover:border-maroon-base transition-all shadow-xs cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            Prev
        </button>

        <div class="flex items-center gap-2">
            <h2 id="acal-label" class="font-serif text-base sm:text-lg font-bold text-maroon-base min-w-[160px] text-center"></h2>
            <button id="acal-today" type="button"
                class="hidden sm:inline-flex items-center px-3 py-1 rounded-lg bg-cream-soft border border-border-card text-[11px] font-bold text-ink-muted
                       hover:bg-maroon-base hover:text-white hover:border-maroon-base transition-all cursor-pointer">
                Today
            </button>
        </div>

        {{-- Status filter --}}
        <div class="flex items-center gap-1.5">
            <button data-acal-filter="all" type="button"
                class="acal-filter inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold border cursor-pointer transition-all
                       bg-maroon-base text-white border-maroon-base">All</button>
            <button data-acal-filter="upcoming" type="button"
                class="acal-filter inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold border cursor-pointer transition-all
                       bg-white text-ink-muted border-border-card hover:bg-green-base hover:text-white hover:border-green-base">Upcoming</button>
            <button data-acal-filter="completed" type="button"
                class="acal-filter inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold border cursor-pointer transition-all
                       bg-white text-ink-muted border-border-card hover:bg-gray-500 hover:text-white hover:border-gray-500">Completed</button>
            <button data-acal-filter="cancelled" type="button"
                class="acal-filter inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold border cursor-pointer transition-all
                       bg-white text-ink-muted border-border-card hover:bg-red-500 hover:text-white hover:border-red-500">Cancelled</button>

            {{-- Next --}}
            <button id="acal-next" type="button" aria-label="Next month"
                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white border border-border-card text-xs font-bold text-ink-base
                       hover:bg-maroon-base hover:text-white hover:border-maroon-base transition-all shadow-xs cursor-pointer ml-1">
                Next
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Calendar grid --}}
    <div class="bg-white border border-border-card rounded-2xl shadow-xs overflow-hidden">
        {{-- Day-of-week headers --}}
        <div class="grid grid-cols-7 border-b border-border-card bg-cream-soft">
            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dow)
            <div class="py-2 text-center text-[10px] font-bold uppercase tracking-wider text-ink-muted">{{ $dow }}</div>
            @endforeach
        </div>
        <div id="acal-grid" class="grid grid-cols-7"></div>
    </div>

    {{-- Legend --}}
    <div class="mt-3 flex flex-wrap items-center gap-3 text-[10px] font-semibold text-ink-muted">
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded bg-green-base/20 border border-green-base/40"></span>Upcoming</span>
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded bg-gray-100 border border-gray-300"></span>Completed</span>
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded bg-red-100 border border-red-300"></span>Cancelled</span>
        <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-maroon-base"></span>Today</span>
        <span class="ml-auto text-[10px] text-ink-muted italic">Click an event to edit or delete</span>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     LIST VIEW  (hidden by default)
══════════════════════════════════════════════════════════ --}}
<div id="admin-list-view" class="hidden">

    {{-- Filter Bar --}}
    <div class="bg-white border border-border-card rounded-2xl p-4 sm:p-5 shadow-xs mb-5">
        <form action="{{ route('admin.events.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activities by title, summary, or venue..."
                    class="w-full pl-9 pr-4 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base
                           focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                <svg class="w-4 h-4 text-ink-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </div>
            <select name="category" onchange="this.form.submit()"
                class="w-full sm:w-44 px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base
                       focus:bg-white focus:border-maroon-base focus:outline-none transition-colors font-medium">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="approval_status" onchange="this.form.submit()"
                class="w-full sm:w-44 px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base
                       focus:bg-white focus:border-maroon-base focus:outline-none transition-colors font-medium">
                <option value="">All Approvals</option>
                <option value="approved"         {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Approved (Live)</option>
                <option value="pending_approval" {{ request('approval_status') === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                <option value="reviewed"         {{ request('approval_status') === 'reviewed' ? 'selected' : '' }}>Director Reviewed</option>
                <option value="rejected"         {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>Needs Revision</option>
                <option value="draft"            {{ request('approval_status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <select name="status" onchange="this.form.submit()"
                class="w-full sm:w-36 px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base
                       focus:bg-white focus:border-maroon-base focus:outline-none transition-colors font-medium">
                <option value="">All Statuses</option>
                <option value="upcoming"  {{ request('status') === 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed / Past</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit"
                class="px-4 py-2 bg-green-base hover:bg-green-hover text-white text-xs font-bold rounded-lg transition-colors w-full sm:w-auto">
                Filter
            </button>
            @if(request('search') || request('category') || request('status') || request('approval_status'))
            <a href="{{ route('admin.events.index') }}" class="text-xs text-maroon-base hover:underline self-center">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-border-card rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-cream-soft border-b border-border-card text-ink-muted uppercase text-[10px] tracking-wider font-bold">
                    <tr>
                        <th class="py-3.5 px-4 sm:px-6">Activity Title &amp; Category</th>
                        <th class="py-3.5 px-4">Date &amp; Schedule</th>
                        <th class="py-3.5 px-4 text-center">Daily Details</th>
                        <th class="py-3.5 px-4 text-center">Public Details</th>
                        <th class="py-3.5 px-4">Workflow Status</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-card/60">
                    @forelse ($eventsList as $event)
                    <tr class="hover:bg-cream-soft/50 transition-colors">
                        <td class="py-4 px-4 sm:px-6">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="bg-maroon-base/10 text-maroon-base text-[9.5px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-full">
                                    {{ $event->category }}
                                </span>
                                @if($event->end_date && $event->end_date != $event->event_date)
                                <span class="bg-amber-100 text-amber-800 text-[9px] font-bold tracking-wider uppercase px-1.5 py-0.5 rounded">
                                    Multi-day
                                </span>
                                @endif
                            </div>
                            <span class="font-bold text-ink-base block text-sm leading-snug">{{ $event->title }}</span>
                            @if($event->summary)
                            <p class="text-[11px] text-ink-muted line-clamp-1 mt-0.5">{{ $event->summary }}</p>
                            @endif
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="font-semibold text-ink-base block">{{ $event->formatted_date_range }}</span>
                            <span class="text-[10px] text-ink-muted">{{ $event->venue }}</span>
                        </td>
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @if($event->logs_count > 0)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">
                                {{ $event->logs_count }} {{ Str::plural('Entry', $event->logs_count) }}
                            </span>
                            @else
                            <span class="text-[10px] text-ink-muted">None (Simple)</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @if($event->enable_view_link)
                            <a href="{{ route('events.show', $event->slug) }}" target="_blank"
                                class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                                <span>Enabled &rarr;</span>
                            </a>
                            @else
                            <span class="text-[10px] font-semibold text-ink-muted px-2 py-0.5 rounded-full bg-gray-100">
                                Disabled
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="text-[10px] uppercase font-extrabold px-2.5 py-1 rounded-full border bg-green-100 text-green-800 border-green-300">
                                Published (Live)
                            </span>
                            @if ($event->director_comments)
                            <button type="button" onclick="openEventReviewById({{ $event->id }})"
                                class="mt-1 text-[10px] text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-300 rounded px-1.5 py-0.5 max-w-[170px] truncate block text-left cursor-pointer transition-colors" title="Director Feedback: {{ $event->director_comments }}">
                                <span class="font-bold text-maroon-base">Feedback:</span> {{ $event->director_comments }}
                            </button>
                            @endif
                        </td>
                        <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                @if (Auth::guard('admin')->user()?->canReview())
                                <button type="button" onclick="openEventReviewById({{ $event->id }})"
                                    class="px-2.5 py-1.5 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-xs font-bold transition-all shadow-xs cursor-pointer border border-maroon-dark/20" title="Director Review & Feedback">
                                    Feedback
                                </button>
                                @elseif ($event->director_comments)
                                <button type="button" onclick="openEventReviewById({{ $event->id }})"
                                    class="px-2.5 py-1.5 rounded-lg bg-gold-light/80 hover:bg-gold-light text-maroon-base text-xs font-bold transition-all border border-gold-base/50 cursor-pointer" title="View Director Feedback">
                                    View Feedback
                                </button>
                                @endif

                                @if (Auth::guard('admin')->user()?->canEdit())
                                <a href="{{ route('admin.events.edit', $event->id) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-cream-soft hover:bg-maroon-base hover:text-white text-ink-base transition-colors text-xs font-semibold" title="Edit Event & Details">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>
                                @endif

                                @if (Auth::guard('admin')->user()?->canDelete())
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this activity?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer" title="Delete Activity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 px-6 text-center text-ink-muted">No activities found matching your filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($eventsList->hasPages())
        <div class="p-4 border-t border-border-card bg-cream-soft">
            {{ $eventsList->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     EVENT QUICK-VIEW POPUP  (calendar)
══════════════════════════════════════════════════════════ --}}
<div id="acal-popup"
    class="hidden fixed inset-0 z-[300] flex items-center justify-center p-4"
    role="dialog" aria-modal="true" aria-label="Event Actions">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-xs" id="acal-popup-backdrop"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10">
        <button id="acal-popup-close" type="button" aria-label="Close"
            class="absolute top-3 right-3 p-1.5 rounded-lg hover:bg-cream-soft transition-colors cursor-pointer">
            <svg class="w-4 h-4 text-ink-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div id="acal-popup-body" class="space-y-3"></div>
        <div id="acal-popup-actions" class="mt-4 flex flex-wrap items-center gap-2 pt-3 border-t border-border-card"></div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     EVENT DIRECTOR REVIEW & FEEDBACK MODAL
══════════════════════════════════════════════════════════ --}}
<div id="event-review-modal" class="fixed inset-0 z-[350] bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-border-card w-full max-w-lg overflow-hidden">
        <div class="p-5 border-b border-border-card bg-cream-soft flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-gold-base"></span>
                <h3 class="font-serif text-lg font-bold text-maroon-base">
                    Director Review &amp; Feedback
                </h3>
            </div>
            <button type="button" onclick="closeEventReviewModal()" class="p-1.5 rounded-lg text-ink-muted hover:bg-black/5 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-4 text-xs">
            <div>
                <span id="ev-modal-category" class="bg-maroon-base/10 text-maroon-base text-[9.5px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full"></span>
                <h4 id="ev-modal-title" class="text-sm font-bold text-ink-base mt-1.5 leading-snug"></h4>
                <p id="ev-modal-schedule" class="text-[11px] text-ink-muted mt-0.5"></p>
            </div>

            <div id="ev-modal-comments-box" class="p-3.5 bg-amber-50 border border-amber-300 rounded-xl hidden">
                <div class="flex items-center justify-between gap-2 mb-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-900 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gold-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        Director Quality Review Feedback:
                    </span>
                    <span id="ev-modal-comments-meta" class="text-[10px] text-ink-muted font-medium"></span>
                </div>
                <p id="ev-modal-comments-text" class="text-ink-base text-xs leading-relaxed whitespace-pre-wrap pl-5"></p>
            </div>

            <!-- Reviewer Form (Director/Admin only) -->
            <form id="ev-review-form" method="POST" action="" class="space-y-3 pt-2 border-t border-border-card {{ Auth::guard('admin')->user()?->canReview() ? '' : 'hidden' }}">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-ink-base mb-1">
                        Director's Assessment &amp; Instructions:
                    </label>
                    <textarea id="ev-review-comments-input" name="director_comments" rows="3"
                        placeholder="Write constructive notes on what's wrong, what needs editing, or instructions for staff/admin..."
                        class="w-full px-3.5 py-2 bg-cream-soft/40 border border-border-card rounded-lg text-xs text-ink-base focus:border-maroon-base focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="button" onclick="closeEventReviewModal()" class="px-3 py-1.5 rounded-lg border border-border-card text-ink-muted hover:bg-cream-soft font-semibold text-xs cursor-pointer">
                        Cancel
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-maroon-base hover:bg-maroon-hover text-white font-bold text-xs transition-all shadow-xs cursor-pointer border border-gold-base/30">
                            Save Quality Feedback
                        </button>
                    </div>
                </div>
            </form>

            @if (!Auth::guard('admin')->user()?->canReview())
            <div id="ev-staff-action-box" class="flex items-center justify-between pt-3 border-t border-border-card">
                <button type="button" onclick="closeEventReviewModal()" class="px-4 py-2 rounded-lg border border-border-card text-ink-muted hover:bg-cream-soft font-semibold text-xs cursor-pointer">
                    Close
                </button>
                <a id="ev-staff-edit-btn" href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-maroon-base hover:bg-maroon-dark text-white font-bold text-xs transition-all shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                    </svg>
                    <span>Edit Activity to Address Notes</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    'use strict';

    // ── Permissions ──────────────────────────────────────────────────────────
    const CAN_EDIT    = {{ Auth::guard('admin')->user()?->canEdit() ? 'true' : 'false' }};
    const CAN_REVIEW  = {{ Auth::guard('admin')->user()?->canReview() ? 'true' : 'false' }};
    const CAN_DELETE  = {{ Auth::guard('admin')->user()?->canDelete() ? 'true' : 'false' }};

    // ── Data ─────────────────────────────────────────────────────────────────
    const ALL_EVENTS = @json($calendarData);
    const CSRF       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const MONTHS     = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    // ── State ────────────────────────────────────────────────────────────────
    const now      = new Date();
    let curYear    = now.getFullYear();
    let curMonth   = now.getMonth();
    let calFilter  = 'all';

    // ── DOM refs ─────────────────────────────────────────────────────────────
    const grid     = document.getElementById('acal-grid');
    const lbl      = document.getElementById('acal-label');
    const prevBtn  = document.getElementById('acal-prev');
    const nextBtn  = document.getElementById('acal-next');
    const todayBtn = document.getElementById('acal-today');
    const popup    = document.getElementById('acal-popup');
    const popBody  = document.getElementById('acal-popup-body');
    const popActs  = document.getElementById('acal-popup-actions');
    const popClose = document.getElementById('acal-popup-close');
    const popBack  = document.getElementById('acal-popup-backdrop');

    // ── Helpers ───────────────────────────────────────────────────────────────
    const pad = n => String(n).padStart(2, '0');

    function eventsOnDate(dateStr) {
        return ALL_EVENTS.filter(e => {
            if (!e.event_date || e.event_date !== dateStr) return false;
            if (calFilter === 'upcoming')  return e.status === 'upcoming';
            if (calFilter === 'completed') return e.status === 'completed';
            if (calFilter === 'cancelled') return e.status === 'cancelled';
            return true;
        });
    }

    const STATUS_CLASSES = {
        upcoming:  { pill: 'bg-green-base/10 text-green-base border-green-base/30 hover:bg-green-base hover:text-white',  badge: 'bg-green-100 text-green-800' },
        completed: { pill: 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-500 hover:text-white',                badge: 'bg-gray-100 text-gray-600'  },
        cancelled: { pill: 'bg-red-50 text-red-500 border-red-200 hover:bg-red-500 hover:text-white',                     badge: 'bg-red-100 text-red-800'    },
    };

    // ── Render ────────────────────────────────────────────────────────────────
    function render() {
        lbl.textContent = MONTHS[curMonth] + ' ' + curYear;
        grid.innerHTML  = '';

        const firstDay    = new Date(curYear, curMonth, 1).getDay();
        const daysInMonth = new Date(curYear, curMonth + 1, 0).getDate();
        const todayStr    = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}`;

        // Leading blanks
        for (let i = 0; i < firstDay; i++) {
            const b = document.createElement('div');
            b.className = 'min-h-[70px] border-b border-r border-border-card/40 bg-cream-soft/30';
            grid.appendChild(b);
        }

        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr = `${curYear}-${pad(curMonth+1)}-${pad(d)}`;
            const evs     = eventsOnDate(dateStr);
            const isToday = dateStr === todayStr;
            const isLast  = (firstDay + d - 1) % 7 === 6;

            const cell = document.createElement('div');
            cell.className = [
                'min-h-[70px] p-1 border-b flex flex-col gap-0.5 transition-colors',
                isLast ? '' : 'border-r',
                'border-border-card/40',
                isToday ? 'bg-maroon-base/[0.06]' : 'bg-white hover:bg-cream-soft/50',
            ].join(' ');

            // Day number
            const dn = document.createElement('span');
            dn.textContent = d;
            dn.className = [
                'text-[11px] font-bold w-5 h-5 flex items-center justify-center rounded-full shrink-0 self-start',
                isToday ? 'bg-maroon-base text-white' : 'text-ink-muted',
            ].join(' ');
            cell.appendChild(dn);

            // Event pills
            evs.forEach(ev => {
                const sc  = STATUS_CLASSES[ev.status] || STATUS_CLASSES.completed;
                const btn = document.createElement('button');
                btn.type  = 'button';
                btn.className = `w-full text-left border rounded px-1 py-0.5 text-[9px] font-bold leading-tight truncate transition-colors cursor-pointer ${sc.pill}`;
                btn.textContent = ev.title;
                if (ev.is_featured) btn.textContent = '★ ' + ev.title;
                btn.addEventListener('click', () => openPopup(ev));
                cell.appendChild(btn);
            });

            grid.appendChild(cell);
        }

        // Trailing blanks to complete the last row
        const total = firstDay + daysInMonth;
        const rem   = total % 7;
        if (rem !== 0) {
            for (let i = 0; i < 7 - rem; i++) {
                const b = document.createElement('div');
                b.className = 'min-h-[70px] border-b border-r border-border-card/40 bg-cream-soft/30 last:border-r-0';
                grid.appendChild(b);
            }
        }
    }

    // ── Popup ─────────────────────────────────────────────────────────────────
    function openPopup(ev) {
        const sc      = STATUS_CLASSES[ev.status] || STATUS_CLASSES.completed;
        const dateObj = ev.event_date ? new Date(ev.event_date + 'T00:00:00') : null;
        const dateLbl = dateObj
            ? dateObj.toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric' })
            : '';
        const dateRangeLbl = ev.formatted_date_range || dateLbl;

        const appStatusBadge = {
            approved: '<span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-green-100 text-green-800 border border-green-300">Live (Approved)</span>',
            reviewed: '<span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-gold-light text-maroon-dark border border-gold-base/40 font-bold">Director Reviewed</span>',
            rejected: '<span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-red-100 text-red-800 border border-red-300">Needs Revision</span>',
            pending_approval: '<span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-300">Pending Approval</span>'
        }[ev.approval_status || 'approved'] || '<span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-300">Pending Approval</span>';

        popBody.innerHTML = `
            <div class="flex flex-wrap items-center gap-1.5 mb-1">
                <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-maroon-base/10 text-maroon-base">${ev.category||'Activity'}</span>
                <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full ${sc.badge}">${(ev.status||'').toUpperCase()}</span>
                ${appStatusBadge}
                ${ev.end_date && ev.end_date !== ev.event_date ? '<span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">Multi-day</span>' : ''}
            </div>
            <h3 class="font-serif text-base font-bold text-maroon-base leading-snug">${ev.title}</h3>
            <div class="space-y-1 text-xs text-ink-muted mt-2 bg-cream-soft p-3 rounded-xl border border-border-card/60">
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gold-deep shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                    </svg>
                    <span class="font-semibold text-ink-base">${dateRangeLbl}</span>
                </div>
                ${ev.venue ? `
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-maroon-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    <span>${ev.venue}</span>
                </div>` : ''}
                <div class="flex items-center gap-1.5 pt-1 border-t border-border-card/40 text-[11px] font-semibold text-maroon-base">
                    <span>Daily Activity Records: ${ev.logs_count} entries</span>
                </div>
            </div>
            ${ev.director_comments ? `
            <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-300 text-xs mt-2">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-900 block">Director Feedback:</span>
                    ${ev.reviewed_by_name ? `<span class="text-[9.5px] text-ink-muted font-medium">${ev.reviewed_by_name}${ev.reviewed_at ? ' • ' + ev.reviewed_at : ''}</span>` : ''}
                </div>
                <p class="text-ink-base text-[11px] mt-0.5 leading-relaxed">${ev.director_comments}</p>
            </div>` : ''}
            ${ev.summary ? `<p class="text-[11px] text-ink-muted mt-2 leading-relaxed line-clamp-2">${ev.summary}</p>` : ''}
        `;

        popActs.innerHTML = `
            ${CAN_EDIT ? `
            <a href="${ev.edit_url}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-maroon-base text-white text-xs font-bold hover:bg-maroon-dark transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                </svg>
                <span>Edit &amp; Daily Details</span>
            </a>` : ''}
            ${CAN_REVIEW ? `
            <button type="button" data-review-event
               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-maroon-base text-white text-xs font-bold hover:bg-maroon-hover transition-colors cursor-pointer border border-maroon-dark/20">
                <span>Feedback</span>
            </button>` : (ev.director_comments ? `
            <button type="button" data-review-event
               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-gold-light/80 text-maroon-base text-xs font-bold hover:bg-gold-light border border-gold-base/50 transition-colors cursor-pointer">
                <span>View Feedback</span>
            </button>` : '')}
            ${ev.enable_view_link ? `
            <a href="${ev.public_url}" target="_blank"
               class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg bg-gold-light text-gold-deep border border-gold-base/30 text-xs font-bold hover:bg-gold-base hover:text-maroon-dark transition-colors">
                <span>View Public</span>
            </a>` : ''}
            ${CAN_DELETE ? `
            <button type="button" data-delete-url="${ev.delete_url}"
               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-bold hover:bg-red-100 transition-colors cursor-pointer border border-red-200 ml-auto">
                Delete
            </button>` : ''}
        `;

        const revBtn = popActs.querySelector('[data-review-event]');
        if (revBtn) {
            revBtn.addEventListener('click', function () {
                closePopup();
                window.openEventReviewModal(ev);
            });
        }

        // Delete handler
        const delBtn = popActs.querySelector('[data-delete-url]');
        if (delBtn) {
            delBtn.addEventListener('click', function () {
                const url = this.dataset.deleteUrl;
                Swal.fire({
                    title: 'Delete this event?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#7B1113',
                    cancelButtonColor: '#5A554D',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    customClass: { popup: 'rounded-2xl shadow-xl font-sans' }
                }).then(result => {
                    if (!result.isConfirmed) return;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `<input type="hidden" name="_token" value="${CSRF}"><input type="hidden" name="_method" value="DELETE">`;
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        }

        popup.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePopup() {
        popup.classList.add('hidden');
        document.body.style.overflow = '';
    }

    popClose.addEventListener('click', closePopup);
    popBack.addEventListener('click', closePopup);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closePopup(); });

    // ── Global Review Modal functions ──────────────────────────────────────────
    window.openEventReviewById = function (id) {
        const ev = ALL_EVENTS.find(e => e.id === Number(id));
        if (ev) {
            window.openEventReviewModal(ev);
        }
    };

    window.openEventReviewModal = function (ev) {
        const modal = document.getElementById('event-review-modal');
        if (!modal) return;
        document.getElementById('ev-modal-category').textContent = ev.category || 'Activity';
        document.getElementById('ev-modal-title').textContent = ev.title || '';
        document.getElementById('ev-modal-schedule').textContent = (ev.formatted_date_range || ev.event_date || '') + (ev.venue ? ' • ' + ev.venue : '');

        const commentsBox = document.getElementById('ev-modal-comments-box');
        const commentsText = document.getElementById('ev-modal-comments-text');
        const commentsMeta = document.getElementById('ev-modal-comments-meta');
        if (ev.director_comments && ev.director_comments.trim() !== '') {
            commentsText.textContent = ev.director_comments;
            if (commentsMeta) {
                commentsMeta.textContent = (ev.reviewed_by_name ? `From ${ev.reviewed_by_name}` : 'Recorded') + (ev.reviewed_at ? ` on ${ev.reviewed_at}` : '');
            }
            commentsBox.classList.remove('hidden');
        } else {
            commentsBox.classList.add('hidden');
        }

        const form = document.getElementById('ev-review-form');
        const input = document.getElementById('ev-review-comments-input');
        if (input) {
            input.value = ev.director_comments || '';
        }
        if (form) {
            form.action = ev.review_url || `/admin/events/${ev.id}/review`;
        }

        const editBtn = document.getElementById('ev-staff-edit-btn');
        if (editBtn) {
            editBtn.href = ev.edit_url || `/admin/events/${ev.id}/edit`;
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closeEventReviewModal = function () {
        const modal = document.getElementById('event-review-modal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    // ── Navigation ────────────────────────────────────────────────────────────
    prevBtn.addEventListener('click', () => {
        curMonth--; if (curMonth < 0) { curMonth = 11; curYear--; } render();
    });
    nextBtn.addEventListener('click', () => {
        curMonth++; if (curMonth > 11) { curMonth = 0; curYear++; } render();
    });
    todayBtn.addEventListener('click', () => {
        curYear = now.getFullYear(); curMonth = now.getMonth(); render();
    });

    // ── Status filters ────────────────────────────────────────────────────────
    document.querySelectorAll('.acal-filter').forEach(btn => {
        btn.addEventListener('click', function () {
            calFilter = this.dataset.acalFilter;
            document.querySelectorAll('.acal-filter').forEach(b => {
                b.classList.remove('bg-maroon-base','text-white','border-maroon-base',
                                   'bg-green-base','border-green-base',
                                   'bg-gray-500','border-gray-500',
                                   'bg-red-500','border-red-500');
                b.classList.add('bg-white','text-ink-muted','border-border-card');
            });
            const activeMap = {
                all: ['bg-maroon-base','text-white','border-maroon-base'],
                upcoming:  ['bg-green-base','text-white','border-green-base'],
                completed: ['bg-gray-500','text-white','border-gray-500'],
                cancelled: ['bg-red-500','text-white','border-red-500'],
            };
            this.classList.remove('bg-white','text-ink-muted','border-border-card');
            this.classList.add(...(activeMap[calFilter]||[]));
            render();
        });
    });

    // ── View toggle (Calendar / List) ─────────────────────────────────────────
    const calView  = document.getElementById('admin-calendar-view');
    const listView = document.getElementById('admin-list-view');
    const tabCal   = document.getElementById('view-calendar');
    const tabList  = document.getElementById('view-list');

    function setTab(tab) {
        const isCalendar = tab === 'calendar';
        calView.classList.toggle('hidden', !isCalendar);
        listView.classList.toggle('hidden', isCalendar);

        tabCal.classList.toggle('bg-maroon-base',  isCalendar);
        tabCal.classList.toggle('text-white',       isCalendar);
        tabCal.classList.toggle('border-maroon-base',isCalendar);
        tabCal.classList.toggle('bg-white',         !isCalendar);
        tabCal.classList.toggle('text-ink-muted',   !isCalendar);
        tabCal.classList.toggle('border-border-card',!isCalendar);

        tabList.classList.toggle('bg-maroon-base',  !isCalendar);
        tabList.classList.toggle('text-white',       !isCalendar);
        tabList.classList.toggle('border-maroon-base',!isCalendar);
        tabList.classList.toggle('bg-white',         isCalendar);
        tabList.classList.toggle('text-ink-muted',   isCalendar);
        tabList.classList.toggle('border-border-card',isCalendar);

        localStorage.setItem('adminEventsView', tab);
    }

    tabCal.addEventListener('click',  () => setTab('calendar'));
    tabList.addEventListener('click', () => setTab('list'));

    // Restore saved preference
    const saved = localStorage.getItem('adminEventsView') || 'calendar';
    setTab(saved);

    // ── Init ──────────────────────────────────────────────────────────────────
    render();

    // Auto open feedback review modal if highlighted from notification
    const urlParams = new URLSearchParams(window.location.search);
    const highlightEventId = urlParams.get('highlight_event');
    const openFeedback = urlParams.get('open_feedback');
    if (highlightEventId && openFeedback) {
        setTimeout(() => {
            window.openEventReviewById(Number(highlightEventId));
        }, 150);
    }

    // Legacy confirmDelete (used by the list table)
    window.confirmDelete = function (e, type) {
        e.preventDefault();
        const form = e.target;
        Swal.fire({
            title: 'Delete this ' + type + '?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7B1113',
            cancelButtonColor: '#5A554D',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'rounded-2xl shadow-xl font-sans' }
        }).then(result => { if (result.isConfirmed) form.submit(); });
        return false;
    };
})();
</script>
@endpush

@endsection
