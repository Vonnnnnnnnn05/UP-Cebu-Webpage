<!-- =========================================================
     ACTIVITY REGISTRATION & INQUIRY MODAL
========================================================== -->
<div id="booking-modal" class="fixed inset-0 z-[120] bg-black/65 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 opacity-0 pointer-events-none transition-all duration-300" role="dialog" aria-modal="true" aria-labelledby="booking-modal-title">
    <div class="booking-dialog-card w-full max-w-2xl bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-border-card overflow-hidden flex flex-col max-h-[92vh] transform scale-95 transition-all duration-300">

        <!-- Header Bar -->
        <div class="p-4 sm:p-5 border-b border-border-card/80 bg-cream-soft flex items-center justify-between gap-4 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-maroon-base/10 text-maroon-base flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-maroon-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <h2 id="booking-modal-title" class="font-serif text-base sm:text-lg font-bold text-maroon-base leading-tight">
                        Activity Registration &amp; Booking
                    </h2>
                    <p class="text-[11px] text-ink-muted">
                        Connect with UP Cebu TTBDO to register an activity, schedule consultation, or book facilities.
                    </p>
                </div>
            </div>

            <button id="booking-modal-close" type="button" aria-label="Close booking dialog"
                class="p-1.5 rounded-lg text-ink-muted hover:text-maroon-base hover:bg-black/5 transition-colors cursor-pointer shrink-0">
                <kbd class="hidden sm:inline-block text-[10px] font-mono px-1.5 py-0.5 rounded bg-white border border-border-card text-ink-muted shadow-2xs mr-1">ESC</kbd>
                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Scrollable Form Body -->
        <div class="p-5 sm:p-6 lg:p-7 overflow-y-auto">
            @if ($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs">
                <strong class="font-bold block mb-1">Please correct the following:</strong>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('inquiries.store') }}" method="POST" class="space-y-4" id="public-booking-form">
                @csrf

                <!-- Name & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="modal_inq_full_name" class="block text-[11px] font-bold uppercase tracking-wider text-ink-base mb-1">
                            Full Name <span class="text-maroon-base">*</span>
                        </label>
                        <input type="text" id="modal_inq_full_name" name="full_name" required
                            value="{{ old('full_name') }}"
                            placeholder="e.g. Maria Santos"
                            class="w-full px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                    </div>

                    <div>
                        <label for="modal_inq_email" class="block text-[11px] font-bold uppercase tracking-wider text-ink-base mb-1">
                            Email Address <span class="text-maroon-base">*</span>
                        </label>
                        <input type="email" id="modal_inq_email" name="email" required
                            value="{{ old('email') }}"
                            placeholder="e.g. msantos@up.edu.ph"
                            class="w-full px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                    </div>
                </div>

                <!-- Contact Number & Organization (Fillable) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="modal_inq_contact_number" class="block text-[11px] font-bold uppercase tracking-wider text-ink-base mb-1">
                            Contact / Mobile No.
                        </label>
                        <input type="tel" id="modal_inq_contact_number" name="contact_number"
                            value="{{ old('contact_number') }}"
                            placeholder="e.g. 0917 123 4567"
                            class="w-full px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                    </div>

                    <div>
                        <label for="modal_inq_affiliation" class="block text-[11px] font-bold uppercase tracking-wider text-ink-base mb-1">
                            Organization <span class="text-maroon-base">*</span>
                        </label>
                        <input type="text" id="modal_inq_affiliation" name="affiliation" required
                            value="{{ old('affiliation') }}"
                            placeholder="e.g. SKSU / UP Cebu / Agency / Company"
                            class="w-full px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                    </div>
                </div>

                <!-- Category & Subject Line -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="modal_inq_type" class="block text-[11px] font-bold uppercase tracking-wider text-ink-base mb-1">
                            Category <span class="text-maroon-base">*</span>
                        </label>
                        <select id="modal_inq_type" name="inquiry_type" required
                            class="w-full px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">
                            <option value="Activity" {{ old('inquiry_type') === 'Activity' ? 'selected' : '' }}>Activity / Event Booking</option>
                            <option value="Meeting" {{ old('inquiry_type') === 'Meeting' ? 'selected' : '' }}>Meeting / Consultation</option>
                            <option value="Incubation" {{ old('inquiry_type') === 'Incubation' ? 'selected' : '' }}>Incubation &amp; Facility Booking</option>
                            <option value="University Visit" {{ old('inquiry_type') === 'University Visit' ? 'selected' : '' }}>University Visit / Study Tour</option>
                            <option value="Partnership" {{ old('inquiry_type') === 'Partnership' ? 'selected' : '' }}>Partnership Proposal</option>
                            <option value="IP Rights & Licensing" {{ old('inquiry_type') === 'IP Rights & Licensing' ? 'selected' : '' }}>IP Rights &amp; Technology Licensing</option>
                            <option value="Training & Workshop" {{ old('inquiry_type') === 'Training & Workshop' ? 'selected' : '' }}>Training &amp; Workshop</option>
                            <option value="General Inquiry" {{ old('inquiry_type', 'General Inquiry') === 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                        </select>
                    </div>

                    <div>
                        <label for="modal_inq_subject" class="block text-[11px] font-bold uppercase tracking-wider text-ink-base mb-1">
                            Subject Line <span class="text-maroon-base">*</span>
                        </label>
                        <input type="text" id="modal_inq_subject" name="subject" required
                            value="{{ old('subject') }}"
                            placeholder="Brief summary of your topic or project"
                            class="w-full px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                    </div>
                </div>

                <!-- Message -->
                <div>
                    <label for="modal_inq_message" class="block text-[11px] font-bold uppercase tracking-wider text-ink-base mb-1">
                        Message &amp; Project Details <span class="text-maroon-base">*</span>
                    </label>
                    <textarea id="modal_inq_message" name="message" rows="3" required
                        placeholder="Provide relevant context regarding your invention, startup stage, timeline, or consultation needs..."
                        class="w-full px-3 py-2 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors leading-relaxed">{{ old('message') }}</textarea>
                </div>

                <!-- Privacy Notice & Submit -->
                <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-border-card/60">
                    <p class="text-[10.5px] text-ink-muted leading-relaxed max-w-sm">
                        By submitting, you agree that your inquiry will be processed under the university’s data privacy policies.
                    </p>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-maroon-base hover:bg-maroon-dark text-white text-xs sm:text-sm font-bold transition-all shadow-md active:scale-95 cursor-pointer shrink-0">
                        <span>Send Message</span>
                        <svg class="w-3.5 h-3.5 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
