# AI Instructions & Rules — HRIS System

> [!IMPORTANT]
> **Core Directive**: AI assistants MUST read and adhere to these rules for EVERY task.  
> 1) **Never regress** existing UX or dark mode.  
> 2) **Prefer existing patterns** over inventing new ones.  
> 3) When in doubt, treat:
>    - `admin/users/index.blade.php` as the **gold standard** for tables & empty states.  
>    - `admin/calendar/index.blade.php` as the **gold standard** for calendars & modals.  
>    - `layouts/dashboard.blade.php` as the **gold standard** for layout & navigation.

---

## 1. Design System (shadcn/ui Inspired)

The system follows a clean, minimal, and high-contrast professional aesthetic.

### 1.1 Visual Tokens
- **Borders**: `border border-gray-200 dark:border-neutral-800`.
- **Card Backgrounds**:
  - Light: `bg-white`.
  - Dark: `dark:bg-neutral-900` (never `dark:bg-neutral-950` for cards).
- **Shadows**: `shadow-sm` for cards/tables; `shadow` for dropdowns/modals. Avoid `shadow-lg`+.
- **Radius**: `rounded-md` (6px) for cards, inputs, buttons.  
  - `rounded-full` ONLY for avatars and small badges (chips, status dots).
- **Spacing**: Prefer `space-y-6`, `gap-4`, `gap-6`, `p-4` / `p-6` for consistency.

### 1.2 Colors (Brand Palette)
- **Primary**: `#013CFC` (Electric Blue). Hover: `#0031BC`.
- **Surface (Page)**:
  - Light: `bg-[#FDFDFD]`.
  - Dark: `dark:bg-neutral-950`.
- **Card Surfaces**:
  - Light: `bg-white`.
  - Dark: `dark:bg-neutral-900`.
- **Muted Text**: `text-gray-500 dark:text-gray-400`.
- **Body Text**: `text-gray-900 dark:text-gray-100`.
- **Semantic**:
  - Approved / Success: Emerald (`bg-emerald-100 text-emerald-800` / `dark:bg-emerald-500/30 dark:text-emerald-400`).
  - Rejected / Error: Red (`bg-red-100 text-red-800` / `dark:bg-red-500/30 dark:text-red-400`).
  - Pending / Warning: Amber (`bg-amber-100 text-amber-800` / `dark:bg-amber-500/30 dark:text-amber-400`).
- **Never** introduce new random HEX colors; reuse these or Tailwind tokens.

### 1.3 Dark Mode Rules
- **Dark mode should be dark, not blue**:
  - Avoid blue-tinted backgrounds like `bg-[#013CFC]/[0.08]` for large surfaces.
  - Use neutrals: `dark:bg-neutral-900` for cards, `dark:bg-neutral-950` only for full page background.
- **Contrast**:
  - Do NOT use `dark:text-gray-400` on very dark backgrounds for primary text; use `dark:text-gray-100` or `dark:text-gray-200`.
  - Icons in dark mode should be at least `text-gray-300`; never `text-gray-500` on `dark:bg-neutral-900`.
- **Borders**:
  - Replace `dark:border-neutral-900` with `dark:border-neutral-800` or `dark:border-neutral-700` to ensure visible separation.

---

## 2. Core Components & UX Patterns

### 2.1 Modals (Standard Pattern)
- **Widths**:
  - Simple forms: `max-w-md` / `max-w-lg`.
  - Complex forms or details (training, leave, PDS): `max-w-2xl` or `max-w-4xl`.
- **Shell**:
  - Overlay: `fixed inset-0 bg-black/50`.
  - Centering: parent `fixed inset-0 flex items-center justify-center p-4 pointer-events-none`.
  - Dialog: child `relative w-full max-w-? rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm flex flex-col max-h-[80vh] pointer-events-auto`.
- **Sections**:
  - **Header**: `px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex items-center justify-between`.
  - **Body**: `flex-1 min-h-0 overflow-y-auto p-6`.
  - **Footer**: `px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3`.
- **Buttons**:
  - Primary: `h-9 px-4 rounded-md bg-[#013CFC] text-white text-sm font-medium hover:bg-[#013CFC]/90`.
  - Secondary: `h-9 px-4 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-neutral-800`.
- **Behavior Rules (role-specific)**:
  - Employee Training Details (employee page): **must always** have a Close button, even if status = Pending.
  - HR Leave Application Details (HR page): **no Close button** when status = Pending (force Approve/Reject).
  - HR Training Details:
    - Footer button label must be **“Close”** (not “Close Details”).
    - When status = Pending, show **Approve** and **Reject** buttons.
  - HR PDS Preview (Approved status): Must have a visible **Close** button in the footer.

### 2.2 Tables (Standard Pattern)
- **Container**:  
  `rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden`.
- **Header Row**:
  - `text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider`.
  - Use `bg-gray-50 dark:bg-neutral-900` for thead background.
- **Employee Cell** (pattern from `admin/users/index.blade.php`):
  - Avatar circle: `h-10 w-10 rounded-full bg-[#013CFC]/10 text-[#013CFC] flex items-center justify-center text-sm font-bold`.
  - Name & ID stacked with `ml-3` and `space-y-0.5`.
- **Status Chips**:
  - Base: `inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold`.
  - Colors: Emerald/Red/Amber as in 1.2.

### 2.3 Empty States (Global Standard)
- **Always** follow the pattern used in `admin/users/index.blade.php` and calendar agenda empty states:
  - Icon container: `w-12 h-12 rounded-md bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-3`.
  - Icon: `w-6 h-6 text-gray-400`.
  - Title: `text-sm font-medium text-gray-900 dark:text-gray-100`.
  - Description: `text-sm text-gray-500 dark:text-gray-400 mt-1`.
- NEVER leave plain text like “No data found” without this structured layout.

### 2.4 Calendars (FullCalendar) — Admin / HR / Employee
- Use **Admin Calendar** (`admin/calendar/index.blade.php`) as source of truth for:
  - Custom header (prev/next/Today + view switcher Year/Month/Week/List).
  - Dark mode colors (borders, day numbers, hover states).
  - Event pills and modals.
- **Styling Rules**:
  - FullCalendar root:
    - Light: `--fc-border-color: #f1f5f9`.
    - Dark: `--fc-border-color: #404040`.
  - Day numbers:
    - Light: `text-gray-500`.
    - Dark: `text-neutral-400` / `text-gray-300` (never too dim).
  - Today:
    - Use circular highlight: dark text on light circle in dark mode, inverse in light mode.
  - Hover:
    - Light: `#f8fafc`.
    - Dark: `#171717`.
- **Events Coloring (logic)**:
  - Category = `holiday` → **Red**.
  - Category contains `training` → **Blue**.
  - Category = `leave`:
    - Status = `approved` → Emerald.
    - Status = `pending` → Amber.
    - Status = `rejected` → Red.
- **Admin Calendar**:
  - Events endpoint returns **holidays only**; if user filters to leave/training, return empty JSON.
  - Right-hand column shows **Custom Holidays** with cards matching the existing design.
- **HR & Employee Calendars**:
  - Use realistic mock events for leave & training plus holidays (Google + CustomHoliday).
  - Filters:
    - Category filter: `all | leave | training | holiday`.
    - Status filter: shown only when category = `leave`.
  - Agenda “Today” list must only show:
  - Events happening **today**, and
  - Either holidays or `status = approved` for leave/training.

### 2.5 Cards & Stat Blocks
- **Generic Cards**:
  - Wrapper: `rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm p-4` (or `p-6` on dashboards).
  - Use `space-y-2` / `space-y-3` inside, not random margins.
- **Dashboard Stat Cards** (totals, counts, etc.):
  - Layout: small label, big number, optional trend.
  - Title: `text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide`.
  - Value: `text-2xl font-semibold text-gray-900 dark:text-gray-50`.
  - Subtitle or trend: `text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1`.
  - Icon slot (if used): right-aligned faint icon: `w-5 h-5 text-gray-300 dark:text-neutral-700`.
- **List Cards (e.g., custom holidays, agenda items)**:
  - Use border + hover states:
    - Base: `p-3 rounded-md border border-gray-100 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm transition-all`.
    - Hover: `hover:border-[#013CFC]/30 hover:bg-[#013CFC]/5 dark:hover:bg-[#013CFC]/5`.
  - Leading visual:
    - Date bubble: `w-10 h-10 rounded-md bg-gray-50 dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 flex flex-col items-center justify-center`.
  - Text:
    - Title: `text-xs font-semibold text-gray-900 dark:text-gray-100 truncate`.
    - Meta row: `flex items-center gap-1.5 mt-0.5 text-[10px] text-gray-400`.

### 2.6 Dropdowns & Menus
- **Trigger Button**:
  - Base: `inline-flex items-center gap-2 h-9 px-3 rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800 shadow-sm`.
  - Chevron icon: `w-4 h-4 text-gray-400`.
- **Menu Panel**:
  - Position: `absolute right-0 top-full mt-2` relative to trigger container.
  - Shell: `w-48 rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-lg py-1 z-50`.
- **Menu Items**:
  - Each item: `flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-neutral-800 cursor-pointer`.
  - Optional icon: `w-4 h-4 text-gray-400`.
  - Dividers: `border-t border-gray-100 dark:border-neutral-800 my-1`.
- **Behavior**:
  - Close when clicking outside (document click handler).
  - Close on Escape key when menu is open.

### 2.7 Message Modals & Alerts (Inline Dialogs)
- **Message / Confirmation Modals** (e.g., delete confirm, info-only dialogs):
  - Reuse the standard modal shell (2.1) but keep body simple:
    - Icon row at top:
      - Container: `w-10 h-10 rounded-md bg-red-50 dark:bg-red-900/20 flex items-center justify-center mb-3` for destructive actions.
      - Icon: `w-5 h-5 text-red-500`.
    - Title: `text-sm font-semibold text-gray-900 dark:text-gray-100`.
    - Description: `text-sm text-gray-600 dark:text-gray-300 mt-1`.
  - Footer buttons:
    - Cancel: secondary button style.
    - Confirm: colored according to action (e.g., `bg-red-600 hover:bg-red-700` for delete).
- **Inline Alerts / Banners** (inside pages or dashboards):
  - Container: `mb-4 rounded-md p-4 flex items-start gap-3`.
  - Background + text color based on type:
    - `info`: `bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300`.
    - `success`: `bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300`.
    - `warning`: `bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300`.
    - `danger`: `bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300`.
  - Icon: `w-5 h-5` aligned to top.
  - Title: `text-sm font-medium`.
  - Message: `text-sm opacity-90`.
  - Close button (if dismissible): small ghost icon button `p-1 rounded-md hover:bg-black/10 dark:hover:bg-white/10`.

### 2.8 Toasts / Temporary Messages
- Prefer **inline banners** near the content (see 2.7) over floating toasts, unless a design already exists.
- If using floating toast:
  - Position: `fixed bottom-4 right-4 z-50 space-y-2`.
  - Card: `max-w-sm rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-lg p-3 flex items-start gap-2`.
  - Keep text short and action-light (e.g., “Undo” link).


---

## 3. Architecture & File Structure (Feature-First)

The app is now organized by **feature/domain**, not by type.

### 3.1 Controllers
- New controllers belong under `app/Features/{Feature}/Http/Controllers[/Role]/...`.
  - Examples:
    - `App\Features\Calendar\Http\Controllers\Admin\CalendarController`.
    - `App\Features\Users\Http\Controllers\Admin\UserController`.
    - `App\Features\Leave\Http\Controllers\HR\LeaveController`.
- **Do NOT** create new controllers under `app/Http/Controllers` (except the base `Controller`).

### 3.2 Models
- New domain models belong under `app/Features/{Feature}/Models`.
  - Examples:
    - `App\Features\Calendar\Models\CustomHoliday`.
    - `App\Features\Notices\Models\Notice`.
    - `App\Features\Users\Models\User`.
- **Auth model** is `App\Features\Users\Models\User` and configured in `config/auth.php`.

### 3.3 Views
- Feature entry views live under `resources/views/features/{feature}/{role}/...`.
  - They may internally render older `admin.*`, `hr.*`, `employee.*` views, but **new work should prefer features paths**.
  - Examples:
    - `features.calendar.admin.index`.
    - `features.leave.hr.index`.
    - `features.dashboard.employee.index`.
- When updating a screen:
  - Prefer editing the **original role-based view** (e.g., `admin/calendar/index.blade.php`) if the wrapper just proxies to it.
  - If creating a brand new screen, create it directly under `features/*` and route to that.

### 3.4 Routes
- Web routes are split by concern:
  - `routes/web/public.php` — landing & public pages.
  - `routes/web/auth.php` — login, register, forgot password.
  - `routes/web/dashboard.php` — `/admin/dashboard`, `/hr/dashboard`, `/employee/dashboard` + `/dashboard` redirect.
  - `routes/web/admin.php`, `routes/web/hr.php`, `routes/web/employee.php` — role-specific modules & features.
- When adding routes:
  - Put **Admin** features in `routes/web/admin.php`.
  - Put **HR** features in `routes/web/hr.php`.
  - Put **Employee** features in `routes/web/employee.php`.
  - Always import controllers from `App\Features\...`, not `App\Http\Controllers\...`.

---

## 4. Technical Implementation Details

### 4.1 Stack
- **PHP**: 8.2.12  
- **Composer**: 2.9.5  
- **Backend**: Laravel 12.48.1  
- **Frontend**: Tailwind CSS, Vanilla JS (no new JS libs like jQuery/Alpine unless already present).  
- **Build**: Vite (`npm run dev`, `npm run build`).

### 4.2 JavaScript Boundaries
- **Placement**: Every inline `<script>` in Blade must live inside `@push('scripts')`.
- **Scope**:
  - Use `window.functionName` only when an inline Blade `onclick="..."` or similar needs it.
  - Otherwise, keep functions local to the DOMContentLoaded handler.
- **File Upload / Attachments**:
  - Reuse existing patterns (e.g., leave/training attachment UIs); never invent new ad-hoc implementations.

### 4.3 Navigation & Layouts
- **Layout**: `layouts/dashboard.blade.php` is the main shell and must remain the single source of truth.
- **Sidebar**:
  - Defined in `partials/sidebar.blade.php`.
  - Active state uses `request()->routeIs(...)` with a subtle background: `bg-[#013CFC]/10` and text/icon color `text-[#013CFC]`.
- **Icons**:
  - Use Lucide-style SVG icons.
  - Sizes:
    - Inline and table icons: `w-4 h-4`.
    - Navigation icons: `w-5 h-5`.

---

## 5. AI Pitfalls & Guardrails (DO NOT DO THESE)

- **❌ No Pill Buttons**:
  - Do not use `rounded-full` on primary/secondary buttons.
  - Use `rounded-md` consistently.
- **❌ No Placeholder Stock Images**:
  - Use CSS or SVG initials for avatars.
- **❌ No Direct Deletes without CSRF**:
  - Always use `<form method="POST">` with `@csrf` and `@method('DELETE')` for destructive actions.
- **❌ No Random Global JS**:
  - Do not attach arbitrary properties to `window` except when bridging Blade event handlers.
- **❌ No Hardcoded Magic Colors**:
  - Use Tailwind utilities or the brand HEX values defined above.
- **❌ No SVG Bloat**:
  - Keep icons simple and consistent with existing ones.
- **❌ No Regressions in Dark Mode**:
  - Never reintroduce overly dark blues, unreadable text, or low-contrast icons.
- **❌ No Breaking Feature-First Structure**:
  - Do not recreate old `App\Http\Controllers\FooController` or `App\Models\Bar` under legacy namespaces.

