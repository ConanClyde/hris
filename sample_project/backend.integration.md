# Backend Integration Rules — HRIS System

> These rules define how backend code should be structured and integrated in this project.  
> They complement `ai.rules.md` (UI/UX) and MUST be followed for all new backend work.

---

## 1. Architecture Principles

### 1.1 Feature-First Organization
- **All new backend code must be feature-based**, not type-based:
  - Controllers: `app/Features/{Feature}/Http/Controllers[/Role]/...`
  - Models: `app/Features/{Feature}/Models/...`
  - Jobs / Events / Services (when added): `app/Features/{Feature}/{Jobs,Events,Services}/...`
- Do **not** recreate controllers or models under `app/Http/Controllers` or `app/Models` (except the shared base `Controller`).
- Keep feature boundaries clear:
  - Calendar logic stays in `Features/Calendar`.
  - Notices in `Features/Notices`.
  - Users/auth in `Features/Users` and `Features/Auth`.

### 1.2 Controllers as Thin Orchestrators
- Controllers should:
  - Validate requests using `$request->validate([...])`.
  - Call models/services to perform work.
  - Return views or JSON with already-prepared data structures.
- Controllers should **not**:
  - Contain complex business logic or data transformation pipelines.
  - Talk directly to external APIs without going through a dedicated helper/service (where complexity grows).
  - Render HTML strings manually; always use Blade.

### 1.3 Keep Views Presentation-Only
- Blade views must NOT:
  - Execute queries (no Eloquent or `DB::` in Blade).
  - Perform heavy logic; only simple conditionals/loops are allowed.
- Allowed patterns:
  - Mapping pre-computed values to UI.
  - Transformations like `ucfirst()`, `format('Y-m-d')`, `number_format()` for display.

---

## 2. Auth, Session, and Roles

### 2.1 Current Auth Model
- The system currently uses a **demo configuration**:
  - Users are loaded from `config('demo_users.users')` in `App\Features\Auth\Http\Controllers\AuthController`.
  - Passwords are hashed and verified via `Hash::check`.
  - Session contains:
    - `user_id` — string ID (e.g. `'admin-001'`).
    - `role` — one of `'admin'`, `'hr'`, `'employee'`.
- **Rule**: Whenever you need role checks or auth gating:
  - First check `session('user_id')` to ensure the user is logged in.
  - Then read `session('role')` for role-specific behavior.

### 2.2 Redirect Rules
- If `session('user_id')` is missing:
  - Controller endpoints should either:
    - Redirect to `route('login')` for web pages, or
    - Return `401` JSON for API-style endpoints.
- `/dashboard` must always redirect to the **role-appropriate dashboard**:
  - Admin → `route('admin.dashboard')`
  - HR → `route('hr.dashboard')`
  - Employee (default) → `route('employee.dashboard')`

### 2.3 Future Integration with Real Users Table
- When integrating a real `users` table:
  - Keep the **feature model** as `App\Features\Users\Models\User`.
  - Update `config/auth.php` provider model only (already pointing to this class).
  - Gradually replace:
    - `config('demo_users.users')` with real Eloquent lookups.
    - Session-only role checks with policies/guards **behind** the same controller contracts.

---

## 3. Data Access & Models

### 3.1 Feature Models
- Existing feature models:
  - `App\Features\Calendar\Models\CustomHoliday`
  - `App\Features\Notices\Models\Notice`
  - `App\Features\Users\Models\User`
- Rules:
  - New domain entities must be placed under the correct `Features/*/Models` namespace.
  - Use Eloquent relationships and scopes there, not in controllers.

### 3.2 Sample / Mock Data
- Several features currently use **mock data in-memory** (collections), e.g.:
  - User management, leave applications, training lists, activity logs, backup metadata.
- When converting mocks to real DB:
  - Keep method signatures unchanged where possible (e.g. controllers still receive a `LengthAwarePaginator`).
  - Replace in-place collection pipelines with Eloquent queries+scopes that return equivalent shapes.
  - Preserve realistic sample data semantics (pending/approved/rejected, etc.) so dashboards remain “believable”.

### 3.3 Validation & Mass Assignment
- Always validate user input via `$request->validate([...])` at controller entry.
- Keep `protected $fillable` and `protected $casts` defined on models to:
  - Control which attributes are mass-assignable.
  - Ensure consistent casting (dates, booleans, JSON).

---

## 4. Routes & HTTP Conventions

### 4.1 Route Files
- Use the split routes already in place:
  - `routes/web/public.php` — public pages.
  - `routes/web/auth.php` — auth-related routes.
  - `routes/web/dashboard.php` — dashboards.
  - `routes/web/admin.php` — admin-only modules.
  - `routes/web/hr.php` — HR modules.
  - `routes/web/employee.php` — employee modules.
- All new feature routes must be added to the correct file by role.

### 4.2 Naming & Resource Patterns
- Use explicit names:
  - `admin.users.index`, `hr.leave-applications.index`, `employee.training.index`, etc.
  - This allows the sidebar and redirects to remain stable.
- For REST-like features:
  - Use `Route::resource()` when appropriate with clear names, or
  - Use explicit verbs (`GET index`, `POST store`, `PUT update`, `DELETE destroy`) with `name(...)`.

### 4.3 CSRF & Destructive Actions
- All destructive actions (delete, restore, etc.) must:
  - Use `POST` forms with `@csrf` and `@method('DELETE')` (or `PUT`/`PATCH`).
  - Never rely on plain `GET` for destructive semantics.

---

## 5. External Integrations (APIs, Files, Caching)

### 5.1 Google Calendar (Holidays)
- The admin/HR/employee calendars use Google Calendar API for public holidays:
  - Implemented via `Http::get(...)` inside feature controllers.
  - Responses are cached with `Cache::remember` per date range.
- Rules:
  - Always wrap external calls in `try/catch` with `Log::error(...)` on failure.
  - Return an empty array (not exceptions) on failure so the UI degrades gracefully.
  - Keep all Google Calendar–specific logic isolated in feature-specific private methods (e.g. `getGoogleCalendarHolidays`).

### 5.2 Files & Storage
- Backups and activity logs use `Storage::disk('local')`.
- Avatars and user-uploaded files use `Storage::disk('public')`.
- Rules:
  - Always validate file size and type in controllers.
  - Use feature-specific directories (`backups/`, `activity-logs/`, `avatars/`).
  - When deleting or replacing files, always clean up old files if they exist.

### 5.3 Caching & Performance
- Use `Cache::remember(...)` for:
  - External APIs (Google Calendar).
  - Mock-heavy data generation if it becomes expensive.
- Never cache raw request objects or closures that close over large context; only cache serializable data arrays/objects.

---

## 6. Testing & Safety

### 6.1 Defensive Coding
- Always guard entry points with:
  - Auth/session checks (`session('user_id')`).
  - Validation for required fields.
  - Existence checks (`findOrFail`) for DB-backed models.
- Log unexpected errors with enough context to debug but without leaking secrets.

### 6.2 Future Tests
- When adding tests:
  - Group them by feature, mirroring `app/Features/*`.
  - Use feature-specific factories and seeds.
  - Test both:
    - Role-based access rules (admin/hr/employee).
    - Data shape expected by Blade views (counts, labels, etc.).

---

## 7. Code Quality Expectations

- **Consistency over cleverness**:
  - Follow existing patterns in this codebase before introducing new abstractions.
  - Prefer clear, boring PHP/Laravel over “smart” one-liners.
- **Small, focused methods**:
  - Avoid controllers or methods that exceed a screenful of logic.
  - Extract reusable logic into private methods or feature services when it improves clarity.
- **Naming**:
  - Use descriptive, domain-focused names (`LeaveStatusUpdated`, `TrainingAssigned`, `CustomHolidayController`).
  - Avoid abbreviations that are not already common in the project.
- **Duplication**:
  - It is acceptable to have **small, deliberate duplication** when DRYing up would harm readability or couple unrelated features.
  - Only extract shared helpers when the behavior is truly cross-feature and stable.
- **Comments & documentation**:
  - Comment **why**, not **what**. The code should explain “what” by itself.
  - Update this file and `ai.rules.md` when you introduce significant new patterns that others (or AI) should follow.
- **Zero-tolerance for broken builds**:
  - Do not leave syntax errors, obvious type errors, or failing happy-path flows.
  - When refactoring, keep public interfaces stable or update all call sites in the same change.


