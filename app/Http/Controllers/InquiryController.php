<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'contact_number' => 'nullable|string|max:30',
            'affiliation' => 'required|string|in:student,faculty,researcher,msme,industry_partner,general_public',
            'inquiry_type' => 'required|string|in:ip_protection,business_incubation,simp_mentorship,licensing,msme_support,press_media,general',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        $validated['ip_address'] = $request->ip();
        $validated['status'] = 'pending';

        $inquiry = Inquiry::create($validated);

        $successMessage = 'Thank you for reaching out! Your inquiry has been submitted successfully to the TTBDO team. We will review your request and get back to you shortly.';

        if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'inquiry_id' => $inquiry->id,
            ]);
        }

        return redirect()->to(url()->previous() . '#inquire')->with([
            'submission_status' => 'success',
            'submission_message' => $successMessage,
        ]);
    }
}
