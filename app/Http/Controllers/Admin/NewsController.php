<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->query('search', ''));
        $category = trim($request->query('category', ''));

        $newsList = News::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('summary', 'like', "%{$search}%");
                });
            })
            ->when($category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->latest('published_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.news.index', compact('newsList', 'search', 'category'));
    }

    public function create(): View
    {
        return view('admin.news.form', [
            'news' => new News([
                'category' => 'PARTNERSHIP',
                'badge_label' => 'NEW',
                'published_date' => now()->format('Y-m-d'),
                'author' => 'TTBDO Media Communications',
                'is_published' => true,
                'is_featured' => false,
            ]),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'badge_label' => 'nullable|string|max:50',
            'published_date' => 'required|date',
            'author' => 'required|string|max:100',
            'summary' => 'required|string',
            'content' => 'nullable|string',
            'startups_supported' => 'nullable|string|max:50',
            'meeting_focus' => 'nullable|string|max:100',
            'coverage_area' => 'nullable|string|max:100',
            'image_url' => 'nullable|string|max:255',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');
        $validated['slug'] = News::generateUniqueSlug($validated['title']);

        if ($validated['is_featured']) {
            News::where('is_featured', true)->update(['is_featured' => false]);
        }

        if (empty($validated['image_url'])) {
            $validated['image_url'] = 'assets/hero-campus.jpg';
        }

        News::create($validated);

        return redirect()->route('admin.news.index')->with('success_message', 'News article posted successfully.');
    }

    public function edit(News $news): View
    {
        return view('admin.news.form', [
            'news' => $news,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'badge_label' => 'nullable|string|max:50',
            'published_date' => 'required|date',
            'author' => 'required|string|max:100',
            'summary' => 'required|string',
            'content' => 'nullable|string',
            'startups_supported' => 'nullable|string|max:50',
            'meeting_focus' => 'nullable|string|max:100',
            'coverage_area' => 'nullable|string|max:100',
            'image_url' => 'nullable|string|max:255',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');

        if ($news->title !== $validated['title']) {
            $validated['slug'] = News::generateUniqueSlug($validated['title'], $news->id);
        }

        if ($validated['is_featured']) {
            News::where('id', '!=', $news->id)->where('is_featured', true)->update(['is_featured' => false]);
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success_message', 'News article updated successfully.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success_message', 'News article deleted successfully.');
    }

    public function toggleFeatured(News $news): RedirectResponse
    {
        $newState = !$news->is_featured;

        if ($newState) {
            News::where('is_featured', true)->update(['is_featured' => false]);
        }

        $news->update(['is_featured' => $newState]);

        return redirect()->back()->with('success_message', 'Featured status updated.');
    }
}
