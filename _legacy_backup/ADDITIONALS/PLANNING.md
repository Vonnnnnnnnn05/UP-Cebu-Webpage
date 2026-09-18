# UP Cebu TTBDO Website — PHP Backend Planning & Architecture Guide

## 1. Project Overview & Architectural Suggestion

The current **UP Cebu TTBDO** website is constructed with modern Tailwind CSS and a modular section architecture included via `index.php` (`SECTIONS/header.html`, `SECTIONS/home.html`, `SECTIONS/news.html`, etc.).

### Suggested Architecture: "Modular Component CMS"
Rather than introducing a heavy framework (like Laravel) which complicates deployment on standard university XAMPP servers, the cleanest and most maintainable approach is a **Modular Component Architecture with Procedural / Clean MySQLi Helpers**:
1. **Frontend (Public Portal)**:
   - Keep the clean design and UI intact.
   - Upgrade data-heavy sections (`news.html`, `events.html`, `contact.html`) into PHP-enabled components (`news.php`, `events.php`, `contact.php`).
   - Load dynamic records directly from MySQL via prepared statements (`mysqli_prepare` / `bind_param`).
2. **Backend (Admin Portal in `ADMIN/`)**:
   - Provide an authenticated management dashboard for TTBDO staff to publish news, schedule events, and view incoming inquiries without touching HTML code.
3. **Database Layer (`ADDITIONALS/`)**:
   - Centralized `db_connect.php` providing a reusable `$conn` and four lightweight wrapper functions (`db_fetch_all`, `db_fetch_one`, `db_execute`, `e`) using strict prepared statements.

---

## 2. Rules & Conventions

- **Database Driver**: Use `mysqli` exclusively (no PDO).
- **Security**: Always use parameterized queries (`bind_param`) for all user-supplied inputs and variables. Never concatenate raw `$_GET` or `$_POST` into SQL strings.
- **Output Escaping**: Use the `e($str)` helper (`htmlspecialchars`) to prevent XSS.
- **Frontend Aesthetics**: Strictly use **SVG vector icons** (Lucide / Heroicons style) instead of emojis.
- **Code Simplicity**: Flat, understandable PHP scripts with minimal nesting, clear comments, and robust error handling.

---

## 3. Database Schema Overview (`ADDITIONALS/database.sql`)

The database `up_cebu_ttbdo_db` has been designed and tested with the following tables:

| Table | Purpose | Key Fields |
| :--- | :--- | :--- |
| **`admins`** | Authentication & roles | `id`, `username`, `email`, `password_hash`, `full_name`, `role`, `last_login` |
| **`news`** | News, research, grant calls | `id`, `title`, `slug`, `category`, `badge_label`, `summary`, `content`, `image_url`, `is_featured`, `is_published`, `published_date` |
| **`events`** | Summits, clinics, workshops | `id`, `title`, `slug`, `category`, `event_date`, `start_time`, `end_time`, `venue`, `venue_type`, `summary`, `is_featured`, `status` |
| **`inquiries`** | Contact & IP consultation requests | `id`, `full_name`, `email`, `contact_number`, `affiliation`, `inquiry_type`, `subject`, `message`, `status` |
| **`site_settings`**| Office contacts, phone, motto | `setting_key`, `setting_value`, `setting_group`, `description` |

> Default Admin Account:
> - **Username**: `admin`
> - **Password**: `admin123`

---

## 4. Step-by-Step Implementation Roadmap

### Phase 1: Database Connection Layer (Completed)
- [x] Create `ADDITIONALS/database.sql` with schema and seed data.
- [x] Create `ADDITIONALS/db_connect.php` with MySQLi prepared statement helpers.
- [x] Verify database creation and table accessibility in local MariaDB.

### Phase 2: Dynamic Public Sections (What to Alter)
1. **`index.php`**:
   - Add `require_once 'ADDITIONALS/db_connect.php';` at the top of the file.
   - Fetch global settings and pass connection to sections.
2. **`SECTIONS/news.php`** (Convert from `news.html`):
   - Query 1: Fetch featured article (`WHERE is_featured = 1 LIMIT 1`).
   - Query 2: Fetch recent 3 news items (`WHERE is_featured = 0 AND is_published = 1 ORDER BY published_date DESC LIMIT 3`).
   - Replace static hardcoded articles with a clean PHP `foreach` loop.
3. **`SECTIONS/events.php`** (Convert from `events.html`):
   - Query 1: Fetch upcoming featured event (e.g. "Central Visayas Innovation Summit").
   - Query 2: Fetch next upcoming events (`WHERE status = 'upcoming' ORDER BY event_date ASC`).
   - Render dates dynamically (`date('M d, Y', strtotime($row['event_date']))`).
4. **`SECTIONS/contact.php`** (Enhance `contact.html`):
   - Integrate an inquiry form or modal allowing faculty, students, and MSMEs to submit IP or licensing inquiries.
   - Process submission via POST request using `db_execute()` with `bind_param`.

### Phase 3: Admin Dashboard (`ADMIN/`)
Structure for the empty `ADMIN` folder:
- **`ADMIN/login.php`**: Secure login form with session handling (`$_SESSION['admin_id']`).
- **`ADMIN/logout.php`**: Destroys session and redirects to login.
- **`ADMIN/auth_check.php`**: Protects all admin pages from unauthorized access.
- **`ADMIN/index.php`**: Dashboard displaying counts (total news, upcoming events, new inquiries).
- **`ADMIN/manage-news.php`**: List, create, edit, and delete news articles.
- **`ADMIN/manage-events.php`**: List, schedule, edit, and delete calendar events.
- **`ADMIN/manage-inquiries.php`**: View contact messages, filter by status (`pending`, `in_review`, `resolved`).

### Phase 4: Dynamic Search (`api/search.php`)
- Provide a clean JSON endpoint that queries both `news` and `events` tables, or dynamically refreshes `search-data.json` when an admin publishes new content.

---

## 5. Summary of What to Alter in Existing Files

| File | Proposed Change |
| :--- | :--- |
| `index.php` | Add `require_once 'ADDITIONALS/db_connect.php';`, change `include 'SECTIONS/news.html'` to `news.php`, and `events.html` to `events.php`. |
| `SECTIONS/news.html` -> `.php` | Replace static articles with `foreach ($news_items as $item)` loop. |
| `SECTIONS/events.html` -> `.php` | Replace static event cards with dynamic event date queries. |
| `SECTIONS/contact.html` -> `.php` | Add dynamic submission handler for visitor inquiries. |
| `ADMIN/` | Populate with clean, simple admin management scripts. |
