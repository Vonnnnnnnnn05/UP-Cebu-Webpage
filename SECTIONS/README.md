# Modular Sections Directory (`/SECTIONS`)

This directory contains individual, standalone HTML section components extracted from the primary template for cleaner maintainability and modular architecture.

---

## File Manifest

| File | Description | Anchor ID |
| :--- | :--- | :--- |
| **`header.html`** | Sticky top bar (Logo, two-row brand, desktop navigation, search toggle, mobile drawer) | `#home` |
| **`home.html`** | Campus hero banner, Oblation backdrop, badge, and call-to-action buttons | `#home` |
| **`news.html`** | News grid, featured DOST partnership article, announcement pills, download links | `#news` |
| **`programs.html`** | Innovation portfolio (IP Rights, Incubation, SIMP, Licensing, MSME, Internship) + 3-step pathway + custom slot | `#programs` |
| **`events.html`** | Calendar, featured Innovation Summit 2025 at SRP campus, and legal clinic schedule | `#events` |
| **`about.html`** | Institutional mandate, Vision & Mission, 4 Strategic Pillars, impact metrics, office card (`3rd Flr, Technology Innovation Center`), custom slot | `#about` |
| **`footer.html`** | Footer brand mark, official office address (`3rd Floor, Technology Innovation Center, UP Cebu`), phone, email, socials, UP motto | `#contact` |
| **`contact.html`** | Direct alias of `footer.html` containing the contact and location information | `#contact` |
| **`search-modal.html`** | Animated search modal dialog card, filter chips, input, and keyboard navigation controls | `#search-modal` |
| **`back-to-top.html`** | Floating smooth back-to-top button | `#back-to-top-btn` |

---

## Usage in PHP (Local XAMPP Server)

All files can be included dynamically in PHP via `include`:

```php
<?php include 'SECTIONS/header.html'; ?>
<main>
    <?php include 'SECTIONS/home.html'; ?>
    <?php include 'SECTIONS/news.html'; ?>
    <?php include 'SECTIONS/programs.html'; ?>
    <?php include 'SECTIONS/events.html'; ?>
    <?php include 'SECTIONS/about.html'; ?>
</main>
<?php include 'SECTIONS/footer.html'; ?>
<?php include 'SECTIONS/back-to-top.html'; ?>
<?php include 'SECTIONS/search-modal.html'; ?>
```

The root file [`index.php`](../index.php) already provides this modular integration for XAMPP.
The root file [`index.html`](../index.html) continues to serve as the unified static page for GitHub Pages and offline viewing.
