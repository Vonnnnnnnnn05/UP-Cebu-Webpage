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

                const headerHeight = headerEl ? headerEl.offsetHeight : 76;
                const targetY = href === "#home" 
                    ? 0 
                    : Math.max(0, targetEl.getBoundingClientRect().top + window.pageYOffset - headerHeight + 2);

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

    const sectionIds = ["contact", "news", "resources", "programs", "about", "home"];

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