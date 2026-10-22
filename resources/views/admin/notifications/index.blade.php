@extends('admin.layouts.admin')

@section('title', 'Feedback & Notifications Center')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    <!-- Header & Action Row -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-border-card shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gold-deep mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
                <span>&bull;</span>
                <span>System Inbox</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold font-serif text-maroon-base tracking-tight flex items-center gap-2.5">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-gold-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                <span>Feedback &amp; Notifications</span>
            </h1>
            <p class="text-xs sm:text-sm text-ink-muted mt-1 max-w-xl">
                Internal review comments and quality notes — stored strictly in-system with no external emails.
            </p>
        </div>

        <!-- Global Action Buttons -->
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            @if(($stats['unread'] ?? 0) > 0)
            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gold-base hover:bg-gold-base/90 text-maroon-dark font-bold text-xs transition-all shadow-xs cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span>Mark All Read</span>
                </button>
            </form>
            @endif

            @if(($stats['total'] ?? 0) > ($stats['unread'] ?? 0))
            <form action="{{ route('admin.notifications.clear-read') }}" method="POST" onsubmit="return confirm('Clear all read notifications from your history?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-cream-soft hover:bg-cream-soft/80 border border-border-card text-ink-muted hover:text-ink-base font-semibold text-xs transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Clear Read</span>
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Quick Stats Cards Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3">
        <!-- Unread Feedback -->
        <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}"
            class="p-3 sm:p-3.5 rounded-xl border {{ $filter === 'unread' ? 'border-gold-base bg-gold-light/40 ring-2 ring-gold-base/50' : 'border-border-card bg-white hover:bg-cream-soft/60' }} transition-all shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10.5px] font-bold uppercase tracking-wider text-ink-muted">Unread</span>
                <span class="w-2 h-2 rounded-full {{ ($stats['unread'] ?? 0) > 0 ? 'bg-gold-base animate-ping' : 'bg-stone-300' }}"></span>
            </div>
            <div class="text-xl sm:text-2xl font-black font-serif text-maroon-base mt-1.5">
                {{ $stats['unread'] ?? 0 }}
            </div>
            <p class="text-[10px] text-ink-muted mt-0.5">Awaiting attention</p>
        </a>

        <!-- Event Feedback -->
        <a href="{{ route('admin.notifications.index', ['filter' => 'event']) }}"
            class="p-3 sm:p-3.5 rounded-xl border {{ $filter === 'event' ? 'border-maroon-base bg-maroon-base/5 ring-2 ring-maroon-base/20' : 'border-border-card bg-white hover:bg-cream-soft/60' }} transition-all shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10.5px] font-bold uppercase tracking-wider text-ink-muted">Events</span>
                <span class="p-1 rounded bg-maroon-base/10 text-maroon-base">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </span>
            </div>
            <div class="text-xl sm:text-2xl font-black font-serif text-maroon-base mt-1.5">
                {{ $stats['event_feedback'] ?? 0 }}
            </div>
            <p class="text-[10px] text-ink-muted mt-0.5">Activity reviews</p>
        </a>

        <!-- Inquiry Feedback -->
        <a href="{{ route('admin.notifications.index', ['filter' => 'inquiry']) }}"
            class="p-3 sm:p-3.5 rounded-xl border {{ $filter === 'inquiry' ? 'border-amber-400 bg-amber-50 ring-2 ring-amber-300' : 'border-border-card bg-white hover:bg-cream-soft/60' }} transition-all shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10.5px] font-bold uppercase tracking-wider text-ink-muted">Inquiries</span>
                <span class="p-1 rounded bg-amber-100 text-amber-700">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </span>
            </div>
            <div class="text-xl sm:text-2xl font-black font-serif text-amber-900 mt-1.5">
                {{ $stats['inquiry_feedback'] ?? 0 }}
            </div>
            <p class="text-[10px] text-ink-muted mt-0.5">Endorsements &amp; notes</p>
        </a>

        <!-- Total History -->
        <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}"
            class="p-3 sm:p-3.5 rounded-xl border {{ $filter === 'all' ? 'border-border-card bg-cream-soft ring-2 ring-maroon-base/20' : 'border-border-card bg-white hover:bg-cream-soft/60' }} transition-all shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10.5px] font-bold uppercase tracking-wider text-ink-muted">All</span>
                <span class="p-1 rounded bg-stone-100 text-stone-600">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                </span>
            </div>
            <div class="text-xl sm:text-2xl font-black font-serif text-ink-base mt-1.5">
                {{ $stats['total'] ?? 0 }}
            </div>
            <p class="text-[10px] text-ink-muted mt-0.5">Lifetime archive</p>
        </a>
    </div>

    <!-- Filter Navigation Tabs -->
    <div class="flex items-center gap-1.5 border-b border-border-card pb-2.5 overflow-x-auto text-xs font-bold scrollbar-none">
        <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}"
            class="px-3 py-1 rounded-lg transition-all {{ $filter === 'all' ? 'bg-maroon-base text-white shadow-2xs' : 'bg-white text-ink-muted hover:text-ink-base border border-border-card' }}">
            All ({{ $stats['total'] ?? 0 }})
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}"
            class="px-3 py-1 rounded-lg transition-all flex items-center gap-1.5 {{ $filter === 'unread' ? 'bg-maroon-base text-white shadow-2xs' : 'bg-white text-ink-muted hover:text-ink-base border border-border-card' }}">
            <span>Unread</span>
            @if(($stats['unread'] ?? 0) > 0)
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $filter === 'unread' ? 'bg-gold-base text-maroon-dark' : 'bg-amber-100 text-amber-800' }}">
                {{ $stats['unread'] }}
            </span>
            @endif
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'event']) }}"
            class="px-3 py-1 rounded-lg transition-all {{ $filter === 'event' ? 'bg-maroon-base text-white shadow-2xs' : 'bg-white text-ink-muted hover:text-ink-base border border-border-card' }}">
            Events ({{ $stats['event_feedback'] ?? 0 }})
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'inquiry']) }}"
            class="px-3 py-1 rounded-lg transition-all {{ $filter === 'inquiry' ? 'bg-amber-800 text-white shadow-2xs' : 'bg-white text-ink-muted hover:text-ink-base border border-border-card' }}">
            Inquiries ({{ $stats['inquiry_feedback'] ?? 0 }})
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'read']) }}"
            class="px-3 py-1 rounded-lg transition-all {{ $filter === 'read' ? 'bg-maroon-base text-white shadow-2xs' : 'bg-white text-ink-muted hover:text-ink-base border border-border-card' }}">
            Read Archive
        </a>
    </div>

    <!-- Notification Feed List -->
    @if($notifications->count() > 0)
    <div class="space-y-3.5">
        @foreach($notifications as $notification)
        @php
            $data = is_array($notification->data) ? $notification->data : (json_decode($notification->data, true) ?? []);
            $isUnread = $notification->read_at === null;
            $itemType = $data['item_type'] ?? 'general';
            $reviewerRole = $data['reviewer_role'] ?? 'director';
            $roleLabel = match($reviewerRole) {
                'director' => 'Director (Quality Review)',
                'admin'    => 'Administrator',
                default    => 'Staff Member',
            };
            $roleBadgeColor = match($reviewerRole) {
                'director' => 'bg-gold-light text-maroon-dark font-black border border-gold-base/50',
                'admin'    => 'bg-maroon-base/10 text-maroon-base font-bold',
                default    => 'bg-emerald-100 text-emerald-800 font-bold',
            };
            $typeTheme = match($itemType) {
                'event'   => ['border' => 'border-maroon-base/30', 'bg' => 'bg-maroon-base/5', 'iconBg' => 'bg-maroon-base', 'tag' => 'bg-maroon-base/10 text-maroon-base border-maroon-base/20'],
                'inquiry' => ['border' => 'border-amber-200', 'bg' => 'bg-amber-50/40', 'iconBg' => 'bg-amber-600', 'tag' => 'bg-amber-100 text-amber-900 border-amber-200'],
                default   => ['border' => 'border-border-card', 'bg' => 'bg-white', 'iconBg' => 'bg-maroon-base', 'tag' => 'bg-cream-soft text-ink-base border-border-card'],
            };
        @endphp

        <div class="bg-white rounded-xl border transition-all duration-150 overflow-hidden shadow-2xs {{ $isUnread ? 'border-gold-base/60 ring-1 ring-gold-base/20' : 'border-border-card' }}">
            @if($isUnread)
            <div class="h-1 bg-gradient-to-r from-gold-base via-maroon-base to-gold-base"></div>
            @endif

            <div class="p-4 sm:p-5 space-y-3">
                <!-- Card Header -->
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Reviewer Avatar Initials -->
                        <div class="w-9 h-9 rounded-lg {{ $typeTheme['iconBg'] }} text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                            {{ strtoupper(substr($data['reviewer_name'] ?? 'U', 0, 1)) }}{{ strtoupper(substr(strrchr($data['reviewer_name'] ?? ' ', ' ') ?: ' ', 1, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                <span class="font-bold text-ink-base text-sm truncate">
                                    {{ $data['reviewer_name'] ?? 'Reviewer' }}
                                </span>
                                <span class="text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-full {{ $roleBadgeColor }}">
                                    {{ $roleLabel }}
                                </span>
                                @if($isUnread)
                                <span class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase tracking-wider px-1.5 py-0.5 rounded-full bg-gold-base/20 text-maroon-dark border border-gold-base/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gold-deep animate-ping"></span>
                                    New
                                </span>
                                @endif
                            </div>

                            <p class="text-[11px] text-ink-muted mt-0.5 flex items-center gap-1.5">
                                <span>{{ $notification->created_at?->format('M d, Y • h:i A') }}</span>
                                <span>&bull;</span>
                                <span class="italic text-ink-base/75 font-medium">{{ $notification->created_at?->diffForHumans() }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Right Quick Badges / Actions -->
                    <div class="flex items-center gap-1.5 shrink-0">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border {{ $typeTheme['tag'] }}">
                            {{ $data['type_label'] ?? ucfirst($itemType) }}
                        </span>

                        <!-- Delete button -->
                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Remove this notification?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 rounded-md text-ink-muted hover:text-red-700 hover:bg-red-50 transition-colors cursor-pointer" title="Delete notification">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Subject Title & Target Context -->
                <div>
                    <h2 class="font-serif text-sm sm:text-base font-bold text-maroon-base leading-snug">
                        {{ $data['title'] ?? 'Review Feedback' }}
                    </h2>
                    @if(!empty($data['item_title']) && !str_contains($data['title'] ?? '', $data['item_title']))
                    <p class="text-xs text-ink-muted mt-0.5">
                        Target Record: <strong class="text-ink-base font-medium">{{ $data['item_title'] }}</strong>
                    </p>
                    @endif
                </div>

                <!-- Feedback Message Callout Box -->
                <div class="rounded-lg border-l-4 border-l-gold-base border border-amber-200/70 bg-amber-50/50 p-3 sm:p-3.5">
                    <div class="flex items-center gap-1.5 text-[10.5px] font-bold uppercase tracking-wider text-amber-900 mb-1">
                        <svg class="w-3.5 h-3.5 text-gold-deep shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        <span>Quality Reviewer Comments:</span>
                    </div>
                    <p class="text-xs sm:text-sm text-ink-base leading-relaxed whitespace-pre-line font-normal">{{ trim($data['message'] ?? 'No written comment provided.') }}</p>
                </div>

                <!-- Card Footer & Action Buttons -->
                <div class="pt-2 border-t border-border-card/50 flex flex-wrap items-center justify-between gap-2.5 text-xs">
                    <div class="flex items-center gap-2">
                        @if(!empty($data['action_url']) && $data['action_url'] !== '#')
                        <a href="{{ $data['action_url'] }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white font-bold text-xs transition-colors shadow-2xs">
                            <span>Open &amp; Review Activity</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                        </a>
                        @endif

                        @if($isUnread)
                        <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-border-card bg-cream-soft hover:bg-cream-soft/80 text-ink-base font-semibold text-xs transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-gold-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span>Mark Read</span>
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.notifications.unread', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-border-card bg-white hover:bg-cream-soft text-ink-muted hover:text-ink-base font-medium text-xs transition-colors cursor-pointer">
                                <span>Mark Unread</span>
                            </button>
                        </form>
                        @endif
                    </div>

                    <div class="text-[11px] text-ink-muted flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>Internal Portal Notification &bull; No Email Sent</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pt-4">
        {{ $notifications->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl p-12 text-center border border-border-card shadow-xs">
        <div class="w-16 h-16 rounded-2xl bg-gold-light/40 text-gold-deep mx-auto flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        <h3 class="font-serif text-xl font-bold text-maroon-base">
            No Feedback Notifications in this View
        </h3>
        <p class="text-xs sm:text-sm text-ink-muted max-w-md mx-auto mt-1.5">
            When the Director or Administrators submit review notes on published events or incoming inquiries, they will appear here in real time.
        </p>
        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('admin.events.index') }}" class="px-4 py-2 rounded-xl bg-maroon-base text-white text-xs font-bold hover:bg-maroon-hover transition-colors">
                View Published Events
            </a>
            <a href="{{ route('admin.inquiries.index') }}" class="px-4 py-2 rounded-xl border border-border-card bg-cream-soft text-ink-base text-xs font-bold hover:bg-cream-soft/80 transition-colors">
                View Inquiries Queue
            </a>
        </div>
    </div>
    @endif

</div>
@endsection
