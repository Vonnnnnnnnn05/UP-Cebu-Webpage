@extends('admin.layouts.admin')

@section('title', 'Inquiries & Consultations')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-maroon-base leading-tight">
            Consultations &amp; Inquiries Inbox
        </h1>
        <p class="text-xs sm:text-sm text-ink-muted mt-1">
            Review incoming research disclosures, MSME requests, and licensing questions.
        </p>
    </div>
</div>

<!-- Status Filter Pills -->
<div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 text-xs font-semibold scrollbar-none">
    <a href="{{ route('admin.inquiries.index', ['status' => 'all', 'search' => request('search')]) }}"
        class="px-3.5 py-1.5 rounded-full whitespace-nowrap transition-colors {{ $status === 'all' ? 'bg-maroon-base text-white font-bold' : 'bg-white border border-border-card text-ink-muted hover:bg-cream-soft' }}">
        All Inquiries
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'pending', 'search' => request('search')]) }}"
        class="px-3.5 py-1.5 rounded-full whitespace-nowrap transition-colors {{ $status === 'pending' ? 'bg-amber-600 text-white font-bold' : 'bg-white border border-border-card text-ink-muted hover:bg-cream-soft' }}">
        Pending
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'in_review', 'search' => request('search')]) }}"
        class="px-3.5 py-1.5 rounded-full whitespace-nowrap transition-colors {{ $status === 'in_review' ? 'bg-blue-600 text-white font-bold' : 'bg-white border border-border-card text-ink-muted hover:bg-cream-soft' }}">
        In Review
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'resolved', 'search' => request('search')]) }}"
        class="px-3.5 py-1.5 rounded-full whitespace-nowrap transition-colors {{ $status === 'resolved' ? 'bg-green-700 text-white font-bold' : 'bg-white border border-border-card text-ink-muted hover:bg-cream-soft' }}">
        Resolved
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'archived', 'search' => request('search')]) }}"
        class="px-3.5 py-1.5 rounded-full whitespace-nowrap transition-colors {{ $status === 'archived' ? 'bg-gray-700 text-white font-bold' : 'bg-white border border-border-card text-ink-muted hover:bg-cream-soft' }}">
        Archived
    </a>
</div>

<!-- Search Input -->
<div class="bg-white border border-border-card rounded-2xl p-4 shadow-xs mb-6">
    <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex items-center gap-3">
        <input type="hidden" name="status" value="{{ $status }}">
        <div class="relative flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, subject, or message content..."
                class="w-full pl-9 pr-4 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
            <svg class="w-4 h-4 text-ink-muted absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </div>
        <button type="submit" class="px-4 py-2 bg-maroon-base hover:bg-maroon-hover text-white text-xs font-bold rounded-lg transition-colors">
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
        <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-cream-soft border-b border-border-card text-ink-muted uppercase text-[10px] tracking-wider font-bold">
                <tr>
                    <th class="py-3.5 px-4 sm:px-6">Sender &amp; Affiliation</th>
                    <th class="py-3.5 px-4">Subject &amp; Category</th>
                    <th class="py-3.5 px-4">Status</th>
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
                            {{ str_replace('_', ' ', $inq->affiliation) }}
                        </span>
                    </td>
                    <td class="py-4 px-4 max-w-xs">
                        <span class="bg-maroon-base/10 text-maroon-base text-[9.5px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-full inline-block mb-1">
                            {{ str_replace('_', ' ', $inq->inquiry_type) }}
                        </span>
                        <span class="font-bold text-ink-base block text-xs leading-snug line-clamp-1">{{ $inq->subject }}</span>
                        <p class="text-[11px] text-ink-muted line-clamp-1 mt-0.5">{{ $inq->message }}</p>
                    </td>
                    <td class="py-4 px-4 whitespace-nowrap">
                        @php
                            $badgeClass = match($inq->status) {
                                'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
                                'in_review' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'resolved' => 'bg-green-100 text-green-800 border-green-300',
                                default => 'bg-gray-100 text-gray-700 border-gray-300',
                            };
                        @endphp
                        <span class="text-[10px] uppercase font-bold px-2.5 py-1 rounded-full border {{ $badgeClass }}">
                            {{ str_replace('_', ' ', $inq->status) }}
                        </span>
                    </td>
                    <td class="py-4 px-4 whitespace-nowrap text-ink-muted text-xs">
                        {{ $inq->created_at ? $inq->created_at->format('M d, Y') : '' }}
                        <span class="block text-[10px] text-ink-muted/70">{{ $inq->created_at ? $inq->created_at->format('h:i A') : '' }}</span>
                    </td>
                    <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" onclick="openInquiryModal({{ json_encode($inq) }})"
                                class="px-3 py-1.5 rounded-lg bg-cream-soft hover:bg-gold-base/20 text-maroon-base font-bold text-xs transition-colors cursor-pointer">
                                View &amp; Update
                            </button>

                            <form action="{{ route('admin.inquiries.destroy', $inq->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer" title="Delete Inquiry">
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
                    <td colspan="5" class="py-10 px-6 text-center text-ink-muted">
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

<!-- Inquiry Detail & Status Update Modal -->
<div id="inquiry-detail-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-border-card w-full max-w-2xl max-h-[90vh] overflow-y-auto">
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
            <!-- Sender Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3.5 bg-cream-soft rounded-xl border border-border-card text-xs">
                <div>
                    <span class="text-[10px] uppercase font-bold text-ink-muted block">Full Name</span>
                    <strong id="modal-inq-name" class="text-ink-base block text-sm"></strong>
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

            <!-- Message Body -->
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-ink-base block mb-1.5">Submitted Message</span>
                <div class="p-4 bg-cream-soft/70 rounded-xl border border-border-card text-xs sm:text-sm text-ink-base leading-relaxed whitespace-pre-wrap" id="modal-inq-message">
                </div>
            </div>

            <!-- Update Status & Notes Form -->
            <form id="inquiry-update-form" method="POST" action="" class="pt-4 border-t border-border-card space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="modal-status-select" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                        Workflow Status
                    </label>
                    <select id="modal-status-select" name="status" required
                        class="w-full px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none">
                        <option value="pending">Pending</option>
                        <option value="in_review">In Review</option>
                        <option value="resolved">Resolved</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <div>
                    <label for="modal-notes" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                        Internal Staff Notes / Next Steps
                    </label>
                    <textarea id="modal-notes" name="admin_notes" rows="3"
                        placeholder="Log consultation findings, coordinator assignments, or follow-up notes here..."
                        class="w-full px-3.5 py-2 bg-cream-soft border border-border-card rounded-lg text-xs text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeInquiryModal()"
                        class="px-4 py-2 rounded-lg border border-border-card text-xs font-semibold text-ink-muted hover:bg-cream-soft">
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
function openInquiryModal(inq) {
    document.getElementById('modal-inq-category').textContent = inq.inquiry_type ? inq.inquiry_type.replace('_', ' ').toUpperCase() : '';
    document.getElementById('modal-inq-subject').textContent = inq.subject || '';
    document.getElementById('modal-inq-name').textContent = inq.full_name || '';
    document.getElementById('modal-inq-email').textContent = inq.email || '';
    document.getElementById('modal-inq-phone').textContent = inq.contact_number || 'N/A';
    document.getElementById('modal-inq-message').textContent = inq.message || '';
    document.getElementById('modal-status-select').value = inq.status || 'pending';
    document.getElementById('modal-notes').value = inq.admin_notes || '';

    // Set action URL
    const updateUrl = "{{ url('admin/inquiries') }}/" + inq.id;
    document.getElementById('inquiry-update-form').action = updateUrl;

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

@if (isset($selectedInquiry) && $selectedInquiry)
document.addEventListener('DOMContentLoaded', () => {
    openInquiryModal({{ json_encode($selectedInquiry) }});
});
@endif
</script>
@endsection
