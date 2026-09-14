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

// Handle click on all navigation links with sticky header offset
[...desktopLinks, ...mobileLinks].forEach(link => {
    link.addEventListener("click", (e) => {
        const href = link.getAttribute("href");
        if (href && href.startsWith("#")) {
            e.preventDefault();
            setActiveNav(href);
            closeMobileMenu();

            if (href === "#home") {
                window.scrollTo({ top: 0, behavior: "smooth" });
                // Also update URL hash cleanly
                history.pushState(null, null, "#home");
            } else {
                const targetEl = document.querySelector(href);
                if (targetEl) {
                    const header = document.querySelector("header");
                    const headerHeight = header ? header.offsetHeight : 76;
                    const targetY = targetEl.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                    window.scrollTo({ top: targetY, behavior: "smooth" });
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
    if ((window.innerHeight + window.scrollY) >= (document.documentElement.scrollHeight - 60)) {
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

window.addEventListener("scroll", updateScrollSpy, { passive: true });

// Initialize active link on load based on hash or position
window.addEventListener("DOMContentLoaded", () => {
    if (window.location.hash) {
        setActiveNav(window.location.hash);
    } else {
        updateScrollSpy();
    }
});