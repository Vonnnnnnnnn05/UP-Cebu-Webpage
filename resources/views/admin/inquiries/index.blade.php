@extends('admin.layouts.admin')

@section('title', 'Manage Inquiries & Booking Requests')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-tight">
            Consultation &amp; Activity Inquiries
        </h1>
        <p class="text-xs sm:text-sm text-ink-muted mt-1">
            Review incoming activity booking inquiries, technology transfer questions, and partnership proposals.
        </p>
    </div>
</div>

<!-- Workflow Filter Tabs -->
<div class="flex items-center gap-2 overflow-x-auto pb-2 mb-4 text-xs font-semibold scrollbar-none">
    <a href="{{ route('admin.inquiries.index', ['status' => 'all', 'search' => request('search')]) }}"
        class="px-3.5 py-1.5 rounded-full whitespace-nowrap transition-colors {{ $status === 'all' ? 'bg-maroon-base text-white font-bold' : 'bg-white border border-border-card text-ink-muted hover:bg-cream-soft' }}">
        All Inquiries
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'pending', 'search' => request('search')]) }}"
        class="px-3.5 py-1.5 rounded-full whitespace-nowrap transition-colors {{ $status === 'pending' ? 'bg-amber-600 text-white font-bold' : 'bg-white border border-border-card text-ink-muted hover:bg-cream-soft' }}">
        Pending
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'approved', 'search' => request('search')]) }}"
        class="px-3.5 py-1.5 rounded-full whitespace-nowrap transition-colors {{ $status === 'approved' ? 'bg-green-700 text-white font-bold' : 'bg-white border border-border-card text-ink-muted hover:bg-cream-soft' }}">
        Approved
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'declined', 'search' => request('search')]) }}"
        class="px-3.5 py-1.5 rounded-full whitespace-nowrap transition-colors {{ in_array($status, ['declined', 'rejected']) ? 'bg-red-700 text-white font-bold' : 'bg-white border border-border-card text-ink-muted hover:bg-cream-soft' }}">
        Declined
    </a>
</div>

<!-- Search Input -->
<div class="bg-white border border-border-card rounded-2xl p-4 shadow-xs mb-6">
    <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex items-center gap-3">
        <input type="hidden" name="status" value="{{ $status }}">
        <div class="relative flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, organization, subject, email, or message..."
                class="w-full pl-9 pr-4 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
            <svg class="w-4 h-4 text-ink-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </div>
        <button type="submit" class="px-4 py-2 bg-maroon-base hover:bg-maroon-hover text-white text-xs font-bold rounded-lg transition-colors cursor-pointer">
            Search
        </button>
        @if(request('search'))
        <a href="{{ route('admin.inquiries.index', ['status' => $status]) }}" class="text-xs text-maroon-base hover:underline">Clear</a>
        @endif
    </form>
</div>

<!-- Inquiries Table -->
<div class="bg-white border border-border-card rounded-2xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs sm:text-sm">
            <thead>
                <tr class="border-b border-border-card bg-cream-soft text-ink-muted uppercase font-bold text-[10.5px] tracking-wider">
                    <th class="py-3.5 px-4 sm:px-6">Sender &amp; Org</th>
                    <th class="py-3.5 px-4">Subject &amp; Category</th>
                    <th class="py-3.5 px-4">Inquiry Status</th>
                    <th class="py-3.5 px-4">Director / Notes</th>
                    <th class="py-3.5 px-4">Received</th>
                    <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-card/60">
                @forelse ($inquiriesList as $inq)
                <tr class="hover:bg-cream-soft/50 transition-colors {{ $inq->status === 'pending' ? 'bg-amber-50/40' : '' }}">
                    <td class="py-4 px-4 sm:px-6">
                        <span class="font-bold text-ink-base block text-sm">{{ $inq->full_name }}</span>
                        <span class="text-[11px] text-ink-muted block">{{ $inq->email }}</span>
                        <span class="text-[10px] text-green-base font-semibold uppercase tracking-wider block mt-0.5">
                            {{ $inq->affiliation }}
                        </span>
                    </td>
                    <td class="py-4 px-4 max-w-xs">
                        <span class="bg-maroon-base/10 text-maroon-base text-[9.5px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-full inline-block mb-1">
                            {{ $inq->inquiry_type }}
                        </span>
                        <span class="font-bold text-ink-base block text-xs leading-snug line-clamp-1">{{ $inq->subject }}</span>
                        <p class="text-[11px] text-ink-muted line-clamp-1 mt-0.5">{{ $inq->message }}</p>
                    </td>
                    <td class="py-4 px-4 whitespace-nowrap">
                        @php
                            $badgeClass = match($inq->status) {
                                'pending'              => 'bg-amber-100 text-amber-800 border-amber-300',
                                'approved'             => 'bg-green-100 text-green-800 border-green-300',
                                'rejected', 'declined' => 'bg-red-100 text-red-800 border-red-300',
                                default                => 'bg-gray-100 text-gray-700 border-gray-300',
                            };
                            $badgeLabel = match($inq->status) {
                                'pending'              => 'Pending',
                                'approved'             => 'Approved',
                                'rejected', 'declined' => 'Declined',
                                default                => ucfirst(str_replace('_', ' ', $inq->status)),
                            };
                        @endphp
                        <span class="text-[10px] uppercase font-bold px-2.5 py-1 rounded-full border {{ $badgeClass }}">
                            {{ $badgeLabel }}
                        </span>
                    </td>
                    <td class="py-4 px-4 max-w-[200px]">
                        @if ($inq->director_comments)
                            <div class="text-[10.5px] text-amber-900 bg-amber-50 border border-amber-200 rounded px-2 py-1 line-clamp-2" title="Director Feedback: {{ $inq->director_comments }}">
                                <span class="font-bold block text-[9.5px] uppercase text-maroon-base">Director Note:</span>
                                {{ $inq->director_comments }}
                            </div>
                        @elseif ($inq->admin_notes)
                            <span class="text-[11px] text-ink-muted line-clamp-2 italic">{{ $inq->admin_notes }}</span>
                        @else
                            <span class="text-[10px] text-ink-muted/50 italic">No notes yet</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 whitespace-nowrap text-ink-muted text-xs">
                        {{ $inq->created_at ? $inq->created_at->format('M d, Y') : '' }}
                        <span class="block text-[10px] text-ink-muted/70">{{ $inq->created_at ? $inq->created_at->format('h:i A') : '' }}</span>
                    </td>
                    <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" onclick="openInquiryModal({{ json_encode($inq) }})"
                                class="px-3 py-1.5 rounded-lg bg-cream-soft hover:bg-gold-base/20 text-maroon-base font-bold text-xs transition-colors cursor-pointer">
                                View Inquiry
                            </button>

                            @if (Auth::guard('admin')->user()?->canEdit())
                            <form action="{{ route('admin.inquiries.destroy', $inq->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer" title="Delete Inquiry">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 px-6 text-center text-ink-muted">
                        No inquiries found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($inquiriesList->hasPages())
    <div class="p-4 border-t border-border-card bg-cream-soft">
        {{ $inquiriesList->links() }}
    </div>
    @endif
</div>

<!-- Inquiry Detail & Review Workflow Modal -->
<div id="inquiry-detail-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-border-card w-full max-w-2xl max-h-[92vh] overflow-y-auto">
        <div class="p-5 border-b border-border-card bg-cream-soft flex items-center justify-between">
            <div>
                <span id="modal-inq-category" class="bg-maroon-base text-white text-[9.5px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">
                    Category
                </span>
                <h3 id="modal-inq-subject" class="font-serif text-lg font-bold text-maroon-base mt-1.5 leading-snug">
                    Subject Line
                </h3>
            </div>
            <button type="button" onclick="closeInquiryModal()" class="p-1.5 rounded-lg text-ink-muted hover:bg-black/5 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-4">
            <!-- Sender Info Grid (Read-Only) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 p-3.5 bg-cream-soft rounded-xl border border-border-card text-xs">
                <div>
                    <span class="text-[10px] uppercase font-bold text-ink-muted block">Full Name</span>
                    <strong id="modal-inq-name" class="text-ink-base block text-sm"></strong>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-ink-muted block">Organization</span>
                    <span id="modal-inq-org" class="text-ink-base font-semibold block"></span>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-ink-muted block">Email Address</span>
                    <span id="modal-inq-email" class="text-ink-base break-all"></span>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-ink-muted block">Contact No.</span>
                    <span id="modal-inq-phone" class="text-ink-base"></span>
                </div>
            </div>

            <!-- Submitted Proposal & Message (Read-Only) -->
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-ink-base block mb-1.5">Submitted Proposal &amp; Message</span>
                <div class="p-4 bg-cream-soft/70 rounded-xl border border-border-card text-xs sm:text-sm text-ink-base leading-relaxed whitespace-pre-wrap" id="modal-inq-message">
                </div>
            </div>

            <!-- Existing Director Feedback Display -->
            <div id="modal-director-feedback-box" class="p-3.5 rounded-xl bg-amber-50 border border-amber-300 text-xs hidden">
                <div class="flex items-center gap-1.5 text-amber-900 font-bold uppercase tracking-wider text-[10.5px] mb-1">
                    <svg class="w-4 h-4 text-gold-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                    <span>Director Review Feedback / Required Revisions:</span>
                </div>
                <p id="modal-director-feedback-text" class="text-ink-base leading-relaxed pl-5 whitespace-pre-wrap"></p>
            </div>

            <!-- DIRECTOR REVIEW FORM (For Director or Admin) -->
            @if (Auth::guard('admin')->user()?->canReview())
            <div class="p-4 rounded-xl border border-gold-base/50 bg-gold-light/20 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-maroon-base flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-gold-base"></span>
                        Director Review &amp; Comments
                    </span>
                    <span class="text-[10px] text-ink-muted font-semibold">Comment on what needs adjustment or next steps</span>
                </div>

                <form id="inquiry-director-review-form" method="POST" action="" class="space-y-3">
                    @csrf
                    <div>
                        <textarea id="director-comment-input" name="director_comments" rows="2"
                            placeholder="Write review comments or notes on what's wrong / what needs adjustment before approval..."
                            class="w-full px-3.5 py-2 bg-white border border-border-card rounded-lg text-xs text-ink-base focus:border-maroon-base focus:outline-none"></textarea>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <button type="submit" name="review_decision" value="rejected"
                            class="px-3.5 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all cursor-pointer">
                            Request Revision / Flag Issue
                        </button>
                        <button type="submit" name="review_decision" value="reviewed"
                            class="px-4 py-1.5 rounded-lg bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold transition-all shadow-2xs cursor-pointer">
                            Mark as Reviewed
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- WORKFLOW STATUS & NOTES FORM -->
            <form id="inquiry-update-form" method="POST" action="" class="pt-3 border-t border-border-card space-y-3">
                @csrf
                @method('PUT')

                <!-- INQUIRY STATUS COMBOBOX -->
                <div>
                    <label for="modal-status-select" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1">
                        Inquiry Status
                    </label>
                    <select id="modal-status-select" name="status" required
                        class="w-full px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="declined">Declined</option>
                    </select>
                    <p class="text-[11px] text-ink-muted mt-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-maroon-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                        <span>Selecting <strong>Approved</strong> or <strong>Declined</strong> sends an automated official email to the sender.</span>
                    </p>
                </div>

                <div>
                    <label for="modal-notes" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1">
                        Internal Staff Notes / Coordination
                    </label>
                    <textarea id="modal-notes" name="admin_notes" rows="2"
                        placeholder="Log consultation findings, coordinator assignments, or follow-up notes here..."
                        class="w-full px-3.5 py-2 bg-cream-soft border border-border-card rounded-lg text-xs text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeInquiryModal()"
                        class="px-4 py-2 rounded-lg border border-border-card text-xs font-semibold text-ink-muted hover:bg-cream-soft cursor-pointer">
                        Close
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                        Update Status &amp; Notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentInquiryId = null;

function openInquiryModal(inq) {
    currentInquiryId = inq.id;
    document.getElementById('modal-inq-category').textContent = inq.inquiry_type ? inq.inquiry_type.replace('_', ' ').toUpperCase() : 'GENERAL';
    document.getElementById('modal-inq-subject').textContent = inq.subject || '';
    document.getElementById('modal-inq-name').textContent = inq.full_name || '';
    document.getElementById('modal-inq-org').textContent = inq.affiliation || 'N/A';
    document.getElementById('modal-inq-email').textContent = inq.email || '';
    document.getElementById('modal-inq-phone').textContent = inq.contact_number || 'N/A';
    document.getElementById('modal-inq-message').textContent = inq.message || '';

    let currentStatus = inq.status || 'pending';
    if (currentStatus === 'rejected') {
        currentStatus = 'declined';
    } else if (!['pending', 'approved', 'declined'].includes(currentStatus)) {
        currentStatus = 'pending';
    }
    document.getElementById('modal-status-select').value = currentStatus;
    document.getElementById('modal-notes').value = inq.admin_notes || '';

    // Director feedback display
    const feedbackBox = document.getElementById('modal-director-feedback-box');
    const feedbackText = document.getElementById('modal-director-feedback-text');
    if (inq.director_comments && inq.director_comments.trim() !== '') {
        feedbackText.textContent = inq.director_comments;
        feedbackBox.classList.remove('hidden');
    } else {
        feedbackBox.classList.add('hidden');
    }

    const directorInput = document.getElementById('director-comment-input');
    if (directorInput) {
        directorInput.value = inq.director_comments || '';
    }

    // Set action URLs
    const updateUrl = "{{ url('admin/inquiries') }}/" + inq.id;
    document.getElementById('inquiry-update-form').action = updateUrl;

    const reviewForm = document.getElementById('inquiry-director-review-form');
    if (reviewForm) {
        reviewForm.action = "{{ url('admin/inquiries') }}/" + inq.id + "/review";
    }

    document.getElementById('inquiry-detail-modal').classList.remove('hidden');
}

function closeInquiryModal() {
    document.getElementById('inquiry-detail-modal').classList.add('hidden');
}

function confirmDelete(e) {
    e.preventDefault();
    const form = e.target;
    Swal.fire({
        title: 'Delete this inquiry?',
        text: 'This inquiry record will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#7B1113',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
</script>
@endsection
