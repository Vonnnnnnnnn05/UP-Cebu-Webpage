<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InquiryApprovedMail;
use App\Mail\InquiryDeclinedMail;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $status = trim($request->query('status', 'all'));
        $search = trim($request->query('search', ''));

        $allowedStatuses = ['pending', 'approved', 'declined', 'rejected', 'in_review', 'reviewed', 'resolved', 'archived'];

        $inquiriesList = Inquiry::query()
            ->with(['reviewer', 'approver'])
            ->when($status !== 'all' && in_array($status, $allowedStatuses), function ($query) use ($status) {
                if (in_array($status, ['declined', 'rejected'])) {
                    $query->whereIn('status', ['declined', 'rejected']);
                } else {
                    $query->where('status', $status);
                }
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('affiliation', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $selectedInquiry = null;
        if ($request->has('id')) {
            $selectedInquiry = Inquiry::with(['reviewer', 'approver'])->find($request->query('id'));
        }

        return view('admin.inquiries.index', compact('inquiriesList', 'status', 'search', 'selectedInquiry'));
    }

    public function show(Inquiry $inquiry): JsonResponse
    {
        $inquiry->load(['reviewer', 'approver']);
        return response()->json($inquiry);
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'full_name'         => 'sometimes|required|string|max:100',
            'email'             => 'sometimes|required|email|max:100',
            'contact_number'    => 'nullable|string|max:30',
            'affiliation'       => 'nullable|string|max:100',
            'inquiry_type'      => 'nullable|string|max:100',
            'subject'           => 'sometimes|required|string|max:200',
            'message'           => 'sometimes|required|string|max:10000',
            'status'            => 'required|string|in:pending,approved,declined,rejected,in_review,reviewed,resolved,archived',
            'admin_notes'       => 'nullable|string|max:5000',
            'director_comments' => 'nullable|string|max:5000',
        ]);

        $currentUser = Auth::guard('admin')->user();
        $oldStatus = $inquiry->status;

        // If Director submits review comments
        if ($request->filled('director_comments') && $currentUser && $currentUser->canReview()) {
            $validated['director_comments'] = $request->input('director_comments');
            $validated['reviewed_by']       = $currentUser->id;
            $validated['reviewed_at']       = now();
        }

        // If status is approved
        if ($validated['status'] === 'approved') {
            $validated['approved_by'] = $currentUser ? $currentUser->id : null;
            $validated['approved_at'] = now();
        }

        $inquiry->update($validated);

        // Send email notifications on approval or decline
        $this->sendStatusNotification($inquiry, $oldStatus, $validated['status']);

        $message = match ($validated['status']) {
            'approved' => 'Inquiry record updated and approval email sent to ' . $inquiry->email . '.',
            'declined' => 'Inquiry record updated and notification email sent to ' . $inquiry->email . '.',
            default    => 'Inquiry record updated successfully.',
        };

        return redirect()->back()->with('success_message', $message);
    }

    /**
     * Director reviews the request and comments on what is wrong or needs adjustment.
     */
    public function review(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();
        if ($currentUser && !$currentUser->canReview()) {
            return redirect()->back()->with('error_message', 'Only the Director or Admin can submit reviews.');
        }

        $request->validate([
            'director_comments' => 'nullable|string|max:5000',
            'review_decision'   => 'required|string|in:reviewed,rejected,in_review',
        ]);

        $inquiry->update([
            'director_comments' => $request->input('director_comments'),
            'status'            => $request->input('review_decision'),
            'reviewed_by'       => $currentUser->id,
            'reviewed_at'       => now(),
        ]);

        return redirect()->back()->with('success_message', 'Director review and comments recorded successfully.');
    }

    /**
     * Admin approves the booking request.
     */
    public function approve(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();
        $oldStatus = $inquiry->status;

        $inquiry->update([
            'status'      => 'approved',
            'approved_by' => $currentUser?->id,
            'approved_at' => now(),
            'admin_notes' => $request->input('admin_notes', $inquiry->admin_notes),
        ]);

        $this->sendStatusNotification($inquiry, $oldStatus, 'approved');

        return redirect()->back()->with('success_message', 'Inquiry request has been officially approved and notification email sent.');
    }

    /**
     * Staff or Admin declines the inquiry request.
     */
    public function decline(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $oldStatus = $inquiry->status;

        $inquiry->update([
            'status'      => 'declined',
            'admin_notes' => $request->input('admin_notes', $inquiry->admin_notes),
        ]);

        $this->sendStatusNotification($inquiry, $oldStatus, 'declined');

        return redirect()->back()->with('success_message', 'Inquiry request has been marked as Declined and notification email sent.');
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();
        return redirect()->back()->with('success_message', 'Inquiry record deleted successfully.');
    }

    /**
     * Dispatch email notification when inquiry status transitions to approved or declined.
     */
    private function sendStatusNotification(Inquiry $inquiry, ?string $oldStatus, string $newStatus): void
    {
        if ($oldStatus === $newStatus) {
            return;
        }

        if (empty($inquiry->email)) {
            return;
        }

        try {
            if ($newStatus === 'approved') {
                Mail::to($inquiry->email)->send(new InquiryApprovedMail($inquiry));
            } elseif ($newStatus === 'declined' || $newStatus === 'rejected') {
                Mail::to($inquiry->email)->send(new InquiryDeclinedMail($inquiry));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed sending inquiry status email to ' . $inquiry->email . ': ' . $e->getMessage());
        }
    }
}
