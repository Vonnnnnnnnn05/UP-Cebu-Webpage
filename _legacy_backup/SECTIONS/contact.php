<?php
/**
 * Dynamic Consultation & Contact Section + Footer
 * UP Cebu TTBDO
 */
// Ensure database connection
if (!isset($conn)) {
    require_once __DIR__ . '/../ADDITIONALS/db_connect.php';
}

$submission_status = null;
$submission_message = '';

// Handle Inquiry Form POST Submission
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_inquiry') {
    $full_name      = trim($_POST['full_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $affiliation    = trim($_POST['affiliation'] ?? 'general_public');
    $inquiry_type   = trim($_POST['inquiry_type'] ?? 'general');
    $subject        = trim($_POST['subject'] ?? '');
    $message        = trim($_POST['message'] ?? '');

    // Validation
    if (empty($full_name) || empty($email) || empty($subject) || empty($message)) {
        $submission_status = 'error';
        $submission_message = 'Please fill in all required fields (Name, Email, Subject, and Message).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $submission_status = 'error';
        $submission_message = 'Please provide a valid email address.';
    } else {
        // Insert into database using prepared statement with bind_param
        $sql = "INSERT INTO inquiries (full_name, email, contact_number, affiliation, inquiry_type, subject, message, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
        $types = "sssssss";
        $params = [$full_name, $email, $contact_number, $affiliation, $inquiry_type, $subject, $message];

        $inserted = db_execute($conn, $sql, $types, $params);
        if ($inserted) {
            $submission_status = 'success';
            $submission_message = 'Thank you for reaching out! Your inquiry has been submitted successfully to the TTBDO team. We will review your request and get back to you shortly.';
        } else {
            $submission_status = 'error';
            $submission_message = 'An unexpected system error occurred while processing your request. Please try again or email us directly.';
        }
    }
}
?>

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

        <?php if ($submission_status === 'success'): ?>
        <div class="max-w-2xl mx-auto mb-8 p-4 bg-green-base/10 border border-green-base/30 rounded-xl flex items-start gap-3 text-green-dark text-xs sm:text-[13px]">
            <svg class="w-5 h-5 text-green-base shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <div>
                <strong class="font-bold block mb-0.5">Inquiry Sent Successfully</strong>
                <span><?= e($submission_message) ?></span>
            </div>
        </div>
        <?php elseif ($submission_status === 'error'): ?>
        <div class="max-w-2xl mx-auto mb-8 p-4 bg-maroon-base/10 border border-maroon-base/30 rounded-xl flex items-start gap-3 text-maroon-base text-xs sm:text-[13px]">
            <svg class="w-5 h-5 text-maroon-base shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 8.25h.008v.008H12v-.008Z" />
            </svg>
            <div>
                <strong class="font-bold block mb-0.5">Submission Error</strong>
                <span><?= e($submission_message) ?></span>
            </div>
        </div>
        <?php endif; ?>

        <div class="max-w-2xl mx-auto bg-white border border-border-card rounded-2xl p-6 sm:p-8 shadow-xs reveal-on-scroll">
            <form action="#inquire" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="submit_inquiry">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-xs font-semibold text-ink-base mb-1.5">
                            Full Name <span class="text-maroon-base">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name" required
                            placeholder="Prof. / Dr. / Juan Dela Cruz"
                            class="w-full px-3.5 py-2 text-xs rounded-lg border border-border-card bg-cream-soft/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base transition-all">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-semibold text-ink-base mb-1.5">
                            Email Address <span class="text-maroon-base">*</span>
                        </label>
                        <input type="email" id="email" name="email" required
                            placeholder="name@up.edu.ph"
                            class="w-full px-3.5 py-2 text-xs rounded-lg border border-border-card bg-cream-soft/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="contact_number" class="block text-xs font-semibold text-ink-base mb-1.5">
                            Contact Number
                        </label>
                        <input type="text" id="contact_number" name="contact_number"
                            placeholder="0917 123 4567"
                            class="w-full px-3.5 py-2 text-xs rounded-lg border border-border-card bg-cream-soft/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base transition-all">
                    </div>
                    <div>
                        <label for="affiliation" class="block text-xs font-semibold text-ink-base mb-1.5">
                            Affiliation
                        </label>
                        <select id="affiliation" name="affiliation"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-border-card bg-cream-soft/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base transition-all">
                            <option value="student">UP Student</option>
                            <option value="faculty">UP Faculty / Researcher</option>
                            <option value="researcher">External Researcher</option>
                            <option value="msme">Local MSME / Enterprise</option>
                            <option value="industry_partner">Industry / Investor</option>
                            <option value="general_public" selected>General Public</option>
                        </select>
                    </div>
                    <div>
                        <label for="inquiry_type" class="block text-xs font-semibold text-ink-base mb-1.5">
                            Service of Interest
                        </label>
                        <select id="inquiry_type" name="inquiry_type"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-border-card bg-cream-soft/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base transition-all">
                            <option value="ip_protection" selected>IP Protection / Patent</option>
                            <option value="business_incubation">Business Incubation (TBI)</option>
                            <option value="simp_mentorship">SIMP Student Mentorship</option>
                            <option value="licensing">Technology Licensing</option>
                            <option value="msme_support">MSME Advisory / Extension</option>
                            <option value="press_media">Media &amp; Press</option>
                            <option value="general">General Inquiries</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="subject" class="block text-xs font-semibold text-ink-base mb-1.5">
                        Inquiry Subject <span class="text-maroon-base">*</span>
                    </label>
                    <input type="text" id="subject" name="subject" required
                        placeholder="e.g. Invention Disclosure Consultation Request"
                        class="w-full px-3.5 py-2 text-xs rounded-lg border border-border-card bg-cream-soft/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base transition-all">
                </div>

                <div>
                    <label for="message" class="block text-xs font-semibold text-ink-base mb-1.5">
                        Detailed Message / Project Background <span class="text-maroon-base">*</span>
                    </label>
                    <textarea id="message" name="message" rows="4" required
                        placeholder="Please describe your technology, proposal, or the specific assistance you are seeking..."
                        class="w-full px-3.5 py-2 text-xs rounded-lg border border-border-card bg-cream-soft/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-base transition-all"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <span class="text-[11px] text-ink-muted">Fields marked with <span class="text-maroon-base">*</span> are required.</span>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-maroon-base hover:bg-maroon-hover text-white text-xs font-bold transition-all shadow-sm hover:shadow active:scale-95 cursor-pointer">
                        <span>Submit Inquiry</span>
                        <svg class="w-3.5 h-3.5 text-gold-base" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- =========================================================
     OFFICIAL FOOTER & OFFICE CONTACT DETAILS
========================================================== -->
<footer id="contact" class="bg-maroon-base text-white pt-10 pb-6 border-t-2 border-gold-base">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll">

        <!-- Footer Top -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_auto] gap-6 sm:gap-8 pb-8 items-start sm:items-center">

            <!-- Footer Brand -->
            <div class="flex items-center gap-3 sm:gap-3.5">
                <div class="bg-white rounded-md px-1.5 py-1 shadow-xs border border-gold-base/50 flex items-center justify-center shrink-0">
                    <img src="assets/ttbdo-full-logo.png" alt="UP Cebu TTBDO Logo"
                        class="h-7 sm:h-8 lg:h-9 w-auto object-contain" />
                </div>
                <div class="min-w-0">
                    <span class="block text-[7.5px] sm:text-[8px] tracking-wider uppercase text-cream-bg/80 font-medium truncate">
                        UNIVERSITY OF THE PHILIPPINES CEBU
                    </span>
                    <strong class="block font-serif text-[13px] sm:text-sm font-bold leading-tight mt-0.5 text-white">
                        Technology Transfer and<br>Business Development Office
                    </strong>
                </div>
            </div>

            <!-- Address / Office Location -->
            <div class="office-location-info flex items-start gap-3 border-t sm:border-t-0 sm:border-l border-white/15 pt-4 sm:pt-0 sm:pl-6 text-[11px] leading-relaxed text-cream-bg/85 cursor-default select-text">
                <svg class="office-location-icon w-5 h-5 text-gold-base shrink-0 mt-0.5 transition-transform duration-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <div>
                    <strong class="office-location-title text-white block font-semibold mb-0.5 transition-colors duration-300">Office Location</strong>
                    <div class="office-location-text text-cream-bg/85 transition-colors duration-300">
                        Technology Transfer and Business Development Office<br>
                        3rd Floor, Technology Innovation Center<br>
                        University of the Philippines Cebu
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="flex items-start gap-3 border-t lg:border-t-0 lg:border-l border-white/15 pt-4 lg:pt-0 lg:pl-6 text-[11px] leading-relaxed text-cream-bg/85">
                <svg class="w-5 h-5 text-gold-base shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
                <div>
                    <a href="mailto:ttbdo@upcebu.edu.ph"
                        class="hover:text-gold-base transition-colors block py-0.5">
                        ttbdo@upcebu.edu.ph
                    </a>
                    <a href="tel:+63322326001" class="hover:text-gold-base transition-colors block py-0.5">
                        (032) 232-6001 loc. 301
                    </a>
                </div>
            </div>

            <!-- Socials -->
            <div class="flex items-center gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-white/15 sm:border-transparent">
                <a href="https://www.facebook.com/upcebuttbdo" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                    class="w-8 h-8 rounded-full bg-green-base text-white flex items-center justify-center hover:scale-110 active:scale-95 hover:bg-gold-base hover:text-maroon-base transition-all shadow-xs border border-green-light/40">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                </a>
                <a href="mailto:ttbdo@upcebu.edu.ph" aria-label="Email"
                    class="w-8 h-8 rounded-full bg-green-base text-white flex items-center justify-center hover:scale-110 active:scale-95 hover:bg-gold-base hover:text-maroon-base transition-all shadow-xs border border-green-light/40">
                    <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </a>
                <a href="ADMIN/login.php" aria-label="Admin Portal" title="Staff Admin Portal"
                    class="w-8 h-8 rounded-full bg-maroon-dark text-gold-base flex items-center justify-center hover:scale-110 active:scale-95 hover:bg-gold-base hover:text-maroon-base transition-all shadow-xs border border-gold-base/30">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </a>
            </div>

        </div>

        <!-- Footer Bottom with Official Motto -->
        <div class="border-t border-white/15 pt-5 mt-2 flex flex-col sm:flex-row items-center justify-between gap-2.5 text-[9.5px] sm:text-[10px] text-cream-bg/75 text-center sm:text-left">
            <span>
                © <?= date('Y') ?> University of the Philippines Cebu. All rights reserved.
            </span>
            <span class="italic text-gold-base font-medium">
                Nurtured to Create • Inspired to Innovate • Destined to Serve
            </span>
        </div>

    </div>
</footer>
