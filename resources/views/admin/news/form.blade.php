@extends('admin.layouts.admin')

@section('title', $isEdit ? 'Edit News Article' : 'Post New Article')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <div class="flex items-center gap-2 text-xs font-bold text-ink-muted mb-1">
            <a href="{{ route('admin.news.index') }}" class="hover:text-maroon-base">News &amp; Research</a>
            <span>&rsaquo;</span>
            <span class="text-maroon-base">{{ $isEdit ? 'Edit Article' : 'New Article' }}</span>
        </div>
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-tight">
            {{ $isEdit ? 'Edit Article' : 'Post News & Research Announcement' }}
        </h1>
    </div>
    <a href="{{ route('admin.news.index') }}"
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

<form action="{{ $isEdit ? route('admin.news.update', $news->id) : route('admin.news.store') }}" method="POST"
    class="bg-white border border-border-card rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <!-- Row 1: Title -->
    <div>
        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
            Article Headline / Title <span class="text-maroon-base">*</span>
        </label>
        <input type="text" id="title" name="title" required
            value="{{ old('title', $news->title) }}"
            placeholder="e.g. UP Cebu and DOST Regional Office VII Formalize Partnership"
            class="w-full px-4 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors font-medium" />
    </div>

    <!-- Row 2: Category, Badge, Date, Author -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label for="category" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Category <span class="text-maroon-base">*</span>
            </label>
            <select id="category" name="category" required
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">
                @php $cat = old('category', $news->category); @endphp
                <option value="PARTNERSHIP" {{ $cat === 'PARTNERSHIP' ? 'selected' : '' }}>Partnership</option>
                <option value="CALL FOR PROPOSALS" {{ $cat === 'CALL FOR PROPOSALS' ? 'selected' : '' }}>Call for Proposals</option>
                <option value="PATENT & IP" {{ $cat === 'PATENT & IP' ? 'selected' : '' }}>Patent &amp; IP</option>
                <option value="CAPACITY BUILDING" {{ $cat === 'CAPACITY BUILDING' ? 'selected' : '' }}>Capacity Building</option>
                <option value="ANNOUNCEMENT" {{ $cat === 'ANNOUNCEMENT' ? 'selected' : '' }}>Announcement</option>
                <option value="MILESTONE" {{ $cat === 'MILESTONE' ? 'selected' : '' }}>Milestone</option>
            </select>
        </div>

        <div>
            <label for="badge_label" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Badge Label
            </label>
            <input type="text" id="badge_label" name="badge_label"
                value="{{ old('badge_label', $news->badge_label) }}"
                placeholder="e.g. FEATURED STORY, GRANT CALL, NEW"
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
        </div>

        <div>
            <label for="published_date" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Published Date <span class="text-maroon-base">*</span>
            </label>
            <input type="date" id="published_date" name="published_date" required
                value="{{ old('published_date', $news->published_date ? $news->published_date->format('Y-m-d') : date('Y-m-d')) }}"
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
        </div>

        <div>
            <label for="author" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                Author / Office <span class="text-maroon-base">*</span>
            </label>
            <input type="text" id="author" name="author" required
                value="{{ old('author', $news->author ?? 'TTBDO Media Communications') }}"
                placeholder="e.g. TTBDO Media Communications"
                class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
        </div>
    </div>

    <!-- Row 3: Summary Lead -->
    <div>
        <label for="summary" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
            Brief Summary / Lead Text <span class="text-maroon-base">*</span>
        </label>
        <textarea id="summary" name="summary" rows="3" required
            placeholder="A concise 1-2 sentence lead summarizing the story for card previews and modals..."
            class="w-full px-4 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">{{ old('summary', $news->summary) }}</textarea>
    </div>

    <!-- Row 4: Full Article Content -->
    <div>
        <label for="content" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
            Full Article Body (HTML Supported)
        </label>
        <textarea id="content" name="content" rows="7"
            placeholder="<p>Full article paragraphs, quotes, and research details displayed inside the modal reader...</p>"
            class="w-full px-4 py-2.5 bg-cream-soft border border-border-card rounded-xl text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none font-mono transition-colors">{{ old('content', $news->content) }}</textarea>
    </div>

    <!-- Row 5: Metrics Badges (Optional Highlight Metrics) -->
    <div class="p-4 rounded-xl bg-cream-soft border border-border-card">
        <span class="text-xs font-bold uppercase text-ink-base block mb-3">Optional Metric Badges (For Article Modal Reader)</span>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="startups_supported" class="block text-[11px] font-semibold text-ink-muted mb-1">Startups Assisted</label>
                <input type="text" id="startups_supported" name="startups_supported"
                    value="{{ old('startups_supported', $news->startups_supported) }}"
                    placeholder="e.g. 12 Startups Assisted"
                    class="w-full px-3 py-2 bg-white border border-border-card rounded-lg text-xs text-ink-base focus:border-maroon-base focus:outline-none" />
            </div>
            <div>
                <label for="meeting_focus" class="block text-[11px] font-semibold text-ink-muted mb-1">Meeting / Track Focus</label>
                <input type="text" id="meeting_focus" name="meeting_focus"
                    value="{{ old('meeting_focus', $news->meeting_focus) }}"
                    placeholder="e.g. Innovation Hub & Prototyping"
                    class="w-full px-3 py-2 bg-white border border-border-card rounded-lg text-xs text-ink-base focus:border-maroon-base focus:outline-none" />
            </div>
            <div>
                <label for="coverage_area" class="block text-[11px] font-semibold text-ink-muted mb-1">Coverage Area / Region</label>
                <input type="text" id="coverage_area" name="coverage_area"
                    value="{{ old('coverage_area', $news->coverage_area) }}"
                    placeholder="e.g. Cebu & Region VII"
                    class="w-full px-3 py-2 bg-white border border-border-card rounded-lg text-xs text-ink-base focus:border-maroon-base focus:outline-none" />
            </div>
        </div>
    </div>

    <!-- Row 6: Featured & Published Status -->
    <div class="flex flex-wrap items-center gap-6 pt-2">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}
                class="rounded border-border-card text-gold-base focus:ring-gold-base h-4 w-4">
            <span class="text-xs font-bold text-ink-base">Featured on Homepage Hero Column</span>
        </label>

        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $news->is_published ?? true) ? 'checked' : '' }}
                class="rounded border-border-card text-green-base focus:ring-green-base h-4 w-4">
            <span class="text-xs font-bold text-ink-base">Publish publicly</span>
        </label>
    </div>

    <!-- Submit Bar -->
    <div class="pt-4 border-t border-border-card flex items-center justify-end gap-3">
        <a href="{{ route('admin.news.index') }}"
            class="px-5 py-2.5 rounded-lg border border-border-card text-xs font-semibold text-ink-muted hover:bg-cream-soft transition-colors">
            Cancel
        </a>
        <button type="submit"
            class="px-6 py-2.5 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-xs sm:text-sm font-bold shadow-xs transition-all cursor-pointer">
            {{ $isEdit ? 'Save Changes' : 'Publish Article' }}
        </button>
    </div>
</form>
@endsection
