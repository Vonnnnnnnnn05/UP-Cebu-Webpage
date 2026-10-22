@extends('admin.layouts.admin')

@section('title', $isEdit ? 'Edit Event: ' . $event->title : 'Schedule New Activity / Event')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-xs font-bold text-ink-muted mb-1">
            <a href="{{ route('admin.events.index') }}" class="hover:text-maroon-base">TTBDO Activities</a>
            <span>&rsaquo;</span>
            <span class="text-maroon-base">{{ $isEdit ? 'Edit Activity' : 'New Activity' }}</span>
        </div>
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-tight">
            {{ $isEdit ? 'Edit Activity / Event' : 'Add TTBDO Activity or Meeting' }}
        </h1>
        <p class="text-xs sm:text-sm text-ink-muted mt-1">
            Create simple activities (like visits or ceremonies) or multi-day events with daily documentation.
        </p>
    </div>
    <div class="flex items-center gap-2 self-start sm:self-auto">
        @if($isEdit && $event->slug)
        <a href="{{ route('events.show', $event->slug) }}" target="_blank"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gold-light border border-gold-base/40 text-xs font-bold text-gold-deep hover:bg-gold-base hover:text-maroon-dark transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
            <span>View Public Page</span>
        </a>
        @endif
        <a href="{{ route('admin.events.index') }}"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-border-card text-xs font-semibold text-ink-muted hover:text-ink-base transition-colors shadow-2xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span>Back to List</span>
        </a>
    </div>
</div>

{{-- Validation Alerts --}}
@if ($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-xs sm:text-sm">
    <strong class="font-bold block mb-1">Please fix the following validation errors:</strong>
    <ul class="list-disc list-inside space-y-0.5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('success_message'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-xs sm:text-sm flex items-center justify-between">
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
        </svg>
        <span>{{ session('success_message') }}</span>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════
     EVENT PRIMARY FORM
══════════════════════════════════════════════════════════ --}}
<form action="{{ $isEdit ? route('admin.events.update', $event->id) : route('admin.events.store') }}" method="POST" enctype="multipart/form-data"
    class="bg-white border border-border-card rounded-2xl p-6 sm:p-8 shadow-xs space-y-6 mb-8">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <!-- Row 1: Title -->
    <div>
        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
            Activity / Event Title <span class="text-maroon-base">*</span>
        </label>
        <input type="text" id="title" name="title" required
            value="{{ old('title', $event->title) }}"
            placeholder="e.g. SKSU Visit, BARMM Visit for Incubation, DOST Consultation Meeting"
            class="w-full px-4 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors font-medium" />
    </div>

    <!-- Row 2: Category, Start Date, End Date, Status -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label for="category" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Category <span class="text-maroon-base">*</span>
            </label>
            <select id="category" name="category" required
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors font-medium">
                @php $cat = old('category', $event->category); @endphp
                @foreach ($categories as $catOption)
                    <option value="{{ $catOption }}" {{ strcasecmp($cat, $catOption) === 0 ? 'selected' : '' }}>
                        {{ $catOption }}
                    </option>
                @endforeach
            </select>
            <span class="text-[10px] text-ink-muted mt-1 block">At minimum: Activity or Meeting.</span>
        </div>

        <div>
            <label for="event_date" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Start Date <span class="text-maroon-base">*</span>
            </label>
            <input type="date" id="event_date" name="event_date" required
                value="{{ old('event_date', $event->event_date ? $event->event_date->format('Y-m-d') : date('Y-m-d')) }}"
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors font-medium" />
            <span class="text-[10px] text-ink-muted mt-1 block">Calendar entry appears on this date.</span>
        </div>

        <div>
            <label for="end_date" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                End Date <span class="text-ink-muted text-[10px] lowercase font-normal">(if multi-day)</span>
            </label>
            <input type="date" id="end_date" name="end_date"
                value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d') : '') }}"
                placeholder="Optional"
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors font-medium" />
            <span class="text-[10px] text-ink-muted mt-1 block">Leave empty for single-day activities.</span>
        </div>

        <div>
            <label for="status" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Status <span class="text-maroon-base">*</span>
            </label>
            <select id="status" name="status" required
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors font-medium">
                @php $st = old('status', $event->status); @endphp
                <option value="upcoming" {{ $st === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="completed" {{ $st === 'completed' ? 'selected' : '' }}>Completed / Past</option>
                <option value="cancelled" {{ $st === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
    </div>

    <!-- Workflow Approval & Review Status -->
    <div class="p-4 sm:p-5 rounded-2xl border border-gold-base/40 bg-gold-light/15 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-border-card/60 pb-3">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-maroon-base flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gold-base"></span>
                    Workflow &amp; Publication Status
                </span>
                <p class="text-[11px] text-ink-muted mt-0.5">
                    Activities must be approved by an Administrator to appear on the public calendar.
                </p>
            </div>
            @php
                $appStatus = old('approval_status', $event->approval_status ?? 'approved');
                $appBadge = match($appStatus) {
                    'approved' => 'bg-green-100 text-green-800 border-green-300',
                    'reviewed' => 'bg-gold-light text-maroon-dark border-gold-base/50 font-bold',
                    'rejected' => 'bg-red-100 text-red-800 border-red-300',
                    default    => 'bg-amber-100 text-amber-800 border-amber-300',
                };
            @endphp
            <div class="flex items-center gap-2">
                <span class="text-[10.5px] uppercase font-extrabold px-3 py-1 rounded-full border {{ $appBadge }}">
                    Published (Live)
                </span>
            </div>
        </div>

        @if ($event->director_comments)
        <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-300 text-xs">
            <div class="flex items-center gap-1.5 text-amber-900 font-bold uppercase tracking-wider text-[10.5px] mb-1">
                <svg class="w-4 h-4 text-gold-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>
                <span>Director Review Feedback &amp; Suggestions:</span>
            </div>
            <p class="text-ink-base leading-relaxed pl-5 whitespace-pre-wrap">{{ $event->director_comments }}</p>
            @if ($event->reviewer)
            <div class="text-[10px] text-ink-muted pl-5 mt-1">
                Feedback from <strong class="text-ink-base">{{ $event->reviewer->full_name }}</strong> on {{ $event->reviewed_at?->format('M d, Y h:i A') }}
            </div>
            @endif
        </div>
        @endif

        {{-- Administrator can update or clear Director feedback notes once addressed --}}
        @if (Auth::guard('admin')->user()?->isAdminRole() && $isEdit)
        <div>
            <label for="director_comments" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Director Feedback Notes (Admin can edit or clear once corrections are completed)
            </label>
            <textarea id="director_comments" name="director_comments" rows="2"
                placeholder="Leave notes or clear when all corrections are completed..."
                class="w-full px-3.5 py-2 bg-white border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:border-maroon-base focus:outline-none transition-colors">{{ old('director_comments', $event->director_comments) }}</textarea>
        </div>
        @endif
    </div>

    <!-- Row 3: View Details Toggle & Short Information -->
    <div class="p-4 sm:p-5 rounded-2xl bg-cream-soft/70 border border-border-card space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input type="checkbox" id="enable_view_link" name="enable_view_link" value="1"
                        {{ old('enable_view_link', $event->enable_view_link ?? false) ? 'checked' : '' }}
                        class="rounded border-border-card text-maroon-base focus:ring-maroon-base h-4 w-4">
                    <span class="text-xs sm:text-sm font-bold text-maroon-base">Enable Public "View Details" Page</span>
                </label>
                <p class="text-[11px] text-ink-muted mt-1 pl-6">
                    If enabled, a "View Details" link will be displayed on the calendar to open the dedicated activity/daily documentation page.
                    Disable for simple activities that only need to appear on the calendar.
                </p>
            </div>
        </div>

        <div>
            <label for="summary" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Optional Short Information / Overview
            </label>
            <textarea id="summary" name="summary" rows="2"
                placeholder="Brief summary or objective (optional for simple activities)..."
                class="w-full px-4 py-2 bg-white border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:border-maroon-base focus:outline-none transition-colors">{{ old('summary', $event->summary) }}</textarea>
        </div>

        <!-- Activity Cover / Banner Image -->
        <div class="pt-3 border-t border-border-card/60">
            <label class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Activity Banner / Cover Photo <span class="text-ink-muted text-[10px] lowercase font-normal">(optional)</span>
            </label>
            <div class="flex flex-col sm:flex-row items-start gap-4 p-3 bg-white border border-border-card rounded-xl">
                @if ($event->image_url)
                <div class="relative group shrink-0">
                    <img src="{{ asset($event->image_url) }}" alt="Activity Image" class="w-28 h-20 sm:w-32 sm:h-24 object-cover rounded-lg border border-border-card shadow-2xs">
                    <label class="mt-1 flex items-center gap-1.5 text-[11px] text-red-600 cursor-pointer font-medium hover:underline">
                        <input type="checkbox" name="remove_image" value="1" class="rounded border-border-card text-red-600 focus:ring-red-500 h-3.5 w-3.5">
                        <span>Remove current photo</span>
                    </label>
                </div>
                @endif
                <div class="flex-1 w-full space-y-1.5">
                    <input type="file" name="image" accept="image/*"
                        class="w-full text-xs text-ink-muted file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-cream-soft file:text-maroon-base hover:file:bg-gold-light hover:file:text-gold-deep cursor-pointer" />
                    <p class="text-[10.5px] text-ink-muted leading-tight">
                        Upload a featured landscape banner or cover photo for this activity. JPG, PNG, WEBP up to 5MB.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional Logistics (Collapsible / Clean) -->
    <details class="group border border-border-card/80 rounded-xl p-4 bg-white">
        <summary class="text-xs font-bold text-ink-base uppercase tracking-wider cursor-pointer flex items-center justify-between">
            <span>Additional Optional Details (Venue, Time, Formats)</span>
            <span class="text-xs text-maroon-base font-normal group-open:rotate-180 transition-transform">&dtrif;</span>
        </summary>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 pt-4 border-t border-border-card/60">
            <div>
                <label for="venue" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                    Venue / Location
                </label>
                <input type="text" id="venue" name="venue"
                    value="{{ old('venue', $event->venue ?? 'UP Cebu Campus') }}"
                    placeholder="e.g. UP Cebu Campus, TIC 3rd Floor"
                    class="w-full px-3.5 py-2 bg-cream-soft border border-border-card rounded-xl text-xs text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
            </div>

            <div>
                <label for="start_time" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                    Start Time
                </label>
                <input type="text" id="start_time" name="start_time"
                    value="{{ old('start_time', $event->start_time ?? '09:00 AM') }}"
                    placeholder="e.g. 09:00 AM"
                    class="w-full px-3.5 py-2 bg-cream-soft border border-border-card rounded-xl text-xs text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
            </div>

            <div>
                <label for="end_time" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                    End Time
                </label>
                <input type="text" id="end_time" name="end_time"
                    value="{{ old('end_time', $event->end_time ?? '05:00 PM') }}"
                    placeholder="e.g. 05:00 PM"
                    class="w-full px-3.5 py-2 bg-cream-soft border border-border-card rounded-xl text-xs text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
            </div>
        </div>
    </details>

    <!-- Form Submit Actions -->
    <div class="pt-4 border-t border-border-card flex flex-wrap items-center justify-end gap-3">
        <a href="{{ route('admin.events.index') }}"
            class="px-5 py-2.5 rounded-xl border border-border-card text-xs font-semibold text-ink-muted hover:bg-cream-soft transition-colors">
            Cancel
        </a>

        @if (!$isEdit)
        <button type="submit" name="save_and_add_details" value="1"
            class="px-5 py-2.5 rounded-xl bg-maroon-base hover:bg-maroon-dark text-white text-xs sm:text-sm font-bold shadow-xs transition-all cursor-pointer">
            Save &amp; Add Daily Details
        </button>
        @endif

        <button type="submit"
            class="px-6 py-2.5 rounded-xl bg-green-base hover:bg-green-hover text-white text-xs sm:text-sm font-bold shadow-xs transition-all cursor-pointer">
            {{ $isEdit ? 'Save Activity Changes' : 'Save Activity' }}
        </button>
    </div>
</form>

{{-- ══════════════════════════════════════════════════════════
     MANAGE DAILY DETAILS (FOR MULTI-DAY OR DETAILED ACTIVITIES)
══════════════════════════════════════════════════════════ --}}
@if ($isEdit)
<div id="daily-details-section" class="bg-white border border-border-card rounded-2xl p-6 sm:p-8 shadow-xs">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-border-card">
        <div>
            <h2 class="font-serif text-xl sm:text-2xl font-bold text-maroon-base">
                Daily Activity Breakdown (Event Documentation)
            </h2>
            <p class="text-xs text-ink-muted mt-1">
                Add multiple daily records under this event (e.g. Day 1 arrival, Day 2 meeting, Day 3 ceremony).
            </p>
        </div>
        <span class="text-xs font-bold text-ink-base bg-cream-soft px-3 py-1.5 rounded-xl border border-border-card self-start sm:self-auto">
            {{ $event->logs->count() }} {{ Str::plural('Daily Detail', $event->logs->count()) }}
        </span>
    </div>

    {{-- Existing Daily Details List --}}
    @if ($event->logs->isNotEmpty())
    <div class="space-y-4 mb-8">
        @foreach ($event->logs as $index => $detail)
        <div class="p-4 sm:p-5 rounded-xl bg-cream-soft/40 border border-border-card hover:border-maroon-base/30 transition-all flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="flex items-start gap-3 sm:gap-4 flex-1">
                <span class="w-8 h-8 rounded-full bg-maroon-base text-white text-xs font-bold flex items-center justify-center shrink-0">
                    D{{ $index + 1 }}
                </span>
                <div class="space-y-1.5 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-maroon-base bg-maroon-base/10 px-2 py-0.5 rounded">
                            {{ $detail->log_date ? $detail->log_date->format('F d, Y') : '' }}
                        </span>
                        <span class="text-[11px] text-ink-muted font-medium">
                            ({{ $detail->log_date ? $detail->log_date->format('l') : '' }})
                        </span>
                    </div>
                    <h3 class="font-serif text-sm sm:text-base font-bold text-ink-base leading-snug">
                        {{ $detail->title }}
                    </h3>
                    @if($detail->description)
                    <p class="text-xs sm:text-[13px] text-ink-muted leading-relaxed whitespace-pre-line pt-1">
                        {{ $detail->description }}
                    </p>
                    @endif

                    @if ($detail->image_path)
                    <div class="pt-2">
                        <img src="{{ asset($detail->image_path) }}" alt="{{ $detail->title }}"
                            class="h-28 sm:h-36 w-auto max-w-full rounded-xl object-cover border border-border-card shadow-2xs">
                    </div>
                    @endif
                </div>
            </div>

            {{-- Action buttons for this daily detail --}}
            <div class="flex items-center gap-2 shrink-0 self-end md:self-start">
                {{-- Edit Button (triggers inline edit toggle) --}}
                <button type="button" onclick="toggleEditDetailModal({{ $detail->id }})"
                    class="px-3 py-1.5 rounded-lg bg-white border border-border-card text-xs font-semibold text-ink-base hover:bg-gold-light hover:text-gold-deep transition-all cursor-pointer">
                    Edit
                </button>

                {{-- Delete Form with Confirmation --}}
                <form action="{{ route('admin.events.details.destroy', [$event->id, $detail->id]) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this daily detail?');" class="inline m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-3 py-1.5 rounded-lg bg-white border border-border-card text-xs font-semibold text-red-600 hover:bg-red-50 hover:border-red-300 transition-all cursor-pointer">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Hidden Edit Modal/Drawer for this daily detail --}}
        <div id="edit-detail-box-{{ $detail->id }}" class="hidden p-5 rounded-2xl bg-amber-50/60 border border-amber-200 mb-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gold-deep mb-3">Edit Daily Detail (Day {{ $index + 1 }})</h4>
            <form action="{{ route('admin.events.details.update', [$event->id, $detail->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1">Date *</label>
                        <input type="date" name="log_date" required
                            min="{{ $event->event_date->format('Y-m-d') }}"
                            max="{{ $event->end_date ? $event->end_date->format('Y-m-d') : $event->event_date->format('Y-m-d') }}"
                            value="{{ old('log_date', $detail->log_date ? $detail->log_date->format('Y-m-d') : '') }}"
                            class="w-full px-3 py-2 bg-white border border-border-card rounded-xl text-xs font-medium" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1">Title / Headline *</label>
                        <input type="text" name="title" required
                            value="{{ old('title', $detail->title) }}"
                            class="w-full px-3 py-2 bg-white border border-border-card rounded-xl text-xs font-medium" />
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1">Description *</label>
                    <textarea name="description" rows="3" required
                        class="w-full px-3 py-2 bg-white border border-border-card rounded-xl text-xs font-medium leading-relaxed">{{ old('description', $detail->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1">
                        Day Photo / Documentation Image <span class="text-ink-muted text-[10px] lowercase font-normal">(optional)</span>
                    </label>
                    @if ($detail->image_path)
                    <div class="flex items-center gap-3 p-2.5 bg-white border border-border-card rounded-xl mb-2">
                        <img src="{{ asset($detail->image_path) }}" alt="Day photo" class="w-16 h-12 rounded object-cover border border-border-card">
                        <label class="flex items-center gap-1.5 text-xs text-red-600 font-medium cursor-pointer">
                            <input type="checkbox" name="remove_image" value="1" class="rounded border-border-card text-red-600 focus:ring-red-500 h-3.5 w-3.5">
                            <span>Delete current photo</span>
                        </label>
                    </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                        class="w-full text-xs text-ink-muted file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-white file:text-maroon-base hover:file:bg-gold-light cursor-pointer" />
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" onclick="toggleEditDetailModal({{ $detail->id }})"
                        class="px-3.5 py-1.5 rounded-lg border border-border-card text-xs text-ink-muted hover:bg-white transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-1.5 rounded-lg bg-maroon-base text-white text-xs font-bold hover:bg-maroon-dark transition-all cursor-pointer">
                        Save Day Changes
                    </button>
                </div>
            </form>
        </div>
        @endforeach
    </div>
    @else
    <div class="p-6 rounded-xl bg-cream-soft/50 border border-dashed border-border-card text-center mb-8">
        <p class="text-xs sm:text-sm text-ink-muted">
            No daily activity details added yet for this event.
        </p>
        <p class="text-[11px] text-ink-muted/80 mt-0.5">
            If this is a simple activity (like SKSU Visit), you can leave this empty.
        </p>
    </div>
    @endif

    {{-- Add New Daily Detail Card --}}
    <div class="bg-cream-soft/60 border border-border-card rounded-2xl p-5 sm:p-6">
        <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-maroon-base mb-3 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Add a Daily Detail Entry</span>
        </h3>

        <form action="{{ route('admin.events.details.store', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="detail_date" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                        Date <span class="text-maroon-base">*</span>
                    </label>
                    <input type="date" id="detail_date" name="log_date" required
                        min="{{ $event->event_date->format('Y-m-d') }}"
                        max="{{ $event->end_date ? $event->end_date->format('Y-m-d') : $event->event_date->format('Y-m-d') }}"
                        value="{{ old('log_date', $event->event_date->format('Y-m-d')) }}"
                        class="w-full px-3.5 py-2.5 bg-white border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:border-maroon-base focus:outline-none transition-colors font-medium" />
                    <span class="text-[10px] text-ink-muted mt-1 block">
                        Between {{ $event->event_date->format('M d') }} and {{ $event->end_date ? $event->end_date->format('M d, Y') : $event->event_date->format('M d, Y') }}
                    </span>
                </div>

                <div class="sm:col-span-2">
                    <label for="detail_title" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                        Daily Title / Activity Heading <span class="text-maroon-base">*</span>
                    </label>
                    <input type="text" id="detail_title" name="title" required
                        value="{{ old('title') }}"
                        placeholder="e.g. Arrival of BARMM Officials, Continuation of Meeting, Closing Ceremony"
                        class="w-full px-3.5 py-2.5 bg-white border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:border-maroon-base focus:outline-none transition-colors font-medium" />
                </div>
            </div>

            <div>
                <label for="detail_desc" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                    Description of Day's Activities <span class="text-maroon-base">*</span>
                </label>
                <textarea id="detail_desc" name="description" rows="3" required
                    placeholder="Details about what occurred on this specific day..."
                    class="w-full px-4 py-2.5 bg-white border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:border-maroon-base focus:outline-none transition-colors font-normal leading-relaxed">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="detail_image" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                    Day Documentation Photo / Image <span class="text-ink-muted text-[10px] lowercase font-normal">(optional)</span>
                </label>
                <div class="p-3 bg-white border border-border-card rounded-xl">
                    <input type="file" id="detail_image" name="image" accept="image/*"
                        class="w-full text-xs text-ink-muted file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-cream-soft file:text-maroon-base hover:file:bg-gold-light hover:file:text-gold-deep cursor-pointer" />
                    <span class="text-[10.5px] text-ink-muted mt-1 block">
                        Attach photo documentation for this specific day (e.g. photos of visits, meetings, workshops). JPG, PNG, WEBP up to 5MB.
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-maroon-base hover:bg-maroon-dark text-white text-xs sm:text-sm font-bold shadow-xs transition-all cursor-pointer">
                    + Add Daily Detail
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
function toggleEditDetailModal(id) {
    const box = document.getElementById('edit-detail-box-' + id);
    if (box) {
        box.classList.toggle('hidden');
    }
}
</script>
@endpush
@endsection
