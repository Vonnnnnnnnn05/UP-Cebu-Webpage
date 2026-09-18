<!-- =========================================================
     INQUIRY & CONSULTATION FORM SECTION
========================================================== -->
<section id="inquire" class="bg-cream-soft py-16 sm:py-20 border-b border-border-card scroll-mt-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-10 reveal-on-scroll">
            <div class="inline-flex items-center gap-2 text-[11px] font-bold tracking-[1.5px] uppercase text-green-base mb-2">
                <span class="inline-block w-6 sm:w-7 h-0.5 bg-green-base rounded-full"></span>
                CONNECT WITH OUR TEAM
            </div>
            <h2 class="font-serif text-3xl sm:text-4xl font-bold text-maroon-base tracking-tight leading-tight">
                Submit an Innovation or IP Inquiry
            </h2>
            <p class="text-xs sm:text-[13px] leading-relaxed text-ink-muted mt-2">
                Have an invention disclosure, startup venture, licensing question, or partnership proposal? Send us a message and our specialists will assist you.
            </p>
        </div>

        @if (session('submission_status') === 'success')
        <div class="max-w-2xl mx-auto mb-8 p-4 bg-green-base/10 border border-green-base/30 rounded-xl flex items-start gap-3 text-green-dark text-xs sm:text-[13px]">
            <svg class="w-5 h-5 text-green-base shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <div>
                <strong class="font-bold block mb-0.5">Inquiry Sent Successfully</strong>
                <span>{{ session('submission_message') }}</span>
            </div>
        </div>
        @elseif ($errors->any())
        <div class="max-w-2xl mx-auto mb-8 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs sm:text-[13px]">
            <strong class="font-bold block mb-1">Please correct the following errors:</strong>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Consultation Request Form Card -->
        <div class="max-w-2xl mx-auto bg-white rounded-2xl border border-border-card p-6 sm:p-8 lg:p-10 shadow-sm reveal-on-scroll">
            <form action="{{ route('inquiries.store') }}" method="POST" class="space-y-4 sm:space-y-5" id="public-inquiry-form">
                @csrf

                <!-- Name & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="inq_full_name" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                            Full Name <span class="text-maroon-base">*</span>
                        </label>
                        <input type="text" id="inq_full_name" name="full_name" required
                            value="{{ old('full_name') }}"
                            placeholder="e.g. Maria Santos"
                            class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                    </div>

                    <div>
                        <label for="inq_email" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                            Email Address <span class="text-maroon-base">*</span>
                        </label>
                        <input type="email" id="inq_email" name="email" required
                            value="{{ old('email') }}"
                            placeholder="e.g. msantos@up.edu.ph"
                            class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                    </div>
                </div>

                <!-- Contact Number & Affiliation -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="inq_contact_number" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                            Contact / Mobile No.
                        </label>
                        <input type="tel" id="inq_contact_number" name="contact_number"
                            value="{{ old('contact_number') }}"
                            placeholder="e.g. 0917 123 4567"
                            class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                    </div>

                    <div>
                        <label for="inq_affiliation" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                            Affiliation / Background <span class="text-maroon-base">*</span>
                        </label>
                        <select id="inq_affiliation" name="affiliation" required
                            class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">
                            <option value="student" {{ old('affiliation') === 'student' ? 'selected' : '' }}>UP Cebu Student</option>
                            <option value="faculty" {{ old('affiliation') === 'faculty' ? 'selected' : '' }}>UP Faculty / Professor</option>
                            <option value="researcher" {{ old('affiliation') === 'researcher' ? 'selected' : '' }}>Researcher / Lab Associate</option>
                            <option value="msme" {{ old('affiliation') === 'msme' ? 'selected' : '' }}>Regional MSME / Enterprise</option>
                            <option value="industry_partner" {{ old('affiliation') === 'industry_partner' ? 'selected' : '' }}>Industry / Corporate Partner</option>
                            <option value="general_public" {{ old('affiliation', 'general_public') === 'general_public' ? 'selected' : '' }}>General Public / Other</option>
                        </select>
                    </div>
                </div>

                <!-- Inquiry Category -->
                <div>
                    <label for="inq_type" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                        Inquiry Category <span class="text-maroon-base">*</span>
                    </label>
                    <select id="inq_type" name="inquiry_type" required
                        class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">
                        <option value="ip_protection" {{ old('inquiry_type') === 'ip_protection' ? 'selected' : '' }}>Invention Disclosure &amp; IP Protection</option>
                        <option value="business_incubation" {{ old('inquiry_type') === 'business_incubation' ? 'selected' : '' }}>Technology Business Incubation (TBI)</option>
                        <option value="simp_mentorship" {{ old('inquiry_type') === 'simp_mentorship' ? 'selected' : '' }}>Student Mentorship Program (SIMP)</option>
                        <option value="licensing" {{ old('inquiry_type') === 'licensing' ? 'selected' : '' }}>Technology Licensing &amp; Commercialization</option>
                        <option value="msme_support" {{ old('inquiry_type') === 'msme_support' ? 'selected' : '' }}>Regional MSME Assistance &amp; Extension</option>
                        <option value="press_media" {{ old('inquiry_type') === 'press_media' ? 'selected' : '' }}>Press, Media &amp; Communications</option>
                        <option value="general" {{ old('inquiry_type', 'general') === 'general' ? 'selected' : '' }}>General Inquiry / Partnership Proposal</option>
                    </select>
                </div>

                <!-- Subject -->
                <div>
                    <label for="inq_subject" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                        Subject Line <span class="text-maroon-base">*</span>
                    </label>
                    <input type="text" id="inq_subject" name="subject" required
                        value="{{ old('subject') }}"
                        placeholder="Brief summary of your topic or project"
                        class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors" />
                </div>

                <!-- Message -->
                <div>
                    <label for="inq_message" class="block text-xs font-bold uppercase tracking-wider text-ink-base mb-1.5">
                        Message &amp; Project Details <span class="text-maroon-base">*</span>
                    </label>
                    <textarea id="inq_message" name="message" rows="4" required
                        placeholder="Provide relevant context regarding your invention, startup stage, timeline, or consultation needs..."
                        class="w-full px-3.5 py-2.5 bg-cream-soft border border-border-card rounded-lg text-xs sm:text-sm text-ink-base focus:bg-white focus:border-maroon-base focus:outline-none transition-colors">{{ old('message') }}</textarea>
                </div>

                <!-- Privacy notice & Submit Button -->
                <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <p class="text-[11px] text-ink-muted leading-relaxed max-w-sm">
                        By submitting, you agree that your inquiry will be processed under the university’s data privacy policies.
                    </p>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-xs sm:text-sm font-bold transition-all shadow-md active:scale-95 cursor-pointer">
                        <span>Send Message</span>
                        <svg class="w-4 h-4 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- =========================================================
     FOOTER SECTION
========================================================== -->
<footer id="contact" class="bg-maroon-base text-white pt-10 pb-6 border-t-2 border-gold-base">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll">

        <!-- Footer Top -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_auto] gap-6 sm:gap-8 pb-8 items-start sm:items-center">

            <!-- Footer Brand -->
            <div class="flex items-center gap-3 sm:gap-3.5">
                <div class="bg-white rounded-md px-1.5 py-1 shadow-xs border border-gold-base/50 flex items-center justify-center shrink-0">
                    <img src="{{ asset('assets/ttbdo-full-logo.png') }}" alt="UP Cebu TTBDO Logo" class="h-7 sm:h-8 lg:h-9 w-auto object-contain" />
                </div>
                <div class="min-w-0">
                    <span class="block text-[7.5px] sm:text-[8px] tracking-wider uppercase text-cream-bg/80 font-medium truncate">
                        {{ $siteSettings['university_name'] ?? 'UNIVERSITY OF THE PHILIPPINES CEBU' }}
                    </span>
                    <strong class="block font-serif text-[13px] sm:text-sm font-bold leading-tight mt-0.5 text-white">
                        Technology Transfer and<br>Business Development Office
                    </strong>
                </div>
            </div>

            <!-- Address / Office Location -->
            <div class="office-location-info flex items-start gap-3 border-t sm:border-t-0 sm:border-l border-white/15 pt-4 sm:pt-0 sm:pl-6 text-[11px] leading-relaxed text-cream-bg/85 cursor-default select-text">
                <svg class="office-location-icon w-5 h-5 text-gold-base shrink-0 mt-0.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <div>
                    <strong class="office-location-title text-white block font-semibold mb-0.5 transition-colors duration-300">Office Location</strong>
                    <div class="office-location-text text-cream-bg/85 transition-colors duration-300">
                        {!! nl2br(e($siteSettings['office_location'] ?? "Technology Transfer and Business Development Office\n3rd Floor, Technology Innovation Center\nUniversity of the Philippines Cebu")) !!}
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="flex items-start gap-3 border-t lg:border-t-0 lg:border-l border-white/15 pt-4 lg:pt-0 lg:pl-6 text-[11px] leading-relaxed text-cream-bg/85">
                <svg class="w-5 h-5 text-gold-base shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
                <div>
                    <a href="mailto:{{ $siteSettings['contact_email'] ?? 'ttbdo@upcebu.edu.ph' }}"
                        class="hover:text-gold-base transition-colors block py-0.5">
                        {{ $siteSettings['contact_email'] ?? 'ttbdo@upcebu.edu.ph' }}
                    </a>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone'] ?? '+63322326001') }}" class="hover:text-gold-base transition-colors block py-0.5">
                        {{ $siteSettings['contact_phone'] ?? '(032) 232-6001 loc. 301' }}
                    </a>
                </div>
            </div>

            <!-- Socials & Admin Link -->
            <div class="flex items-center gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-white/15 sm:border-transparent">
                <a href="{{ $siteSettings['facebook_url'] ?? 'https://www.facebook.com/upcebuttbdo' }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                    class="w-8 h-8 rounded-full bg-green-base text-white flex items-center justify-center hover:scale-110 active:scale-95 hover:bg-gold-base hover:text-maroon-base transition-all shadow-xs border border-green-light/40">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                </a>
                <a href="mailto:{{ $siteSettings['contact_email'] ?? 'ttbdo@upcebu.edu.ph' }}" aria-label="Email"
                    class="w-8 h-8 rounded-full bg-green-base text-white flex items-center justify-center hover:scale-110 active:scale-95 hover:bg-gold-base hover:text-maroon-base transition-all shadow-xs border border-green-light/40">
                    <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </a>
                @if(Auth::guard('admin')->check())
                <a href="{{ route('admin.dashboard') }}" aria-label="Admin Dashboard" title="Go to Admin Dashboard"
                    class="w-8 h-8 rounded-full bg-gold-base text-maroon-dark flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xs border border-gold-base">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-13.5 18v-2.25Z" />
                    </svg>
                </a>
                @else
                <a href="{{ route('admin.login') }}" aria-label="Staff Login" title="Staff Admin Portal"
                    class="w-8 h-8 rounded-full bg-maroon-dark text-gold-base flex items-center justify-center hover:scale-110 active:scale-95 hover:bg-gold-base hover:text-maroon-base transition-all shadow-xs border border-gold-base/30">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </a>
                @endif
            </div>

        </div>

        <!-- Footer Bottom with Official Motto -->
        <div class="border-t border-white/15 pt-5 mt-2 flex flex-col sm:flex-row items-center justify-between gap-2.5 text-[9.5px] sm:text-[10px] text-cream-bg/75 text-center sm:text-left">
            <span>
                © {{ date('Y') }} {{ $siteSettings['university_name'] ?? 'University of the Philippines Cebu' }}. All rights reserved.
            </span>
            <span class="italic text-gold-base font-medium">
                {{ $siteSettings['motto'] ?? 'Nurtured to Create • Inspired to Innovate • Destined to Serve' }}
            </span>
        </div>

    </div>
</footer>
