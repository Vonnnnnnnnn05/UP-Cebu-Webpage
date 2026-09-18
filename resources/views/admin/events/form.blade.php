@extends('admin.layouts.admin')

@section('title', $isEdit ? 'Edit Event' : 'Schedule New Event')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <div class="flex items-center gap-2 text-xs font-bold text-ink-muted mb-1">
            <a href="{{ route('admin.events.index') }}" class="hover:text-maroon-base">Events &amp; Summits</a>
            <span>&rsaquo;</span>
            <span class="text-maroon-base">{{ $isEdit ? 'Edit Event' : 'New Event' }}</span>
        </div>
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-tight">
            {{ $isEdit ? 'Edit Event Details' : 'Schedule Innovation Event or Workshop' }}
        </h1>
    </div>
    <a href="{{ route('admin.events.index') }}"
        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white border border-border-card text-xs font-semibold text-ink-muted hover:text-ink-base transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        <span>Back to List</span>
    </a>
</div>

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

<form action="{{ $isEdit ? route('admin.events.update', $event->id) : route('admin.events.store') }}" method="POST"
    class="bg-white border border-border-card rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <!-- Row 1: Title -->
    <div>
        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
            Event Title / Headline <span class="text-maroon-base">*</span>
        </label>
        <input type="text" id="title" name="title" required
            value="{{ old('title', $event->title) }}"
            placeholder="e.g. Central Visayas Innovation Summit & Startup Demo Day 2025"
            class="w-full px-4 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors font-medium" />
    </div>

    <!-- Row 2: Category, Badge, Date, Status -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label for="category" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Category <span class="text-maroon-base">*</span>
            </label>
            <select id="category" name="category" required
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">
                @php $cat = old('category', $event->category); @endphp
                <option value="ANNUAL SUMMIT" {{ $cat === 'ANNUAL SUMMIT' ? 'selected' : '' }}>Annual Summit</option>
                <option value="IP LEGAL CLINIC" {{ $cat === 'IP LEGAL CLINIC' ? 'selected' : '' }}>IP Legal Clinic</option>
                <option value="STUDENT MENTORSHIP" {{ $cat === 'STUDENT MENTORSHIP' ? 'selected' : '' }}>Student Mentorship</option>
                <option value="WORKSHOP" {{ $cat === 'WORKSHOP' ? 'selected' : '' }}>Workshop</option>
                <option value="DEMO DAY" {{ $cat === 'DEMO DAY' ? 'selected' : '' }}>Demo Day</option>
                <option value="WEBINAR" {{ $cat === 'WEBINAR' ? 'selected' : '' }}>Webinar</option>
            </select>
        </div>

        <div>
            <label for="badge_label" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Badge Label
            </label>
            <input type="text" id="badge_label" name="badge_label"
                value="{{ old('badge_label', $event->badge_label) }}"
                placeholder="e.g. FLAGSHIP EVENT, FREE WEBINAR"
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
        </div>

        <div>
            <label for="event_date" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Event Date <span class="text-maroon-base">*</span>
            </label>
            <input type="date" id="event_date" name="event_date" required
                value="{{ old('event_date', $event->event_date ? $event->event_date->format('Y-m-d') : date('Y-m-d')) }}"
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
        </div>

        <div>
            <label for="status" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Event Status <span class="text-maroon-base">*</span>
            </label>
            <select id="status" name="status" required
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">
                @php $st = old('status', $event->status); @endphp
                <option value="upcoming" {{ $st === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="completed" {{ $st === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $st === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
    </div>

    <!-- Row 3: Times, Venue Type, Venue -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label for="start_time" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Start Time <span class="text-maroon-base">*</span>
            </label>
            <input type="text" id="start_time" name="start_time" required
                value="{{ old('start_time', $event->start_time ?? '09:00 AM') }}"
                placeholder="e.g. 08:30 AM"
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
        </div>

        <div>
            <label for="end_time" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                End Time <span class="text-maroon-base">*</span>
            </label>
            <input type="text" id="end_time" name="end_time" required
                value="{{ old('end_time', $event->end_time ?? '05:00 PM') }}"
                placeholder="e.g. 05:00 PM"
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
        </div>

        <div>
            <label for="venue_type" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Delivery Format <span class="text-maroon-base">*</span>
            </label>
            <select id="venue_type" name="venue_type" required
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">
                @php $vt = old('venue_type', $event->venue_type); @endphp
                <option value="in-person" {{ $vt === 'in-person' ? 'selected' : '' }}>In-Person</option>
                <option value="hybrid" {{ $vt === 'hybrid' ? 'selected' : '' }}>Hybrid (Live + Stream)</option>
                <option value="virtual" {{ $vt === 'virtual' ? 'selected' : '' }}>Online / Virtual</option>
            </select>
        </div>

        <div>
            <label for="venue" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Venue Location <span class="text-maroon-base">*</span>
            </label>
            <input type="text" id="venue" name="venue" required
                value="{{ old('venue', $event->venue ?? 'UP Cebu Lahug Campus') }}"
                placeholder="e.g. TIC Innovation Lab, 3rd Floor"
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
        </div>
    </div>

    <!-- Row 4: Summary -->
    <div>
        <label for="summary" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
            Event Summary / Highlight <span class="text-maroon-base">*</span>
        </label>
        <textarea id="summary" name="summary" rows="3" required
            placeholder="A short overview describing the purpose, target audience and key topics..."
            class="w-full px-4 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">{{ old('summary', $event->summary) }}</textarea>
    </div>

    <!-- Row 5: Description & Registration URL -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="registration_url" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Registration URL / Form Link
            </label>
            <input type="text" id="registration_url" name="registration_url"
                value="{{ old('registration_url', $event->registration_url ?? '#contact') }}"
                placeholder="#contact or https://forms.gle/..."
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
        </div>

        <div class="flex items-center pt-5">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}
                    class="rounded border-border-card text-gold-base focus:ring-gold-base h-4 w-4">
                <span class="text-xs font-bold text-ink-base">Flagship / Featured Summit (Hero Card)</span>
            </label>
        </div>
    </div>

    <div>
        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
            Detailed Description / Schedule (Optional)
        </label>
        <textarea id="description" name="description" rows="5"
            placeholder="Additional agenda or registration requirements..."
            class="w-full px-4 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none font-mono transition-colors">{{ old('description', $event->description) }}</textarea>
    </div>

    <!-- Submit Bar -->
    <div class="pt-4 border-t border-border-card flex items-center justify-end gap-3">
        <a href="{{ route('admin.events.index') }}"
            class="px-5 py-2.5 rounded-lg border border-border-card text-xs font-semibold text-ink-muted hover:bg-cream-soft transition-colors">
            Cancel
        </a>
        <button type="submit"
            class="px-6 py-2.5 rounded-lg bg-green-base hover:bg-green-hover text-white text-xs sm:text-sm font-bold shadow-xs transition-all cursor-pointer">
            {{ $isEdit ? 'Save Changes' : 'Schedule Event' }}
        </button>
    </div>
</form>
@endsection
