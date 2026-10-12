@extends('layouts.app')

@section('title', $event->title . ' | TTBDO Activities')
@section('description', $event->summary ?: ($event->title . ' - ' . $event->category))

@section('content')
<main class="min-h-screen bg-cream-bg py-8 sm:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ── Clean Top Bar (Back to Calendar + Minimal Brand) ── --}}
        <div class="mb-6 flex items-center justify-between gap-4">
            <a href="{{ url('/') }}#events"
                class="inline-flex items-center gap-2 text-xs font-bold text-ink-muted hover:text-maroon-base transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                <span>Back to Activities Calendar</span>
            </a>

            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-bold text-maroon-base hover:text-maroon-hover transition-colors group" title="Return to UP Cebu TTBDO Homepage">
                <img src="{{ asset('assets/ttbdo-full-logo.png') }}" alt="UP Cebu TTBDO Logo" class="h-6 w-auto object-contain transition-transform group-hover:scale-105" />
                <span class="hidden sm:inline font-serif font-bold text-ink-base group-hover:text-maroon-base transition-colors">UP Cebu TTBDO</span>
            </a>
        </div>

        {{-- ── Event Banner / Cover Photo (if present) ── --}}
        @if($event->image_url)
        <div class="mb-6 overflow-hidden rounded-2xl border border-border-card shadow-xs bg-black/5">
            <img src="{{ asset($event->image_url) }}" alt="{{ $event->title }}" class="w-full max-h-80 sm:max-h-96 object-cover">
        </div>
        @endif

        {{-- ── Event Header Card ── --}}
        <div class="bg-white border border-border-card rounded-2xl p-6 sm:p-8 shadow-xs mb-8">

            {{-- Badges / Category --}}
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="bg-maroon-base text-white text-[11px] font-bold tracking-wider uppercase px-3 py-1 rounded-full">
                    {{ $event->category }}
                </span>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full
                    {{ $event->status === 'upcoming'  ? 'bg-green-100 text-green-800'  :
                      ($event->status === 'completed' ? 'bg-gray-100 text-gray-600'   :
                                                        'bg-red-100 text-red-700') }}">
                    {{ ucfirst($event->status) }}
                </span>
                @if($event->badge_label)
                <span class="bg-gold-light text-gold-deep text-[10px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full">
                    {{ $event->badge_label }}
                </span>
                @endif
            </div>

            {{-- Event Title --}}
            <h1 class="font-serif text-2xl sm:text-3xl lg:text-4xl font-bold text-maroon-base leading-tight mb-4">
                {{ $event->title }}
            </h1>

            {{-- Key Information Line --}}
            <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-xs sm:text-sm text-ink-muted border-t border-border-card/60 pt-4">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gold-deep shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                    </svg>
                    <span class="font-semibold text-ink-base">
                        {{ $event->formatted_date_range }}
                    </span>
                </div>

                @if($event->venue)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-maroon-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    <span>{{ $event->venue }}</span>
                </div>
                @endif
            </div>

            @if($event->summary)
            <div class="mt-4 pt-4 border-t border-border-card/40 text-xs sm:text-sm text-ink-muted leading-relaxed">
                {{ $event->summary }}
            </div>
            @endif


        </div>

        {{-- ── Daily Detailed Activity Information (Chronological) ── --}}
        @if($event->logs->isNotEmpty())
        <div class="mb-10">
            <div class="flex items-center gap-2.5 mb-6">
                <span class="inline-block w-6 h-0.5 bg-maroon-base rounded-full"></span>
                <h2 class="font-serif text-xl sm:text-2xl font-bold text-maroon-base">
                    Daily Activity Documentation
                </h2>
                <span class="text-xs font-semibold text-ink-muted bg-white border border-border-card rounded-full px-2.5 py-0.5 ml-auto">
                    {{ $event->logs->count() }} {{ Str::plural('Entry', $event->logs->count()) }}
                </span>
            </div>

            <div class="relative">
                {{-- Connecting vertical line --}}
                <div class="absolute left-4 sm:left-5 top-5 bottom-5 w-0.5 bg-border-card rounded-full" aria-hidden="true"></div>

                <div class="space-y-6">
                    @foreach($event->logs as $index => $detail)
                    <div class="relative flex items-start gap-4 sm:gap-6">
                        {{-- Timeline Day Number Marker --}}
                        <div class="shrink-0 w-8 sm:w-10 h-8 sm:h-10 rounded-full bg-maroon-base text-white border-4 border-cream-bg flex flex-col items-center justify-center shadow-xs z-10">
                            <span class="text-[9px] sm:text-[10px] font-bold leading-none">D{{ $index + 1 }}</span>
                        </div>

                        {{-- Day Details Card --}}
                        <div class="flex-1 bg-white border border-border-card rounded-2xl p-5 sm:p-6 shadow-xs hover:border-maroon-base/40 transition-colors">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-maroon-base bg-maroon-base/8 px-2.5 py-1 rounded-md">
                                        {{ $detail->log_date ? $detail->log_date->format('F d, Y') : '' }}
                                    </span>
                                    <span class="text-[11px] font-medium text-ink-muted">
                                        {{ $detail->log_date ? $detail->log_date->format('l') : '' }}
                                    </span>
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-ink-muted/70">
                                    Day {{ $index + 1 }}
                                </span>
                            </div>

                            <h3 class="font-serif text-base sm:text-lg font-bold text-ink-base leading-snug mb-3">
                                {{ $detail->title }}
                            </h3>

                            @if($detail->description)
                            <div class="text-xs sm:text-[13px] leading-relaxed text-ink-base/80 whitespace-pre-line border-t border-border-card/40 pt-3">
                                {{ $detail->description }}
                            </div>
                            @endif

                            @if($detail->image_path)
                            <div class="mt-4 pt-3 border-t border-border-card/40">
                                <div class="overflow-hidden rounded-xl border border-border-card/70 shadow-2xs group bg-black/5">
                                    <img src="{{ asset($detail->image_path) }}" alt="{{ $detail->title }}"
                                        class="w-full max-h-80 sm:max-h-96 object-cover transition-transform duration-300 group-hover:scale-[1.01]" />
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="bg-white border border-border-card rounded-2xl p-8 text-center text-ink-muted text-sm shadow-xs mb-8">
            <svg class="w-10 h-10 mx-auto mb-2 text-border-card" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
            </svg>
            <p class="font-medium text-ink-base">Detailed daily activity breakdown has not been added yet.</p>
            <p class="text-xs text-ink-muted mt-1">This activity is registered on the TTBDO calendar.</p>
        </div>
        @endif

        {{-- ── Bottom Navigation ── --}}
        <div class="pt-4 border-t border-border-card flex items-center justify-between">
            <a href="{{ url('/') }}#events"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-border-card text-xs font-bold text-ink-base hover:bg-maroon-base hover:text-white hover:border-maroon-base transition-colors shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                <span>Back to Monthly Calendar</span>
            </a>

            @if(Auth::guard('admin')->check())
            <a href="{{ route('admin.events.edit', $event->id) }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gold-light border border-gold-base/40 text-gold-deep text-xs font-bold hover:bg-gold-base hover:text-maroon-dark transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                </svg>
                <span>Edit as Admin</span>
            </a>
            @endif
        </div>

    </div>
</main>

@include('partials.contact')
@endsection
