/* ============================================================
   MOBILE DROPDOWN NAVIGATION
============================================================ */

const menuToggle = document.getElementById("menu-toggle");
const mobileMenu = document.getElementById("mobile-menu");
const menuIconOpen = document.getElementById("menu-icon-open");
const menuIconClose = document.getElementById("menu-icon-close");

function toggleMobileMenu() {
    if (!mobileMenu || !menuToggle) return;

    const isClosed = mobileMenu.classList.contains("hidden");

    if (isClosed) {
        mobileMenu.classList.remove("hidden");
        mobileMenu.classList.add("flex");
        menuToggle.setAttribute("aria-expanded", "true");
        if (menuIconOpen) menuIconOpen.classList.add("hidden");
        if (menuIconClose) menuIconClose.classList.remove("hidden");
    } else {
        closeMobileMenu();
    }
}

function closeMobileMenu() {
    if (!mobileMenu || !menuToggle) return;

    mobileMenu.classList.add("hidden");
    mobileMenu.classList.remove("flex");
    menuToggle.setAttribute("aria-expanded", "false");
    if (menuIconOpen) menuIconOpen.classList.remove("hidden");
    if (menuIconClose) menuIconClose.classList.add("hidden");
}

if (menuToggle && mobileMenu) {
    menuToggle.addEventListener("click", toggleMobileMenu);
}

// Reset mobile menu on resize to desktop
window.addEventListener("resize", () => {
    if (window.innerWidth >= 1024) {
        closeMobileMenu();
    }
});

/* ============================================================
   ACTIVE LINK SYNCHRONIZATION & SCROLL SPY
============================================================ */

const desktopLinks = document.querySelectorAll(".nav-link");
const mobileLinks = document.querySelectorAll(".mobile-nav-link");
const headerEl = document.querySelector("header");

function setActiveNav(targetHref) {
    if (!targetHref) return;

    // Normalize href (e.g. "#home")
    const cleanHref = targetHref.startsWith("#") ? targetHref : `#${targetHref}`;

    // Update desktop nav links
    desktopLinks.forEach(link => {
        if (link.getAttribute("href") === cleanHref) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });

    // Update mobile dropdown links
    mobileLinks.forEach(link => {
        if (link.getAttribute("href") === cleanHref) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });
}

// Universal smooth scroll for all anchor links (navigation, hero CTA, logo, buttons)
document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener("click", (e) => {
        const href = link.getAttribute("href");
        if (!href || href === "#") return;

        // Skip non-section anchors if any
        if (href.startsWith("#") && href.length > 1) {
            const targetEl = href === "#home" ? document.body : document.querySelector(href);
            if (targetEl) {
                e.preventDefault();
                closeMobileMenu();
                setActiveNav(href);

                // If link was inside a desktop dropdown menu, close it cleanly
                const parentDropdown = link.closest('.group\\/dropdown');
                if (parentDropdown) {
                    const dropdownPanel = parentDropdown.querySelector('.absolute');
                    if (dropdownPanel) {
                        dropdownPanel.style.display = 'none';
                        setTimeout(() => {
                            dropdownPanel.style.display = '';
                        }, 400);
                    }
                }

                const headerHeight = headerEl ? headerEl.offsetHeight : 80;
                const targetY = href === "#home" 
                    ? 0 
                    : Math.max(0, targetEl.getBoundingClientRect().top + window.pageYOffset - headerHeight - 12);

                window.scrollTo({
                    top: targetY,
                    behavior: "smooth"
                });

                if (history.pushState) {
                    history.pushState(null, null, href);
                }
            }
        }
    });
});

// Real-time ScrollSpy: Dynamically update active link on scroll
function updateScrollSpy() {
    const scrollPos = window.scrollY + 200;

    // If reached bottom of page, highlight contact
    if ((window.innerHeight + window.scrollY) >= (document.documentElement.scrollHeight - 70)) {
        setActiveNav("#contact");
        return;
    }

    const sectionIds = ["contact", "about", "events", "programs", "news", "home"];

    for (const id of sectionIds) {
        const el = document.getElementById(id);
        if (el) {
            const top = el.offsetTop;
            if (scrollPos >= top) {
                setActiveNav(`#${id}`);
                break;
            }
        }
    }
}

/* ============================================================
   SMOOTH SCROLL PROGRESS BAR & BACK TO TOP BUTTON
============================================================ */

const progressBar = document.getElementById("scroll-progress-bar");
const backToTopBtn = document.getElementById("back-to-top-btn");

function updateScrollUI() {
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;

    // Reading scroll progress bar
    if (progressBar && docHeight > 0) {
        const scrollPercent = Math.min(100, Math.max(0, (scrollTop / docHeight) * 100));
        progressBar.style.width = `${scrollPercent}%`;
    }

    // Header elevation depth on scroll
    if (headerEl) {
        if (scrollTop > 20) {
            headerEl.classList.add("is-scrolled");
        } else {
            headerEl.classList.remove("is-scrolled");
        }
    }

    // Back-to-top button appearance
    if (backToTopBtn) {
        if (scrollTop > 320) {
            backToTopBtn.classList.add("is-visible");
        } else {
            backToTopBtn.classList.remove("is-visible");
        }
    }
}

// Back to top button smooth scroll click
if (backToTopBtn) {
    backToTopBtn.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
        setActiveNav("#home");
        if (history.pushState) {
            history.pushState(null, null, "#home");
        }
    });
}

// High-performance scroll handler via requestAnimationFrame
let ticking = false;
window.addEventListener("scroll", () => {
    if (!ticking) {
        window.requestAnimationFrame(() => {
            updateScrollSpy();
            updateScrollUI();
            ticking = false;
        });
        ticking = true;
    }
}, { passive: true });

/* ============================================================
   SMOOTH SCROLL-TRIGGERED REVEAL ANIMATIONS (INTERSECTION OBSERVER)
============================================================ */

function initScrollReveal() {
    const revealElements = document.querySelectorAll(
        ".reveal-on-scroll, .reveal-slide-left, .reveal-slide-right"
    );
    if (!revealElements.length) return;

    // Respect reduced motion settings
    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (prefersReducedMotion) {
        revealElements.forEach(el => el.classList.add("is-revealed"));
        return;
    }

    const observerOptions = {
        root: null,
        rootMargin: "0px 0px -50px 0px",
        threshold: 0.12
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("is-revealed");
                observer.unobserve(entry.target); // Trigger once smoothly
            }
        });
    }, observerOptions);

    revealElements.forEach(el => revealObserver.observe(el));
}

// Initialize on DOM ready
window.addEventListener("DOMContentLoaded", () => {
    initScrollReveal();
    updateScrollUI();

    if (window.location.hash) {
        setActiveNav(window.location.hash);
    } else {
        updateScrollSpy();
    }
});

/* ============================================================
   ANIMATED SEARCH MODAL & JSON-DRIVEN SEARCH ENGINE
============================================================ */

let searchData = [];
let activeFilter = "all";
let selectedIndex = -1;

const searchToggleBtn = document.getElementById("search-toggle-btn");
const mobileSearchToggleBtn = document.getElementById("mobile-search-toggle-btn");
const searchModal = document.getElementById("search-modal");
const searchModalClose = document.getElementById("search-modal-close");
const searchModalInput = document.getElementById("search-modal-input");
const searchResultsList = document.getElementById("search-results-list");
const searchResultsCount = document.getElementById("search-results-count");
const filterChips = document.querySelectorAll(".search-filter-chip");

// Fallback search dataset (guarantees offline & file:// protocol operation)
const defaultSearchData = [
    {
        id: "ip-protection",
        title: "Intellectual Property Rights Protection",
        category: "Programs",
        description: "Comprehensive institutional assistance for patents, copyright deposits, trademark registrations, and IPOPHL filings.",
        url: "#program-ip",
        keywords: ["patent", "copyright", "trademark", "ipophl", "prior art", "invention", "claims", "protection", "legal"]
    },
    {
        id: "business-incubation",
        title: "Technology Business Incubation (TBI)",
        category: "Programs",
        description: "Co-working space, venture networking, SEC registration assistance, and seed funding access for student and faculty startups.",
        url: "#program-incubation",
        keywords: ["incubation", "startup", "founder", "spin-off", "co-working", "venture", "seed funding", "accelerator"]
    },
    {
        id: "simp-mentorship",
        title: "Student Innovation Mentorship Program (SIMP)",
        category: "Programs",
        description: "Prototype validation grants, pitch competition training, and industry mentoring for undergraduate and capstone projects.",
        url: "#program-simp",
        keywords: ["student", "simp", "capstone", "mentorship", "grant", "prototype", "thesis", "pitch"]
    },
    {
        id: "tech-licensing",
        title: "Technology Licensing & Commercialization",
        category: "Programs",
        description: "Commercial licensing negotiation, valuation, and royalty sharing for UP-developed technologies and inventions.",
        url: "#program-licensing",
        keywords: ["licensing", "commercialization", "royalty", "industry", "contract", "tech transfer", "market"]
    },
    {
        id: "msme-extension",
        title: "Regional MSME Mentorship & Extension",
        category: "Programs",
        description: "Appropriate technology transfer, branding advisory, and DOST SETUP linkages for Central Visayas small enterprises.",
        url: "#program-msme",
        keywords: ["msme", "extension", "community", "enterprise", "dost setup", "cooperative", "regional"]
    },
    {
        id: "internship-cert",
        title: "Internship & IP Skills Certification",
        category: "Programs",
        description: "Hands-on casework on real-world patent dockets and mentorship by licensed patent agents and IP lawyers.",
        url: "#program-internship",
        keywords: ["internship", "certification", "training", "skills", "patent agent", "law", "experience"]
    },
    {
        id: "dost-partnership",
        title: "UP Cebu and DOST-VII Regional Innovation Hub",
        category: "News",
        description: "Formalized partnership with DOST-VII establishing ₱5.2M prototyping fund and acceleration tracks for Central Visayas spin-offs.",
        url: "#news",
        keywords: ["dost", "dost-vii", "partnership", "prototyping fund", "innovation hub", "regional", "grant"]
    },
    {
        id: "ip-filings-news",
        title: "Five UP Cebu Innovations Secure IPOPHL Certificates",
        category: "News",
        description: "Utility models and copyright registrations officially granted across computer science and industrial design laboratories.",
        url: "#news",
        keywords: ["utility models", "certificates", "awards", "granted", "computer science", "industrial design"]
    },
    {
        id: "patent-search-workshop",
        title: "TTBDO Hands-On Prior Art Patent Search Workshop",
        category: "News",
        description: "Over 45 faculty researchers trained on IPOPHL databases, Espacenet, and Google Patents navigation.",
        url: "#news",
        keywords: ["workshop", "prior art", "espacenet", "training", "search", "faculty"]
    },
    {
        id: "innovation-summit-2025",
        title: "Central Visayas Innovation Summit & Startup Demo Day 2025",
        category: "Events",
        description: "Annual flagship gathering of angel investors, researchers, and student founders at UP Cebu SRP Campus on April 15, 2025.",
        url: "#events",
        keywords: ["summit", "demo day", "april 15", "investors", "srp campus", "conference"]
    },
    {
        id: "ai-ip-clinic",
        title: "Clinic: Navigating Generative AI and Copyright Law",
        category: "Events",
        description: "Interactive legal clinic examining copyright boundaries in algorithmic creations and synthetic datasets on March 28, 2025.",
        url: "#events",
        keywords: ["ai", "generative ai", "copyright", "clinic", "workshop", "march 28", "machine learning"]
    },
    {
        id: "vision-mission",
        title: "Vision & Mission of UP Cebu TTBDO",
        category: "About Us",
        description: "Pioneering knowledge translation, protecting creators' rights, and driving regional economic development in Central Visayas.",
        url: "#about-vision",
        keywords: ["vision", "mission", "mandate", "chancellor", "goals", "public service"]
    },
    {
        id: "four-pillars",
        title: "Four Strategic Pillars of TTBDO",
        category: "About Us",
        description: "IP Governance, Commercial Licensing, Startup Acceleration, and Regional Extension pathways.",
        url: "#about-pillars",
        keywords: ["pillars", "governance", "commercialization", "acceleration", "extension", "framework"]
    },
    {
        id: "impact-metrics",
        title: "Impact & Statistics (45+ Filings, 18+ Startups)",
        category: "About Us",
        description: "Track record of assisted intellectual property disclosures, incubated ventures, and partner enterprises.",
        url: "#about-impact",
        keywords: ["metrics", "statistics", "45 filings", "18 startups", "30 partners", "500 innovators", "impact"]
    },
    {
        id: "office-location",
        title: "Office Location: 168 Gorordo Ave, Cebu City",
        category: "Contact",
        description: "TTBDO Office of the Chancellor, University of the Philippines Cebu, 168 Gorordo Ave, Cebu City, 6000 Cebu, Philippines.",
        url: "#contact",
        keywords: ["location", "address", "gorordo", "cebu city", "office", "door 4", "contact", "map", "phone", "email"]
    },
    {
        id: "tdf-form",
        title: "Technology Disclosure Form (TDF) Download",
        category: "Forms",
        description: "Official initial disclosure document required to begin IP evaluation and patent search assistance.",
        url: "assets/downloads/sample-form.pdf",
        keywords: ["tdf", "technology disclosure form", "form", "download", "pdf", "file invention"]
    }
];

// Asynchronously load JSON database with fallback
async function loadSearchData() {
    try {
        const res = await fetch("search-data.json");
        if (res.ok) {
            searchData = await res.json();
        } else {
            searchData = defaultSearchData;
        }
    } catch (err) {
        searchData = defaultSearchData;
    }

    if (searchResultsCount) {
        searchResultsCount.textContent = `${searchData.length} entries indexed from JSON`;
    }
}

// Category Badge Color Helper
function getCategoryBadge(cat) {
    switch (cat) {
        case "Programs":
            return `<span class="text-[9.5px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-green-base/15 text-green-dark border border-green-base/20">Program</span>`;
        case "News":
            return `<span class="text-[9.5px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-maroon-base/15 text-maroon-base border border-maroon-base/20">News</span>`;
        case "Events":
            return `<span class="text-[9.5px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-gold-base/20 text-gold-deep border border-gold-base/30">Event</span>`;
        case "About Us":
            return `<span class="text-[9.5px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-ink-base/10 text-ink-base border border-ink-base/20">About</span>`;
        case "Contact":
            return `<span class="text-[9.5px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-blue-500/15 text-blue-800 border border-blue-500/20">Location</span>`;
        default:
            return `<span class="text-[9.5px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-gold-base/20 text-gold-deep border border-gold-base/30">${cat}</span>`;
    }
}

// Highlight matching search terms safely
function highlightText(text, query) {
    if (!query || !query.trim()) return text;
    const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    const regex = new RegExp(`(${escaped})`, "gi");
    return text.replace(regex, '<mark class="search-highlight">$1</mark>');
}

// Open Search Modal with Animation
function openSearchModal() {
    if (!searchModal) return;
    searchModal.classList.add("is-open");
    document.body.style.overflow = "hidden";
    selectedIndex = -1;

    // Load data if empty
    if (!searchData.length) {
        loadSearchData();
    }

    setTimeout(() => {
        if (searchModalInput) {
            searchModalInput.focus();
            searchModalInput.select();
        }
        renderSearchResults();
    }, 60);
}

// Close Search Modal with Animation
function closeSearchModal() {
    if (!searchModal) return;
    searchModal.classList.remove("is-open");
    document.body.style.overflow = "";
    if (searchModalInput) {
        searchModalInput.value = "";
    }
}

// Filter and Render Search Results
function renderSearchResults() {
    if (!searchResultsList) return;
    const query = (searchModalInput ? searchModalInput.value : "").trim().toLowerCase();

    // Filter items
    let filtered = searchData.filter(item => {
        // Category filter chip check
        if (activeFilter !== "all" && item.category !== activeFilter) {
            return false;
        }

        if (!query) return true;

        const titleMatch = item.title.toLowerCase().includes(query);
        const descMatch = item.description.toLowerCase().includes(query);
        const keywordMatch = (item.keywords || []).some(k => k.toLowerCase().includes(query));
        const catMatch = item.category.toLowerCase().includes(query);

        return titleMatch || descMatch || keywordMatch || catMatch;
    });

    // Update count indicator
    if (searchResultsCount) {
        if (query) {
            searchResultsCount.textContent = `${filtered.length} matching result${filtered.length === 1 ? '' : 's'} for "${query}"`;
        } else {
            searchResultsCount.textContent = activeFilter === "all" 
                ? `${filtered.length} indexed topics (Showing all)` 
                : `${filtered.length} topics in ${activeFilter}`;
        }
    }

    // Empty state
    if (!filtered.length) {
        searchResultsList.innerHTML = `
            <div class="py-10 px-4 text-center">
                <div class="w-12 h-12 rounded-full bg-maroon-base/10 text-maroon-base flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <h4 class="font-serif text-base font-bold text-maroon-base">No results found for "${query}"</h4>
                <p class="text-xs text-ink-muted mt-1 max-w-sm mx-auto">
                    Try searching for <span class="font-semibold text-gold-deep">"patents"</span>, <span class="font-semibold text-gold-deep">"incubation"</span>, <span class="font-semibold text-gold-deep">"grants"</span>, or <span class="font-semibold text-gold-deep">"168 Gorordo"</span>.
                </p>
            </div>
        `;
        return;
    }

    // Render items list
    searchResultsList.innerHTML = filtered.map((item, idx) => {
        const isSelected = idx === selectedIndex;
        const highlightedTitle = highlightText(item.title, query);
        const highlightedDesc = highlightText(item.description, query);

        return `
            <a href="${item.url}" data-idx="${idx}" class="search-result-item block p-3 rounded-xl border border-transparent hover:border-gold-base/40 transition-all ${isSelected ? 'is-selected bg-cream-bg' : ''}">
                <div class="flex items-start justify-between gap-3 mb-1">
                    <h4 class="font-serif text-sm sm:text-[14.5px] font-bold text-maroon-base leading-snug">
                        ${highlightedTitle}
                    </h4>
                    <div class="shrink-0">
                        ${getCategoryBadge(item.category)}
                    </div>
                </div>
                <p class="text-[11.5px] sm:text-xs text-ink-muted leading-relaxed line-clamp-2">
                    ${highlightedDesc}
                </p>
                <div class="flex items-center gap-1.5 mt-2 text-[10px] font-semibold text-green-base">
                    <span>Navigate to section</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </div>
            </a>
        `;
    }).join("");

    // Attach click handlers to navigate and dismiss modal
    searchResultsList.querySelectorAll(".search-result-item").forEach(itemEl => {
        itemEl.addEventListener("click", (e) => {
            const href = itemEl.getAttribute("href");
            if (href) {
                closeSearchModal();
                if (href.startsWith("#")) {
                    e.preventDefault();
                    setActiveNav(href);
                    const targetEl = document.querySelector(href);
                    if (targetEl) {
                        const headerHeight = headerEl ? headerEl.offsetHeight : 80;
                        const targetY = Math.max(0, targetEl.getBoundingClientRect().top + window.pageYOffset - headerHeight - 12);
                        window.scrollTo({
                            top: targetY,
                            behavior: "smooth"
                        });
                        if (history.pushState) {
                            history.pushState(null, null, href);
                        }
                    }
                }
            }
        });
    });
}

// Event Listeners for Search
if (searchToggleBtn) {
    searchToggleBtn.addEventListener("click", openSearchModal);
}
if (mobileSearchToggleBtn) {
    mobileSearchToggleBtn.addEventListener("click", openSearchModal);
}
if (searchModalClose) {
    searchModalClose.addEventListener("click", closeSearchModal);
}

// Click outside modal card to dismiss
if (searchModal) {
    searchModal.addEventListener("click", (e) => {
        if (e.target === searchModal) {
            closeSearchModal();
        }
    });
}

// Typing in search input
if (searchModalInput) {
    searchModalInput.addEventListener("input", () => {
        selectedIndex = -1;
        renderSearchResults();
    });

    // Keyboard navigation (Up, Down, Enter, Escape)
    searchModalInput.addEventListener("keydown", (e) => {
        const items = searchResultsList ? searchResultsList.querySelectorAll(".search-result-item") : [];
        if (!items.length) return;

        if (e.key === "ArrowDown") {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % items.length;
            renderSearchResults();
            const selectedEl = searchResultsList.querySelector(`[data-idx="${selectedIndex}"]`);
            if (selectedEl) selectedEl.scrollIntoView({ block: "nearest" });
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            renderSearchResults();
            const selectedEl = searchResultsList.querySelector(`[data-idx="${selectedIndex}"]`);
            if (selectedEl) selectedEl.scrollIntoView({ block: "nearest" });
        } else if (e.key === "Enter") {
            e.preventDefault();
            if (selectedIndex >= 0 && items[selectedIndex]) {
                items[selectedIndex].click();
            } else if (items[0]) {
                items[0].click();
            }
        } else if (e.key === "Escape") {
            closeSearchModal();
        }
    });
}

// Filter chips click handling
filterChips.forEach(chip => {
    chip.addEventListener("click", () => {
        filterChips.forEach(c => {
            c.classList.remove("bg-maroon-base", "text-white");
            c.classList.add("bg-cream-bg", "text-ink-base");
        });
        chip.classList.remove("bg-cream-bg", "text-ink-base");
        chip.classList.add("bg-maroon-base", "text-white");

        activeFilter = chip.getAttribute("data-filter") || "all";
        selectedIndex = -1;
        renderSearchResults();
    });
});

// Global Keyboard Shortcut (Ctrl+K, Cmd+K, or Slash '/')
document.addEventListener("keydown", (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "k") {
        e.preventDefault();
        openSearchModal();
    } else if (e.key === "/" && document.activeElement.tagName !== "INPUT" && document.activeElement.tagName !== "TEXTAREA") {
        e.preventDefault();
        openSearchModal();
    } else if (e.key === "Escape" && searchModal && searchModal.classList.contains("is-open")) {
        closeSearchModal();
    }
});

// Load search JSON index on startup
loadSearchData();