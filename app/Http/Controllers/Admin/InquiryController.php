<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $status = trim($request->query('status', 'all'));
        $search = trim($request->query('search', ''));

        $inquiriesList = Inquiry::query()
            ->when($status !== 'all' && in_array($status, ['pending', 'in_review', 'resolved', 'archived']), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $selectedInquiry = null;
        if ($request->has('id')) {
            $selectedInquiry = Inquiry::find($request->query('id'));
        }

        return view('admin.inquiries.index', compact('inquiriesList', 'status', 'search', 'selectedInquiry'));
    }

    public function show(Inquiry $inquiry): JsonResponse
    {
        return response()->json($inquiry);
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,in_review,resolved,archived',
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $inquiry->update($validated);

        return redirect()->back()->with('success_message', 'Inquiry status updated successfully.');
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();
        return redirect()->back()->with('success_message', 'Inquiry record deleted successfully.');
    }
}
