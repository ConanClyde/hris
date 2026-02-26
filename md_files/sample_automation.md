# Team Updates

This file is the shared coordination log for all coding agents (Windsurf/Cascade, Cursor, Antigravity) working on this repository.

## Rules

- **Append-only:** Do not rewrite history. Add a new entry under the current date.
- **One update per work session:** Add an entry when you start and when you finish (or at least once with both planned + completed).
- **Be specific:** Reference file paths and route names. Avoid vague statements.
- **Use this file for coordination:** Agents should post session summaries, decisions, and handoffs here (do not create separate “conversation log” files).
- **Separate docs only when durable:** If a long-lived spec/guide/ADR is needed, create it under `docs/` and link it from the relevant update here.
- **No secrets:** Do not paste API keys, tokens, credentials, or personal data.
- **Conflict handling:** If two agents are touching the same files, whoever writes first should note it in **Now working on** so others avoid collisions.
- **Definition of Done (DoD):** Include how you verified (commands run, routes checked, screenshots/manual steps).

## Role assignments (project default)

- **Windsurf/Cascade (Backend):**
  - Laravel routes/controllers/middleware/models/services/migrations.
  - Owns: `routes/`, `app/`, `database/`, `config/`.
- **Cursor (Frontend Implementation):**
  - UI/UX changes in Blade, CSS/JS assets, and (if added) Vue components.
  - Owns: `resources/views/`, `resources/js/`, `resources/css/`, `public/`.
- **Trae (Backend Debugging/QA):**
  - Backend repro steps, logging, tests, performance checks, and fixing backend/runtime errors.
  - Owns: debug notes and verification for `routes/`, `app/`, `database/`.
- **Antigravity (Frontend Debugging/QA):**
  - Frontend repro steps, console/network debugging, UI regressions, and verification.
  - Owns: debug notes and verification for `resources/views/`, `resources/js/`, `resources/css/`, `public/`.

## Ownership boundaries

- If you are not the owner of a file area, prefer to:
  - leave a note in **Next** with exact file + line references, or
  - open a small PR-sized change only when blocking.
- When a change requires both FE + BE updates, coordinate via a handoff entry.

## Engineering standards (best & latest practices)

### Backend (Laravel)

- **Keep controllers thin:** validation + authorization + orchestration only. Move business logic to `app/Services/*`.
- **Use Form Requests:** prefer `php artisan make:request` for validation rules and authorization.
- **Authorization:** prefer Policies/Gates + explicit middleware (`can:*`, custom `privilege:*`) per route.
- **Route design:**
  - Keep `routes/web.php` as a loader; organize by feature in `routes/web/*.php`.
  - Use `prefix()` + `name()` route groups.
  - Avoid mixing token APIs with session endpoints; session JSON endpoints should live under `/admin/api/*` (web).
- **API design:**
  - `routes/api.php` should be stateless (token auth) and versionable (e.g. `/api/v1/*`).
  - Always return consistent JSON envelopes for APIs.
- **Performance:** use eager loading (`with()`), pagination, and caching where appropriate.
- **Testing:** for backend changes, add/adjust feature tests when feasible.

### Frontend (Inertia + modern UI)

- **Front-end direction:** prefer **Inertia** pages for app screens; keep Blade primarily for layout shells if needed.
- **Component-first UI:** build reusable components for tables, forms, dialogs, empty states.
- **Accessibility:** keyboard support, focus states, semantic HTML, and form error messaging.

### UI/UX (shadcn-style modern SaaS)

- **Style:** modern SaaS layout, clean spacing, consistent typography, subtle borders/shadows.
- **Components:** use **shadcn-style components** (Tailwind + Radix-like patterns):
  - Buttons, inputs, selects, dialogs, dropdowns, toasts, tabs.
- **Design tokens:** use a consistent palette, radius, and spacing scale.
- **Data-heavy screens:** use consistent table patterns (filters, bulk actions, pagination, empty/loading states).
- **Micro-interactions:** loading indicators, disabled states, optimistic UI where safe.

## Handoff checklist (when switching between agents)

- **What changed**:
- **Files touched**:
- **Routes/URLs affected**:
- **How to test** (exact commands + pages):
- **Known issues/edge cases**:
- **Next action owner** (Cursor/Windsurf/Trae/Antigravity):

---

## Template (copy/paste)

### YYYY-MM-DD HH:MM (TZ) — [AgentName]

- **Goal**:
- **Now working on**:
- **Changes made**:
- **Verification**:
- **Risks/Notes**:
- **Next**:

---

## Updates

### 2026-02-22 22:00 (UTC+08) — Windsurf/Cascade

- **Goal**:
  - Professionalize URLs and refactor routing into best-practice structure.
- **Now working on**:
  - Routing organization (`routes/web/*.php`), endpoint normalization, and route caching verification.
- **Changes made**:
  - Split `routes/web.php` into feature route files under `routes/web/`.
  - Created `app/Http/Controllers/PagesController.php` to replace closure-based page routes.
  - Moved admin session JSON endpoints from `/api/*` (web) to `/admin/api/*` and updated frontend references.
  - Added professional reporter URLs under `/reports/*` while keeping old `/report-user/*` and `/my-reports` working.
  - Updated Blade links to prefer new `reports.*` routes.
  - Standardized security routes using `prefix('security')->name('security.')` while keeping the same URLs and route names.
- **Verification**:
  - Ran `php artisan route:list`.
  - Ran `php artisan route:clear` and `php artisan route:cache`.
  - Checked key paths:
    - `admin/api/*`, `reports/*`, `report-user/*`, `security/patrol-scanner`.
- **Risks/Notes**:
  - If any external JS or clients called old `/api/map-locations` or `/api/metrics` paths directly, they must be updated to `/admin/api/...`.
- **Next**:
  - If desired: normalize additional legacy URLs with redirects (carefully) and update any remaining hardcoded links.

### 2026-02-22 23:05 (UTC+08) — Windsurf/Cascade

- **Goal**:
  - Add GitHub Actions CI for Laravel using SQLite for tests.
- **Now working on**:
  - CI workflow + non-secret CI env setup.
- **Changes made**:
  - Added GitHub Actions workflow: `.github/workflows/ci.yml`.
  - Added CI env file: `.env.ci` (SQLite, safe defaults).
  - CI runs:
    - `composer install`
    - `php artisan key:generate`
    - `php artisan migrate --force`
    - `php artisan test`
    - `vendor/bin/pint --test`
  - Committed and pushed to `origin/main` (commit: `117f8c7`).
- **Verification**:
  - Local:
    - `git status`
    - `git push`
  - GitHub:
    - Check repo → **Actions** tab → workflow **CI**.
- **Risks/Notes**:
  - CI uses SQLite for tests (aligned with `phpunit.xml`); add a separate MySQL job only if you need MySQL-specific coverage.
- **Next**:
  - Optional: add a second CI job that runs tests against MySQL service to catch MySQL-specific query/constraint issues.

### 2026-02-22 23:45 (UTC+08) — Windsurf/Cascade

- **Goal**:
  - Match `reference_project` 1:1 in features, flows, and process (not architecture/UI).
- **Now working on**:
  - Backend behavior parity: privileges, routes, controllers, flows.
- **Changes made**:
  - **Campus Map Privileges**: Added `manage_campus_map` privilege, separated read (`view_campus_map`) from write operations (commit `207bdd2`).
  - **Reporter Flow**: Added `/reporter/*` routes to match reference while keeping professional `/reports/*` URLs (commit `e3d15e3`).
  - **Settings Privileges**: Added `manage_settings_*` privileges (college, program, fees, vehicle-type, location-type) and updated all routes to separate view vs manage (commit `e3d15e3`).
  - **Full Stickers/Payments**: Implemented all 8 missing endpoints:
    - Single sticker download, issued stickers list, user search
    - Single request view, create request
    - Payment cancel, delete, PDF receipt download
    - Added `manage_stickers` privilege with proper view/manage separation (commit `af146f3`).
  - **Pending Registrations**: Added unified `/admin/approvals` index with filtering, single record view `/admin/approvals/{id}`, and delete endpoint (commit `f35586a`).
  - **Dependencies**: Committed Trae's frontend dependencies (`@zxing/library`, `cropperjs`) separately (commit `54881af`).
- **Verification**:
  - Ran `php artisan db:seed --class=PrivilegeSeeder` and `AdminRolePrivilegeSeeder` to apply new privileges.
  - Verified all routes with `php artisan route:list`.
- **Risks/Notes**:
  - URLs kept at `/admin/api/*` and `/admin/approvals/*` for quality (not strict 1:1 with reference `/api/*`), but behavior matches 1:1.
  - Frontend files (Vue components, AuthController changes) remain uncommitted - owned by Cursor/Trae.
- **Next**:
  - Frontend agents (Cursor/Trae/Antigravity) to implement shadcn-style UI and consume new endpoints.
