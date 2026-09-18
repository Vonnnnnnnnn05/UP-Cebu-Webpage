@extends('admin.layouts.admin')

@section('title', 'Manage Events & Summits')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
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

<!-- Filter Bar -->
<div class="bg-white border border-border-card rounded-2xl p-4 sm:p-5 shadow-xs mb-6">
    <form action="{{ route('admin.events.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
        <div class="relative flex-1 w-full">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events by title, venue or topic..."
                class="w-full pl-9 pr-4 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
            <svg class="w-4 h-4 text-ink-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </div>

        <select name="status" onchange="this.form.submit()"
            class="w-full sm:w-48 px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">
            <option value="">All Statuses</option>
            <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <button type="submit"
            class="px-4 py-2 bg-green-base hover:bg-green-hover text-white text-xs font-bold rounded-lg transition-colors w-full sm:w-auto">
            Filter
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.events.index') }}" class="text-xs text-maroon-base hover:underline self-center">Clear</a>
        @endif
    </form>
</div>

<!-- Events Table -->
<div class="bg-white border border-border-card rounded-2xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-cream-soft border-b border-border-card text-ink-muted uppercase text-[10px] tracking-wider font-bold">
                <tr>
                    <th class="py-3.5 px-4 sm:px-6">Event Title &amp; Category</th>
                    <th class="py-3.5 px-4">Date &amp; Time</th>
                    <th class="py-3.5 px-4">Venue</th>
                    <th class="py-3.5 px-4 text-center">Featured</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-card/60">
                @forelse ($eventsList as $event)
                <tr class="hover:bg-cream-soft/50 transition-colors">
                    <td class="py-4 px-4 sm:px-6">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-green-base/10 text-green-base text-[9.5px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-full">
                                {{ $event->category }}
                            </span>
                            <span class="bg-gold-light text-gold-deep text-[9px] font-bold tracking-wider uppercase px-1.5 py-0.5 rounded">
                                {{ $event->venue_type }}
                            </span>
                        </div>
                        <span class="font-bold text-ink-base block text-sm leading-snug">{{ $event->title }}</span>
                        <p class="text-[11px] text-ink-muted line-clamp-1 mt-0.5">{{ $event->summary }}</p>
                    </td>
                    <td class="py-4 px-4 whitespace-nowrap">
                        <span class="font-semibold text-ink-base block">{{ $event->event_date ? $event->event_date->format('M d, Y') : '' }}</span>
                        <span class="text-[10px] text-ink-muted">{{ $event->start_time }} – {{ $event->end_time }}</span>
                    </td>
                    <td class="py-4 px-4 text-ink-muted text-xs">
                        {{ $event->venue }}
                    </td>
                    <td class="py-4 px-4 text-center">
                        <form action="{{ route('admin.events.toggle-featured', $event->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Click to toggle featured status"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition-all cursor-pointer {{ $event->is_featured ? 'bg-gold-base text-maroon-dark shadow-2xs' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                <svg class="w-3 h-3 {{ $event->is_featured ? 'fill-current text-maroon-dark' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                </svg>
                                <span>{{ $event->is_featured ? 'Featured' : 'Standard' }}</span>
                            </button>
                        </form>
                    </td>
                    <td class="py-4 px-4 whitespace-nowrap">
                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full {{ $event->status === 'upcoming' ? 'bg-green-100 text-green-800' : ($event->status === 'completed' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-800') }}">
                            {{ $event->status }}
                        </span>
                    </td>
                    <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.events.edit', $event->id) }}"
                                class="p-1.5 rounded-lg bg-cream-soft hover:bg-gold-base/20 text-ink-base transition-colors" title="Edit Event">
                                <svg class="w-4 h-4 text-maroon-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>

                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event, 'event')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer" title="Delete Event">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 px-6 text-center text-ink-muted">
                        No events found matching your filter.
                    </td>
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

<script>
function confirmDelete(e, type) {
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
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
</script>
@endsection
