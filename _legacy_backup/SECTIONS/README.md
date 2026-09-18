# Modular Sections Directory (`/SECTIONS`)

This directory contains individual, standalone HTML section components extracted from the primary template for cleaner maintainability and modular architecture.

---

## File Manifest

| File | Description | Anchor ID |
| :--- | :--- | :--- |
| **`header.php`** | Sticky top bar (Logo, brand, navigation, search toggle, Staff Login button, mobile drawer) | `#home` |
| **`home.php`** | Campus hero banner, Oblation backdrop, badge, and call-to-action buttons | `#home` |
| **`news.php`** | Dynamic MySQL-driven news grid, featured DOST partnership article, announcement cards | `#news` |
| **`programs.php`** | Innovation portfolio (IP Rights, Incubation, SIMP, Licensing, MSME, Internship) + pathways | `#programs` |
| **`events.php`** | Dynamic MySQL-driven calendar, flagship Innovation Summit, and legal clinics | `#events` |
| **`about.php`** | Institutional mandate, Vision & Mission, 4 Strategic Pillars, impact metrics, office card | `#about` |
| **`contact.php`** | Innovation Consultation & Inquiry Form (inserts into MySQL) + Official Footer | `#inquire` / `#contact` |
| **`footer.php`** | Standalone footer brand mark, address, phone, email, socials, UP motto | `#contact` |
| **`search-modal.php`** | Animated search modal dialog card, filter chips, input, and keyboard navigation controls | `#search-modal` |
| **`back-to-top.php`** | Floating smooth back-to-top button | `#back-to-top-btn` |

---

## Usage in PHP (Local XAMPP Server)

All sections are modular PHP components included cleanly in [`index.php`](../index.php):

```php
<?php
require_once __DIR__ . '/config/sessions.php';
require_once __DIR__ . '/config/conn.php';
?>
...
<?php include 'SECTIONS/header.php'; ?>
<main>
    <?php include 'SECTIONS/home.php'; ?>
    <?php include 'SECTIONS/news.php'; ?>
    <?php include 'SECTIONS/programs.php'; ?>
    <?php include 'SECTIONS/events.php'; ?>
    <?php include 'SECTIONS/about.php'; ?>
</main>
<?php include 'SECTIONS/contact.php'; ?>
<?php include 'SECTIONS/back-to-top.php'; ?>
<?php include 'SECTIONS/search-modal.php'; ?>
```
